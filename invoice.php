<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'db_config.php';

$conn = getDBConnection();
$error = '';
$invoice = null;
$items = [];
$customer = [];

// Fetch order details
if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
    $order_id = trim($_GET['order_id']);

    if (!$conn) {
        $error = 'Database connection failed.';
    } else {
        $stmt = $conn->prepare("
            SELECT o.id, o.order_group_id, o.product_id, o.name, o.price, o.image, o.weight,
                   o.quantity, o.item_total, o.order_status, o.order_date,
                   c.full_name, c.phone, c.email, c.address, c.city, c.pincode
            FROM orders o
            LEFT JOIN order_customers c ON o.order_group_id = c.order_group_id
            WHERE o.order_group_id = ?
            ORDER BY o.id ASC
        ");
        if ($stmt) {
            $stmt->bind_param("s", $order_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $items[] = $row;
                }
                $invoice = $items[0];
                $customer = [
                    'full_name' => $invoice['full_name'] ?? 'N/A',
                    'phone'     => $invoice['phone'] ?? '',
                    'email'     => $invoice['email'] ?? '',
                    'address'   => $invoice['address'] ?? '',
                    'city'      => $invoice['city'] ?? '',
                    'pincode'   => $invoice['pincode'] ?? '',
                ];
            } else {
                $error = 'No order found with this Order ID.';
            }
            $stmt->close();
        } else {
            $error = 'Database error. Please try again.';
        }
    }
} else {
    $error = 'Please provide an Order ID.';
}

// Compute totals
$subtotal = 0;
$total_items = 0;
if (!empty($items)) {
    foreach ($items as $it) {
        $subtotal += floatval($it['item_total']);
        $total_items += intval($it['quantity']);
    }
}
$shipping = 0; // Free shipping
$grand_total = $subtotal + $shipping;

// GST breakdown (18% assumed for jewellery).
// IMPORTANT: Product prices are GST-INCLUSIVE (as noted on the invoice), so the
// grand total must equal the subtotal. We only split the embedded tax for
// informational display — we do NOT add it on top again.
$gst_rate = 0.18;
$gst_included = $subtotal - ($subtotal / (1 + $gst_rate));
$cgst = $gst_included / 2;
$sgst = $gst_included / 2;

function fmt($num) {
    return '₹' . number_format($num, 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice – PASVI Jewellery</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f0f0f0;
    padding: 30px 15px;
    color: #333;
}

/* Toolbar */
.invoice-toolbar {
    max-width: 860px;
    margin: 0 auto 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.invoice-toolbar .back-link {
    color: #888;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}
.invoice-toolbar .back-link:hover {
    color: #8b1c22;
}
.print-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 30px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(139, 28, 34, 0.2);
}
.print-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 28, 34, 0.3);
}

/* Invoice Sheet */
.invoice-sheet {
    max-width: 860px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

/* Header */
.invoice-header {
    background: linear-gradient(135deg, #1a0a0c, #2d0d10, #641820);
    padding: 40px 50px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    color: #fff;
    gap: 30px;
    flex-wrap: wrap;
}
.invoice-brand {
    display: flex;
    align-items: center;
    gap: 16px;
}
.invoice-brand .brand-icon {
    font-size: 40px;
    color: #d4af37;
}
.invoice-brand h1 {
    font-family: 'Playfair Display', serif;
    font-size: 34px;
    letter-spacing: 3px;
    color: #d4af37;
    line-height: 1;
}
.invoice-brand p {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: 1px;
    margin-top: 4px;
}
.invoice-title-box {
    text-align: right;
}
.invoice-title-box h2 {
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    letter-spacing: 4px;
    color: #fff;
    margin-bottom: 6px;
}
.invoice-title-box .inv-no {
    font-size: 13px;
    color: #d4af37;
    background: rgba(212, 175, 55, 0.15);
    padding: 5px 14px;
    border-radius: 20px;
    display: inline-block;
    letter-spacing: 1px;
}

/* Body */
.invoice-body {
    padding: 40px 50px;
}

/* Meta rows */
.invoice-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}
.invoice-meta .meta-block h4 {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #999;
    font-weight: 600;
    margin-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 8px;
}
.invoice-meta .meta-block .meta-line {
    font-size: 14px;
    color: #555;
    padding: 2px 0;
    display: flex;
    justify-content: space-between;
    max-width: 320px;
}
.invoice-meta .meta-block .meta-line span:first-child {
    color: #aaa;
    min-width: 90px;
}
.invoice-meta .meta-block .meta-line span:last-child {
    font-weight: 500;
    color: #333;
    text-align: right;
}

/* Items table */
.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 25px;
}
.invoice-table thead th {
    background: #faf9f5;
    text-align: left;
    padding: 13px 14px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    color: #888;
    font-weight: 600;
    border-bottom: 2px solid #d4af37;
}
.invoice-table thead th:last-child {
    text-align: right;
}
.invoice-table tbody td {
    padding: 14px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
    color: #555;
    vertical-align: middle;
}
.invoice-table tbody tr:last-child td {
    border-bottom: none;
}
.invoice-table .pd-item {
    display: flex;
    align-items: center;
    gap: 12px;
}
.invoice-table .pd-item img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}
.invoice-table .pd-item .pd-name {
    font-weight: 500;
    color: #222;
}
.invoice-table .pd-item .pd-weight {
    font-size: 12px;
    color: #aaa;
}
.invoice-table td.num {
    text-align: right;
    font-weight: 500;
}
.invoice-table td.total {
    font-weight: 600;
    color: #8b1c22;
}

