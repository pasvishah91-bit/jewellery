<?php
session_start();
require_once 'db_config.php';

// Sync cart from MySQL to Session on page load
// This ensures cart persists even if session is cleared
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
    $session_id = session_id();
    $conn = getDBConnection();
    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM cart WHERE session_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $_SESSION['cart'][$row['product_id']] = [
                'id' => $row['product_id'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image'],
                'weight' => $row['weight'],
                'quantity' => intval($row['quantity'])
            ];
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart – PASVI Jewellery</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    background: #f9f9f9;
}

/* HEADER */
.top-header {
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 10px 70px;
    background: #fff;
}

.logo {
    text-align: center;
    color: #8b1c22;
    font-family: serif;
}

.logo h2 {
    font-size: 22px;
    line-height: 18px;
}

.search {
    width: 630px;
    height: 45px;
    border: 1px solid #ddd;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    background: #fff;
}

.search input {
    width: 90%;
    border: none;
    outline: none;
    font-size: 16px;
}

/* Search Wrapper & Dropdown */
.search-wrapper {
    position: relative;
    width: 630px;
}

.search-dropdown {
    position: absolute;
    top: 50px;
    left: 0;
    width: 100%;
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    display: none;
    z-index: 9999;
    max-height: 420px;
    overflow-y: auto;
}

.search-dropdown.active {
    display: block;
}

.search-dropdown .search-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f5f5f5;
}

.search-dropdown .search-result-item:last-child {
    border-bottom: none;
}

.search-dropdown .search-result-item:hover {
    background: #fdf8f0;
}

.search-dropdown .search-result-item img {
    width: 48px;
    height: 48px;
    object-fit: cover;
    border-radius: 6px;
}

.search-dropdown .search-result-item .sr-name {
    font-size: 14px;
    font-weight: 500;
    color: #222;
}

.search-dropdown .search-result-item .sr-price {
    font-size: 13px;
    color: #8b1c22;
    font-weight: 600;
}

.search-dropdown .search-result-item .sr-category {
    font-size: 11px;
    color: #999;
}

.search-dropdown .search-no-results {
    padding: 30px;
    text-align: center;
    color: #999;
    font-size: 14px;
}

.request-custom-btn {
    display: inline-block;
    margin-top: 12px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    color: #1a0a0c;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.request-custom-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

/* Custom Request Modal Styles */
.custom-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10001;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}

.custom-modal-overlay.active {
    display: flex;
}

.custom-modal {
    background: #fff;
    border-radius: 16px;
    width: 500px;
    max-width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 35px 40px 30px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}

@keyframes modalSlideIn {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.custom-modal .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f5f5f5;
}

.custom-modal .modal-header h2 {
    font-size: 22px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.custom-modal .modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #aaa;
    cursor: pointer;
    padding: 0 5px;
    transition: color 0.3s;
    line-height: 1;
}

.custom-modal .modal-close:hover {
    color: #8b1c22;
}

.custom-modal .form-row {
    margin-bottom: 18px;
}

.custom-modal .form-row label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
}

.custom-modal .form-row label .required {
    color: #e74c3c;
}

.custom-modal .form-row input,
.custom-modal .form-row textarea,
.custom-modal .form-row select {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
    transition: border-color 0.3s;
    background: #fafafa;
    outline: none;
}

.custom-modal .form-row input:focus,
.custom-modal .form-row textarea:focus,
.custom-modal .form-row select:focus {
    border-color: #d4af37;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.custom-modal .form-row textarea {
    resize: vertical;
    min-height: 80px;
}

.custom-modal .form-row-half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.custom-modal .form-error {
    font-size: 12px;
    color: #e74c3c;
    margin-top: 4px;
    display: none;
}

.custom-modal .form-error.show {
    display: block;
}

.custom-modal .modal-submit-btn {
    width: 100%;
    padding: 16px 0;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border: none;
    border-radius: 8px;
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.3s;
    letter-spacing: 1px;
}

.custom-modal .modal-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

.custom-modal .modal-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.custom-modal .modal-note {
    font-size: 12px;
    color: #999;
    margin-top: 15px;
    text-align: center;
    line-height: 1.5;
}

