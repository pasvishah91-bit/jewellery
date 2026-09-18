<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'db_config.php';

// Page title
$page_title = "My Orders – PASVI Jewellery";
$page_description = "View your orders and track the status of your jewellery purchases at PASVI.";

include 'header.php';

$conn = getDBConnection();
$orders = [];

if ($conn) {
    $session_id = session_id();

    // Build query to fetch orders for this session.
    // If the user is logged in, also include orders placed under their email
    // (this covers orders made on a different session/device before logging in).
    $sql = "
        SELECT o.id, o.order_group_id, o.product_id, o.name, o.price, o.image, o.weight,
               o.quantity, o.item_total, o.order_status, o.order_date,
               c.full_name, c.phone, c.email, c.address, c.city, c.pincode
        FROM orders o
        LEFT JOIN order_customers c ON o.order_group_id = c.order_group_id
        WHERE o.session_id = ?
    ";
    $params = [$session_id];
    $types = "s";

    if (isset($_SESSION['user_email']) && $_SESSION['user_email']) {
        $sql .= " OR c.email = ?";
        $params[] = $_SESSION['user_email'];
        $types .= "s";
    }

    $sql .= " ORDER BY o.order_date DESC, o.id DESC";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        $stmt->close();
    }
}

// Group order line items by order_group_id
$grouped = [];
foreach ($orders as $row) {
    $gid = $row['order_group_id'];
    if (!isset($grouped[$gid])) {
        $grouped[$gid] = [
            'order_group_id' => $gid,
            'order_date'     => $row['order_date'],
            'order_status'   => $row['order_status'],
            'customer'       => [
                'full_name' => $row['full_name'],
                'phone'     => $row['phone'],
                'email'     => $row['email'],
                'address'   => $row['address'],
                'city'      => $row['city'],
                'pincode'   => $row['pincode'],
            ],
            'items'          => [],
            'total'          => 0,
            'item_count'     => 0,
        ];
    }
    $grouped[$gid]['items'][] = $row;
    $grouped[$gid]['total'] += floatval($row['item_total']);
    $grouped[$gid]['item_count'] += intval($row['quantity']);
}

// Status filter
$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
$valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return_requested', 'returned'];
if ($status_filter && in_array($status_filter, $valid_statuses)) {
    $grouped = array_filter($grouped, function ($g) use ($status_filter) {
        return $g['order_status'] === $status_filter;
    });
}

// Order status display metadata
$status_meta = [
    'pending'          => ['label' => 'Pending',          'icon' => '⏳', 'color' => '#f9a825'],
    'processing'       => ['label' => 'Processing',       'icon' => '⚙️', 'color' => '#1e88e5'],
    'shipped'          => ['label' => 'Shipped',          'icon' => '🚚', 'color' => '#43a047'],
    'delivered'        => ['label' => 'Delivered',        'icon' => '✅', 'color' => '#2e7d32'],
    'cancelled'        => ['label' => 'Cancelled',        'icon' => '✕', 'color' => '#e53935'],
    'return_requested' => ['label' => 'Return Requested', 'icon' => '↩️', 'color' => '#ff7043'],
    'returned'         => ['label' => 'Returned',         'icon' => '🔁', 'color' => '#8e24aa'],
];

function formatPrice($price) {
    // Normalize: strip ₹ and commas, then re-format
    $num = floatval(str_replace(['₹', ',', ' '], '', $price));
    return '₹' . number_format($num, 0);
}

/**
 * Calculate estimated delivery date = order date + 5 business days
 * (skips Saturday & Sunday so delivery is shown on a working day)
 */
function estimatedDeliveryDate($order_date) {
    $ts = strtotime($order_date);
    $business_days = 5;
    $added = 0;
    while ($added < $business_days) {
        $ts = strtotime('+1 day', $ts);
        $day_of_week = date('N', $ts); // 1=Mon ... 6=Sat, 7=Sun
        if ($day_of_week < 6) { // skip Sat(6) & Sun(7)
            $added++;
        }
    }
    return $ts;
}
?>
<style>
/* =====================================
   MY ORDERS PAGE STYLES
   ===================================== */
