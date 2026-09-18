<?php
session_start();
define('JEWELLERY_ACCESS', true);

$wedding_products = [
    ["image" => "images/35.jpg", "title" => "Bridal Gold Necklace Set", "weight" => "22K (42.5g)", "price" => "₹2,89,999"],
    ["image" => "images/36.jpg", "title" => "Diamond Engagement Ring", "weight" => "1.00 Ct (18K)", "price" => "₹1,25,000"],
    ["image" => "images/37.jpg", "title" => "Bridal Choker Set", "weight" => "22K (28.8g)", "price" => "₹1,85,600"],
    ["image" => "images/38.jpg", "title" => "Wedding Earrings Set", "weight" => "22K (18.2g)", "price" => "₹1,12,500"],
    ["image" => "images/39.jpg", "title" => "Engagement Ring Set", "weight" => "0.75 Ct (18K)", "price" => "₹94,800"],
    ["image" => "images/40.jpg", "title" => "Wedding Bangles Set", "weight" => "22K (52.5g)", "price" => "₹3,12,999"],
    ["image" => "images/41.jpg", "title" => "Bridal Tiara", "weight" => "22K (12.8g)", "price" => "₹78,500"],
    ["image" => "images/42.jpg", "title" => "Wedding Mangalsutra", "weight" => "22K (18.5g)", "price" => "₹1,42,000"],
    ["image" => "images/43.jpg", "title" => "Bridal Pendant Set", "weight" => "22K (16.2g)", "price" => "₹96,700"],
    ["image" => "images/44.jpg", "title" => "Wedding Nose Pin", "weight" => "18K (3.5g)", "price" => "₹42,300"],
    ["image" => "images/45.jpg", "title" => "Bridal Anklet", "weight" => "22K (22.8g)", "price" => "₹1,35,600"],
    ["image" => "images/46.jpg", "title" => "Wedding Bracelet", "weight" => "22K (15.2g)", "price" => "₹88,999"]
];

$wedding_categories = [
    ["name" => "Bridal Sets", "image" => "images/35.jpg"],
    ["name" => "Engagement Rings", "image" => "images/36.jpg"],
    ["name" => "Wedding Necklaces", "image" => "images/37.jpg"],
    ["name" => "Chokers", "image" => "images/38.jpg"],
    ["name" => "Bangles", "image" => "images/40.jpg"],
    ["name" => "Earrings", "image" => "images/42.jpg"],
    ["name" => "Tiaras", "image" => "images/41.jpg"],
    ["name" => "Mangalsutra", "image" => "images/43.jpg"]
];