/* Cart Toast */
/* Wishlist Link & Badge */
.wishlist-link { position: relative; display: inline-block; text-decoration: none; color: #7d1d22; }
.wishlist-badge {
    position: absolute; top: -10px; right: -12px; background: #ff4757; color: #fff;
    font-size: 11px; font-weight: 600; border-radius: 50%; width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif; box-shadow: 0 2px 6px rgba(255, 71, 87, 0.4);
}
.cart-link { position: relative; display: inline-block; text-decoration: none; color: #7d1d22; }
.cart-badge {
    position: absolute; top: -10px; right: -12px; background: #8b1c22; color: #fff;
    font-size: 11px; font-weight: 600; border-radius: 50%; width: 20px; height: 20px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Poppins', sans-serif; box-shadow: 0 2px 6px rgba(139, 28, 34, 0.4);
}

/* Cart Toast */
.cart-toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, #2d0d10, #641820);
    color: #fff;
    padding: 16px 28px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 500;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.4s ease;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 12px;
    border-left: 4px solid #d4af37;
}

.cart-toast.show {
    transform: translateY(0);
    opacity: 1;
}

.cart-toast .toast-icon {
    font-size: 24px;
}

.cart-toast .toast-text {
    flex: 1;
}

.cart-toast .toast-close {
    background: none;
    border: none;
    color: rgba(255,255,255,0.6);
    font-size: 18px;
    cursor: pointer;
    padding: 0 4px;
}

.cart-toast .toast-close:hover {
    color: #fff;
}

.icons {
    font-size: 25px;
    color: #7d1d22;
    display: flex;
    align-items: center;
    gap: 12px;
}

nav {
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 45px;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
    background: #fff;
}

nav a {
    font-size: 17px;
    color: #333;
    cursor: pointer;
    text-decoration: none;
    transition: color 0.3s;
}

nav a:hover {
    color: #8b1c22;
}

/* CART PAGE */
.cart-page {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 40px;
}

.cart-page h1 {
    font-size: 34px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 8px;
}

.cart-sep {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #f9d423);
    margin: 12px 0 30px;
    border-radius: 2px;
}

.cart-empty {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
}

.cart-empty .cart-icon-big {
    font-size: 80px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.cart-empty h2 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.cart-empty p {
    color: #888;
    margin-bottom: 25px;
}

.cart-empty .shop-btn {
    display: inline-block;
    padding: 14px 40px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}

.cart-empty .shop-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 28, 34, 0.3);
}

.cart-items {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
}

.cart-header {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
    padding: 18px 25px;
    background: #fafafa;
    border-bottom: 1px solid #eee;
    font-weight: 600;
    color: #555;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cart-item {
    display: grid;
    grid-template-columns: 3fr 1fr 1fr 1fr 0.5fr;
    padding: 20px 25px;
    border-bottom: 1px solid #f0f0f0;
    align-items: center;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-product {
    display: flex;
    align-items: center;
    gap: 18px;
}

.cart-item-product img {
    width: 85px;
    height: 85px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-item-product .item-details h4 {
    font-size: 16px;
    font-weight: 500;
    color: #222;
    margin-bottom: 4px;
}

.cart-item-product .item-details .item-weight {
    font-size: 13px;
    color: #999;
}

.cart-item-product .item-details .item-price-text {
    font-size: 17px;
    font-weight: 600;
    color: #8b1c22;
    margin-top: 5px;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cart-item-quantity .qty-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #ddd;
    background: #fff;
    border-radius: 6px;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.cart-item-quantity .qty-btn:hover {
    border-color: #8b1c22;
    color: #8b1c22;
}

.cart-item-quantity .qty-num {
    font-size: 16px;
    font-weight: 500;
    width: 30px;
    text-align: center;
}

.cart-item-total {
    font-size: 18px;
    font-weight: 600;
    color: #8b1c22;
}

.cart-item-remove {
    text-align: right;
}

.cart-item-remove button {
    background: none;
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #ccc;
    transition: color 0.3s;
    padding: 5px;
}

.cart-item-remove button:hover {
    color: #e74c3c;
}

.cart-summary {
    margin-top: 30px;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.05);
    max-width: 400px;
    margin-left: auto;
}

.cart-summary h3 {
    font-size: 20px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 15px;
    color: #555;
}

.summary-row.total {
    border-top: 2px solid #eee;
    margin-top: 10px;
    padding-top: 15px;
    font-size: 20px;
    font-weight: 700;
    color: #8b1c22;
}

.checkout-btn {
    width: 100%;
    padding: 16px 0;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border: none;
    border-radius: 8px;
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 20px;
    transition: all 0.3s;
    letter-spacing: 1px;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

.continue-shopping {
    display: inline-block;
    margin-top: 20px;
    color: #888;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s;
}

.continue-shopping:hover {
    color: #8b1c22;
}

/* CHECKOUT MODAL */
.checkout-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(4px);
}

.checkout-modal-overlay.active {
    display: flex;
}

.checkout-modal {
    background: #fff;
    border-radius: 16px;
    width: 520px;
    max-width: 95%;
    max-height: 90vh;
    overflow-y: auto;
    padding: 35px 40px 30px;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease;
}

.checkout-modal .modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f5f5f5;
}

.checkout-modal .modal-header h2 {
    font-size: 24px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin: 0;
}

.checkout-modal .modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #aaa;
    cursor: pointer;
    padding: 0 5px;
    transition: color 0.3s;
    line-height: 1;
}

