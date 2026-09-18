<?php
session_start();
define('JEWELLERY_ACCESS', true);
require_once 'products_data.php';

$gold_products = [
    ["image" => "images/1.jpg", "title" => "Gold Bangle Set", "weight" => "22K (24.6g)", "price" => "₹1,12,999"],
    ["image" => "images/2.jpg", "title" => "Gold Chain Necklace", "weight" => "22K (18.5g)", "price" => "₹88,500"],
    ["image" => "images/3.jpg", "title" => "Gold Earring Set", "weight" => "22K (8.2g)", "price" => "₹42,800"],
    ["image" => "images/4.jpg", "title" => "Gold Mangalsutra", "weight" => "22K (14.6g)", "price" => "₹68,999"],
    ["image" => "images/5.jpg", "title" => "Gold Bracelet", "weight" => "22K (12.4g)", "price" => "₹58,700"],
    ["image" => "images/6.jpg", "title" => "Gold Pendant", "weight" => "22K (6.8g)", "price" => "₹35,200"],
    ["image" => "images/7.jpg", "title" => "Gold Kada", "weight" => "24K (32.5g)", "price" => "₹1,85,600"],
    ["image" => "images/8.jpg", "title" => "Gold Nose Pin", "weight" => "22K (2.4g)", "price" => "₹12,999"],
    ["image" => "images/9.jpg", "title" => "Gold Ring", "weight" => "22K (5.8g)", "price" => "₹28,400"],
    ["image" => "images/10.jpg", "title" => "Gold Anklet", "weight" => "22K (16.2g)", "price" => "₹72,500"],
    ["image" => "images/11.jpg", "title" => "Gold Locket", "weight" => "18K (4.2g)", "price" => "₹22,800"],
    ["image" => "images/12.jpg", "title" => "Gold Toe Ring Set", "weight" => "22K (3.6g)", "price" => "₹15,999"]
];

$gold_categories = [
    ["name" => "Bangles", "image" => "images/1.jpg"],
    ["name" => "Chains", "image" => "images/2.jpg"],
    ["name" => "Necklaces", "image" => "images/3.jpg"],
    ["name" => "Bracelets", "image" => "images/5.jpg"],
    ["name" => "Pendants", "image" => "images/6.jpg"],
    ["name" => "Kada", "image" => "images/7.jpg"],
    ["name" => "Mangalsutra", "image" => "images/4.jpg"],
    ["name" => "Gold Coins", "image" => "images/8.jpg"]
];