/* Totals */
.invoice-totals {
    margin-left: auto;
    max-width: 340px;
}
.invoice-totals .tot-row {
    display: flex;
    justify-content: space-between;
    padding: 7px 0;
    font-size: 14px;
    color: #666;
}
.invoice-totals .tot-row.tax {
    color: #999;
}
.invoice-totals .tot-row.grand {
    border-top: 2px solid #222;
    margin-top: 8px;
    padding-top: 14px;
    font-size: 20px;
    font-weight: 700;
    color: #8b1c22;
    font-family: 'Playfair Display', serif;
}
.invoice-totals .tot-row.grand span:last-child {
    font-size: 24px;
}

/* GST note */
.gst-note {
    margin-top: 20px;
    padding: 12px 16px;
    background: #faf9f5;
    border-left: 3px solid #d4af37;
    border-radius: 0 8px 8px 0;
    font-size: 12px;
    color: #888;
    line-height: 1.6;
}

/* Footer */
.invoice-footer {
    padding: 25px 50px;
    border-top: 1px solid #f0f0f0;
    text-align: center;
    background: #fafafa;
}
.invoice-footer .thanks {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #8b1c22;
    margin-bottom: 5px;
}
.invoice-footer p {
    font-size: 12px;
    color: #aaa;
}
.invoice-footer .contact-line {
    margin-top: 10px;
    font-size: 12px;
    color: #999;
}

/* Error state */
.invoice-error {
    max-width: 860px;
    margin: 0 auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    padding: 60px 40px;
    text-align: center;
}
.invoice-error .err-icon {
    font-size: 70px;
    opacity: 0.3;
    margin-bottom: 20px;
}
.invoice-error h2 {
    font-family: 'Playfair Display', serif;
    color: #333;
    margin-bottom: 10px;
}
.invoice-error p {
    color: #888;
    margin-bottom: 25px;
}
.invoice-error a {
    display: inline-block;
    padding: 13px 35px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 500;
}

/* Print styles */
@media print {
    body {
        background: #fff;
        padding: 0;
    }
    .invoice-toolbar {
        display: none !important;
    }
    .invoice-sheet {
        box-shadow: none;
        border-radius: 0;
        max-width: 100%;
    }
    .invoice-header {
        padding: 25px 30px;
    }
    .invoice-body {
        padding: 25px 30px;
    }
    .invoice-footer {
        padding: 18px 30px;
    }
    .no-print {
        display: none !important;
    }
    .invoice-table thead th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}

/* Responsive */
@media(max-width: 640px) {
    .invoice-header {
        padding: 25px 20px;
    }
    .invoice-body {
        padding: 20px;
    }
    .invoice-footer {
        padding: 18px 20px;
    }
    .invoice-meta {
        grid-template-columns: 1fr;
        gap: 18px;
    }
    .invoice-header {
        flex-direction: column;
    }
    .invoice-title-box {
        text-align: left;
    }
}
</style>
</head>
<body>

<div class="invoice-toolbar no-print">
    <a href="order.php" class="back-link">← Back to My Orders</a>
    <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
</div>

<?php if ($error): ?>
<div class="invoice-error">
    <div class="err-icon">🧾</div>
    <h2>Invoice Not Found</h2>
    <p><?php echo htmlspecialchars($error); ?></p>
    <a href="order.php">GO TO MY ORDERS</a>
</div>
<?php elseif ($invoice): ?>

