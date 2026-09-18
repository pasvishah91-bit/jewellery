<?php
session_start();
define('JEWELLERY_ACCESS', true);

$dailywear_products = [
    ["image" => "images/1.jpg", "title" => "Daily Wear Gold Necklace", "weight" => "22K (8.2g)", "price" => "₹45,999"],
    ["image" => "images/6.jpg", "title" => "Light Gold Hoop Earrings", "weight" => "18K (4.5g)", "price" => "₹28,500"],
    ["image" => "images/3.jpg", "title" => "Simple Diamond Pendant", "weight" => "18K (3.2g)", "price" => "₹32,400"],
    ["image" => "images/10.jpg", "title" => "Gold Chain for Daily", "weight" => "22K (6.8g)", "price" => "₹38,600"],
    ["image" => "images/7.jpg", "title" => "Gold Stud Earrings", "weight" => "18K (2.8g)", "price" => "₹18,999"],
    ["image" => "images/2.jpg", "title" => "Gold Bangle Set", "weight" => "22K (18.5g)", "price" => "₹1,12,500"],
    ["image" => "images/5.jpg", "title" => "Gold Ring", "weight" => "22K (4.2g)", "price" => "₹24,800"],
    ["image" => "images/8.jpg", "title" => "Gold Nose Pin Set", "weight" => "18K (1.2g)", "price" => "₹8,999"],
    ["image" => "images/4.jpg", "title" => "Gold Anklet", "weight" => "22K (10.5g)", "price" => "₹58,700"],
    ["image" => "images/9.jpg", "title" => "Gold Bracelet", "weight" => "18K (5.8g)", "price" => "₹32,500"],
    ["image" => "images/11.jpg", "title" => "Gold Earring Set", "weight" => "22K (7.2g)", "price" => "₹42,200"],
    ["image" => "images/12.jpg", "title" => "Daily Wear Ring", "weight" => "18K (3.5g)", "price" => "₹22,999"]
];

$dailywear_categories = [
    ["name" => "Necklaces", "image" => "images/1.jpg"],
    ["name" => "Earrings", "image" => "images/7.jpg"],
    ["name" => "Rings", "image" => "images/5.jpg"],
    ["name" => "Bangles", "image" => "images/2.jpg"],
    ["name" => "Pendants", "image" => "images/3.jpg"],
    ["name" => "Bracelets", "image" => "images/9.jpg"],
    ["name" => "Chains", "image" => "images/10.jpg"],
    ["name" => "Nose Pins", "image" => "images/8.jpg"]
];

