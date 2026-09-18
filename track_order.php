<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'db_config.php';

$page_title = "Track Order – PASVI Jewellery";
$page_description = "Track your order status and see where your jewellery has reached.";

include 'header.php';

$conn = getDBConnection();
$order_found = false;
$track_error = '';
$tracked = null;
$tracked_items = [];

// Status progress order
$status_steps = ['pending', 'processing', 'shipped', 'delivered'];

// Status display metadata
$status_meta = [
    'pending'          => ['label' => 'Order Placed',     'icon' => '📦', 'desc' => 'We have received your order', 'color' => '#f9a825'],
    'processing'       => ['label' => 'Processing',       'icon' => '⚙️', 'desc' => 'Your order is being prepared', 'color' => '#1e88e5'],
    'shipped'          => ['label' => 'Shipped',          'icon' => '🚚', 'desc' => 'Your order is on the way', 'color' => '#43a047'],
    'delivered'        => ['label' => 'Delivered',        'icon' => '✅', 'desc' => 'Your order has been delivered', 'color' => '#2e7d32'],
    'cancelled'        => ['label' => 'Cancelled',        'icon' => '✕', 'desc' => 'This order has been cancelled', 'color' => '#e53935'],
    'return_requested' => ['label' => 'Return Requested', 'icon' => '↩️', 'desc' => 'Return request submitted, awaiting approval', 'color' => '#ff7043'],
    'returned'         => ['label' => 'Returned',         'icon' => '🔁', 'desc' => 'This order has been returned', 'color' => '#8e24aa'],
];

// Handle tracking lookup
if (isset($_POST['track_order']) || isset($_GET['order_id'])) {
    $order_id = isset($_POST['order_id']) ? trim($_POST['order_id']) : trim($_GET['order_id']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

    if (empty($order_id)) {
        $track_error = 'Please enter your Order ID.';
    } elseif ($conn) {
        // Lookup order group
        $sql = "SELECT o.id, o.order_group_id, o.product_id, o.name, o.price, o.image, o.weight,
                       o.quantity, o.item_total, o.order_status, o.order_date,
                       c.full_name, c.phone, c.email, c.address, c.city, c.pincode
                FROM orders o
                LEFT JOIN order_customers c ON o.order_group_id = c.order_group_id
                WHERE o.order_group_id = ?";
        $params = [$order_id];
        $types = "s";

        if (!empty($phone)) {
            $sql .= " AND c.phone = ?";
            $params[] = $phone;
            $types .= "s";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $tracked_items[] = $row;
                }
                $order_found = true;
                $tracked = $tracked_items[0];
            } else {
                $track_error = 'No order found with the given details. Please check your Order ID' . (!empty($phone) ? ' and Phone number' : '') . '.';
            }
            $stmt->close();
        } else {
            $track_error = 'Database error. Please try again.';
        }
    } else {
        $track_error = 'Database connection failed.';
    }
}

// Build order summary
$order_total = 0;
$item_count = 0;
if ($order_found && !empty($tracked_items)) {
    foreach ($tracked_items as $it) {
        $order_total += floatval($it['item_total']);
        $item_count += intval($it['quantity']);
    }
}

function formatPrice($price) {
    $num = floatval(str_replace(['₹', ',', ' '], '', $price));
    return '₹' . number_format($num, 0);
}

// Determine current step index
$current_step = -1;
$is_cancelled = false;
$is_return = false;
if ($order_found && $tracked) {
    if ($tracked['order_status'] === 'cancelled') {
        $is_cancelled = true;
    } elseif ($tracked['order_status'] === 'return_requested' || $tracked['order_status'] === 'returned') {
        $is_return = true;
        $current_step = array_search('delivered', $status_steps);
        if ($current_step === false) $current_step = 3;
    } else {
        $current_step = array_search($tracked['order_status'], $status_steps);
        if ($current_step === false) $current_step = 0;
    }
}
?>

<style>
/* =====================================
   TRACK ORDER PAGE STYLES
   ===================================== */
.track-page {
    max-width: 900px;
    margin: 40px auto 60px;
    padding: 0 40px;
}
.track-page h1 {
    font-size: 34px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 8px;
}
.track-sep {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #f9d423);
    margin: 12px 0 30px;
    border-radius: 2px;
}