$gold_guide = [
    ["type" => "24K Gold", "icon" => "✦", "desc" => "Pure gold with 99.9% purity. Soft and ideal for investment coins and bars. Not recommended for daily wear jewellery due to softness.", "color" => "#d4af37"],
    ["type" => "22K Gold", "icon" => "✦", "desc" => "91.6% pure gold alloyed with other metals. The most popular choice for traditional Indian jewellery offering perfect balance of purity and durability.", "color" => "#c59b27"],
    ["type" => "18K Gold", "icon" => "✦", "desc" => "75% pure gold mixed with stronger alloys. Ideal for diamond settings and contemporary designs. Offers excellent durability for everyday wear.", "color" => "#b8962b"],
    ["type" => "Hallmark", "icon" => "✦", "desc" => "BIS hallmarked gold guarantees purity. Each piece is tested and certified at BIS-assayed centres ensuring you get exactly what you pay for.", "color" => "#8b1c22"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Traditional Gold Collection"],
    ["image" => "images/19.jpg", "title" => "Contemporary Gold Designs"],
    ["image" => "images/20.jpg", "title" => "Party Wear Gold"],
    ["image" => "images/21.jpg", "title" => "Bridal Gold Collection"],
    ["image" => "images/22.jpg", "title" => "Everyday Gold Range"],
    ["image" => "images/23.jpg", "title" => "Designer Gold Statement"]
];

$page_title = "Gold Jewellery Collection – PASVI";
$page_description = "Explore PASVI's exclusive Gold Jewellery collection – Bangles, Chains, Necklaces, Bracelets, Pendants, Kada & more. 22K & 24K certified gold.";
include 'header.php';
?>
<style>
.gold-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #1a0a0c 0%, #3b1218 40%, #6b1a24 70%, #2d0d10 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.gold-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(212,175,55,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(212,175,55,0.1) 0%, transparent 50%);
}
.gold-hero-particles {
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
    animation: goldFloat 6s infinite ease-in-out;
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
@keyframes goldFloat {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
    50% { transform: translateY(-40px) scale(1.5); opacity: 1; }
}
.gold-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: #fff;
    padding: 0 20px;
}
.gold-hero-content .gold-badge {
    display: inline-block;
    background: rgba(212,175,55,0.2);
    border: 1px solid rgba(212,175,55,0.4);
    padding: 8px 28px;
    border-radius: 30px;
    font-size: 13px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #d4af37;
    margin-bottom: 25px;
    font-weight: 500;
}
.gold-hero-content h1 {
    font-size: 70px;
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    letter-spacing: 4px;
    background: linear-gradient(135deg, #f9d423, #d4af37, #c59b27, #f9d423);
    background-size: 300% 300%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: goldShimmer 4s ease-in-out infinite;
    margin-bottom: 15px;
}
@keyframes goldShimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
.gold-hero-content p {
    font-size: 18px;
    color: rgba(255,255,255,0.85);
    font-weight: 300;
    letter-spacing: 1px;
    margin-bottom: 30px;
}
.gold-hero-content .gold-btn {
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
.gold-hero-content .gold-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(212,175,55,0.3);
}
.gold-breadcrumb {
    max-width: 1300px;
    margin: 20px auto;
    padding: 0 40px;
    font-size: 14px;
    color: #888;
}
.gold-breadcrumb a { color: #888; text-decoration: none; transition: color 0.3s; }
.gold-breadcrumb a:hover { color: #d4af37; }
.gold-breadcrumb span { color: #333; font-weight: 500; }
.section-title { text-align: center; margin: 60px auto 40px; padding: 0 20px; }
.section-title h2 { font-size: 34px; font-family: 'Playfair Display', serif; color: #222; margin-bottom: 8px; }
.section-title .gold-sep { width: 60px; height: 3px; background: linear-gradient(90deg, #d4af37, #f9d423); margin: 12px auto; border-radius: 2px; }
.section-title p { font-size: 15px; color: #888; font-weight: 300; }
.gold-categories { max-width: 1300px; margin: 0 auto; padding: 0 40px; }
.gold-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.gold-cat-card { position: relative; cursor: pointer; overflow: hidden; border-radius: 10px; aspect-ratio: 1/1; transition: all 0.4s; }
.gold-cat-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.gold-cat-card:hover img { transform: scale(1.08); }
.gold-cat-card .cat-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px 15px; text-align: center; }
.gold-cat-card .cat-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.gold-cat-card .cat-overlay .gold-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.gold-products { max-width: 1300px; margin: 0 auto; padding: 0 40px 40px; }
.gold-guide { background: linear-gradient(135deg, #1a0a0c 0%, #2d0d10 50%, #1a0a0c 100%); padding: 70px 40px; margin: 40px 0; }
.guide-container { max-width: 1300px; margin: 0 auto; }
.guide-container .section-title h2 { color: #fff; }
.guide-container .section-title p { color: rgba(255,255,255,0.6); }
.guide-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 20px; }
.guide-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2); border-radius: 16px; padding: 36px 24px; text-align: center; transition: all 0.4s; backdrop-filter: blur(10px); }
.guide-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(212,175,55,0.5); transform: translateY(-5px); }
.guide-card .guide-icon { font-size: 48px; margin-bottom: 15px; display: block; color: #fff; }
.guide-card h3 { font-size: 20px; font-family: 'Playfair Display', serif; color: #d4af37; margin-bottom: 5px; }
.guide-card .guide-label { font-size: 12px; color: rgba(212,175,55,0.8); letter-spacing: 2px; margin-bottom: 12px; font-weight: 500; text-transform: uppercase; }
.guide-card p { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
.guide-card .guide-btn { display: inline-block; margin-top: 20px; padding: 10px 30px; border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 30px; font-size: 13px; cursor: pointer; transition: all 0.3s; text-decoration: none; letter-spacing: 1px; }
.guide-card .guide-btn:hover { background: #d4af37; color: #1a0a0c; }
.designer-picks { max-width: 1300px; margin: 40px auto; padding: 0 40px; }
.picks-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.pick-card { position: relative; overflow: hidden; border-radius: 12px; aspect-ratio: 3/2; cursor: pointer; }
.pick-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.pick-card:hover img { transform: scale(1.08); }
.pick-card .pick-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent 20%, rgba(0,0,0,0.85)); padding: 30px 20px 20px; text-align: center; }
.pick-card .pick-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.pick-card .pick-overlay .gold-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.gold-features { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 1300px; margin: 50px auto; padding: 0 40px; gap: 20px; }
.feature-box { text-align: center; padding: 30px 15px; border: 1px solid #f0f0f0; border-radius: 12px; transition: all 0.3s; }
.feature-box:hover { border-color: #d4af37; box-shadow: 0 10px 30px rgba(212,175,55,0.1); }
.feature-box .feature-icon { font-size: 32px; margin-bottom: 12px; }
.feature-box h5 { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 5px; }
.feature-box p { font-size: 13px; color: #999; font-weight: 300; }
.gold-cta { background: linear-gradient(135deg, #8b1c22 0%, #641820 50%, #3b1218 100%); text-align: center; padding: 70px 40px; margin: 40px 0; }
.gold-cta h2 { font-size: 36px; font-family: 'Playfair Display', serif; color: #fff; margin-bottom: 10px; }
.gold-cta p { color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 25px; }
.gold-cta .cta-btn { display: inline-block; padding: 16px 50px; background: #d4af37; color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
.gold-cta .cta-btn:hover { background: #f9d423; transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
@media(max-width: 1024px) {
    .gold-hero-content h1 { font-size: 52px; }
    .gold-cat-grid, .guide-grid { grid-template-columns: repeat(4, 1fr); }
    .picks-grid { grid-template-columns: repeat(3, 1fr); }
    .product-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .gold-hero { height: 380px; }
    .gold-hero-content h1 { font-size: 40px; }
    .gold-hero-content p { font-size: 15px; }
    .gold-cat-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .gold-breadcrumb, .gold-categories { padding: 0 20px; }
    .gold-products { padding: 0 20px 20px; }
    .guide-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
    .picks-grid { grid-template-columns: 1fr; }
    .designer-picks { padding: 0 20px; }
    .gold-features { grid-template-columns: repeat(2, 1fr); padding: 0 20px; }
    .section-title h2 { font-size: 26px; }
    .gold-cta h2 { font-size: 28px; }
}
@media(max-width: 480px) {
    .gold-hero { height: 320px; }
    .gold-hero-content h1 { font-size: 32px; }
    .gold-hero-content .gold-badge { font-size: 11px; padding: 6px 18px; }
    .gold-hero-content .gold-btn { padding: 12px 30px; font-size: 14px; }
    .gold-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .guide-grid, .gold-features { grid-template-columns: 1fr; }
}
</style>

<!-- GOLD HERO BANNER -->
<section class="gold-hero">
<div class="gold-hero-bg"></div>
<div class="gold-hero-particles">
    <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
    <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
</div>
<div class="gold-hero-content">
<div class="gold-badge">Pure Gold Collection</div>
<h1>GOLD</h1>
<p>Explore our exquisite gold jewellery — Bangles, Chains, Necklaces, Kada & more • 22K & 24K certified purity</p>
<a href="#products" class="gold-btn">EXPLORE COLLECTION</a>
</div>
</section>

<!-- BREADCRUMB -->
<div class="gold-breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; <span>Gold</span></div>

<!-- GOLD CATEGORIES -->
<section class="gold-categories">
<div class="section-title"><h2>Shop by Collection</h2><div class="gold-sep"></div><p>Browse our exquisite gold jewellery selection</p></div>
<div class="gold-cat-grid">
<?php foreach($gold_categories as $cat) { ?>
<div class="gold-cat-card"><img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>"><div class="cat-overlay"><div class="gold-line-small"></div><h4><?php echo $cat['name']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- GOLD PRODUCTS -->
<section class="gold-products" id="products">
<div class="section-title"><h2>Gold Jewellery Collection</h2><div class="gold-sep"></div><p>Exquisite gold pieces crafted for every occasion</p></div>
<div class="product-grid">
<?php foreach($gold_products as $index => $product) { $pid = 'gold_' . $index; ?>
<div class="product-card" data-id="<?php echo $pid; ?>" data-name="<?php echo htmlspecialchars($product['title']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>" data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
<span class="product-badge">HALLMARKED</span>
<img class="product-img" src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
<div class="product-info"><h4><?php echo $product['title']; ?></h4><div class="weight"><?php echo $product['weight']; ?></div><div class="price"><?php echo $product['price']; ?> <small>MRP</small></div><div class="product-actions"><button class="add-cart">ADD TO CART</button><div class="wishlist-btn">♡</div></div></div>
</div>
<?php } ?>
</div>
</section>

<!-- GOLD PURITY GUIDE -->
<section class="gold-guide">
<div class="guide-container">
<div class="section-title"><h2>Gold Purity Guide</h2><div class="gold-sep"></div><p>Understand gold purity and make informed choices</p></div>
<div class="guide-grid">
<?php foreach($gold_guide as $guide) { ?>
<div class="guide-card"><span class="guide-icon"><?php echo $guide['icon']; ?></span><h3><?php echo $guide['type']; ?></h3><div class="guide-label">Purity Guide</div><p><?php echo $guide['desc']; ?></p><button class="guide-btn">Learn More</button></div>
<?php } ?>
</div>
</div>
</section>

<!-- DESIGNER PICKS -->
<section class="designer-picks">
<div class="section-title"><h2>Designer's Pick</h2><div class="gold-sep"></div><p>Curated gold collections by our master jewellers</p></div>
<div class="picks-grid">
<?php foreach($designer_picks as $pick) { ?>
<div class="pick-card"><img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>"><div class="pick-overlay"><div class="gold-line-small"></div><h4><?php echo $pick['title']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- FEATURES -->
<div class="gold-features">
<div class="feature-box"><div class="feature-icon">📜</div><h5>Certified Quality</h5><p>Hallmarked gold & certified diamonds</p></div>
<div class="feature-box"><div class="feature-icon">🔄</div><h5>Easy Exchange</h5><p>Hassle-free return & exchange</p></div>
<div class="feature-box"><div class="feature-icon">📦</div><h5>Free Shipping</h5><p>Complimentary insured delivery</p></div>
<div class="feature-box"><div class="feature-icon">💎</div><h5>Lifetime Care</h5><p>Free cleaning & polishing</p></div>
</div>

<!-- CTA BANNER -->
<section class="gold-cta">
<h2>Ready to Find Your Perfect Gold Piece?</h2>
<p>Visit our store in Surat or browse our entire gold collection online</p>
<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>
</section>

<script>
document.querySelector('.gold-btn')?.addEventListener('click', function(e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if(target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });
</script>

<?php include 'footer.php'; ?>