$dailywear_purities = [
    ["karat" => "24K Gold", "purity" => "99.9% Pure", "desc" => "Purest form of gold, ideal for investments", "color" => "#f9d423"],
    ["karat" => "22K Gold", "purity" => "91.6% Pure", "desc" => "Most popular for jewellery, perfect balance", "color" => "#e8b730"],
    ["karat" => "18K Gold", "purity" => "75.0% Pure", "desc" => "Durable & trendy, ideal for daily wear", "color" => "#d4a017"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Everyday Gold Essentials"],
    ["image" => "images/19.jpg", "title" => "Office Wear Collection"],
    ["image" => "images/20.jpg", "title" => "Lightweight Diamond Range"],
    ["image" => "images/21.jpg", "title" => "Minimalist Gold Edit"],
    ["image" => "images/22.jpg", "title" => "Daily Wear Combo Sets"],
    ["image" => "images/23.jpg", "title" => "Casual Elegance Line"]
];

$page_title = "Daily Wear Jewellery – PASVI";
$page_description = "Explore PASVI's Daily Wear Jewellery – lightweight gold & diamond jewellery perfect for everyday elegance.";
include 'header.php';
?>
<style>
/* ===== DAILYWEAR PAGE SPECIFIC STYLES ===== */
.dailywear-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #1a0a0c 0%, #3b1218 40%, #6b1a24 70%, #2d0d10 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.dailywear-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(212,175,55,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(212,175,55,0.1) 0%, transparent 50%);
}
.dailywear-hero-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; }
.particle {
    position: absolute; width: 4px; height: 4px; background: #d4af37; border-radius: 50%;
    animation: dailyFloat 6s infinite ease-in-out; opacity: 0.6;
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
@keyframes dailyFloat {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
    50% { transform: translateY(-40px) scale(1.5); opacity: 1; }
}
.dailywear-hero-content { position: relative; z-index: 2; text-align: center; color: #fff; padding: 0 20px; }
.dailywear-hero-content .daily-badge {
    display: inline-block; background: rgba(212,175,55,0.2); border: 1px solid rgba(212,175,55,0.4);
    padding: 8px 28px; border-radius: 30px; font-size: 13px; letter-spacing: 3px;
    text-transform: uppercase; color: #d4af37; margin-bottom: 25px; font-weight: 500;
}
.dailywear-hero-content h1 {
    font-size: 70px; font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 4px;
    background: linear-gradient(135deg, #f9d423, #d4af37, #c59b27, #f9d423); background-size: 300% 300%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: dailyShimmer 4s ease-in-out infinite; margin-bottom: 15px;
}
@keyframes dailyShimmer { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
.dailywear-hero-content p { font-size: 18px; color: rgba(255,255,255,0.85); font-weight: 300; letter-spacing: 1px; margin-bottom: 30px; }
.dailywear-hero-content .daily-btn { display: inline-block; padding: 16px 50px; background: linear-gradient(135deg, #d4af37, #c59b27); color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.4s; cursor: pointer; }
.dailywear-hero-content .daily-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
.dailywear-breadcrumb { max-width: 1300px; margin: 20px auto; padding: 0 40px; font-size: 14px; color: #888; }
.dailywear-breadcrumb a { color: #888; text-decoration: none; transition: color 0.3s; }
.dailywear-breadcrumb a:hover { color: #d4af37; }
.dailywear-breadcrumb span { color: #333; font-weight: 500; }
.dailywear-categories { max-width: 1300px; margin: 0 auto; padding: 0 40px; }
.dailywear-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.dailywear-cat-card { position: relative; cursor: pointer; overflow: hidden; border-radius: 10px; aspect-ratio: 1/1; transition: all 0.4s; }
.dailywear-cat-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.dailywear-cat-card:hover img { transform: scale(1.08); }
.dailywear-cat-card .cat-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px 15px; text-align: center; }
.dailywear-cat-card .cat-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.dailywear-cat-card .cat-overlay .daily-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.dailywear-products { max-width: 1300px; margin: 0 auto; padding: 0 40px 40px; }
.dailywear-purity { background: linear-gradient(135deg, #1a0a0c 0%, #2d0d10 100%); padding: 70px 40px; margin: 40px 0; }
.purity-container { max-width: 1300px; margin: 0 auto; }
.purity-container .section-title h2 { color: #fff; }
.purity-container .section-title p { color: rgba(255,255,255,0.6); }
.purity-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-top: 20px; }
.purity-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2); border-radius: 16px; padding: 40px 30px; text-align: center; transition: all 0.4s; backdrop-filter: blur(10px); }
.purity-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(212,175,55,0.5); transform: translateY(-5px); }
.purity-card .purity-icon { font-size: 48px; margin-bottom: 15px; }
.purity-card h3 { font-size: 26px; font-family: 'Playfair Display', serif; color: #d4af37; margin-bottom: 5px; }
.purity-card .purity-percent { font-size: 14px; color: rgba(212,175,55,0.8); letter-spacing: 2px; margin-bottom: 12px; }
.purity-card p { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
.purity-card .purity-btn { display: inline-block; margin-top: 20px; padding: 10px 30px; border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 30px; font-size: 13px; cursor: pointer; transition: all 0.3s; }
.purity-card .purity-btn:hover { background: #d4af37; color: #1a0a0c; }
.designer-picks { max-width: 1300px; margin: 40px auto; padding: 0 40px; }
.picks-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.pick-card { position: relative; overflow: hidden; border-radius: 12px; aspect-ratio: 3/2; cursor: pointer; }
.pick-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.pick-card:hover img { transform: scale(1.08); }
.pick-card .pick-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent 20%, rgba(0,0,0,0.85)); padding: 30px 20px 20px; text-align: center; }
.pick-card .pick-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.pick-card .pick-overlay .daily-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.dailywear-features { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 1300px; margin: 50px auto; padding: 0 40px; gap: 20px; }
.section-title .daily-sep { width: 60px; height: 3px; background: linear-gradient(90deg, #d4af37, #f9d423); margin: 12px auto; border-radius: 2px; }
.dailywear-cta { background: linear-gradient(135deg, #8b1c22 0%, #641820 50%, #3b1218 100%); text-align: center; padding: 70px 40px; margin: 40px 0; }
.dailywear-cta h2 { font-size: 36px; font-family: 'Playfair Display', serif; color: #fff; margin-bottom: 10px; }
.dailywear-cta p { color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 25px; }
.dailywear-cta .cta-btn { display: inline-block; padding: 16px 50px; background: #d4af37; color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
.dailywear-cta .cta-btn:hover { background: #f9d423; transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
@media(max-width: 1024px) {
    .dailywear-hero-content h1 { font-size: 52px; }
    .dailywear-cat-grid { grid-template-columns: repeat(4, 1fr); }
    .purity-grid { grid-template-columns: repeat(3, 1fr); }
    .picks-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .dailywear-hero { height: 380px; }
    .dailywear-hero-content h1 { font-size: 40px; }
    .dailywear-hero-content p { font-size: 15px; }
    .dailywear-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .dailywear-breadcrumb, .dailywear-categories { padding: 0 20px; }
    .dailywear-products { padding: 0 20px 20px; }
    .purity-grid { grid-template-columns: 1fr; }
    .picks-grid { grid-template-columns: 1fr; }
    .dailywear-features { grid-template-columns: repeat(2, 1fr); padding: 0 20px; }
    .dailywear-cta h2 { font-size: 28px; }
}
@media(max-width: 480px) {
    .dailywear-hero { height: 320px; }
    .dailywear-hero-content h1 { font-size: 32px; }
    .dailywear-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .dailywear-features { grid-template-columns: 1fr; }
}
</style>

<!-- DAILYWEAR HERO BANNER -->
<section class="dailywear-hero">
<div class="dailywear-hero-bg"></div>
<div class="dailywear-hero-particles">
    <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
    <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>
</div>
<div class="dailywear-hero-content">
<div class="daily-badge">Everyday Elegance</div>
<h1>DAILY WEAR</h1>
<p>Discover our lightweight jewellery collection — perfect for office, casual & everyday occasions</p>
<a href="#products" class="daily-btn">EXPLORE COLLECTION</a>
</div>
</section>

<!-- BREADCRUMB -->
<div class="dailywear-breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; <span>Daily Wear</span></div>

<!-- DAILYWEAR CATEGORIES -->
<section class="dailywear-categories">
<div class="section-title"><h2>Shop by Category</h2><div class="daily-sep"></div><p>Browse our everyday jewellery collection</p></div>
<div class="dailywear-cat-grid">
<?php foreach($dailywear_categories as $cat) { ?>
<div class="dailywear-cat-card"><img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>"><div class="cat-overlay"><div class="daily-line-small"></div><h4><?php echo $cat['name']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- DAILYWEAR PRODUCTS -->
<section class="dailywear-products" id="products">
<div class="section-title"><h2>Daily Wear Collection</h2><div class="daily-sep"></div><p>Lightweight pieces crafted for your everyday elegance</p></div>
<div class="product-grid">
<?php foreach($dailywear_products as $index => $product) { $pid = 'dailywear_' . $index; ?>
<div class="product-card" data-id="<?php echo $pid; ?>" data-name="<?php echo htmlspecialchars($product['title']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>" data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
<span class="product-badge">BESTSELLER</span>
<img class="product-img" src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
<div class="product-info"><h4><?php echo $product['title']; ?></h4><div class="weight"><?php echo $product['weight']; ?></div><div class="price"><?php echo $product['price']; ?> <small>MRP</small></div><div class="product-actions"><button class="add-cart">ADD TO CART</button><div class="wishlist-btn">♡</div></div></div>
</div>
<?php } ?>
</div>
</section>

<!-- GOLD PURITY -->
<section class="dailywear-purity">
<div class="purity-container">
<div class="section-title"><h2>Choose Your Purity</h2><div class="daily-sep"></div><p>Understanding gold purity — find the perfect karat for you</p></div>
<div class="purity-grid">
<?php foreach($dailywear_purities as $purity) { ?>
<div class="purity-card"><div class="purity-icon">✦</div><h3><?php echo $purity['karat']; ?></h3><div class="purity-percent"><?php echo $purity['purity']; ?></div><p><?php echo $purity['desc']; ?></p><button class="purity-btn">Shop <?php echo $purity['karat']; ?></button></div>
<?php } ?>
</div>
</section>

<!-- DESIGNER PICKS -->
<section class="designer-picks">
<div class="section-title"><h2>Designer's Pick</h2><div class="daily-sep"></div><p>Curated collections by our master jewellers</p></div>
<div class="picks-grid">
<?php foreach($designer_picks as $pick) { ?>
<div class="pick-card"><img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>"><div class="pick-overlay"><div class="daily-line-small"></div><h4><?php echo $pick['title']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- FEATURES -->
<div class="dailywear-features">
<div class="feature-box"><div class="feature-icon">🔏</div><h5>100% Certified</h5><p>BIS Hallmarked gold jewellery</p></div>
<div class="feature-box"><div class="feature-icon">🔄</div><h5>Easy Exchange</h5><p>Hassle-free return & exchange</p></div>
<div class="feature-box"><div class="feature-icon">📦</div><h5>Free Shipping</h5><p>Complimentary insured delivery</p></div>
<div class="feature-box"><div class="feature-icon">💎</div><h5>Lifetime Care</h5><p>Free cleaning & polishing</p></div>
</div>

<!-- CTA BANNER -->
<section class="dailywear-cta">
<h2>Ready to Find Your Perfect Daily Wear Piece?</h2>
<p>Visit our store in Surat or browse our entire daily wear collection online</p>
<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>
</section>

<script>
document.querySelector('.daily-btn')?.addEventListener('click', function(e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if(target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });
</script>

<?php include 'footer.php'; ?>
