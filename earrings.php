<?php
session_start();
define('JEWELLERY_ACCESS', true);

$earrings_products = [
    ["image" => "images/7.jpg", "title" => "Gold Jhumka Earrings", "weight" => "22K (8.2g)", "price" => "₹38,999"],
    ["image" => "images/1.jpg", "title" => "Diamond Stud Earrings", "weight" => "0.50 Ct (18K)", "price" => "₹45,500"],
    ["image" => "images/6.jpg", "title" => "Gold Hoop Earrings", "weight" => "18K (5.8g)", "price" => "₹28,700"],
    ["image" => "images/4.jpg", "title" => "Pearl Drop Earrings", "weight" => "18K (4.2g)", "price" => "₹32,400"],
    ["image" => "images/2.jpg", "title" => "Gold Chandbali Earrings", "weight" => "22K (12.5g)", "price" => "₹68,900"],
    ["image" => "images/10.jpg", "title" => "Gold Stud Earrings", "weight" => "18K (3.2g)", "price" => "₹18,999"],
    ["image" => "images/3.jpg", "title" => "Diamond Drop Earrings", "weight" => "0.75 Ct (18K)", "price" => "₹72,800"],
    ["image" => "images/5.jpg", "title" => "Gold Bali Earrings", "weight" => "22K (6.8g)", "price" => "₹35,200"],
    ["image" => "images/8.jpg", "title" => "Gemstone Earrings", "weight" => "18K (5.2g)", "price" => "₹42,500"],
    ["image" => "images/9.jpg", "title" => "Gold Earring Set", "weight" => "22K (9.5g)", "price" => "₹52,999"],
    ["image" => "images/11.jpg", "title" => "Oxidised Silver Earrings", "weight" => "Silver (6.8g)", "price" => "₹12,800"],
    ["image" => "images/12.jpg", "title" => "Gold Ear Cuff", "weight" => "18K (2.8g)", "price" => "₹22,400"]
];

$earrings_categories = [
    ["name" => "Jhumkas", "image" => "images/7.jpg"],
    ["name" => "Stud Earrings", "image" => "images/2.jpg"],
    ["name" => "Hoop Earrings", "image" => "images/6.jpg"],
    ["name" => "Drop Earrings", "image" => "images/3.jpg"],
    ["name" => "Chandbalis", "image" => "images/4.jpg"],
    ["name" => "Bali Earrings", "image" => "images/5.jpg"],
    ["name" => "Pearl Earrings", "image" => "images/1.jpg"],
    ["name" => "Gemstone Earrings", "image" => "images/8.jpg"]
];

$earrings_types = [
    ["type" => "Jhumka", "icon" => "✦", "desc" => "Traditional bell-shaped earrings with intricate detailing. A timeless Indian classic that adds elegance to any outfit.", "color" => "#d4af37"],
    ["type" => "Stud", "icon" => "✦", "desc" => "Simple yet sophisticated earrings that sit directly on the earlobe. Perfect for daily wear and minimalistic looks.", "color" => "#c59b27"],
    ["type" => "Hoop", "icon" => "✦", "desc" => "Circular earrings ranging from small to large sizes. Versatile and trendy, suitable for both casual and formal occasions.", "color" => "#b8962b"],
    ["type" => "Drop & Dangle", "icon" => "✦", "desc" => "Elegant earrings that hang below the earlobe. Designed to sway gracefully, perfect for parties and celebrations.", "color" => "#8b1c22"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Traditional Earring Edit"],
    ["image" => "images/19.jpg", "title" => "Contemporary Stud Collection"],
    ["image" => "images/20.jpg", "title" => "Party Wear Earrings"],
    ["image" => "images/21.jpg", "title" => "Bridal Earring Collection"],
    ["image" => "images/22.jpg", "title" => "Everyday Earring Range"],
    ["image" => "images/23.jpg", "title" => "Designer Statement Earrings"]
];
?>
<?php
$page_title = "Earrings Collection – PASVI";
$page_description = "Explore PASVI's exclusive Earrings collection – Jhumka, Stud, Hoop, Drop, Chandbali & more. Stunning earrings crafted for every occasion.";
include 'header.php';
?>
<style>
/* =====================================
   EARRINGS HERO
   ===================================== */
.earrings-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #1a0a0c 0%, #3b1218 40%, #6b1a24 70%, #2d0d10 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.earrings-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%);
}