.order-page {
    max-width: 1100px;
    margin: 40px auto 60px;
    padding: 0 40px;
}
.order-page h1 {
    font-size: 34px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 8px;
}
.order-sep {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #f9d423);
    margin: 12px 0 30px;
    border-radius: 2px;
}

/* Filters */
.order-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 28px;
}
.order-filters .filter-btn {
    padding: 8px 20px;
    border: 1.5px solid #e0e0e0;
    border-radius: 30px;
    background: #fff;
    color: #666;
    font-size: 13px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.order-filters .filter-btn:hover {
    border-color: #d4af37;
    color: #b8962b;
}
.order-filters .filter-btn.active {
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border-color: #d4af37;
    color: #1a0a0c;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(212, 175, 55, 0.3);
}
.order-filters .filter-count {
    margin-left: auto;
    font-size: 13px;
    color: #999;
}

/* Empty state */
.order-empty {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
}
.order-empty .order-icon-big {
    font-size: 80px;
    margin-bottom: 20px;
    opacity: 0.3;
}
.order-empty h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}
.order-empty p {
    color: #888;
    margin-bottom: 25px;
}
.order-empty .shop-btn {
    display: inline-block;
    padding: 14px 40px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}
.order-empty .shop-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 28, 34, 0.3);
}

/* Order card */
.order-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    margin-bottom: 26px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
    transition: box-shadow 0.3s;
}
.order-card:hover {
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
}
.order-card-head {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding: 18px 24px;
    background: linear-gradient(180deg, #fdfcf8, #fff);
    border-bottom: 1px solid #f0f0f0;
}
.order-card-head .order-id {
    font-size: 14px;
    font-weight: 600;
    color: #222;
}
.order-card-head .order-id .gid {
    font-size: 12px;
    font-weight: 400;
    color: #999;
    display: block;
    margin-top: 2px;
}
.order-card-head .order-date {
    font-size: 13px;
    color: #888;
    margin-left: 4px;
}
.order-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    background: #f5f5f5;
    color: #666;
    margin-left: auto;
}
.order-card-head .order-total {
    font-size: 18px;
    font-weight: 700;
    color: #8b1c22;
    font-family: 'Playfair Display', serif;
}

/* Order items table */
.order-items {
    padding: 10px 24px;
}
.order-items table {
    width: 100%;
    border-collapse: collapse;
}
.order-items th {
    text-align: left;
    padding: 12px 10px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #999;
    font-weight: 600;
    border-bottom: 1px solid #f0f0f0;
}
.order-items td {
    padding: 12px 10px;
    font-size: 14px;
    color: #555;
    border-bottom: 1px solid #f7f7f7;
    vertical-align: middle;
}
.order-items tr:last-child td {
    border-bottom: none;
}
.order-items .oi-product {
    display: flex;
    align-items: center;
    gap: 14px;
}
.order-items .oi-product img {
    width: 56px;
    height: 56px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}
.order-items .oi-product .oi-name {
    font-weight: 500;
    color: #222;
}
.order-items .oi-product .oi-weight {
    font-size: 12px;
    color: #999;
}
.order-items .oi-price,
.order-items .oi-total {
    font-weight: 500;
}
.order-items .oi-total {
    font-weight: 600;
    color: #8b1c22;
}
.order-items .oi-qty {
    color: #666;
}

/* Order footer */
.order-card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    padding: 16px 24px;
    background: #fafbfc;
    border-top: 1px solid #f0f0f0;
}
.order-card-foot .ship-to {
    font-size: 13px;
    color: #888;
}
.order-card-foot .ship-to strong {
    color: #333;
    font-weight: 500;
}
.order-card-foot .grand-total {
    font-size: 16px;
    font-weight: 600;
    color: #8b1c22;
}
.order-card-foot .grand-total span {
    font-size: 13px;
    font-weight: 400;
    color: #999;
    margin-right: 6px;
}