.checkout-modal .modal-close:hover {
    color: #8b1c22;
}

.checkout-modal .form-row {
    margin-bottom: 18px;
}

.checkout-modal .form-row label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #555;
    margin-bottom: 6px;
    letter-spacing: 0.5px;
}

.checkout-modal .form-row label .required {
    color: #e74c3c;
    margin-left: 2px;
}

.checkout-modal .form-row input,
.checkout-modal .form-row textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
    transition: border-color 0.3s;
    background: #fafafa;
    outline: none;
}

.checkout-modal .form-row input:focus,
.checkout-modal .form-row textarea:focus {
    border-color: #d4af37;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.checkout-modal .form-row textarea {
    resize: vertical;
    min-height: 70px;
}

.checkout-modal .form-row-half {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.checkout-modal .form-error {
    font-size: 12px;
    color: #e74c3c;
    margin-top: 4px;
    display: none;
}

.checkout-modal .form-error.show {
    display: block;
}

.checkout-modal .modal-submit-btn {
    width: 100%;
    padding: 16px 0;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    border: none;
    border-radius: 8px;
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    margin-top: 10px;
    transition: all 0.3s;
    letter-spacing: 1px;
}

.checkout-modal .modal-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

.checkout-modal .modal-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.checkout-modal .order-summary-mini {
    background: #f9f9f9;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-size: 14px;
    color: #555;
}

.checkout-modal .order-summary-mini .mini-row {
    display: flex;
    justify-content: space-between;
    padding: 4px 0;
}

.checkout-modal .order-summary-mini .mini-row.total {
    font-weight: 700;
    color: #8b1c22;
    font-size: 16px;
    border-top: 1px solid #ddd;
    padding-top: 8px;
    margin-top: 4px;
}

/* RESPONSIVE */
@media(max-width: 768px) {
    .top-header {
        flex-wrap: wrap;
        height: auto;
        padding: 15px 20px;
        gap: 15px;
    }
    .logo { order: 1; flex: 1; text-align: left; }
    .icons { order: 2; font-size: 20px; }
    .search, .search-wrapper { order: 3; width: 100%; height: 40px; }
    .search input { font-size: 14px; }
    nav { flex-wrap: wrap; height: auto; gap: 12px; padding: 15px 10px; }
    nav a { font-size: 14px; }
    .cart-page { padding: 0 20px; }
    .cart-page h1 { font-size: 26px; }
    .cart-header { grid-template-columns: 2fr 1fr 1fr 0.5fr; }
    .cart-header .cart-hdr-price { display: none; }
    .cart-item { grid-template-columns: 2fr 1fr 1fr 0.5fr; }
    .cart-item .cart-item-price-col { display: none; }
    .cart-item-product img { width: 60px; height: 60px; }
    .luxury-footer { padding: 40px 25px; }
    .footer-wrapper { grid-template-columns: 1fr; gap: 30px; }
    .cart-summary { max-width: 100%; }
}
</style>
</head>
<body>

<!-- HEADER -->
<header>
<div class="top-header">
<div class="logo">
<h2>♜<br>PASVI</h2>
</div>
<div class="search-wrapper">
<div class="search">
<input type="text" placeholder="Search for jewellery" id="searchInput">
<button>⌕</button>
</div>
<div class="search-dropdown" id="searchDropdown"></div>
</div>
<div class="icons">
💎 &nbsp; 🏬 &nbsp;
<a href="view_wishlist.php" class="wishlist-link" style="text-decoration:none;color:#7d1d22;position:relative;">
♡
<?php if(isset($_SESSION['wishlist']) && count($_SESSION['wishlist']) > 0) { ?>
<span class="wishlist-badge"><?php echo count($_SESSION['wishlist']); ?></span>
<?php } ?>
</a>
<a href="login.php" style="text-decoration:none;color:#7d1d22;">👤</a> &nbsp;
<a href="order.php" style="text-decoration:none;color:#7d1d22;">📋</a> &nbsp;
<a href="view_cart.php" style="text-decoration:none;color:#7d1d22;position:relative;" class="cart-link">
🛒
<?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
<span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
<?php } ?>
</a>
<?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
<a href="admin/dashboard.php" style="text-decoration:none;color:#8b1c22;font-size:16px;font-weight:600;margin-left:8px;" title="Admin Panel">⚙️</a>
<?php endif; ?>
</div>
</div>
<nav>
<a href="index.php">✧ All Jewellery</a>
<a href="gold.php">♢ Gold</a>
<a href="diamond.php">◇ Diamond</a>
<a href="earrings.php">♕ Earrings</a>
<a href="rings.php">◉ Rings</a>
<a href="dailywear.php">♧ Daily Wear</a>
<a href="wedding.php">◉ Wedding</a>
</nav>
<style>
nav a.active { color: #d4af37; font-weight: 600; }
</style>
</header>

<!-- CART CONTENT -->
<div class="cart-page">
<h1>Shopping Cart</h1>
<div class="cart-sep"></div>

<?php
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_empty = empty($cart);

// Total quantity of all items in cart
$total_items = 0;
foreach ($cart as $ci) {
    $total_items += isset($ci['quantity']) ? intval($ci['quantity']) : 1;
}

if ($cart_empty) {
?>
<div class="cart-empty">
<div class="cart-icon-big">🛒</div>
<h2>Your Cart is Empty</h2>
<p>Looks like you haven't added any jewellery to your cart yet.</p>
<a href="index.php" class="shop-btn">CONTINUE SHOPPING</a>
</div>
<?php } else { ?>
<div class="cart-items">
<div class="cart-header">
<span>Product</span>
<span class="cart-hdr-price">Price</span>
<span>Quantity</span>
<span>Total</span>
<span class="cart-hdr-remove">Remove</span>
</div>

<?php
$grand_total = 0;
foreach($cart as $item) {
    // Parse price - remove ₹ and commas
    $price_str = str_replace(['₹', ',', ' '], '', $item['price']);
    $price_val = floatval($price_str);
    $qty = $item['quantity'];
    $item_total = $price_val * $qty;
    $grand_total += $item_total;
?>
<div class="cart-item" data-id="<?php echo htmlspecialchars($item['id']); ?>">
<div class="cart-item-product">
<img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
<div class="item-details">
<h4><?php echo htmlspecialchars($item['name']); ?></h4>
<div class="item-weight"><?php echo htmlspecialchars($item['weight']); ?></div>
<div class="item-price-text"><?php echo htmlspecialchars($item['price']); ?></div>
</div>
</div>
<div class="cart-item-price-col"><?php echo htmlspecialchars($item['price']); ?></div>
<div class="cart-item-quantity">
<button class="qty-btn qty-minus" data-id="<?php echo htmlspecialchars($item['id']); ?>">−</button>
<span class="qty-num"><?php echo $qty; ?></span>
<button class="qty-btn qty-plus" data-id="<?php echo htmlspecialchars($item['id']); ?>">+</button>
</div>
<div class="cart-item-total">₹<?php echo number_format($item_total); ?></div>
<div class="cart-item-remove">
<button class="remove-item" data-id="<?php echo htmlspecialchars($item['id']); ?>">✕</button>
</div>
</div>
<?php } ?>
</div>

<div class="cart-summary">
<h3>Order Summary</h3>
<div class="summary-row"><span>Subtotal</span><span>₹<?php echo number_format($grand_total); ?></span></div>
<div class="summary-row"><span>Shipping</span><span>Free</span></div>
<div class="summary-row total"><span>Total</span><span>₹<?php echo number_format($grand_total); ?></span></div>
<button class="checkout-btn">PROCEED TO CHECKOUT</button>
<a href="index.php" class="continue-shopping">← Continue Shopping</a>
</div>
<?php } ?>
</div>

<?php if (!$cart_empty) { ?>
<!-- CHECKOUT MODAL -->
<div class="checkout-modal-overlay" id="checkoutModal">
<div class="checkout-modal">
<div class="modal-header">
<h2>🛍 Shipping Details</h2>
<button class="modal-close" id="modalCloseBtn">✕</button>
</div>

<div class="order-summary-mini" id="modalOrderSummary">
<div class="mini-row"><span>Items</span><span><?php echo $total_items; ?> item(s)</span></div>
<div class="mini-row"><span>Shipping</span><span>Free</span></div>
<div class="mini-row total"><span>Total</span><span>₹<?php echo number_format($grand_total); ?></span></div>
</div>

<form id="checkoutForm" onsubmit="return false;">
<input type="hidden" name="action" value="checkout">

<div class="form-row">
<label>Full Name <span class="required">*</span></label>
<input type="text" name="full_name" id="full_name" placeholder="Enter your full name" required>
<div class="form-error" id="full_name_error">Please enter your name</div>
</div>

<div class="form-row">
<label>Phone Number <span class="required">*</span></label>
<input type="tel" name="phone" id="phone" placeholder="Enter your phone number" required>
<div class="form-error" id="phone_error">Please enter your phone number</div>
</div>

<div class="form-row">
<label>Email (Optional)</label>
<input type="email" name="email" id="email" placeholder="Enter your email address" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
</div>

<div class="form-row">
<label>Address <span class="required">*</span></label>
<textarea name="address" id="address" placeholder="Enter your full address" required></textarea>
<div class="form-error" id="address_error">Please enter your address</div>
</div>

<div class="form-row-half">
<div class="form-row">
<label>City <span class="required">*</span></label>
<input type="text" name="city" id="city" placeholder="City" required>
<div class="form-error" id="city_error">Please enter your city</div>
</div>
<div class="form-row">
<label>Pincode (Optional)</label>
<input type="text" name="pincode" id="pincode" placeholder="Pincode">
</div>
</div>

<button type="submit" class="modal-submit-btn" id="placeOrderBtn">PLACE ORDER</button>
</form>
</div>
</div>
<?php } ?>

<script>
// Quantity controls
document.querySelectorAll('.qty-plus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const item = this.closest('.cart-item');
        const qtySpan = item.querySelector('.qty-num');
        let qty = parseInt(qtySpan.textContent) + 1;
        updateCartQuantity(id, qty, item);
    });
});