.earrings-hero-particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: #d4af37;
    border-radius: 50%;
    animation: earringFloat 6s infinite ease-in-out;
    opacity: 0.6;
}

.particle:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
.particle:nth-child(2) { top: 20%; left: 80%; animation-delay: 1s; width: 6px; height: 6px; }
.particle:nth-child(3) { top: 50%; left: 20%; animation-delay: 2s; }
.particle:nth-child(4) { top: 70%; left: 70%; animation-delay: 0.5s; width: 3px; height: 3px; }
.particle:nth-child(5) { top: 30%; left: 50%; animation-delay: 1.5s; width: 5px; height: 5px; }
.particle:nth-child(6) { top: 80%; left: 40%; animation-delay: 3s; }
.particle:nth-child(7) { top: 15%; left: 30%; animation-delay: 2.5s; width: 3px; height: 3px; }
.particle:nth-child(8) { top: 60%; left: 90%; animation-delay: 0.8s; width: 5px; height: 5px; }
.particle:nth-child(9) { top: 40%; left: 5%; animation-delay: 1.8s; }
.particle:nth-child(10) { top: 90%; left: 60%; animation-delay: 3.5s; width: 4px; height: 4px; }

@keyframes earringFloat {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
    50% { transform: translateY(-40px) scale(1.5); opacity: 1; }
}

.earrings-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
    padding: 0 20px;
}

.earrings-hero-content .earrings-badge {
    display: inline-block;
    background: rgba(212, 175, 55, 0.2);
    border: 1px solid rgba(212, 175, 55, 0.4);
    padding: 8px 28px;
    border-radius: 30px;
    font-size: 13px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #d4af37;
    margin-bottom: 25px;
    font-weight: 500;
}

.earrings-hero-content h1 {
    font-size: 70px;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 4px;
    background: linear-gradient(135deg, #f9d423, #d4af37, #c59b27, #f9d423);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: earringShimmer 4s ease-in-out infinite;
    margin-bottom: 15px;
}

@keyframes earringShimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.earrings-hero-content p {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.85);
    font-weight: 300;
    letter-spacing: 1px;
    margin-bottom: 30px;
}

.earrings-hero-content .earrings-btn {
    display: inline-block;
    padding: 16px 50px;
    background: linear-gradient(135deg, #d4af37, #c59b27);
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 2px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.4s;
    border: none;
    cursor: pointer;
}

.earrings-hero-content .earrings-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
}

/* =====================================
   EARRINGS BREADCRUMB
   ===================================== */
.earrings-breadcrumb {
    max-width: 1300px;
    margin: 20px auto;
    padding: 0 40px;
    font-size: 14px;
    color: #888;
}

.earrings-breadcrumb a {
    color: #888;
    text-decoration: none;
    transition: color 0.3s;
}

.earrings-breadcrumb a:hover {
    color: #d4af37;
}

.earrings-breadcrumb span {
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

.section-title .earrings-sep {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #d4af37, #f9d423);
    margin: 12px auto;
    border-radius: 2px;
}

.section-title p {
    font-size: 15px;
    color: #888;
    font-weight: 300;
}

/* =====================================
   EARRINGS CATEGORIES
   ===================================== */
.earrings-categories {
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 40px;
}

.earrings-cat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
}

.earrings-cat-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    border-radius: 10px;
    aspect-ratio: 1 / 1;
    transition: all 0.4s;
}

.earrings-cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
}

.earrings-cat-card:hover img {
    transform: scale(1.08);
}

.earrings-cat-card .cat-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    padding: 20px 15px;
    text-align: center;
}

.earrings-cat-card .cat-overlay h4 {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 1px;
}

.earrings-cat-card .cat-overlay .earrings-line-small {
    width: 30px;
    height: 2px;
    background: #d4af37;
    margin: 8px auto;
}

