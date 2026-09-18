<?php
session_start();
define('JEWELLERY_ACCESS', true);

$diamond_products = [
    ["image" => "images/5.jpg", "title" => "Solitaire Diamond Ring", "weight" => "0.50 Ct (18K)", "price" => "₹67,800"],
    ["image" => "images/2.jpg", "title" => "Diamond Pendant Set", "weight" => "0.75 Ct (18K)", "price" => "₹82,500"],
    ["image" => "images/7.jpg", "title" => "Diamond Stud Earrings", "weight" => "0.30 Ct (18K)", "price" => "₹45,999"],
    ["image" => "images/8.jpg", "title" => "Diamond Tennis Bracelet", "weight" => "2.00 Ct (18K)", "price" => "₹1,55,000"],
    ["image" => "images/3.jpg", "title" => "Diamond Nose Pin", "weight" => "0.15 Ct (18K)", "price" => "₹18,900"],
    ["image" => "images/10.jpg", "title" => "Diamond Drop Earrings", "weight" => "0.60 Ct (18K)", "price" => "₹62,400"],
    ["image" => "images/4.jpg", "title" => "Diamond Mangalsutra", "weight" => "1.00 Ct (22K)", "price" => "₹1,12,500"],
    ["image" => "images/12.jpg", "title" => "Diamond Cocktail Ring", "weight" => "1.25 Ct (18K)", "price" => "₹1,38,200"],
    ["image" => "images/6.jpg", "title" => "Diamond Choker Necklace", "weight" => "3.00 Ct (18K)", "price" => "₹2,65,000"],
    ["image" => "images/9.jpg", "title" => "Diamond Bangle Set", "weight" => "2.50 Ct (22K)", "price" => "₹1,92,800"],
    ["image" => "images/11.jpg", "title" => "Diamond Halo Ring", "weight" => "0.80 Ct (18K)", "price" => "₹95,600"],
    ["image" => "images/1.jpg", "title" => "Diamond Bridal Set", "weight" => "4.00 Ct (18K)", "price" => "₹3,85,500"]
];

$diamond_categories = [
    ["name" => "Diamond Rings", "image" => "images/5.jpg"],
    ["name" => "Diamond Earrings", "image" => "images/7.jpg"],
    ["name" => "Diamond Necklaces", "image" => "images/9.jpg"],
    ["name" => "Diamond Pendants", "image" => "images/2.jpg"],
    ["name" => "Diamond Bracelets", "image" => "images/8.jpg"],
    ["name" => "Diamond Mangalsutra", "image" => "images/4.jpg"],
    ["name" => "Diamond Nose Pins", "image" => "images/3.jpg"],
    ["name" => "Diamond Bridal Sets", "image" => "images/1.jpg"]
];

$diamond_cuts = [
    ["cut" => "Round Brilliant", "icon" => "◇", "desc" => "The most popular cut with 58 facets for maximum brilliance and fire. Timeless, classic, and universally loved.", "color" => "#e8f0fe"],
    ["cut" => "Princess Cut", "icon" => "◈", "desc" => "A square or rectangular cut with sharp corners. Modern, edgy, and the second most popular diamond shape.", "color" => "#d0e2f7"],
    ["cut" => "Cushion Cut", "icon" => "✦", "desc" => "A square cut with rounded corners, resembling a pillow. Vintage charm with a soft, romantic glow.", "color" => "#b8d4f0"],
    ["cut" => "Emerald Cut", "icon" => "❖", "desc" => "Step-cut with a rectangular shape and dramatic hall-of-mirrors effect. Sophisticated and elegant.", "color" => "#a0c6e8"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Diamond Luxe Collection"],
    ["image" => "images/19.jpg", "title" => "Bridal Diamond Edit"],
    ["image" => "images/20.jpg", "title" => "Everyday Diamond Range"],
    ["image" => "images/21.jpg", "title" => "Modern Diamond Trends"],
    ["image" => "images/22.jpg", "title" => "Heritage Diamond Line"],
    ["image" => "images/23.jpg", "title" => "Contemporary Classics"]
];
?>
<?php include 'header.php'; ?>
<style>
/* ===== DIAMOND PAGE SPECIFIC STYLES ===== */
.diamond-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 30%, #2c4a6e 50%, #1a2d4a 70%, #0a1628 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.diamond-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(173, 216, 255, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(200, 230, 255, 0.08) 0%, transparent 50%);
}