/* Order tracking mini progress bar */
.order-track {
    padding: 20px 24px;
    border-bottom: 1px solid #f0f0f0;
    background: #fdfcf8;
}
.order-track .track-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #999;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 6px;
}
.track-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    max-width: 640px;
}
.track-steps::before {
    content: '';
    position: absolute;
    top: 14px;
    left: 4%;
    right: 4%;
    height: 3px;
    background: #e8e8e8;
    border-radius: 2px;
}
.track-steps::after {
    content: '';
    position: absolute;
    top: 14px;
    left: 4%;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #c59b27);
    border-radius: 2px;
    width: 0%;
    transition: width 0.6s ease;
    z-index: 1;
}
.track-steps[data-step="0"]::after { width: 0%; }
.track-steps[data-step="1"]::after { width: 26%; }
.track-steps[data-step="2"]::after { width: 52%; }
.track-steps[data-step="3"]::after { width: 78%; }
.track-steps.cancelled::before { background: #ffcdd2; }
.track-steps.cancelled::after { display: none; }

.track-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 25%;
    text-align: center;
}
.track-step .ts-icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #fff;
    border: 2.5px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #bbb;
    margin-bottom: 7px;
    transition: all 0.4s ease;
}
.track-step .ts-label {
    font-size: 11px;
    font-weight: 600;
    color: #aaa;
    transition: color 0.3s;
}
.track-step.active .ts-icon {
    border-color: #d4af37;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    color: #1a0a0c;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
}
.track-step.active .ts-label {
    color: #b8962b;
}
.track-step.completed .ts-icon {
    border-color: #2e7d32;
    background: #2e7d32;
    color: #fff;
}
.track-step.completed .ts-label {
    color: #2e7d32;
}
.track-step.cancelled .ts-icon {
    border-color: #e53935;
    color: #e53935;
    background: #fff;
}
.track-step.cancelled .ts-label {
    color: #e53935;
}

/* Action buttons row */
.order-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.order-actions .track-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    text-decoration: none;
    transition: all 0.3s;
    background: #fff;
    font-family: 'Poppins', sans-serif;
}
.order-actions .track-link:hover {
    border-color: #d4af37;
    color: #b8962b;
}
.order-actions .bill-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
}
.order-actions .bill-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(139, 28, 34, 0.3);
}
.order-actions .cancel-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: #ffebee;
    color: #c62828;
    border: 1.5px solid #ffcdd2;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
}
.order-actions .cancel-btn:hover {
    background: #e53935;
    color: #fff;
    border-color: #e53935;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(229, 57, 53, 0.3);
}
.order-actions .return-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 20px;
    background: #fff3e0;
    color: #ef6c00;
    border: 1.5px solid #ffe0b2;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    transition: all 0.3s;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
}
.order-actions .return-btn:hover {
    background: #ef6c00;
    color: #fff;
    border-color: #ef6c00;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(239, 108, 0, 0.3);
}

/* Reason modal */
.action-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10005;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}
.action-modal-overlay.active {
    display: flex;
}
.action-modal {
    background: #fff;
    border-radius: 16px;
    width: 480px;
    max-width: 95%;
    padding: 30px 32px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}
