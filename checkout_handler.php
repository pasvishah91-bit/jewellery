<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');
require_once 'db_config.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$session_id = session_id();
$conn = getDBConnection();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'checkout':
        $cart = $_SESSION['cart'];
        
        if (empty($cart)) {
            echo json_encode(['success' => false, 'message' => 'Cart is empty']);
            exit;
        }

        // Get customer details from POST
        $full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $address = isset($_POST['address']) ? trim($_POST['address']) : '';
        $city = isset($_POST['city']) ? trim($_POST['city']) : '';
        $pincode = isset($_POST['pincode']) ? trim($_POST['pincode']) : '';

        // Validate required fields
        if (empty($full_name)) {
            echo json_encode(['success' => false, 'message' => 'Please enter your full name']);
            exit;
        }
        if (empty($phone)) {
            echo json_encode(['success' => false, 'message' => 'Please enter your phone number']);
            exit;
        }
        if (empty($address)) {
            echo json_encode(['success' => false, 'message' => 'Please enter your address']);
            exit;
        }
        if (empty($city)) {
            echo json_encode(['success' => false, 'message' => 'Please enter your city']);
            exit;
        }

        // Generate a unique order group ID
        $order_group_id = 'ORD-' . strtoupper(uniqid()) . '-' . date('YmdHis');

        $total_amount = 0;
        $inserted_count = 0;

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert customer details
            $cust_stmt = $conn->prepare("INSERT INTO order_customers (order_group_id, full_name, phone, email, address, city, pincode) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$cust_stmt) {
                throw new Exception("Prepare customer insert failed: " . $conn->error);
            }
            $cust_stmt->bind_param("sssssss", $order_group_id, $full_name, $phone, $email, $address, $city, $pincode);
            if (!$cust_stmt->execute()) {
                throw new Exception("Customer insert failed: " . $cust_stmt->error);
            }
            $cust_stmt->close();

            // Prepare insert statement for orders table
            $stmt = $conn->prepare("INSERT INTO orders (order_group_id, session_id, product_id, name, price, image, weight, quantity, item_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            foreach ($cart as $item) {
                // Parse price - remove ₹ and commas
                $price_str = str_replace(['₹', ',', ' '], '', $item['price']);
                $price_val = floatval($price_str);
                $qty = intval($item['quantity']);
                $item_total = $price_val * $qty;
                $total_amount += $item_total;

                $stmt->bind_param("sssssssid", 
                    $order_group_id, 
                    $session_id, 
                    $item['id'], 
                    $item['name'], 
                    $item['price'], 
                    $item['image'], 
                    $item['weight'], 
                    $qty, 
                    $item_total
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Insert failed: " . $stmt->error);
                }
                $inserted_count++;
            }

            // Clear cart from MySQL
            $delete_stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
            $delete_stmt->bind_param("s", $session_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Commit transaction
            $conn->commit();

            // Clear session cart
            $_SESSION['cart'] = [];

            $stmt->close();

            echo json_encode([
                'success' => true,
                'order_id' => $order_group_id,
                'total_amount' => $total_amount,
                'items_count' => $inserted_count,
                'customer_name' => $full_name,
                'message' => 'Order placed successfully!'
            ]);

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            error_log("Checkout error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Checkout failed: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}