document.querySelectorAll('.qty-minus').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const item = this.closest('.cart-item');
        const qtySpan = item.querySelector('.qty-num');
        let qty = parseInt(qtySpan.textContent) - 1;
        if (qty < 1) qty = 1;
        updateCartQuantity(id, qty, item);
    });
});

function updateCartQuantity(id, qty, itemElement) {
    const formData = new FormData();
    formData.append('action', 'update_quantity');
    formData.append('id', id);
    formData.append('quantity', qty);

    fetch('cart_handler.php', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Remove items
document.querySelectorAll('.remove-item').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const formData = new FormData();
        formData.append('action', 'remove_from_cart');
        formData.append('id', id);

        fetch('cart_handler.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});

// ============ CHECKOUT MODAL ============
const modal = document.getElementById('checkoutModal');
const modalCloseBtn = document.getElementById('modalCloseBtn');
const checkoutForm = document.getElementById('checkoutForm');
const placeOrderBtn = document.getElementById('placeOrderBtn');

document.querySelector('.checkout-btn')?.addEventListener('click', function() {
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
});

function closeModal() {
    if (modal) {
        modal.classList.remove('active');
    }
    document.body.style.overflow = '';
}

modalCloseBtn?.addEventListener('click', closeModal);

modal?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

function showFieldError(fieldId) {
    const errorEl = document.getElementById(fieldId + '_error');
    if (errorEl) errorEl.classList.add('show');
}

function hideFieldError(fieldId) {
    const errorEl = document.getElementById(fieldId + '_error');
    if (errorEl) errorEl.classList.remove('show');
}

checkoutForm?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const full_name = document.getElementById('full_name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    
    ['full_name', 'phone', 'address', 'city'].forEach(hideFieldError);
    
    let valid = true;
    if (!full_name) { showFieldError('full_name'); valid = false; }
    if (!phone) { showFieldError('phone'); valid = false; }
    if (!address) { showFieldError('address'); valid = false; }
    if (!city) { showFieldError('city'); valid = false; }
    
    if (!valid) return;
    
    placeOrderBtn.disabled = true;
    placeOrderBtn.textContent = 'PLACING ORDER...';
    
    const formData = new FormData(this);
    
    fetch('checkout_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            closeModal();
            alert('✅ Order Placed Successfully!\n\nOrder ID: ' + data.order_id + '\nTotal: ₹' + data.total_amount.toLocaleString('en-IN') + '\nItems: ' + data.items_count + '\nCustomer: ' + data.customer_name + '\n\nThank you for shopping with PASVI!');
            window.location.reload();
        } else {
            placeOrderBtn.disabled = false;
            placeOrderBtn.textContent = 'PLACE ORDER';
            alert('Error: ' + (data.message || 'Checkout failed. Please try again.'));
        }
    })
    .catch(err => {
        placeOrderBtn.disabled = false;
        placeOrderBtn.textContent = 'PLACE ORDER';
        alert('Error processing checkout. Please try again.');
        console.error('Checkout error:', err);
    });
});
</script>

<?php include 'footer.php'; ?>