.action-modal .am-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f5f5f5;
}
.action-modal .am-header h3 {
    font-size: 20px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin: 0;
}
.action-modal .am-close {
    background: none;
    border: none;
    font-size: 26px;
    color: #aaa;
    cursor: pointer;
    padding: 0 5px;
    line-height: 1;
    transition: color 0.3s;
}
.action-modal .am-close:hover { color: #8b1c22; }
.action-modal .am-order-id {
    font-size: 12px;
    color: #999;
    background: #f5f5f5;
    padding: 5px 12px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 14px;
    word-break: break-all;
}
.action-modal label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
}
.action-modal select,
.action-modal textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: 'Poppins', sans-serif;
    margin-bottom: 16px;
    outline: none;
    transition: border-color 0.3s;
    background: #fff;
}
.action-modal select:focus,
.action-modal textarea:focus { border-color: #d4af37; }
.action-modal textarea { resize: vertical; min-height: 70px; }
.action-modal .am-submit {
    width: 100%;
    padding: 14px 0;
    border: none;
    border-radius: 8px;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s;
}
.action-modal .am-submit.cancel { background: linear-gradient(135deg, #e53935, #c62828); }
.action-modal .am-submit.return { background: linear-gradient(135deg, #ef6c00, #e65100); }
.action-modal .am-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.2); }
.action-modal .am-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.action-modal .am-note {
    font-size: 12px;
    color: #999;
    margin-top: 12px;
    text-align: center;
}
.action-modal .am-error {
    font-size: 12px;
    color: #e53935;
    margin-bottom: 10px;
    display: none;
}
.action-modal .am-error.show { display: block; }
@keyframes modalSlideIn {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive */
@media(max-width: 768px) {
    .order-page { padding: 0 20px; }
    .order-page h1 { font-size: 26px; }
    .order-card-head { padding: 15px 18px; }
    .order-items { padding: 8px 18px; overflow-x: auto; }
    .order-items table { min-width: 560px; }
    .order-card-foot { padding: 14px 18px; }
    .order-track { padding: 16px 18px; }
    .track-step .ts-label { font-size: 10px; }
}
</style>

<div class="order-page">
<h1>📋 My Orders</h1>
<div class="order-sep"></div>

<!-- Status filters -->
<div class="order-filters">
    <a href="order.php" class="filter-btn <?php echo !$status_filter ? 'active' : ''; ?>">All</a>
    <a href="order.php?status=pending" class="filter-btn <?php echo $status_filter === 'pending' ? 'active' : ''; ?>">⏳ Pending</a>
    <a href="order.php?status=processing" class="filter-btn <?php echo $status_filter === 'processing' ? 'active' : ''; ?>">⚙️ Processing</a>
    <a href="order.php?status=shipped" class="filter-btn <?php echo $status_filter === 'shipped' ? 'active' : ''; ?>">🚚 Shipped</a>
    <a href="order.php?status=delivered" class="filter-btn <?php echo $status_filter === 'delivered' ? 'active' : ''; ?>">✅ Delivered</a>
    <a href="order.php?status=cancelled" class="filter-btn <?php echo $status_filter === 'cancelled' ? 'active' : ''; ?>">✕ Cancelled</a>
    <a href="order.php?status=return_requested" class="filter-btn <?php echo $status_filter === 'return_requested' ? 'active' : ''; ?>">↩️ Return Requested</a>
    <a href="order.php?status=returned" class="filter-btn <?php echo $status_filter === 'returned' ? 'active' : ''; ?>">🔁 Returned</a>
    <div class="filter-count"><?php echo count($grouped); ?> order(s)</div>
</div>

<?php if (empty($grouped)): ?>
<!-- Empty state -->
<div class="order-empty">
    <div class="order-icon-big">📦</div>
    <h2>No Orders Yet</h2>
    <p>You haven't placed any orders. Explore our collection and start shopping!</p>
    <a href="index.php" class="shop-btn">START SHOPPING</a>
</div>
<?php else: ?>

<?php foreach ($grouped as $order): 
    $meta = isset($status_meta[$order['order_status']]) ? $status_meta[$order['order_status']] : ['label' => ucfirst($order['order_status']), 'icon' => '📦', 'color' => '#666'];
    $is_cancelled = ($order['order_status'] === 'cancelled');
    $track_steps = ['pending', 'processing', 'shipped', 'delivered'];
    $step_index = array_search($order['order_status'], $track_steps);
    if ($step_index === false) $step_index = 0;

    // Estimated delivery date (5 business days from order date)
    $est_delivery_ts = estimatedDeliveryDate($order['order_date']);
    $est_delivery_text = date('d M Y', $est_delivery_ts);

    // Cancellation window: only within 24 hours of placing order
    $cancel_allowed = false;
    if (in_array($order['order_status'], ['pending', 'processing'])) {
        $hours_elapsed = (time() - strtotime($order['order_date'])) / 3600;
        $cancel_allowed = ($hours_elapsed <= 24);
    }
?>
<!-- Order card -->
<div class="order-card">
    <div class="order-card-head">
        <div class="order-id">
            <?php echo htmlspecialchars(substr($order['order_group_id'], 0, 20)); ?>
            <span class="gid"><?php echo htmlspecialchars($order['order_group_id']); ?></span>
        </div>
        <div class="order-date">
            📅 <?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?>
            <?php if (!in_array($order['order_status'], ['cancelled', 'delivered', 'returned', 'return_requested'])): ?>
            <div style="font-size:12px;color:#43a047;margin-top:3px;font-weight:500;">
                📦 Estimated Delivery: <strong><?php echo $est_delivery_text; ?></strong>
            </div>
            <?php endif; ?>
            <?php if ($order['order_status'] === 'delivered'): ?>
            <div style="font-size:12px;color:#2e7d32;margin-top:3px;font-weight:500;">✅ Delivered</div>
            <?php endif; ?>
        </div>
        <div class="order-status-badge" style="background: <?php echo $meta['color']; ?>18; color: <?php echo $meta['color']; ?>;">
            <span><?php echo $meta['icon']; ?></span> <?php echo $meta['label']; ?>
        </div>
        <div class="order-total"><?php echo formatPrice($order['total']); ?></div>
    </div>

    <!-- Tracking progress -->
    <?php if ($is_cancelled): ?>
    <div class="order-track">
        <div class="track-title">🚚 Order Tracking</div>
        <div class="track-steps cancelled">
            <?php foreach ($track_steps as $ts):
                $tsm = $status_meta[$ts];
            ?>
            <div class="track-step cancelled">
                <div class="ts-icon">✕</div>
                <div class="ts-label"><?php echo $tsm['label']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="order-track">
        <div class="track-title">🚚 Order Tracking</div>
        <div class="track-steps" data-step="<?php echo $step_index; ?>">
            <?php foreach ($track_steps as $i => $ts):
                $tsm = $status_meta[$ts];
                $state = ($i < $step_index) ? 'completed' : (($i === $step_index) ? 'active' : '');
            ?>
            <div class="track-step <?php echo $state; ?>">
                <div class="ts-icon"><?php echo $tsm['icon']; ?></div>
                <div class="ts-label"><?php echo $tsm['label']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="order-items">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th style="text-align:center;">Qty</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td>
                        <div class="oi-product">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.onerror=null;this.src='images/pa.png';">
                            <div>
                                <div class="oi-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="oi-weight"><?php echo htmlspecialchars($item['weight']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="oi-price"><?php echo htmlspecialchars($item['price']); ?></td>
                    <td style="text-align:center;" class="oi-qty"><?php echo intval($item['quantity']); ?></td>
                    <td style="text-align:right;" class="oi-total">₹<?php echo number_format(floatval($item['item_total']), 0); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="order-card-foot">
        <div class="ship-to">
            📍 Shipping to:
            <strong>
                <?php echo htmlspecialchars($order['customer']['full_name'] ?? 'N/A'); ?>,
                <?php echo htmlspecialchars($order['customer']['city'] ?? ''); ?>
                <?php echo htmlspecialchars($order['customer']['pincode'] ?? ''); ?>
            </strong>
            <?php if (!empty($order['customer']['address'])): ?>
                <div style="font-size:12px;color:#aaa;margin-top:3px;">
                    <?php echo htmlspecialchars($order['customer']['address']); ?>
                </div>
            <?php endif; ?>
        </div>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div class="grand-total">
                <span>Total</span><?php echo formatPrice($order['total']); ?>
            </div>
            <div class="order-actions">
                <a href="track_order.php?order_id=<?php echo urlencode($order['order_group_id']); ?>" class="track-link">🚚 Track</a>
                <a href="invoice.php?order_id=<?php echo urlencode($order['order_group_id']); ?>" class="bill-link" target="_blank">🧾 Download Bill</a>
                <?php if (in_array($order['order_status'], ['pending', 'processing']) && $cancel_allowed): ?>
                <button class="cancel-btn" data-order-id="<?php echo htmlspecialchars($order['order_group_id']); ?>">✕ Cancel Order</button>
                <?php endif; ?>
                <?php if (in_array($order['order_status'], ['pending', 'processing']) && !$cancel_allowed): ?>
                <div style="font-size:12px;color:#e53935;display:inline-flex;align-items:center;gap:5px;padding:9px 14px;border:1.5px dashed #ef9a9a;border-radius:8px;">
                    ⏰ Cancel window (24 hrs) closed
                </div>
                <?php endif; ?>
                <?php if ($order['order_status'] === 'delivered'): ?>
                <button class="return-btn" data-order-id="<?php echo htmlspecialchars($order['order_group_id']); ?>">↩️ Return</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php endif; ?>
</div>

<!-- Action Modal -->
<div class="action-modal-overlay" id="actionModal">
    <div class="action-modal">
        <div class="am-header">
            <h3 id="amTitle">Cancel Order</h3>
            <button class="am-close" id="amClose">✕</button>
        </div>
        <div class="am-order-id" id="amOrderId"></div>
        <div class="am-error" id="amError"></div>
        <label>Select Reason <span style="color:#e74c3c;">*</span></label>
        <select id="amReason">
            <option value="">-- Select a reason --</option>
            <!-- Options injected by JS based on action -->
        </select>
        <label>Additional Details (Optional)</label>
        <textarea id="amDetails" placeholder="Any additional information..."></textarea>
        <button class="am-submit" id="amSubmitBtn" type="button">CONFIRM CANCEL</button>
        <div class="am-note" id="amNote"></div>
    </div>
</div>

<script>
(function() {
    const modal = document.getElementById('actionModal');
    const amTitle = document.getElementById('amTitle');
    const amOrderId = document.getElementById('amOrderId');
    const amReason = document.getElementById('amReason');
    const amDetails = document.getElementById('amDetails');
    const amSubmitBtn = document.getElementById('amSubmitBtn');
    const amNote = document.getElementById('amNote');
    const amError = document.getElementById('amError');
    const amClose = document.getElementById('amClose');

    let currentAction = '';
    let currentOrderId = '';

    const reasons = {
        cancel: [
            'Ordered by mistake',
            'Changed my mind',
            'Found better price elsewhere',
            'Payment related issue',
            'Delivery location changed',
            'Other'
        ],
        return: [
            'Wrong item delivered',
            'Item not as described',
            'Size / fit not as expected',
            'Defective or damaged item',
            'Quality not satisfactory',
            'Other'
        ]
    };

    const notes = {
        cancel: 'Your order will be cancelled and no charges will apply. Refund (if any) will be processed within 5-7 business days.',
        return: 'Our team will review your return request within 24-48 hours. A pickup will be scheduled if approved.'
    };

    // Open modal
    document.querySelectorAll('.cancel-btn, .return-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentAction = this.classList.contains('cancel-btn') ? 'cancel' : 'return';
            currentOrderId = this.dataset.orderId;

            amTitle.textContent = currentAction === 'cancel' ? 'Cancel Order' : 'Return Order';
            amSubmitBtn.textContent = currentAction === 'cancel' ? 'CONFIRM CANCEL' : 'SUBMIT RETURN REQUEST';
            amSubmitBtn.className = 'am-submit ' + currentAction;
            amOrderId.textContent = 'Order: ' + currentOrderId;
            amNote.textContent = notes[currentAction];

            // Fill reasons
            amReason.innerHTML = '<option value="">-- Select a reason --</option>';
            reasons[currentAction].forEach(r => {
                const opt = document.createElement('option');
                opt.value = r;
                opt.textContent = r;
                amReason.appendChild(opt);
            });

            amDetails.value = '';
            amError.classList.remove('show');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    amClose.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Submit
    amSubmitBtn.addEventListener('click', function() {
        const reason = amReason.value;
        const details = amDetails.value.trim();

        if (!reason) {
            amError.textContent = 'Please select a reason.';
            amError.classList.add('show');
            return;
        }

        amError.classList.remove('show');
        this.disabled = true;
        this.textContent = 'PROCESSING...';

        const formData = new FormData();
        formData.append('action', currentAction);
        formData.append('order_id', currentOrderId);
        formData.append('reason', reason);
        formData.append('details', details);

        fetch('order_actions.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            amSubmitBtn.disabled = false;
            amSubmitBtn.textContent = currentAction === 'cancel' ? 'CONFIRM CANCEL' : 'SUBMIT RETURN REQUEST';
            if (data.success) {
                closeModal();
                if (window.showToast) {
                    window.showToast('✅ ' + data.message);
                } else {
                    alert('✅ ' + data.message);
                }
                setTimeout(function() { location.reload(); }, 800);
            } else {
                amError.textContent = data.message || 'Something went wrong. Please try again.';
                amError.classList.add('show');
            }
        })
        .catch(err => {
            amSubmitBtn.disabled = false;
            amSubmitBtn.textContent = currentAction === 'cancel' ? 'CONFIRM CANCEL' : 'SUBMIT RETURN REQUEST';
            amError.textContent = 'Network error. Please try again.';
            amError.classList.add('show');
            console.error('Order action error:', err);
        });
    });
})();
</script>

<?php include 'footer.php'; ?>