/* =====================================
   EARRINGS PRODUCT GRID
   ===================================== */
.earrings-products {
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
    border-color: rgba(212, 175, 55, 0.3);
}

.product-card .product-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: linear-gradient(135deg, #d4af37, #b8962b);
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
    color: #8b1c22;
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
    background: linear-gradient(135deg, #8b1c22, #641820);
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
    background: linear-gradient(135deg, #a5222a, #7d1d22);
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
    border-color: #8b1c22;
    color: #8b1c22;
}

/* =====================================
   EARRINGS STYLE GUIDE
   ===================================== */
.earrings-style {
    background: linear-gradient(135deg, #1a0a0c 0%, #2d0d10 50%, #1a0a0c 100%);
    padding: 70px 40px;
    margin: 40px 0;
}

.style-container {
    max-width: 1300px;
    margin: 0 auto;
}

.style-container .section-title h2 {
    color: #fff;
}

.style-container .section-title p {
    color: rgba(255, 255, 255, 0.6);
}

.style-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 20px;
}

.style-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 16px;
    padding: 36px 24px;
    text-align: center;
    transition: all 0.4s;
    backdrop-filter: blur(10px);
}

.style-card:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: rgba(212, 175, 55, 0.5);
    transform: translateY(-5px);
}

.style-card .style-icon {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
    color: #fff;
}

.style-card h3 {
    font-size: 20px;
    font-family: 'Playfair Display', serif;
    color: #d4af37;
    margin-bottom: 5px;
}

.style-card .style-name {
    font-size: 12px;
    color: rgba(212, 175, 55, 0.8);
    letter-spacing: 2px;
    margin-bottom: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.style-card p {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.6;
}

.style-card .style-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 30px;
    border: 1px solid #d4af37;
    color: #d4af37;
    background: transparent;
    border-radius: 30px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    letter-spacing: 1px;
}

.style-card .style-btn:hover {
    background: #d4af37;
    color: #1a0a0c;
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
    background: linear-gradient(transparent 20%, rgba(0, 0, 0, 0.85));
    padding: 30px 20px 20px;
    text-align: center;
}

.pick-card .pick-overlay h4 {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    letter-spacing: 1px;
}

.pick-card .pick-overlay .earrings-line-small {
    width: 30px;
    height: 2px;
    background: #d4af37;
    margin: 8px auto;
}

/* =====================================
   EARRINGS FEATURES
   ===================================== */
.earrings-features {
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
    border-color: #d4af37;
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.1);
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
.earrings-cta {
    background: linear-gradient(135deg, #8b1c22 0%, #641820 50%, #3b1218 100%);
    text-align: center;
    padding: 70px 40px;
    margin: 40px 0;
}

.earrings-cta h2 {
    font-size: 36px;
    font-family: 'Playfair Display', serif;
    color: #fff;
    margin-bottom: 10px;
}

.earrings-cta p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 16px;
    margin-bottom: 25px;
}

.earrings-cta .cta-btn {
    display: inline-block;
    padding: 16px 50px;
    background: #d4af37;
    color: #1a0a0c;
    font-size: 16px;
    font-weight: 600;
    letter-spacing: 2px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s;
}