.diamond-hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.diamond-sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: #fff;
    border-radius: 50%;
    animation: sparkleFloat 5s infinite ease-in-out;
    opacity: 0;
    box-shadow: 0 0 6px rgba(173, 216, 255, 0.9), 0 0 12px rgba(173, 216, 255, 0.4);
}

.diamond-sparkle:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; width: 5px; height: 5px; }
.diamond-sparkle:nth-child(2) { top: 20%; left: 80%; animation-delay: 0.7s; width: 7px; height: 7px; }
.diamond-sparkle:nth-child(3) { top: 50%; left: 20%; animation-delay: 1.4s; width: 4px; height: 4px; }
.diamond-sparkle:nth-child(4) { top: 70%; left: 75%; animation-delay: 2.1s; width: 6px; height: 6px; }
.diamond-sparkle:nth-child(5) { top: 30%; left: 50%; animation-delay: 0.4s; width: 5px; height: 5px; }
.diamond-sparkle:nth-child(6) { top: 80%; left: 35%; animation-delay: 1.1s; width: 6px; height: 6px; }
.diamond-sparkle:nth-child(7) { top: 15%; left: 40%; animation-delay: 1.8s; width: 4px; height: 4px; }
.diamond-sparkle:nth-child(8) { top: 60%; left: 90%; animation-delay: 0.5s; width: 7px; height: 7px; }
.diamond-sparkle:nth-child(9) { top: 40%; left: 5%; animation-delay: 1.6s; width: 5px; height: 5px; }
.diamond-sparkle:nth-child(10) { top: 90%; left: 60%; animation-delay: 2.5s; width: 4px; height: 4px; }
.diamond-sparkle:nth-child(11) { top: 12%; left: 88%; animation-delay: 0.9s; width: 6px; height: 6px; }
.diamond-sparkle:nth-child(12) { top: 72%; left: 18%; animation-delay: 2s; width: 5px; height: 5px; }
.diamond-sparkle:nth-child(13) { top: 38%; left: 45%; animation-delay: 0.3s; width: 4px; height: 4px; }
.diamond-sparkle:nth-child(14) { top: 95%; left: 48%; animation-delay: 2.8s; width: 5px; height: 5px; }
.diamond-sparkle:nth-child(15) { top: 52%; left: 65%; animation-delay: 1.3s; width: 6px; height: 6px; }

@keyframes sparkleFloat {
    0% { transform: translateY(0) scale(0); opacity: 0; }
    20% { opacity: 1; }
    50% { transform: translateY(-50px) scale(1.5); opacity: 1; }
    80% { opacity: 0.6; }
    100% { transform: translateY(-100px) scale(0); opacity: 0; }
}

.diamond-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
    padding: 0 20px;
}

.diamond-hero-content .diamond-badge {
    display: inline-block;
    background: rgba(173, 216, 255, 0.15);
    border: 1px solid rgba(173, 216, 255, 0.35);
    padding: 8px 28px;
    border-radius: 30px;
    font-size: 13px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #add8ff;
    margin-bottom: 25px;
    font-weight: 500;
}

.diamond-hero-content h1 {
    font-size: 70px;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 4px;
    background: linear-gradient(135deg, #ffffff, #add8ff, #7ec8e3, #ffffff);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: diamondShimmer 4s ease-in-out infinite;
    margin-bottom: 15px;
}

@keyframes diamondShimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.diamond-hero-content p {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 300;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.diamond-hero-content .diamond-btn {
    display: inline-block;
    padding: 16px 50px;
    background: linear-gradient(135deg, #5a9bd5, #3a7bbd);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 2px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.4s;
    border: none;
    cursor: pointer;
}

.diamond-hero-content .diamond-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(90, 155, 213, 0.4);
}

/* =====================================
   DIAMOND BREADCRUMB
   ===================================== */
.diamond-breadcrumb {
    max-width: 1300px;
    margin: 20px auto;
    padding: 0 40px;
    font-size: 14px;
    color: #888;
}

.diamond-breadcrumb a {
    color: #888;
    text-decoration: none;
    transition: color 0.3s;
}

.diamond-breadcrumb a:hover {
    color: #5a9bd5;
}

.diamond-breadcrumb span {
    color: #333;
    font-weight: 500;
}

/* =====================================
   SECTION TITLES
   ===================================== */
.section-title {
    text-align: center;
    margin: 60px auto 40px;
    padding: 0 20px;
}

.section-title h2 {
    font-size: 34px;
    font-family: 'Playfair Display', serif;
    color: #222;
    margin-bottom: 8px;
}

.section-title .diamond-sep {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #add8ff, #5a9bd5);
    margin: 12px auto;
    border-radius: 2px;
}

.section-title p {
    font-size: 15px;
    color: #888;
    font-weight: 300;
}

/* =====================================
   DIAMOND CATEGORIES
   ===================================== */
.diamond-categories {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px;
}

.diamond-cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.diamond-cat-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 10px;
    aspect-ratio: 1 / 1;
    transition: all 0.4s;
}

.diamond-cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}

