<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'db_config.php';

/**
 * Order Actions Handler
 * ----------------------
 * Handles customer-initiated order actions:
 *   - cancel : Customer cancels an order (allowed only for pending/processing)
 *   - return : Customer requests a return (allowed only for delivered orders)
 *
 * Security: The order must belong to the current session OR to the logged-in
 * customer's email to prevent unauthorised actions on other people's orders.
 */

header('Content-Type: application/json; charset=utf-8');

$conn = getDBConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$action = isset($_POST['action']) ? trim($_POST['action']) : '';
$order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
$reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
$details = isset($_POST['details']) ? trim($_POST['details']) : '';

$valid_actions = ['cancel', 'return'];
if (!in_array($action, $valid_actions)) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

if (empty($order_id)) {
    echo json_encode(['success' => false, 'message' => 'Order ID is required']);
    exit;
}

if (empty($reason)) {
    echo json_encode(['success' => false, 'message' => 'Please provide a reason']);
    exit;
}

// ---- Verify order ownership ----
$session_id = session_id();
$allowed = false;
$order_exists = false;
$current_status = '';

$check_sql = "SELECT o.order_status, o.order_date, c.email
              FROM orders o
              LEFT JOIN order_customers c ON o.order_group_id = c.order_group_id
              WHERE o.order_group_id = ?
              LIMIT 1";
$check_stmt = $conn->prepare($check_sql);
if ($check_stmt) {
    $check_stmt->bind_param("s", $order_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    if ($row = $check_result->fetch_assoc()) {
        $order_exists = true;
        $current_status = $row['order_status'];
        $order_date = $row['order_date'] ?? '';
        $order_email = $row['email'] ?? '';

        // Ownership: matching session OR matching logged-in email
        $owns_by_session = false;
        $owns_by_email = false;

        $own_sql = "SELECT COUNT(*) as cnt FROM orders WHERE order_group_id = ? AND session_id = ?";
        $own_stmt = $conn->prepare($own_sql);
        if ($own_stmt) {
            $own_stmt->bind_param("ss", $order_id, $session_id);
            $own_stmt->execute();
            $own_result = $own_stmt->get_result();
            $owns_by_session = ($own_result->fetch_assoc()['cnt'] > 0);
            $own_stmt->close();
        }

        if (isset($_SESSION['user_email']) && $_SESSION['user_email'] && $order_email === $_SESSION['user_email']) {
            $owns_by_email = true;
        }

        $allowed = $owns_by_session || $owns_by_email;
    }
    $check_stmt->close();
}

if (!$order_exists) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit;
}

if (!$allowed) {
    echo json_encode(['success' => false, 'message' => 'You are not authorised to perform this action on this order']);
    exit;
}

// ---- Status transition rules ----
$cancellable_statuses = ['pending', 'processing'];
$returnable_statuses = ['delivered'];
$cancellation_window_hours = 24; // Order cancel sirf 24 hours (1 din) ke andar

if ($action === 'cancel' && !in_array($current_status, $cancellable_statuses)) {
    echo json_encode([
        'success' => false,
        'message' => 'Order can only be cancelled if it has not been shipped yet. Current status: ' . ucfirst($current_status)
    ]);
    exit;
}

// 24-hour cancellation window check (only for cancel action)
if ($action === 'cancel') {
    $order_placed_ts = $order_date ? strtotime($order_date) : time();
    $now_ts = time();
    $hours_elapsed = ($now_ts - $order_placed_ts) / 3600;

    if ($hours_elapsed > $cancellation_window_hours) {
        echo json_encode([
            'success' => false,
            'message' => 'Cancellation window is only ' . $cancellation_window_hours . ' hour(s) (1 day) from placing the order. Your order was placed more than 24 hours ago, so it can no longer be cancelled.'
        ]);
        exit;
    }
}

if ($action === 'return' && !in_array($current_status, $returnable_statuses)) {
    echo json_encode([
        'success' => false,
        'message' => 'Return can only be requested after the order has been delivered. Current status: ' . ucfirst($current_status)
    ]);
    exit;
}

// ---- Perform action ----
$conn->begin_transaction();

try {
    if ($action === 'cancel') {
        // Update order status to cancelled + store reason
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled', cancel_reason = ? WHERE order_group_id = ?");
        $stmt->bind_param("ss", $reason, $order_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to cancel order');
        }
        $stmt->close();

        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Your order has been cancelled successfully.',
            'order_status' => 'cancelled'
        ]);
    } else {
        // return action
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'return_requested', return_reason = ? WHERE order_group_id = ?");
        $stmt->bind_param("ss", $reason, $order_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to submit return request');
        }
        $stmt->close();

        // Insert into order_returns table
        $ret_stmt = $conn->prepare("INSERT INTO order_returns (order_group_id, session_id, reason, details, status) VALUES (?, ?, ?, ?, 'requested')");
        $ret_stmt->bind_param("ssss", $order_id, $session_id, $reason, $details);
        if (!$ret_stmt->execute()) {
            // If duplicate (already requested), fall back to updating existing row
            if (strpos($ret_stmt->error, 'Duplicate') !== false) {
                $upd_ret = $conn->prepare("UPDATE order_returns SET reason = ?, details = ?, status = 'requested' WHERE order_group_id = ?");
                $upd_ret->bind_param("sss", $reason, $details, $order_id);
                $upd_ret->execute();
                $upd_ret->close();
            } else {
                throw new Exception('Failed to create return request');
            }
        }
        $ret_stmt->close();

        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Your return request has been submitted. Our team will review it within 24-48 hours.',
            'order_status' => 'return_requested'
        ]);
    }
} catch (Exception $e) {
    $conn->rollback();
    error_log('Order action error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