.earrings-cta .cta-btn:hover {
    background: #f9d423;
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
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
    .earrings-hero-content h1 {
        font-size: 52px;
    }
    .product-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .earrings-cat-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    .style-grid {
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

    .earrings-hero {
        height: 380px;
    }
    .earrings-hero-content h1 {
        font-size: 40px;
    }
    .earrings-hero-content p {
        font-size: 15px;
    }

    .earrings-cat-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .product-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .earrings-breadcrumb {
        padding: 0 20px;
    }
    .earrings-categories {
        padding: 0 20px;
    }
    .earrings-products {
        padding: 0 20px 20px;
    }

    .style-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .picks-grid {
        grid-template-columns: 1fr;
    }
    .designer-picks {
        padding: 0 20px;
    }

    .earrings-features {
        grid-template-columns: repeat(2, 1fr);
        padding: 0 20px;
    }

    .section-title h2 {
        font-size: 26px;
    }

    .earrings-cta h2 {
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
    .earrings-hero {
        height: 320px;
    }
    .earrings-hero-content h1 {
        font-size: 32px;
    }
    .earrings-hero-content .earrings-badge {
        font-size: 11px;
        padding: 6px 18px;
    }
    .earrings-hero-content .earrings-btn {
        padding: 12px 30px;
        font-size: 14px;
    }
    .earrings-cat-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .product-grid {
        grid-template-columns: 1fr;
    }
    .style-grid {
        grid-template-columns: 1fr;
    }
    .earrings-features {
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

<!-- EARRINGS HERO BANNER -->

<section class="earrings-hero">

<div class="earrings-hero-bg"></div>

<div class="earrings-hero-particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<div class="earrings-hero-content">

<div class="earrings-badge">Exclusive Collection</div>

<h1>EARRINGS</h1>

<p>Discover our stunning earrings collection — Jhumka, Stud, Hoop, Drop & more • A pair for every face, a style for every occasion</p>

<a href="#products" class="earrings-btn">EXPLORE COLLECTION</a>

</div>

</section>


<!-- BREADCRUMB -->

<div class="earrings-breadcrumb">
<a href="index.php">Home</a> &nbsp;/&nbsp; <span>Earrings</span>
</div>


<!-- EARRINGS CATEGORIES -->

<section class="earrings-categories">

<div class="section-title">
<h2>Shop by Style</h2>
<div class="earrings-sep"></div>
<p>Browse our exquisite earrings collection</p>
</div>

<div class="earrings-cat-grid">

<?php foreach($earrings_categories as $cat) { ?>

<div class="earrings-cat-card">

<img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>">

<div class="cat-overlay">
<div class="earrings-line-small"></div>
<h4><?php echo $cat['name']; ?></h4>
</div>

</div>

<?php } ?>

</div>

</section>


<!-- EARRINGS PRODUCTS -->

<section class="earrings-products" id="products">

<div class="section-title">
<h2>Earrings Collection</h2>
<div class="earrings-sep"></div>
<p>Stunning earrings crafted for every occasion</p>
</div>

<div class="product-grid">

<?php foreach($earrings_products as $index => $product) { 
    $pid = 'earrings_' . $index;
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


<!-- EARRINGS STYLE GUIDE -->

<section class="earrings-style">

<div class="style-container">

<div class="section-title">
<h2>Earring Style Guide</h2>
<div class="earrings-sep"></div>
<p>Find the perfect earring style for your face shape and occasion</p>
</div>

<div class="style-grid">

<?php foreach($earrings_types as $style) { ?>

<div class="style-card">

<span class="style-icon"><?php echo $style['icon']; ?></span>

<h3><?php echo $style['type']; ?></h3>
<div class="style-name">Style Guide</div>

<p><?php echo $style['desc']; ?></p>

<button class="style-btn">Explore <?php echo $style['type']; ?></button>

</div>

<?php } ?>

</div>

</div>

</section>


<!-- DESIGNER PICKS -->

<section class="designer-picks">

<div class="section-title">
<h2>Designer's Pick</h2>
<div class="earrings-sep"></div>
<p>Curated collections by our master jewellers</p>
</div>

<div class="picks-grid">

<?php foreach($designer_picks as $pick) { ?>

<div class="pick-card">

<img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>">

<div class="pick-overlay">
<div class="earrings-line-small"></div>
<h4><?php echo $pick['title']; ?></h4>
</div>

</div>

<?php } ?>

</div>

</section>


<!-- FEATURES -->

<div class="earrings-features">

<div class="feature-box">
<div class="feature-icon">📜</div>
<h5>Certified Quality</h5>
<p>Hallmarked gold & certified diamonds</p>
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

<section class="earrings-cta">

<h2>Ready to Find Your Perfect Earrings?</h2>
<p>Visit our store in Surat or browse our entire earrings collection online</p>

<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>

</section>


<!-- SMOOTH SCROLL FOR ANCHOR -->
<script>
document.querySelector('.earrings-btn')?.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if(target) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>

<?php include 'footer.php'; ?>