/* Search form */
.track-form {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    padding: 35px 40px;
    border: 1px solid #f0f0f0;
    margin-bottom: 30px;
}
.track-form h2 {
    font-size: 22px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 6px;
}
.track-form .sub-text {
    font-size: 14px;
    color: #888;
    margin-bottom: 22px;
}
.track-form .form-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr auto;
    gap: 14px;
    align-items: end;
}
.track-form .form-field label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
}
.track-form .form-field input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
    transition: border-color 0.3s;
    outline: none;
}
.track-form .form-field input:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}
.track-btn {
    padding: 13px 32px;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border: none;
    border-radius: 8px;
    color: #1a0a0c;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-family: 'Poppins', sans-serif;
    white-space: nowrap;
}
.track-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3);
}

.track-error {
    background: #ffebee;
    border: 1px solid #ef9a9a;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 20px;
    color: #c62828;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Result card */
.track-result {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
    overflow: hidden;
}
.track-result-head {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    padding: 20px 26px;
    background: linear-gradient(180deg, #fdfcf8, #fff);
    border-bottom: 1px solid #f0f0f0;
}
.track-result-head .tr-order-id {
    font-size: 15px;
    font-weight: 600;
    color: #222;
}
.track-result-head .tr-order-id .full-id {
    font-size: 12px;
    font-weight: 400;
    color: #999;
    display: block;
    margin-top: 2px;
}
.track-result-head .tr-date {
    font-size: 13px;
    color: #888;
}
.track-result-head .tr-status-badge {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.5px;
    background: #f5f5f5;
}
.track-result-head .tr-total {
    font-size: 18px;
    font-weight: 700;
    color: #8b1c22;
    font-family: 'Playfair Display', serif;
}

/* Progress timeline */
.progress-wrap {
    padding: 40px 40px 30px;
}
.progress-steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    max-width: 760px;
    margin: 0 auto;
}
.progress-steps::before {
    content: '';
    position: absolute;
    top: 26px;
    left: 8%;
    right: 8%;
    height: 4px;
    background: #e8e8e8;
    border-radius: 2px;
}
.progress-steps::after {
    content: '';
    position: absolute;
    top: 26px;
    left: 8%;
    height: 4px;
    background: linear-gradient(90deg, #d4af37, #c59b27);
    border-radius: 2px;
    width: 0%;
    transition: width 0.6s ease;
    z-index: 1;
}
.progress-steps[data-step="0"]::after { width: 0%; }
.progress-steps[data-step="1"]::after { width: 28%; }
.progress-steps[data-step="2"]::after { width: 56%; }
.progress-steps[data-step="3"]::after { width: 84%; }
.progress-steps.cancelled::before { background: #ffcdd2; }
.progress-steps.cancelled::after { display: none; }

.progress-step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 25%;
    text-align: center;
}
.progress-step .step-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    transition: all 0.4s ease;
    color: #bbb;
    margin-bottom: 12px;
}
.progress-step .step-label {
    font-size: 13px;
    font-weight: 600;
    color: #aaa;
    transition: color 0.3s;
}
.progress-step .step-desc {
    font-size: 11px;
    color: #ccc;
    margin-top: 3px;
    max-width: 140px;
    line-height: 1.4;
}
.progress-step.active .step-icon {
    border-color: #d4af37;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    color: #1a0a0c;
    box-shadow: 0 6px 18px rgba(212, 175, 55, 0.4);
    animation: pulse 1.5s infinite;
}
.progress-step.active .step-label {
    color: #b8962b;
}
.progress-step.active .step-desc {
    color: #999;
}
.progress-step.completed .step-icon {
    border-color: #2e7d32;
    background: #2e7d32;
    color: #fff;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
}
.progress-step.completed .step-label {
    color: #2e7d32;
}
.progress-step.completed .step-desc {
    color: #999;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 6px 18px rgba(212, 175, 55, 0.4); }
    50% { box-shadow: 0 6px 28px rgba(212, 175, 55, 0.7); }
}

/* Cancelled banner */
.cancelled-banner {
    max-width: 760px;
    margin: 0 auto 10px;
    padding: 14px 20px;
    background: #ffebee;
    border: 1px solid #ef9a9a;
    border-radius: 10px;
    color: #c62828;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
}

/* Return banner */
.return-banner {
    max-width: 760px;
    margin: 0 auto 10px;
    padding: 14px 20px;
    background: #fff3e0;
    border: 1px solid #ffe0b2;
    border-radius: 10px;
    color: #e65100;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
}

/* Order items */
.track-items {
    padding: 0 26px 20px;
}
.track-items table {
    width: 100%;
    border-collapse: collapse;
}
.track-items th {
    text-align: left;
    padding: 12px 10px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #999;
    font-weight: 600;
    border-bottom: 1px solid #f0f0f0;
}
.track-items td {
    padding: 12px 10px;
    font-size: 14px;
    color: #555;
    border-bottom: 1px solid #f7f7f7;
    vertical-align: middle;
}
.track-items tr:last-child td {
    border-bottom: none;
}
.track-items .ti-product {
    display: flex;
    align-items: center;
    gap: 14px;
}
.track-items .ti-product img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}
.track-items .ti-product .ti-name {
    font-weight: 500;
    color: #222;
}
.track-items .ti-product .ti-weight {
    font-size: 12px;
    color: #999;
}
.track-items .ti-total {
    font-weight: 600;
    color: #8b1c22;
}