.diamond-cat-card:hover img {
    transform: scale(1.08);
}

.diamond-cat-card .cat-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(10, 22, 40, 0.85));
    padding: 20px 15px;
    text-align: center;
}

.diamond-cat-card .cat-overlay h4 {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 1px;
}

.diamond-cat-card .cat-overlay .diamond-line-small {
    width: 30px;
    height: 2px;
    background: #add8ff;
    margin: 8px auto;
}

/* =====================================
   DIAMOND PRODUCT GRID
   ===================================== */
.diamond-products {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px 40px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 22px;
}

.product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.4s;
    cursor: pointer;
    border: 1px solid #f0f0f0;
    position: relative;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    border-color: rgba(90, 155, 213, 0.3);
}

.product-card .product-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #5a9bd5, #3a7bbd);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 4px;
    letter-spacing: 1px;
    z-index: 2;
}

.product-card .product-img {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    transition: transform 0.6s;
}

.product-card:hover .product-img {
    transform: scale(1.05);
}

.product-card .product-info {
    padding: 16px 18px 20px;
}

.product-card .product-info h4 {
    font-size: 15px;
    font-weight: 500;
    color: #222;
    margin-bottom: 4px;
}

.product-card .product-info .weight {
    font-size: 13px;
    color: #999;
    font-weight: 300;
}

.product-card .product-info .price {
    font-size: 19px;
    font-weight: 600;
    color: #1a2d4a;
    margin-top: 8px;
    font-family: 'Playfair Display', serif;
}

.product-card .product-info .price small {
    font-size: 13px;
    font-weight: 400;
    color: #888;
}

.product-card .product-actions {
    display: flex;
    gap: 10px;
    margin-top: 14px;
}

.product-card .product-actions .add-cart {
    flex: 1;
    padding: 10px 0;
    background: linear-gradient(135deg, #1a2d4a, #0a1628);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    letter-spacing: 1px;
}

.product-card .product-actions .add-cart:hover {
    background: linear-gradient(135deg, #2c4a6e, #1a2d4a);
    transform: scale(1.02);
}

.product-card .product-actions .wishlist {
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    font-size: 18px;
    transition: all 0.3s;
}

.product-card .product-actions .wishlist:hover {
    border-color: #5a9bd5;
    color: #5a9bd5;
}

/* =====================================
   DIAMOND CUT GUIDE SECTION
   ===================================== */
.diamond-cuts {
    background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 30%, #0a1628 100%);
    padding: 70px 40px;
    margin: 40px 0;
}

.cuts-container {
    max-width: 1300px;
    margin: 0 auto;
}

.cuts-container .section-title h2 {
    color: #fff;
}

.cuts-container .section-title p {
    color: rgba(255, 255, 255, 0.6);
}

.cuts-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 20px;
}

.cuts-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(173, 216, 255, 0.15);
    border-radius: 16px;
    padding: 36px 24px;
    text-align: center;
    transition: all 0.4s;
    backdrop-filter: blur(10px);
}

.cuts-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(173, 216, 255, 0.4);
    transform: translateY(-5px);
}

.cuts-card .cuts-icon {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
    color: #fff;
}

.cuts-card h3 {
    font-size: 20px;
    font-family: 'Playfair Display', serif;
    color: #add8ff;
    margin-bottom: 5px;
}