<div class="invoice-sheet">
    <!-- HEADER -->
    <div class="invoice-header">
        <div class="invoice-brand">
            <span class="brand-icon">♜</span>
            <div>
                <h1>PASVI</h1>
                <p>JEWELLERY • TIMELESS ELEGANCE</p>
            </div>
        </div>
        <div class="invoice-title-box">
            <h2>INVOICE</h2>
            <span class="inv-no"># <?php echo htmlspecialchars($invoice['order_group_id']); ?></span>
        </div>
    </div>

    <div class="invoice-body">
        <!-- BILL TO / SHIP TO -->
        <div class="invoice-meta">
            <div class="meta-block">
                <h4>📋 Billed To</h4>
                <div style="font-size:16px;font-weight:600;color:#222;margin-bottom:6px;">
                    <?php echo htmlspecialchars($customer['full_name']); ?>
                </div>
                <div style="font-size:13px;color:#888;line-height:1.7;">
                    <?php if (!empty($customer['address'])) echo htmlspecialchars($customer['address']) . '<br>'; ?>
                    <?php if (!empty($customer['city'])) echo htmlspecialchars($customer['city']); ?>
                    <?php if (!empty($customer['pincode'])) echo ' - ' . htmlspecialchars($customer['pincode']); ?><br>
                    <?php if (!empty($customer['phone'])) echo '📞 ' . htmlspecialchars($customer['phone']); ?>
                    <?php if (!empty($customer['email'])) echo '<br>✉ ' . htmlspecialchars($customer['email']); ?>
                </div>
            </div>
            <div class="meta-block">
                <h4>🧾 Invoice Details</h4>
                <div class="meta-line"><span>Order Date</span><span><?php echo date('d M Y, h:i A', strtotime($invoice['order_date'])); ?></span></div>
                <div class="meta-line"><span>Order Status</span><span style="text-transform:capitalize;color:<?php
                    $inv_status = $invoice['order_status'];
                    if ($inv_status === 'cancelled') { echo '#e53935'; }
                    elseif ($inv_status === 'return_requested') { echo '#ff7043'; }
                    elseif ($inv_status === 'returned') { echo '#8e24aa'; }
                    else { echo '#2e7d32'; }
                ?>;"><?php echo htmlspecialchars($invoice['order_status']); ?></span></div>
                <div class="meta-line"><span>Payment</span><span>Cash on Delivery</span></div>
                <div class="meta-line"><span>Items</span><span><?php echo $total_items; ?> item(s)</span></div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $it): ?>
                <tr>
                    <td>
                        <div class="pd-item">
                            <img src="<?php echo htmlspecialchars($it['image']); ?>" alt="<?php echo htmlspecialchars($it['name']); ?>" onerror="this.onerror=null;this.src='<?php echo (strpos($it['image'], 'http') === 0) ? '' : 'images/'; ?>pa.png';">
                            <div>
                                <div class="pd-name"><?php echo htmlspecialchars($it['name']); ?></div>
                                <div class="pd-weight"><?php echo htmlspecialchars($it['weight']); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($it['price']); ?></td>
                    <td style="text-align:center;"><?php echo intval($it['quantity']); ?></td>
                    <td class="num total">₹<?php echo number_format(floatval($it['item_total']), 0); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- TOTALS -->
        <div class="invoice-totals">
            <div class="tot-row"><span>Subtotal</span><span><?php echo fmt($subtotal); ?></span></div>
            <div class="tot-row"><span>Shipping</span><span>FREE</span></div>
            <div class="tot-row tax"><span>CGST (9%)</span><span><?php echo fmt($cgst); ?></span></div>
            <div class="tot-row tax"><span>SGST (9%)</span><span><?php echo fmt($sgst); ?></span></div>
            <div class="tot-row grand"><span>Grand Total</span><span><?php echo fmt($grand_total); ?></span></div>
        </div>

        <!-- GST NOTE -->
        <div class="gst-note">
            <strong>Note:</strong> GST is included in the product prices shown. All jewellery items are subject to
            hallmarking and certification as per BIS standards. This is a computer-generated invoice and does not
            require a physical signature.
        </div>
    </div>

    <!-- FOOTER -->
    <div class="invoice-footer">
        <div class="thanks">Thank you for shopping with PASVI Jewellery! ♥</div>
        <p>For any queries regarding your order, contact our customer care.</p>
        <div class="contact-line">
            📍 Surat, Gujarat &nbsp;•&nbsp; 📞 +91 98765 43210 &nbsp;•&nbsp; ✉ care@pasvi.com
        </div>
    </div>
</div>

<?php endif; ?>

</body>
</html>