/* Ship info + bill btn */
.track-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 14px;
    padding: 18px 26px;
    background: #fafbfc;
    border-top: 1px solid #f0f0f0;
}
.track-foot .ship-to {
    font-size: 13px;
    color: #888;
}
.track-foot .ship-to strong {
    color: #333;
    font-weight: 500;
}
.track-foot .grand-total {
    font-size: 18px;
    font-weight: 700;
    color: #8b1c22;
    font-family: 'Playfair Display', serif;
}
.bill-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 26px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}
.bill-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 28, 34, 0.3);
}

/* Empty / no tracking message */
.track-info-note {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    padding: 40px;
    text-align: center;
    color: #888;
    border: 1px solid #f0f0f0;
}
.track-info-note .big-icon {
    font-size: 60px;
    opacity: 0.3;
    margin-bottom: 15px;
}

/* Responsive */
@media(max-width: 768px) {
    .track-page { padding: 0 20px; }
    .track-page h1 { font-size: 26px; }
    .track-form { padding: 25px 20px; }
    .track-form .form-grid { grid-template-columns: 1fr; }
    .track-btn { width: 100%; }
    .progress-wrap { padding: 30px 15px 20px; }
    .progress-step .step-icon { width: 42px; height: 42px; font-size: 18px; }
    .progress-step .step-label { font-size: 11px; }
    .progress-step .step-desc { display: none; }
    .progress-steps::before, .progress-steps::after { top: 21px; }
    .track-items { overflow-x: auto; }
    .track-items table { min-width: 520px; }
}
</style>

<div class="track-page">
<h1>📦 Track Your Order</h1>
<div class="track-sep"></div>

<!-- Search form -->
<div class="track-form">
    <h2>Find Your Order</h2>
    <div class="sub-text">Enter your Order ID (e.g. ORD-...) and Phone number used at checkout to see live status.</div>
    <form method="POST" action="track_order.php" class="form-grid">
        <div class="form-field">
            <label>Order ID <span style="color:#e74c3c;">*</span></label>
            <input type="text" name="order_id" id="order_id" placeholder="ORD-XXXXXXXX-YYYYMMDDHHMMSS" required
                   value="<?php echo isset($order_id) ? htmlspecialchars($order_id) : ''; ?>">
        </div>
        <div class="form-field">
            <label>Phone Number</label>
            <input type="tel" name="phone" id="phone" placeholder="Registered phone (optional)"
                   value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
        </div>
        <button type="submit" name="track_order" class="track-btn">🔍 Track</button>
    </form>
</div>

<?php if ($track_error): ?>
<div class="track-error">
    <span>⚠️</span> <span><?php echo htmlspecialchars($track_error); ?></span>
</div>
<?php endif; ?>