$wedding_collections = [
    ["name" => "Traditional Wedding", "icon" => "✦", "desc" => "Timeless heirloom pieces passed down through generations. Rich craftsmanship with intricate gold work.", "color" => "#d4af37"],
    ["name" => "Contemporary Bridal", "icon" => "◈", "desc" => "Modern designs for the contemporary bride. Sleek, elegant, and effortlessly sophisticated.", "color" => "#c59b27"],
    ["name" => "Royal Heritage", "icon" => "♛", "desc" => "Inspired by royal lineages. Grand, opulent jewellery fit for a queen on her special day.", "color" => "#f9d423"],
    ["name" => "Modern Wedding", "icon" => "❋", "desc" => "Minimalist wedding jewellery with a modern twist. Perfect for intimate and fusion weddings.", "color" => "#d4af37"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Royal Bridal Collection"],
    ["image" => "images/19.jpg", "title" => "Wedding Luxe Edit"],
    ["image" => "images/20.jpg", "title" => "Bridal Dream Collection"],
    ["image" => "images/21.jpg", "title" => "Heritage Wedding Set"],
    ["image" => "images/22.jpg", "title" => "Contemporary Bride"],
    ["image" => "images/23.jpg", "title" => "Grand Wedding Line"]
];

$page_title = "Wedding Jewellery Collection – PASVI";
$page_description = "Explore PASVI's exclusive Wedding Jewellery collection – bridal sets, engagement rings, chokers, tiaras & more.";
include 'header.php';
?>
<style>
/* ===== WEDDING PAGE SPECIFIC STYLES ===== */
.wedding-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #1a0a0c 0%, #3b1218 40%, #6b1a24 70%, #2d0d10 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.wedding-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(212,175,55,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(212,175,55,0.1) 0%, transparent 50%);
}
.wedding-hero-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; }
.wedding-particle {
    position: absolute; width: 6px; height: 6px; background: #d4af37; border-radius: 50%;
    animation: weddingFloat 6s infinite ease-in-out; opacity: 0;
    box-shadow: 0 0 10px rgba(212,175,55,0.8), 0 0 20px rgba(212,175,55,0.4);
}
.wedding-particle:nth-child(1) { top: 5%; left: 10%; animation-delay: 0s; }
.wedding-particle:nth-child(2) { top: 18%; left: 75%; animation-delay: 0.8s; width: 8px; height: 8px; }
.wedding-particle:nth-child(3) { top: 45%; left: 20%; animation-delay: 1.6s; }
.wedding-particle:nth-child(4) { top: 62%; left: 82%; animation-delay: 2.4s; width: 7px; height: 7px; }
.wedding-particle:nth-child(5) { top: 28%; left: 55%; animation-delay: 0.4s; }
.wedding-particle:nth-child(6) { top: 75%; left: 38%; animation-delay: 1.2s; width: 6px; height: 6px; }
.wedding-particle:nth-child(7) { top: 12%; left: 42%; animation-delay: 2s; }
.wedding-particle:nth-child(8) { top: 55%; left: 88%; animation-delay: 0.6s; width: 8px; height: 8px; }
.wedding-particle:nth-child(9) { top: 38%; left: 6%; animation-delay: 1.8s; }
.wedding-particle:nth-child(10) { top: 88%; left: 60%; animation-delay: 2.8s; width: 6px; height: 6px; }
.wedding-particle:nth-child(11) { top: 8%; left: 85%; animation-delay: 1s; }
.wedding-particle:nth-child(12) { top: 68%; left: 15%; animation-delay: 2.2s; width: 7px; height: 7px; }
.wedding-particle:nth-child(13) { top: 32%; left: 45%; animation-delay: 0.2s; }
.wedding-particle:nth-child(14) { top: 92%; left: 48%; animation-delay: 3s; }
.wedding-particle:nth-child(15) { top: 48%; left: 68%; animation-delay: 1.4s; width: 6px; height: 6px; }
@keyframes weddingFloat {
    0% { transform: translateY(0) scale(0); opacity: 0; }
    20% { opacity: 1; }
    50% { transform: translateY(-50px) scale(1.5); opacity: 1; }
    80% { opacity: 0.6; }
    100% { transform: translateY(-100px) scale(0); opacity: 0; }
}
.wedding-hero-content { position: relative; z-index: 2; text-align: center; color: #fff; padding: 0 20px; }
.wedding-sep { width: 60px; height: 3px; background: linear-gradient(90deg, #d4af37, #f9d423); margin: 12px auto; border-radius: 2px; }
.wedding-hero-content .wedding-badge {
    display: inline-block; background: rgba(212,175,55,0.2); border: 1px solid rgba(212,175,55,0.4);
    padding: 8px 28px; border-radius: 30px; font-size: 13px; letter-spacing: 3px;
    text-transform: uppercase; color: #d4af37; margin-bottom: 25px; font-weight: 500;
}
.wedding-hero-content h1 {
    font-size: 70px; font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 4px;
    background: linear-gradient(135deg, #f9d423, #d4af37, #c59b27, #f9d423); background-size: 300% 300%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: weddingShimmer 4s ease-in-out infinite; margin-bottom: 15px;
}
@keyframes weddingShimmer { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
.wedding-hero-content p { font-size: 18px; color: rgba(255,255,255,0.85); font-weight: 300; letter-spacing: 1px; margin-bottom: 30px; }
.wedding-hero-content .wedding-btn { display: inline-block; padding: 16px 50px; background: linear-gradient(135deg, #d4af37, #c59b27); color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.4s; }
.wedding-hero-content .wedding-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
.wedding-breadcrumb { max-width: 1300px; margin: 20px auto; padding: 0 40px; font-size: 14px; color: #888; }
.wedding-breadcrumb a { color: #888; text-decoration: none; transition: color 0.3s; }
.wedding-breadcrumb a:hover { color: #d4af37; }
.wedding-breadcrumb span { color: #333; font-weight: 500; }
.wedding-categories { max-width: 1300px; margin: 0 auto; padding: 0 40px; }
.wedding-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.wedding-cat-card { position: relative; cursor: pointer; overflow: hidden; border-radius: 10px; aspect-ratio: 1/1; transition: all 0.4s; }
.wedding-cat-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.wedding-cat-card:hover img { transform: scale(1.08); }
.wedding-cat-card .cat-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px 15px; text-align: center; }
.wedding-cat-card .cat-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.wedding-cat-card .cat-overlay .wedding-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.wedding-products { max-width: 1300px; margin: 0 auto; padding: 0 40px 40px; }
.wedding-collections { background: linear-gradient(135deg, #1a0a0c 0%, #2d0d10 100%); padding: 70px 40px; margin: 40px 0; }
.collections-container { max-width: 1300px; margin: 0 auto; }
.collections-container .section-title h2 { color: #fff; }
.collections-container .section-title p { color: rgba(255,255,255,0.6); }
.collections-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 20px; }
.collection-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2); border-radius: 16px; padding: 36px 24px; text-align: center; transition: all 0.4s; backdrop-filter: blur(10px); }
.collection-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(212,175,55,0.5); transform: translateY(-5px); }
.collection-card .collection-icon { font-size: 48px; margin-bottom: 15px; display: block; }
.collection-card h3 { font-size: 20px; font-family: 'Playfair Display', serif; color: #d4af37; margin-bottom: 5px; }
.collection-card .collection-name { font-size: 12px; color: rgba(212,175,55,0.7); letter-spacing: 2px; margin-bottom: 12px; font-weight: 500; text-transform: uppercase; }
.collection-card p { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
.collection-card .collection-btn { display: inline-block; margin-top: 20px; padding: 10px 30px; border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 30px; font-size: 13px; cursor: pointer; transition: all 0.3s; }
.collection-card .collection-btn:hover { background: #d4af37; color: #1a0a0c; }
.designer-picks { max-width: 1300px; margin: 40px auto; padding: 0 40px; }
.picks-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.pick-card { position: relative; overflow: hidden; border-radius: 12px; aspect-ratio: 3/2; cursor: pointer; }
.pick-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.pick-card:hover img { transform: scale(1.08); }
.pick-card .pick-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent 20%, rgba(0,0,0,0.85)); padding: 30px 20px 20px; text-align: center; }
.pick-card .pick-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.pick-card .pick-overlay .wedding-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.wedding-features { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 1300px; margin: 50px auto; padding: 0 40px; gap: 20px; }
.wedding-cta { background: linear-gradient(135deg, #8b1c22 0%, #641820 50%, #3b1218 100%); text-align: center; padding: 70px 40px; margin: 40px 0; }
.wedding-cta h2 { font-size: 36px; font-family: 'Playfair Display', serif; color: #fff; margin-bottom: 10px; }
.wedding-cta p { color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 25px; }
.wedding-cta .cta-btn { display: inline-block; padding: 16px 50px; background: #d4af37; color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
.wedding-cta .cta-btn:hover { background: #f9d423; transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
@media(max-width: 1024px) {
    .wedding-hero-content h1 { font-size: 52px; }
    .wedding-cat-grid, .collections-grid { grid-template-columns: repeat(4, 1fr); }
    .picks-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .wedding-hero { height: 380px; }
    .wedding-hero-content h1 { font-size: 40px; }
    .wedding-hero-content p { font-size: 15px; }
    .wedding-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .wedding-breadcrumb, .wedding-categories { padding: 0 20px; }
    .wedding-products { padding: 0 20px 20px; }
    .collections-grid { grid-template-columns: repeat(2, 1fr); }
    .picks-grid { grid-template-columns: 1fr; }
    .wedding-features { grid-template-columns: repeat(2, 1fr); padding: 0 20px; }
    .wedding-cta h2 { font-size: 28px; }
}
@media(max-width: 480px) {
    .wedding-hero { height: 320px; }
    .wedding-hero-content h1 { font-size: 32px; }
    .wedding-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .collections-grid, .wedding-features { grid-template-columns: 1fr; }
}
</style>

<!-- WEDDING HERO BANNER -->
<section class="wedding-hero">
<div class="wedding-hero-bg"></div>
<div class="wedding-hero-particles">
    <div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div>
    <div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div>
    <div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div><div class="wedding-particle"></div>
</div>
<div class="wedding-hero-content">
<div class="wedding-badge">Bridal Collection</div>
<h1>WEDDING</h1>
<p>Discover our exclusive bridal jewellery — Bridal Sets, Engagement Rings, Chokers, Tiaras & more</p>
<a href="#products" class="wedding-btn">EXPLORE COLLECTION</a>
</div>
</section>

<!-- BREADCRUMB -->
<div class="wedding-breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; <span>Wedding Jewellery</span></div>

<!-- WEDDING CATEGORIES -->
<section class="wedding-categories">
<div class="section-title"><h2>Shop by Category</h2><div class="wedding-sep"></div><p>Browse our exquisite wedding jewellery collection</p></div>
<div class="wedding-cat-grid">
<?php foreach($wedding_categories as $cat) { ?>
<div class="wedding-cat-card"><img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>"><div class="cat-overlay"><div class="wedding-line-small"></div><h4><?php echo $cat['name']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- WEDDING PRODUCTS -->
<section class="wedding-products" id="products">
<div class="section-title"><h2>Wedding Jewellery Collection</h2><div class="wedding-sep"></div><p>Stunning bridal pieces crafted for your special day</p></div>
<div class="product-grid">
<?php foreach($wedding_products as $index => $product) { $pid = 'wedding_' . $index; ?>
<div class="product-card" data-id="<?php echo $pid; ?>" data-name="<?php echo htmlspecialchars($product['title']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>" data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
<span class="product-badge">BRIDAL</span>
<img class="product-img" src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
<div class="product-info"><h4><?php echo $product['title']; ?></h4><div class="weight"><?php echo $product['weight']; ?></div><div class="price"><?php echo $product['price']; ?> <small>MRP</small></div><div class="product-actions"><button class="add-cart">ADD TO CART</button><div class="wishlist-btn">♡</div></div></div>
</div>
<?php } ?>
</div>
</section>

<!-- WEDDING COLLECTIONS -->
<section class="wedding-collections">
<div class="collections-container">
<div class="section-title"><h2>Wedding Collections</h2><div class="wedding-sep"></div><p>Find your perfect bridal style — from traditional to contemporary</p></div>
<div class="collections-grid">
<?php foreach($wedding_collections as $collection) { ?>
<div class="collection-card"><span class="collection-icon"><?php echo $collection['icon']; ?></span><h3><?php echo $collection['name']; ?></h3><div class="collection-name">Bridal Collection</div><p><?php echo $collection['desc']; ?></p><button class="collection-btn">Explore <?php echo explode(' ', $collection['name'])[0]; ?></button></div>
<?php } ?>
</div>
</div>
</section>

<!-- DESIGNER PICKS -->
<section class="designer-picks">
<div class="section-title"><h2>Designer's Pick</h2><div class="wedding-sep"></div><p>Curated bridal collections by our master jewellers</p></div>
<div class="picks-grid">
<?php foreach($designer_picks as $pick) { ?>
<div class="pick-card"><img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>"><div class="pick-overlay"><div class="wedding-line-small"></div><h4><?php echo $pick['title']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- FEATURES -->
<div class="wedding-features">
<div class="feature-box"><div class="feature-icon">📜</div><h5>Certified Quality</h5><p>Hallmarked gold & certified diamonds</p></div>
<div class="feature-box"><div class="feature-icon">🔄</div><h5>Easy Exchange</h5><p>Hassle-free return & exchange</p></div>
<div class="feature-box"><div class="feature-icon">📦</div><h5>Free Shipping</h5><p>Complimentary insured delivery</p></div>
<div class="feature-box"><div class="feature-icon">💎</div><h5>Lifetime Care</h5><p>Free cleaning & polishing</p></div>
</div>

<!-- CTA BANNER -->
<section class="wedding-cta">
<h2>Ready to Find Your Perfect Bridal Set?</h2>
<p>Visit our store in Surat or browse our entire wedding collection online</p>
<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>
</section>

<script>
document.querySelector('.wedding-btn')?.addEventListener('click', function(e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if(target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });
</script>

<?php include 'footer.php'; ?>