.cuts-card .cuts-name {
    font-size: 12px;
    color: rgba(173, 216, 255, 0.7);
    letter-spacing: 2px;
    margin-bottom: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.cuts-card p {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
}

.cuts-card .cuts-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 30px;
    border: 1px solid #add8ff;
    color: #add8ff;
    background: transparent;
    border-radius: 30px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    letter-spacing: 1px;
}

.cuts-card .cuts-btn:hover {
    background: #add8ff;
    color: #0a1628;
}

/* =====================================
   DESIGNER PICKS
   ===================================== */
.designer-picks {
    max-width: 1300px;
    margin: 40px auto;
    padding: 0 40px;
}

.picks-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.pick-card {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    aspect-ratio: 3 / 2;
    cursor: pointer;
}

.pick-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}

.pick-card:hover img {
    transform: scale(1.08);
}

.pick-card .pick-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent 20%, rgba(10, 22, 40, 0.85));
    padding: 30px 20px 20px;
    text-align: center;
}

.pick-card .pick-overlay h4 {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 1px;
}

.pick-card .pick-overlay .diamond-line-small {
    width: 30px;
    height: 2px;
    background: #add8ff;
    margin: 8px auto;
}

/* =====================================
   DIAMOND FEATURES
   ===================================== */
.diamond-features {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    max-width: 1300px;
    margin: 50px auto;
    padding: 0 40px;
    gap: 20px;
}

.feature-box {
    text-align: center;
    padding: 30px 15px;
    border: 1px solid #f0f0f0;
    border-radius: 12px;
    transition: all 0.3s;
}

.feature-box:hover {
    border-color: #add8ff;
    box-shadow: 0 10px 30px rgba(173, 216, 255, 0.15);
}

.feature-box .feature-icon {
    font-size: 32px;
    margin-bottom: 12px;
}

.feature-box h5 {
    font-size: 15px;
    font-weight: 600;
    color: #333;
    margin-bottom: 5px;
}

.feature-box p {
    font-size: 13px;
    color: #999;
    font-weight: 300;
}

/* =====================================
   BANNER CTA
   ===================================== */
.diamond-cta {
    background: linear-gradient(135deg, #0a1628 0%, #1a2d4a 50%, #2c4a6e 100%);
    text-align: center;
    padding: 70px 40px;
    margin: 40px 0;
}

.diamond-cta h2 {
    font-size: 36px;
    font-family: 'Playfair Display', serif;
    color: #fff;
    margin-bottom: 10px;
}

.diamond-cta p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 16px;
    margin-bottom: 25px;
}

