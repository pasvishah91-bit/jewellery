<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'products_data.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? trim($_GET['id']) : '';
$product = getProductById($product_id);

if (!$product) {
    $not_found = true;
    $page_title = "Product Not Found – PASVI Jewellery";
    $page_description = "The product you are looking for does not exist or may have been removed.";
} else {
    $not_found = false;

    // Map category for suggestions / related page
    $cat_lower = strtolower($product['category']);
    $suggested_pages = [
        'all jewellery' => 'index.php',
        'daily wear' => 'dailywear.php',
        'gold' => 'gold.php',
        'diamond' => 'diamond.php',
        'earrings' => 'earrings.php',
        'rings' => 'rings.php',
        'wedding' => 'wedding.php'
    ];
    $suggested_page = isset($suggested_pages[$cat_lower]) ? $suggested_pages[$cat_lower] : 'index.php';
    $suggested_page_name = $product['category'];

    // Related products (same category, excluding current product)
    $related_products = getProductsByCategory($product['category']);
    $related_products = array_values(array_filter($related_products, function ($p) use ($product_id) {
        return $p['id'] !== $product_id;
    }));
    $related_products = array_slice($related_products, 0, 4);

    // SEO
    $page_title = $product['name'] . ' – PASVI Jewellery';
    $page_description = isset($product['description']) && trim($product['description']) !== ''
        ? mb_substr(trim(strip_tags($product['description'])), 0, 155)
        : $product['name'] . ' – ' . $product['weight'] . ' – Shop ' . $product['category'] . ' at PASVI Jewellery. BIS Hallmarked, certified quality, free shipping.';
    $og_image = (isset($product['image']) && $product['image']) ? $product['image'] : 'images/1.jpg';

    // Numeric price for structured data
    $numeric_price = preg_replace('/[^0-9]/', '', $product['price']);
    if ($numeric_price === '') {
        $numeric_price = '0';
    }

    // JSON-LD Product schema
    $json_ld = json_encode([
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product['name'],
        'image' => $product['image'],
        'description' => $page_description,
        'sku' => $product['id'],
        'category' => $product['category'],
        'brand' => ['@type' => 'Brand', 'name' => 'PASVI'],
        'offers' => [
            '@type' => 'Offer',
            'url' => 'product.php?id=' . urlencode($product['id']),
            'priceCurrency' => 'INR',
            'price' => $numeric_price,
            'availability' => 'https://schema.org/InStock',
            'seller' => ['@type' => 'Organization', 'name' => 'PASVI Jewellery']
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Wishlist state for this product
    $in_wishlist = isset($_SESSION['wishlist']) && isset($_SESSION['wishlist'][$product_id]);
}

include 'header.php';
?>
<style>
/* =====================================
   PRODUCT DETAIL PAGE STYLES
   ===================================== */
.product-detail {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px 60px;
}
.product-detail-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    align-items: start;
}
.product-gallery {
    position: sticky;
    top: 30px;
}
.product-gallery .img-zoom {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    background: #fafafa;
}
.product-gallery .main-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
    display: block;
}
.product-gallery .img-zoom:hover .main-image {
    transform: scale(1.08);
}

.product-info-section {
    padding-top: 10px;
}
.product-info-section .product-badge {
    display: inline-block;
    background: linear-gradient(135deg, #d4af37, #b8962b);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 5px 16px;
    border-radius: 4px;
    letter-spacing: 1px;
    margin-bottom: 15px;
}
.product-info-section h1 {
    font-size: 32px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 10px;
    line-height: 1.2;
}
.product-info-section .product-weight {
    font-size: 15px;
    color: #888;
    margin-bottom: 8px;
}
.product-info-section .product-sku {
    font-size: 12px;
    color: #bbb;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.product-info-section .product-category {
    font-size: 13px;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
}
.product-info-section .product-price {
    font-size: 36px;
    font-weight: 700;
    color: #8b1c22;
    font-family: 'Playfair Display', serif;
    margin-bottom: 8px;
}
.product-info-section .product-price small {
    font-size: 16px;
    font-weight: 400;
    color: #999;
}
.product-info-section .price-note {
    font-size: 13px;
    color: #999;
    margin-bottom: 25px;
}
.divider {
    width: 100%;
    height: 1px;
    background: #eee;
    margin: 25px 0;
}
.product-info-section .product-description {
    font-size: 15px;
    color: #666;
    line-height: 1.8;
    margin-bottom: 25px;
}
.product-highlights {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-bottom: 30px;
}
.highlight-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    color: #555;
}
.highlight-item .h-icon {
    width: 36px;
    height: 36px;
    background: #fdf8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}
.quantity-selector {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}
.quantity-selector label {
    font-size: 14px;
    font-weight: 500;
    color: #555;
}
.qty-controls {
    display: flex;
    align-items: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}
.qty-controls button {
    width: 40px;
    height: 40px;
    background: #fff;
    border: none;
    font-size: 20px;
    cursor: pointer;
    transition: background 0.2s;
    color: #333;
}
.qty-controls button:hover { background: #f5f5f5; }
.qty-controls span {
    width: 50px;
    text-align: center;
    font-size: 16px;
    font-weight: 500;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    height: 40px;
    line-height: 40px;
    user-select: none;
}
.action-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.add-to-cart-btn {
    flex: 1;
    padding: 16px 30px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 1px;
}
.add-to-cart-btn:hover {
    background: linear-gradient(135deg, #a5222a, #7d1d22);
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 28, 34, 0.3);
}
.add-to-cart-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}
.detail-wishlist-btn {
    width: 54px;
    height: 54px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
    cursor: pointer;
    font-size: 22px;
    transition: all 0.3s;
    color: #555;
}
.detail-wishlist-btn:hover {
    border-color: #ff4757;
    color: #ff4757;
}
.detail-wishlist-btn.filled {
    border-color: #ff4757;
    background: #fff5f5;
    color: #ff4757;
}
.custom-order-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    background: none;
    border: 1px dashed #d4af37;
    color: #a5821f;
    padding: 11px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}
.custom-order-link:hover {
    background: #fdf8f0;
    border-style: solid;
    transform: translateY(-1px);
}
.delivery-info {
    background: #fafafa;
    border-radius: 12px;
    padding: 20px;
    margin-top: 20px;
}
.delivery-info .d-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    font-size: 14px;
    color: #666;
}
.delivery-info .d-row .d-icon { font-size: 18px; }

