<?php
session_start();
require_once 'db_config.php';

// Initialize wishlist session
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Sync wishlist from MySQL to Session on page load
$session_id = session_id();
$conn = getDBConnection();
if ($conn) {
    $stmt = $conn->prepare("SELECT * FROM wishlist WHERE session_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $session_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $_SESSION['wishlist'][$row['product_id']] = [
            'id' => $row['product_id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'weight' => $row['weight']
        ];
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Wishlist – PASVI Jewellery</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
body { background: #f9f9f9; }

/* HEADER */
.top-header {
    height: 90px; display: flex; align-items: center; justify-content: space-around;
    padding: 10px 70px; background: #fff;
}
.logo { text-align: center; color: #8b1c22; font-family: serif; }
.logo h2 { font-size: 22px; line-height: 18px; }
.search {
    width: 630px; height: 45px; border: 1px solid #ddd; border-radius: 30px;
    display: flex; align-items: center; justify-content: space-between; padding: 0 20px; background: #fff;
}
.search input { width: 90%; border: none; outline: none; font-size: 16px; }

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
.search-dropdown.active { display: block; }
.search-dropdown .search-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: background 0.2s;
    border-bottom: 1px solid #f5f5f5;
}
.search-dropdown .search-result-item:last-child { border-bottom: none; }
.search-dropdown .search-result-item:hover { background: #fdf8f0; }
.search-dropdown .search-result-item img { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; }
.search-dropdown .search-result-item .sr-name { font-size: 14px; font-weight: 500; color: #222; }
.search-dropdown .search-result-item .sr-price { font-size: 13px; color: #8b1c22; font-weight: 600; }
.search-dropdown .search-result-item .sr-category { font-size: 11px; color: #999; }
.search-dropdown .search-no-results { padding: 30px; text-align: center; color: #999; font-size: 14px; }
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
.icons { font-size: 25px; color: #7d1d22; display: flex; align-items: center; gap: 12px; }
nav {
    height: 65px; display: flex; align-items: center; justify-content: center;
    gap: 45px; border-top: 1px solid #eee; border-bottom: 1px solid #eee; background: #fff;
}
nav a { font-size: 17px; color: #333; cursor: pointer; text-decoration: none; transition: color 0.3s; }
nav a:hover { color: #8b1c22; }

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

/* Wishlist Page */
.wishlist-page { max-width: 1200px; margin: 40px auto; padding: 0 40px; }
.wishlist-page h1 { font-size: 34px; font-family: 'Playfair Display', serif; color: #222; margin-bottom: 8px; }
.wishlist-sep { width: 60px; height: 3px; background: linear-gradient(90deg, #ff6b81, #ff4757); margin: 12px 0 30px; border-radius: 2px; }

.wishlist-empty {
    text-align: center; padding: 80px 20px; background: #fff;
    border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.05);
}
.wishlist-empty .icon-big { font-size: 80px; margin-bottom: 20px; opacity: 0.3; }
.wishlist-empty h2 { font-size: 24px; color: #333; margin-bottom: 10px; }
.wishlist-empty p { color: #888; margin-bottom: 25px; }
.wishlist-empty .shop-btn { display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #ff4757, #e84118); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 500; transition: all 0.3s; }
.wishlist-empty .shop-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(255, 71, 87, 0.3); }

.wishlist-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }

.product-card {
    background: #fff; border-radius: 12px; overflow: hidden; transition: all 0.4s;
    border: 1px solid #f0f0f0; position: relative; cursor: pointer;
}
.product-card:hover {
    transform: translateY(-8px); box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    border-color: rgba(255, 71, 87, 0.3);
}
.product-card .product-img { width: 100%; aspect-ratio: 1 / 1; object-fit: cover; transition: transform 0.6s; }
.product-card:hover .product-img { transform: scale(1.05); }
.product-card .product-info { padding: 16px 18px 20px; }
.product-card .product-info h4 { font-size: 15px; font-weight: 500; color: #222; margin-bottom: 4px; }
.product-card .product-info .weight { font-size: 13px; color: #999; font-weight: 300; }
.product-card .product-info .price { font-size: 19px; font-weight: 600; color: #8b1c22; margin-top: 8px; font-family: 'Playfair Display', serif; }
.product-card .product-actions { display: flex; gap: 10px; margin-top: 14px; }
.product-card .product-actions .add-cart { flex: 1; padding: 10px 0; background: linear-gradient(135deg, #8b1c22, #641820); color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.3s; letter-spacing: 1px; }
.product-card .product-actions .add-cart:hover { background: linear-gradient(135deg, #a5222a, #7d1d22); transform: scale(1.02); }
.product-card .product-actions .remove-wishlist { width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border: 1px solid #ff4757; border-radius: 6px; background: #fff; cursor: pointer; font-size: 18px; transition: all 0.3s; color: #ff4757; }
.product-card .product-actions .remove-wishlist:hover { background: #ff4757; color: #fff; }

@media(max-width: 1024px) {
    .wishlist-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .top-header { flex-wrap: wrap; height: auto; padding: 15px 20px; gap: 15px; }
    .logo { order: 1; flex: 1; text-align: left; }
    .icons { order: 2; font-size: 20px; }
    .search { order: 3; width: 100%; height: 40px; }
    nav { flex-wrap: wrap; height: auto; gap: 12px; padding: 15px 10px; }
    nav a { font-size: 14px; }
    .wishlist-page { padding: 0 20px; }
    .wishlist-grid { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width: 480px) {
    .wishlist-grid { grid-template-columns: 1fr; }
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
    border-left: 4px solid #ff6b81;
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
</style>
</head>
<body>

<!-- HEADER -->
<header>
<div class="top-header">
<div class="logo"><h2>♜<br>PASVI</h2></div>
<div class="search-wrapper">
<div class="search">
<input type="text" placeholder="Search for jewellery" id="searchInput">
<button>⌕</button>
</div>
<div class="search-dropdown" id="searchDropdown"></div>
</div>
<div class="icons">
<a href="view_wishlist.php" class="wishlist-link">
♡
<?php if(isset($_SESSION['wishlist']) && count($_SESSION['wishlist']) > 0) { ?>
<span class="wishlist-badge"><?php echo count($_SESSION['wishlist']); ?></span>
<?php } ?>
</a>
<a href="login.php" class="cart-link">
👤 &nbsp;
</a>
<a href="order.php" style="text-decoration:none;color:#7d1d22;">📋</a> &nbsp;
<a href="view_cart.php" class="cart-link">🛒
<?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
<span class="cart-badge"><?php echo count($_SESSION['cart']); ?></span>
<?php } ?>
</a>
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
</header>

<!-- WISHLIST CONTENT -->
<div class="wishlist-page">
<h1>❤️ My Wishlist</h1>
<div class="wishlist-sep"></div>

<?php
$wishlist = isset($_SESSION['wishlist']) ? $_SESSION['wishlist'] : [];
if (empty($wishlist)) { ?>
<div class="wishlist-empty">
<div class="icon-big">❤️</div>
<h2>Your Wishlist is Empty</h2>
<p>Browse our collection and click the heart icon to save your favourite pieces.</p>
<a href="index.php" class="shop-btn">EXPLORE COLLECTION</a>
</div>
<?php } else { ?>
<div class="wishlist-grid">
<?php foreach($wishlist as $item) { ?>
<div class="product-card"
    data-id="<?php echo htmlspecialchars($item['id']); ?>"
    data-name="<?php echo htmlspecialchars($item['name']); ?>"
    data-price="<?php echo htmlspecialchars($item['price']); ?>"
    data-image="<?php echo htmlspecialchars($item['image']); ?>"
    data-weight="<?php echo htmlspecialchars($item['weight']); ?>">
<img class="product-img" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
<div class="product-info">
<h4><?php echo htmlspecialchars($item['name']); ?></h4>
<div class="weight"><?php echo htmlspecialchars($item['weight']); ?></div>
<div class="price"><?php echo htmlspecialchars($item['price']); ?></div>
<div class="product-actions">
<button class="add-cart">ADD TO CART</button>
<button class="remove-wishlist" data-id="<?php echo htmlspecialchars($item['id']); ?>">✕</button>
</div>
</div>
</div>
<?php } ?>
</div>
<?php } ?>
</div>

<!-- CUSTOM REQUEST MODAL -->
<div class="custom-modal-overlay" id="customRequestModal">
<div class="custom-modal">
<div class="modal-header">
<h2>📦 Request Custom Order</h2>
<button class="modal-close" id="customModalClose">✕</button>
</div>
<div class="form-row">
<label>What product are you looking for? <span class="required">*</span></label>
<input type="text" name="custom_product_name" id="custom_product_name" placeholder="e.g., Gold Diamond Necklace, Pearl Earrings..." required>
<div class="form-error" id="custom_product_name_error">Please describe what you're looking for</div>
</div>
<div class="form-row">
<label>Describe your requirements <span class="required">*</span></label>
<textarea name="custom_description" id="custom_description" placeholder="Describe the design, metal type, stone preference, approximate weight, occasion, etc." required></textarea>
<div class="form-error" id="custom_description_error">Please describe your requirements</div>
</div>
<div class="form-row-half">
<div class="form-row">
<label>Budget Range</label>
<select name="custom_budget" id="custom_budget">
<option value="">Select budget range</option>
<option value="Under ₹25,000">Under ₹25,000</option>
<option value="₹25,000 - ₹50,000">₹25,000 - ₹50,000</option>
<option value="₹50,000 - ₹1,00,000">₹50,000 - ₹1,00,000</option>
<option value="₹1,00,000 - ₹2,50,000">₹1,00,000 - ₹2,50,000</option>
<option value="Above ₹2,50,000">Above ₹2,50,000</option>
</select>
</div>
<div class="form-row">
<label>Your Name</label>
<input type="text" name="custom_name" id="custom_name" placeholder="Your name">
</div>
</div>
<div class="form-row-half">
<div class="form-row">
<label>Phone Number</label>
<input type="tel" name="custom_phone" id="custom_phone" placeholder="Your phone number">
</div>
<div class="form-row">
<label>Email</label>
<input type="email" name="custom_email" id="custom_email" placeholder="Your email">
</div>
</div>
<button type="submit" class="modal-submit-btn" id="customRequestBtn">SUBMIT REQUEST</button>
<div class="modal-note">Our team will get back to you within 24-48 hours with custom design options and pricing.</div>
</div>
</div>

<script>
// Click product card to view product detail page
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function() {
        const id = this.dataset.id;
        if (id) {
            window.location.href = 'product.php?id=' + encodeURIComponent(id);
        }
    });
});

// Add to cart from wishlist
document.querySelectorAll('.add-cart').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const card = this.closest('.product-card');
        if (!card) return;
        const id = card.dataset.id;
        const name = card.dataset.name;
        const price = card.dataset.price;
        const image = card.dataset.image;
        const weight = card.dataset.weight || '';
        const formData = new FormData();
        formData.append('action', 'add_to_cart');
        formData.append('id', id);
        formData.append('name', name);
        formData.append('price', price);
        formData.append('image', image);
        formData.append('weight', weight);
        fetch('cart_handler.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('✓ ' + name + ' added to cart!');
                window.location.reload();
            }
        })
        .catch(err => console.error(err));
    });
});

// Remove from wishlist
document.querySelectorAll('.remove-wishlist').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const id = this.dataset.id;
        const formData = new FormData();
        formData.append('action', 'remove_from_wishlist');
        formData.append('id', id);
        fetch('cart_handler.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(err => console.error(err));
    });
});

// ============ SEARCH FUNCTIONALITY ============
const searchInput = document.getElementById('searchInput');
const searchDropdown = document.getElementById('searchDropdown');
let searchTimer;

if (searchInput && searchDropdown) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        const query = this.value.trim();

        if (query.length < 1) {
            searchDropdown.classList.remove('active');
            return;
        }

        searchTimer = setTimeout(() => {
            fetch('search_handler.php?q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    let html = '';
                    data.results.forEach(item => {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='product.php?id=${encodeURIComponent(item.id)}'">
                                <img src="${item.image}" alt="${item.name}">
                                <div>
                                    <div class="sr-name">${item.name}</div>
                                    <div class="sr-price">${item.price}</div>
                                    <div class="sr-category">${item.category} • ${item.weight}</div>
                                </div>
                            </div>
                        `;
                    });
                    searchDropdown.innerHTML = html;
                    searchDropdown.classList.add('active');
                } else {
                    searchDropdown.innerHTML = '<div class="search-no-results">No products found for "' + query + '"<br><br><button class="request-custom-btn" onclick="openCustomRequest(\'' + encodeURIComponent(query) + '\')">📦 Request Custom Order</button></div>';
                    searchDropdown.classList.add('active');
                }
            })
            .catch(err => {
                console.error('Search error:', err);
            });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.closest('.search-wrapper') && !e.target.closest('.search-dropdown')) {
            searchDropdown.classList.remove('active');
        }
    });

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            searchDropdown.classList.remove('active');
            this.blur();
        }
    });
}

// ============ CUSTOM REQUEST MODAL ============
function openCustomRequest(searchQuery) {
    const modal = document.getElementById('customRequestModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        const productField = document.getElementById('custom_product_name');
        if (productField && searchQuery) {
            productField.value = decodeURIComponent(searchQuery);
        }
        const dd = document.getElementById('searchDropdown');
        if (dd) dd.classList.remove('active');
    }
}

function closeCustomRequest() {
    const modal = document.getElementById('customRequestModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const customModalClose = document.getElementById('customModalClose');
    const customModal = document.getElementById('customRequestModal');
    const customRequestBtn = document.getElementById('customRequestBtn');

    if (customModalClose) {
        customModalClose.addEventListener('click', closeCustomRequest);
    }
    if (customModal) {
        customModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCustomRequest();
            }
        });
    }
    if (customRequestBtn) {
        customRequestBtn.addEventListener('click', function() {
            const productName = document.getElementById('custom_product_name').value.trim();
            const description = document.getElementById('custom_description').value.trim();

            document.querySelectorAll('#customRequestModal .form-error').forEach(el => el.classList.remove('show'));

            let valid = true;
            if (!productName) {
                document.getElementById('custom_product_name_error').classList.add('show');
                valid = false;
            }
            if (!description) {
                document.getElementById('custom_description_error').classList.add('show');
                valid = false;
            }
            if (!valid) return;

            const budget = document.getElementById('custom_budget').value;
            const name = document.getElementById('custom_name').value.trim();
            const phone = document.getElementById('custom_phone').value.trim();
            const email = document.getElementById('custom_email').value.trim();

            this.disabled = true;
            this.textContent = 'SUBMITTING...';

            const formData = new FormData();
            formData.append('action', 'custom_request');
            formData.append('product_name', productName);
            formData.append('description', description);
            formData.append('budget', budget);
            formData.append('name', name);
            formData.append('phone', phone);
            formData.append('email', email);

            fetch('search_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closeCustomRequest();
                    alert('✅ Custom request submitted! Our team will contact you soon.');
                    document.getElementById('custom_product_name').value = '';
                    document.getElementById('custom_description').value = '';
                    document.getElementById('custom_budget').value = '';
                    document.getElementById('custom_name').value = '';
                    document.getElementById('custom_phone').value = '';
                    document.getElementById('custom_email').value = '';
                } else {
                    alert('Error: ' + (data.message || 'Could not submit request'));
                }
                customRequestBtn.disabled = false;
                customRequestBtn.textContent = 'SUBMIT REQUEST';
            })
            .catch(err => {
                customRequestBtn.disabled = false;
                customRequestBtn.textContent = 'SUBMIT REQUEST';
                alert('Error submitting request. Please try again.');
                console.error('Custom request error:', err);
            });
        });
    }
});
</script>

<?php include 'footer.php'; ?>