<?php if ($order_found && $tracked): ?>
<!-- Track result -->
<div class="track-result">
    <div class="track-result-head">
        <div class="tr-order-id">
            <?php echo htmlspecialchars(substr($tracked['order_group_id'], 0, 20)); ?>
            <span class="full-id"><?php echo htmlspecialchars($tracked['order_group_id']); ?></span>
        </div>
        <div class="tr-date">📅 <?php echo date('d M Y, h:i A', strtotime($tracked['order_date'])); ?></div>
        <?php
        $tr_status = $tracked['order_status'];
        if (!in_array($tr_status, ['cancelled', 'delivered', 'returned', 'return_requested'])):
            // Estimated delivery date = 5 business days from order date
            $tr_ts = strtotime($tracked['order_date']);
            $tr_added = 0;
            while ($tr_added < 5) {
                $tr_ts = strtotime('+1 day', $tr_ts);
                if (date('N', $tr_ts) < 6) { $tr_added++; }
            }
        ?>
        <div style="font-size:12px;color:#43a047;font-weight:500;background:#e8f5e9;padding:6px 14px;border-radius:20px;display:inline-flex;align-items:center;gap:5px;">
            📦 Estimated Delivery: <strong><?php echo date('d M Y', $tr_ts); ?></strong>
        </div>
        <?php endif; ?>
        <?php
        $meta = isset($status_meta[$tracked['order_status']]) ? $status_meta[$tracked['order_status']] : ['label' => ucfirst($tracked['order_status']), 'icon' => '📦', 'color' => '#666'];
        ?>
        <div class="tr-status-badge" style="background: <?php echo $meta['color']; ?>18; color: <?php echo $meta['color']; ?>;">
            <span><?php echo $meta['icon']; ?></span> <?php echo $meta['label']; ?>
        </div>
        <div class="tr-total"><?php echo formatPrice($order_total); ?></div>
    </div>

    <?php if ($is_cancelled): ?>
    <div class="cancelled-banner">✕ This order has been cancelled. Please contact support for assistance.</div>
    <div class="progress-wrap">
        <div class="progress-steps cancelled">
            <?php foreach ($status_steps as $i => $step):
                $sm = $status_meta[$step];
            ?>
            <div class="progress-step">
                <div class="step-icon" style="border-color:#e53935;color:#e53935;">✕</div>
                <div class="step-label"><?php echo $sm['label']; ?></div>
                <div class="step-desc"><?php echo $sm['desc']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php elseif ($is_return): ?>
    <?php if ($tracked['order_status'] === 'return_requested'): ?>
    <div class="return-banner">
        ↩️ Return request submitted. Our team is reviewing it — we will update you within 24-48 hours.
    </div>
    <?php else: ?>
    <div class="return-banner">
        🔁 This order has been returned. Refund (if applicable) will be processed within 5-7 business days.
    </div>
    <?php endif; ?>
    <div class="progress-wrap">
        <div class="progress-steps" data-step="<?php echo $current_step; ?>">
            <?php foreach ($status_steps as $i => $step):
                $sm = $status_meta[$step];
                $state = ($i < $current_step) ? 'completed' : (($i === $current_step) ? 'active' : '');
            ?>
            <div class="progress-step <?php echo $state; ?>">
                <div class="step-icon"><?php echo $sm['icon']; ?></div>
                <div class="step-label"><?php echo $sm['label']; ?></div>
                <div class="step-desc"><?php echo $sm['desc']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="progress-wrap">
        <div class="progress-steps" data-step="<?php echo $current_step; ?>">
            <?php foreach ($status_steps as $i => $step):
                $sm = $status_meta[$step];
                $state = ($i < $current_step) ? 'completed' : (($i === $current_step) ? 'active' : '');
            ?>
            <div class="progress-step <?php echo $state; ?>">
                <div class="step-icon"><?php echo $sm['icon']; ?></div>
                <div class="step-label"><?php echo $sm['label']; ?></div>
                <div class="step-desc"><?php echo $sm['desc']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="track-items">
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
                <?php foreach ($tracked_items as $item): ?>
                <tr>
                    <td>
                        <div class="ti-product">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.onerror=null;this.src='images/pa.png';">
                            <div>
                                <div class="ti-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                <div class="ti-weight"><?php echo htmlspecialchars($item['weight']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                    <td style="text-align:center;"><?php echo intval($item['quantity']); ?></td>
                    <td style="text-align:right;" class="ti-total">₹<?php echo number_format(floatval($item['item_total']), 0); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="track-foot">
        <div class="ship-to">
            📍 Shipping to:
            <strong>
                <?php echo htmlspecialchars($tracked['full_name'] ?? 'N/A'); ?>,
                <?php echo htmlspecialchars($tracked['city'] ?? ''); ?>
                <?php echo htmlspecialchars($tracked['pincode'] ?? ''); ?>
            </strong>
            <?php if (!empty($tracked['address'])): ?>
                <div style="font-size:12px;color:#aaa;margin-top:3px;"><?php echo htmlspecialchars($tracked['address']); ?></div>
            <?php endif; ?>
        </div>
        <div style="display:flex;align-items:center;gap:16px;">
            <div class="grand-total"><?php echo formatPrice($order_total); ?></div>
            <a href="invoice.php?order_id=<?php echo urlencode($tracked['order_group_id']); ?>" class="bill-btn">🧾 Download Bill</a>
        </div>
    </div>
</div>
<?php elseif (!$track_error): ?>
<!-- Default info note -->
<div class="track-info-note">
    <div class="big-icon">🚚</div>
    <h2 style="font-size:20px;color:#333;margin-bottom:8px;">Track Your Order Status</h2>
    <p>Enter your Order ID above to see where your jewellery order has reached. You can also download your bill/invoice anytime.</p>
</div>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>