/* ===== RELATED PRODUCTS ===== */
.related-products {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px 60px;
}
.related-products .section-title {
    text-align: center;
    margin: 20px auto 40px;
}
.related-products .section-title h2 {
    font-size: 30px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 8px;
}
.related-products .section-title p {
    font-size: 14px;
    color: #888;
    font-weight: 300;
}
.related-products .related-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

/* ===== ERROR PAGE ===== */
.error-page {
    text-align: center;
    padding: 100px 40px;
    max-width: 600px;
    margin: 0 auto;
}
.error-page .error-icon { font-size: 80px; margin-bottom: 20px; opacity: 0.4; }
.error-page h1 { font-size: 28px; color: #333; margin-bottom: 10px; }
.error-page p { color: #888; margin-bottom: 25px; font-size: 15px; }
.error-page .back-btn {
    display: inline-block;
    padding: 14px 40px;
    background: linear-gradient(135deg, #8b1c22, #641820);
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}
.error-page .back-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(139, 28, 34, 0.3);
}

/* ===== RESPONSIVE ===== */
@media(max-width: 1024px) {
    .product-detail-wrapper { gap: 30px; }
    .related-products .related-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .breadcrumb { padding: 0 20px; }
    .product-detail { padding: 0 20px 40px; }
    .product-detail-wrapper { grid-template-columns: 1fr; gap: 30px; }
    .product-gallery { position: static; }
    .product-info-section h1 { font-size: 24px; }
    .product-info-section .product-price { font-size: 28px; }
    .product-highlights { grid-template-columns: 1fr; }
    .action-buttons { flex-direction: column; }
    .detail-wishlist-btn { width: 100%; height: 48px; }
    .related-products { padding: 0 20px 40px; }
    .related-products .related-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
}
@media(max-width: 480px) {
    .related-products .related-grid { grid-template-columns: 1fr; }
}
</style>

<?php if ($not_found) { ?>
<!-- ERROR / NOT FOUND -->
<div class="error-page">
<div class="error-icon">🔍</div>
<h1>Product Not Found</h1>
<p>The product you're looking for doesn't exist or may have been removed.</p>
<a href="index.php" class="back-btn">← BACK TO SHOP</a>
</div>
<?php } else { ?>
<!-- BREADCRUMB -->
<div class="breadcrumb">
<a href="index.php">Home</a>
&nbsp;/&nbsp;
<a href="<?php echo htmlspecialchars($suggested_page); ?>"><?php echo htmlspecialchars($suggested_page_name); ?></a>
&nbsp;/&nbsp;
<span><?php echo htmlspecialchars($product['name']); ?></span>
</div>

<!-- PRODUCT DETAIL -->
<section class="product-detail">
<div class="product-detail-wrapper">
<!-- Gallery -->
<div class="product-gallery">
<div class="img-zoom">
<img class="main-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.onerror=null;this.src='images/pa.png';">
</div>
</div>

<!-- Product Info -->
<div class="product-info-section">
<span class="product-badge"><?php echo htmlspecialchars($product['category']); ?></span>
<h1><?php echo htmlspecialchars($product['name']); ?></h1>
<div class="product-sku">SKU: <?php echo htmlspecialchars($product['id']); ?></div>
<div class="product-weight"><?php echo htmlspecialchars($product['weight']); ?></div>
<div class="product-category">Category: <?php echo htmlspecialchars($product['category']); ?></div>
<div class="product-price"><?php echo htmlspecialchars($product['price']); ?> <small>MRP</small></div>
<div class="price-note">Inclusive of all taxes • Free Shipping</div>

<div class="divider"></div>

<div class="product-description">
<?php if (!empty($product['description'])): ?>
<?php echo nl2br(htmlspecialchars($product['description'])); ?>
<?php else: ?>
Experience the timeless elegance of this exquisite piece from PASVI's <?php echo htmlspecialchars($product['category']); ?> collection. Crafted with precision and care, this <?php echo htmlspecialchars($product['name']); ?> features <?php echo htmlspecialchars($product['weight']); ?> of premium quality material. Perfect for both special occasions and everyday grace.
<?php endif; ?>
</div>

<div class="product-highlights">
<div class="highlight-item"><span class="h-icon">📜</span> BIS Hallmarked</div>
<div class="highlight-item"><span class="h-icon">💎</span> Certified Quality</div>
<div class="highlight-item"><span class="h-icon">🔄</span> Easy Exchange</div>
<div class="highlight-item"><span class="h-icon">📦</span> Free Shipping</div>
<div class="highlight-item"><span class="h-icon">🔒</span> Secure Payment</div>
<div class="highlight-item"><span class="h-icon">✨</span> Lifetime Care</div>
</div>

<div class="quantity-selector">
<label>Quantity:</label>
<div class="qty-controls">
<button id="qtyMinus" type="button">−</button>
<span id="qtyDisplay">1</span>
<button id="qtyPlus" type="button">+</button>
</div>
</div>

<div class="action-buttons">
<button class="add-to-cart-btn" id="addToCartBtn"
    data-id="<?php echo htmlspecialchars($product['id']); ?>"
    data-name="<?php echo htmlspecialchars($product['name']); ?>"
    data-price="<?php echo htmlspecialchars($product['price']); ?>"
    data-image="<?php echo htmlspecialchars($product['image']); ?>"
    data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
ADD TO CART
</button>
<button class="detail-wishlist-btn" id="detailWishlistBtn" title="Add to Wishlist"
    data-id="<?php echo htmlspecialchars($product['id']); ?>"
    data-name="<?php echo htmlspecialchars($product['name']); ?>"
    data-price="<?php echo htmlspecialchars($product['price']); ?>"
    data-image="<?php echo htmlspecialchars($product['image']); ?>"
    data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
<?php echo $in_wishlist ? '❤️' : '♡'; ?>
</button>
</div>

<button class="custom-order-link" id="customOrderLink">
📦 Request a Custom Design for This Piece
</button>

<div class="delivery-info">
<div class="d-row"><span class="d-icon">🚚</span> Free delivery within 5–7 business days</div>
<div class="d-row"><span class="d-icon">🔄</span> 30-day easy exchange policy</div>
<div class="d-row"><span class="d-icon">📞</span> Call +91 98765 43210 for enquiries</div>
</div>
</div>
</div>
</section>

<?php if (!empty($related_products)) { ?>
<!-- RELATED PRODUCTS -->
<section class="related-products">
<div class="section-title">
<h2>You May Also Like</h2>
<p>More exquisite pieces from the <?php echo htmlspecialchars($product['category']); ?> collection</p>
</div>
<div class="related-grid">
<?php foreach ($related_products as $rp) { ?>
<div class="product-card"
    data-id="<?php echo htmlspecialchars($rp['id']); ?>"
    data-name="<?php echo htmlspecialchars($rp['name']); ?>"
    data-price="<?php echo htmlspecialchars($rp['price']); ?>"
    data-image="<?php echo htmlspecialchars($rp['image']); ?>"
    data-weight="<?php echo htmlspecialchars($rp['weight']); ?>">
<span class="product-badge"><?php echo htmlspecialchars($rp['category']); ?></span>
<img class="product-img" src="<?php echo htmlspecialchars($rp['image']); ?>" alt="<?php echo htmlspecialchars($rp['name']); ?>" onerror="this.onerror=null;this.src='images/pa.png';">
<div class="product-info">
<h4><?php echo htmlspecialchars($rp['name']); ?></h4>
<div class="weight"><?php echo htmlspecialchars($rp['weight']); ?></div>
<div class="price"><?php echo htmlspecialchars($rp['price']); ?> <small>MRP</small></div>
<div class="product-actions">
<button class="add-cart">ADD TO CART</button>
<div class="wishlist-btn">♡</div>
</div>
</div>
</div>
<?php } ?>
</div>
</section>
<?php } ?>
<?php } ?>

<script>
(function() {
    // ============ QUANTITY CONTROLS ============
    let quantity = 1;
    const qtyDisplay = document.getElementById('qtyDisplay');
    const qtyMinus = document.getElementById('qtyMinus');
    const qtyPlus = document.getElementById('qtyPlus');

    if (qtyMinus && qtyDisplay && qtyPlus) {
        qtyMinus.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                qtyDisplay.textContent = quantity;
            }
        });
        qtyPlus.addEventListener('click', function() {
            if (quantity < 99) {
                quantity++;
                qtyDisplay.textContent = quantity;
            }
        });
    }

    // ============ ADD TO CART (with quantity) ============
    const addToCartBtn = document.getElementById('addToCartBtn');

    function updateCartBadge(count) {
        let badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.textContent = count;
        } else if (count > 0) {
            const cartLink = document.querySelector('.cart-link');
            if (cartLink) {
                const newBadge = document.createElement('span');
                newBadge.className = 'cart-badge';
                newBadge.textContent = count;
                cartLink.appendChild(newBadge);
            }
        }
    }

    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const image = this.dataset.image;
            const weight = this.dataset.weight;

            if (!id || !name || !price) {
                if (window.showToast) window.showToast('Error: Product data missing');
                return;
            }

            this.disabled = true;
            this.textContent = 'ADDING...';

            const formData = new FormData();
            formData.append('action', 'add_to_cart');
            formData.append('id', id);
            formData.append('name', name);
            formData.append('price', price);
            formData.append('image', image);
            formData.append('weight', weight);
            formData.append('quantity', quantity);

            fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.disabled = false;
                this.textContent = 'ADD TO CART';
                if (data.success) {
                    if (window.showToast) window.showToast('✓ ' + quantity + ' × ' + name + ' added to cart!');
                    updateCartBadge(data.cart_count);
                } else {
                    if (window.showToast) window.showToast('Error: ' + (data.message || 'Could not add to cart'));
                }
            })
            .catch(err => {
                this.disabled = false;
                this.textContent = 'ADD TO CART';
                if (window.showToast) window.showToast('Error adding to cart');
                console.error('Cart error:', err);
            });
        });
    }

    // ============ WISHLIST (standalone button) ============
    const wishlistBtn = document.getElementById('detailWishlistBtn');

    function updateWishlistBadge(count) {
        const link = document.querySelector('.wishlist-link');
        if (!link) return;
        let badge = link.querySelector('.wishlist-badge');
        if (count > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.className = 'wishlist-badge';
                link.appendChild(badge);
            }
            badge.textContent = count;
        } else {
            if (badge) badge.remove();
        }
    }

    if (wishlistBtn) {
        <?php if ($in_wishlist) { ?>
        wishlistBtn.classList.add('filled');
        wishlistBtn.textContent = '❤️';
        <?php } ?>

        wishlistBtn.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const price = this.dataset.price;
            const image = this.dataset.image;
            const weight = this.dataset.weight;
            if (!id) return;

            const isFilled = this.classList.contains('filled');

            const formData = new FormData();
            if (isFilled) {
                formData.append('action', 'remove_from_wishlist');
                formData.append('id', id);
            } else {
                formData.append('action', 'add_to_wishlist');
                formData.append('id', id);
                formData.append('name', name);
                formData.append('price', price);
                formData.append('image', image);
                formData.append('weight', weight);
            }

            this.disabled = true;

            fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.disabled = false;
                if (data.success) {
                    if (isFilled) {
                        this.classList.remove('filled');
                        this.textContent = '♡';
                        if (window.showToast) window.showToast('Removed from wishlist');
                    } else {
                        this.classList.add('filled');
                        this.textContent = '❤️';
                        if (window.showToast) window.showToast('✓ ' + name + ' added to wishlist!');
                    }
                    updateWishlistBadge(data.wishlist_count);
                } else {
                    if (window.showToast) window.showToast('Error: ' + (data.message || 'Could not update wishlist'));
                }
            })
            .catch(err => {
                this.disabled = false;
                if (window.showToast) window.showToast('Error updating wishlist');
                console.error('Wishlist error:', err);
            });
        });
    }

    // ============ CUSTOM ORDER PRE-FILL ============
    const customOrderLink = document.getElementById('customOrderLink');
    if (customOrderLink && window.openCustomRequest) {
        customOrderLink.addEventListener('click', function() {
            const productName = <?php echo json_encode($product['name'] ?? ''); ?>;
            window.openCustomRequest(productName);
        });
    }

    // ============ PRODUCT CARD CLICK -> DETAIL ============
    document.querySelectorAll('.related-products .product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.closest('.add-cart') || e.target.closest('.wishlist-btn') || e.target.closest('button')) {
                return;
            }
            const id = this.dataset.id;
            if (id) {
                window.location.href = 'product.php?id=' + encodeURIComponent(id);
            }
        });
    });
})();
</script>

<?php include 'footer.php'; ?>