.diamond-cta .cta-btn {
    display: inline-block;
    padding: 16px 50px;
    background: linear-gradient(135deg, #5a9bd5, #3a7bbd);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 2px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s;
}

.diamond-cta .cta-btn:hover {
    background: linear-gradient(135deg, #7ec8e3, #5a9bd5);
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(90, 155, 213, 0.3);
}

/* =====================================
   FOOTER
   ===================================== */
.luxury-footer {
    background: linear-gradient(135deg, #2b080b, #641820);
    color: white;
    padding: 60px 80px 20px;
    font-family: 'Poppins', sans-serif;
}

.footer-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1.3fr;
    gap: 50px;
}

.footer-brand h2 {
    font-size: 38px;
    letter-spacing: 5px;
    color: #d4af37;
}

.gold-line {
    width: 80px;
    height: 3px;
    background: #d4af37;
    margin: 15px 0;
}

.footer-brand p {
    line-height: 1.8;
    font-size: 14px;
    color: #ddd;
    max-width: 350px;
}

.footer-social a {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 35px;
    height: 35px;
    border: 1px solid #d4af37;
    border-radius: 50%;
    color: #d4af37;
    text-decoration: none;
    margin-right: 10px;
    margin-top: 20px;
    transition: .3s;
}

.footer-social a:hover {
    background: #d4af37;
    color: #3b0b0f;
    transform: translateY(-5px);
}

.footer-menu h3,
.footer-contact h3 {
    color: #d4af37;
    font-size: 20px;
    margin-bottom: 20px;
}

.footer-menu a {
    display: block;
    color: #ddd;
    text-decoration: none;
    font-size: 14px;
    margin: 13px 0;
    transition: .3s;
}

.footer-menu a:hover {
    color: #d4af37;
    padding-left: 8px;
}

.footer-contact p {
    color: #ddd;
    font-size: 14px;
    margin-bottom: 12px;
}

.news-title {
    margin-top: 25px;
}

.subscribe {
    display: flex;
    margin-top: 10px;
}

.subscribe input {
    width: 160px;
    padding: 12px;
    border: none;
    outline: none;
    font-size: 14px;
}

.subscribe button {
    background: #d4af37;
    border: none;
    padding: 0 20px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s;
}

.subscribe button:hover {
    background: #c19b2e;
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, .3);
    margin-top: 50px;
    padding-top: 20px;
    text-align: center;
    color: #ddd;
    font-size: 14px;
}

/* =====================================
   RESPONSIVE
   ===================================== */
@media(max-width: 1024px) {
    .top-header {
        padding: 10px 30px;
    }
    .search {
        width: 350px;
    }
    nav {
        gap: 25px;
    }
    .diamond-hero-content h1 {
        font-size: 52px;
    }
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .diamond-cat-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    .cuts-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    .picks-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media(max-width: 768px) {
    .top-header {
        flex-wrap: wrap;
        height: auto;
        padding: 15px 20px;
        gap: 15px;
    }
    .logo {
        order: 1;
        flex: 1;
        text-align: left;
    }
    .icons {
        order: 2;
        font-size: 20px;
    }
    .search {
        order: 3;
        width: 100%;
        height: 40px;
    }
    .search input {
        font-size: 14px;
    }
    nav {
        flex-wrap: wrap;
        height: auto;
        gap: 12px;
        padding: 15px 10px;
    }
    nav a {
        font-size: 14px;
    }

    .diamond-hero {
        height: 380px;
    }
    .diamond-hero-content h1 {
        font-size: 40px;
    }
    .diamond-hero-content p {
        font-size: 15px;
    }

    .diamond-cat-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .diamond-breadcrumb {
        padding: 0 20px;
    }
    .diamond-categories {
        padding: 0 20px;
    }
    .diamond-products {
        padding: 0 20px 20px;
    }

    .cuts-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .picks-grid {
        grid-template-columns: 1fr;
    }
    .designer-picks {
        padding: 0 20px;
    }

    .diamond-features {
        grid-template-columns: repeat(2, 1fr);
        padding: 0 20px;
    }

    .section-title h2 {
        font-size: 26px;
    }

    .diamond-cta h2 {
        font-size: 28px;
    }

    .footer-wrapper {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    .luxury-footer {
        padding: 40px 25px;
    }
}

@media(max-width: 480px) {
    .diamond-hero {
        height: 320px;
    }
    .diamond-hero-content h1 {
        font-size: 32px;
    }
    .diamond-hero-content .diamond-badge {
        font-size: 11px;
        padding: 6px 18px;
    }
    .diamond-hero-content .diamond-btn {
        padding: 12px 30px;
        font-size: 14px;
    }
    .diamond-cat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .product-grid {
        grid-template-columns: 1fr;
    }
    .cuts-grid {
        grid-template-columns: 1fr;
    }
    .diamond-features {
        grid-template-columns: 1fr;
    }
    .icons {
        font-size: 16px;
    }
    nav a {
        font-size: 12px;
    }
}
</style>


<!-- DIAMOND HERO BANNER -->

<section class="diamond-hero">

<div class="diamond-hero-bg"></div>

<div class="diamond-hero-particles">
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
    <div class="diamond-sparkle"></div>
</div>

<div class="diamond-hero-content">

<div class="diamond-badge">Exclusive Collection</div>

<h1>DIAMOND</h1>

<p>Discover our brilliant diamond jewellery — Solitaire, Princess, Cushion & more • Certified diamonds crafted to perfection</p>

<a href="#products" class="diamond-btn">EXPLORE COLLECTION</a>

</div>

</section>


<!-- BREADCRUMB -->

<div class="diamond-breadcrumb">
<a href="index.php">Home</a> &nbsp;/&nbsp; <span>Diamond Jewellery</span>
</div>


<!-- DIAMOND CATEGORIES -->

<section class="diamond-categories">

<div class="section-title">
<h2>Shop by Category</h2>
<div class="diamond-sep"></div>
<p>Browse our exquisite diamond jewellery collection</p>
</div>

<div class="diamond-cat-grid">

<?php foreach($diamond_categories as $cat) { ?>

<div class="diamond-cat-card">

<img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">

<div class="cat-overlay">
<div class="diamond-line-small"></div>
<h4><?php echo $cat['name']; ?></h4>
</div>

</div>

<?php } ?>

</div>

</section>


<!-- DIAMOND PRODUCTS -->

<section class="diamond-products" id="products">

<div class="section-title">
<h2>Diamond Jewellery Collection</h2>
<div class="diamond-sep"></div>
<p>Brilliant diamond pieces for every occasion</p>
</div>

<div class="product-grid">

<?php foreach($diamond_products as $index => $product) { 
    $pid = 'diamond_' . $index;
?>

<div class="product-card"
    data-id="<?php echo $pid; ?>"
    data-name="<?php echo htmlspecialchars($product['title']); ?>"
    data-price="<?php echo htmlspecialchars($product['price']); ?>"
    data-image="<?php echo htmlspecialchars($product['image']); ?>"
    data-weight="<?php echo htmlspecialchars($product['weight']); ?>">

<span class="product-badge">CERTIFIED</span>

<img class="product-img" src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">

<div class="product-info">

<h4><?php echo $product['title']; ?></h4>
<div class="weight"><?php echo $product['weight']; ?></div>
<div class="price"><?php echo $product['price']; ?> <small>MRP</small></div>

<div class="product-actions">

<button class="add-cart">ADD TO CART</button>

<div class="wishlist-btn">♡</div>

</div>

</div>

</div>

<?php } ?>

</div>

</section>


<!-- DIAMOND CUT GUIDE -->

<section class="diamond-cuts">

<div class="cuts-container">

<div class="section-title">
<h2>Diamond Cut Guide</h2>
<div class="diamond-sep"></div>
<p>Understanding diamond cuts — find the perfect shape for your style</p>
</div>

<div class="cuts-grid">

<?php foreach($diamond_cuts as $cut) { ?>

<div class="cuts-card">

<span class="cuts-icon"><?php echo $cut['icon']; ?></span>

<h3><?php echo $cut['cut']; ?></h3>
<div class="cuts-name">Diamond Cut</div>

<p><?php echo $cut['desc']; ?></p>

<button class="cuts-btn">Explore <?php echo explode(' ', $cut['cut'])[0]; ?></button>

</div>

<?php } ?>

</div>

</div>

</section>


<!-- DESIGNER PICKS -->

<section class="designer-picks">

<div class="section-title">
<h2>Designer's Pick</h2>
<div class="diamond-sep"></div>
<p>Curated collections by our master jewellers</p>
</div>

<div class="picks-grid">

<?php foreach($designer_picks as $pick) { ?>

<div class="pick-card">

<img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>">

<div class="pick-overlay">
<div class="diamond-line-small"></div>
<h4><?php echo $pick['title']; ?></h4>
</div>

</div>

<?php } ?>

</div>

</section>


<!-- FEATURES -->

<div class="diamond-features">

<div class="feature-box">
<div class="feature-icon">📜</div>
<h5>Certified Diamonds</h5>
<p>IGI & GIA certified diamonds</p>
</div>

<div class="feature-box">
<div class="feature-icon">🔄</div>
<h5>Easy Exchange</h5>
<p>Hassle-free return & exchange</p>
</div>

<div class="feature-box">
<div class="feature-icon">📦</div>
<h5>Free Shipping</h5>
<p>Complimentary insured delivery</p>
</div>

<div class="feature-box">
<div class="feature-icon">💎</div>
<h5>Lifetime Care</h5>
<p>Free cleaning & polishing</p>
</div>

</div>


<!-- CTA BANNER -->

<section class="diamond-cta">

<h2>Ready to Find Your Perfect Diamond?</h2>
<p>Visit our store in Surat or browse our entire diamond collection online</p>

<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>

</section>


<!-- SMOOTH SCROLL FOR ANCHOR -->
<script>
document.querySelector('.diamond-btn')?.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if(target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>

<?php include 'footer.php'; ?>

