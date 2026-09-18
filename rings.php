<?php
session_start();
define('JEWELLERY_ACCESS', true);

$rings_products = [
    ["image" => "images/2.jpg", "title" => "Solitaire Diamond Ring", "weight" => "0.50 Ct (18K)", "price" => "₹67,800"],
    ["image" => "images/7.jpg", "title" => "Cocktail Sapphire Ring", "weight" => "18K (6.8g)", "price" => "₹58,700"],
    ["image" => "images/3.jpg", "title" => "Wedding Band Set", "weight" => "22K (8.2g)", "price" => "₹72,900"],
    ["image" => "images/1.jpg", "title" => "Eternity Diamond Ring", "weight" => "0.75 Ct (18K)", "price" => "₹89,500"],
    ["image" => "images/6.jpg", "title" => "Stackable Gold Ring", "weight" => "18K (3.2g)", "price" => "₹28,500"],
    ["image" => "images/10.jpg", "title" => "Gemstone Cocktail Ring", "weight" => "18K (5.5g)", "price" => "₹45,200"],
    ["image" => "images/4.jpg", "title" => "Pearl Statement Ring", "weight" => "18K (4.8g)", "price" => "₹38,400"],
    ["image" => "images/5.jpg", "title" => "Bridal Diamond Ring", "weight" => "1.50 Ct (18K)", "price" => "₹1,85,000"],
    ["image" => "images/8.jpg", "title" => "Rose Gold Eternity", "weight" => "18K (3.8g)", "price" => "₹48,700"],
    ["image" => "images/9.jpg", "title" => "Vintage Emerald Ring", "weight" => "18K (6.2g)", "price" => "₹63,500"],
    ["image" => "images/11.jpg", "title" => "Promise Ring Set", "weight" => "18K (2.8g)", "price" => "₹22,800"],
    ["image" => "images/12.jpg", "title" => "Platinum Solitaire", "weight" => "1.00 Ct (Platinum)", "price" => "₹1,25,600"]
];

$rings_categories = [
    ["name" => "Solitaire Rings", "image" => "images/2.jpg"],
    ["name" => "Cocktail Rings", "image" => "images/7.jpg"],
    ["name" => "Wedding Bands", "image" => "images/3.jpg"],
    ["name" => "Eternity Rings", "image" => "images/1.jpg"],
    ["name" => "Stackable Rings", "image" => "images/6.jpg"],
    ["name" => "Statement Rings", "image" => "images/4.jpg"],
    ["name" => "Gemstone Rings", "image" => "images/10.jpg"],
    ["name" => "Promise Rings", "image" => "images/11.jpg"]
];

$rings_styles = [
    ["style" => "Classic Solitaire", "icon" => "◇", "desc" => "A single stunning diamond or gemstone taking center stage. Timeless, elegant, and perfect for engagements and milestones.", "color" => "#d4af37"],
    ["style" => "Halo Setting", "icon" => "✦", "desc" => "Center stone encircled by a 'halo' of smaller diamonds for maximum brilliance and a larger-looking centerpiece.", "color" => "#c59b27"],
    ["style" => "Three-Stone", "icon" => "◈", "desc" => "Three stones side by side representing past, present and future. A meaningful choice for anniversaries and commitments.", "color" => "#b8962b"],
    ["style" => "Vintage & Antique", "icon" => "❋", "desc" => "Intricate filigree work, milgrain detailing and ornate designs inspired by Victorian, Art Deco and Edwardian eras.", "color" => "#8b1c22"]
];

$designer_picks = [
    ["image" => "images/13.jpg", "title" => "Royal Solitaire Edit"],
    ["image" => "images/19.jpg", "title" => "Bridal Ring Collection"],
    ["image" => "images/20.jpg", "title" => "Everyday Elegance Range"],
    ["image" => "images/21.jpg", "title" => "Modern Ring Trends"],
    ["image" => "images/22.jpg", "title" => "Heritage Gemstone Line"],
    ["image" => "images/23.jpg", "title" => "Contemporary Classics"]
];

$page_title = "Rings Collection – PASVI";
$page_description = "Explore PASVI's exclusive Rings collection – solitaire, cocktail, wedding, eternity, gemstone & promise rings.";
include 'header.php';
?>
<style>
/* ===== RINGS PAGE SPECIFIC STYLES ===== */
.rings-hero {
    position: relative;
    width: 100%;
    height: 500px;
    overflow: hidden;
    background: linear-gradient(135deg, #1a0a0c 0%, #3b1218 40%, #6b1a24 70%, #2d0d10 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}
.rings-hero-bg {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(ellipse at 30% 50%, rgba(212,175,55,0.15) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 50%, rgba(212,175,55,0.1) 0%, transparent 50%);
}
.rings-hero-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; }
.gold-particle {
    position: absolute; width: 4px; height: 4px; background: #d4af37; border-radius: 50%;
    animation: goldFloat 6s infinite ease-in-out; opacity: 0.6;
}
.gold-particle:nth-child(1) { top: 10%; left: 15%; animation-delay: 0s; width: 5px; height: 5px; }
.gold-particle:nth-child(2) { top: 25%; left: 72%; animation-delay: 0.8s; width: 8px; height: 8px; }
.gold-particle:nth-child(3) { top: 48%; left: 28%; animation-delay: 1.6s; width: 4px; height: 4px; }
.gold-particle:nth-child(4) { top: 68%; left: 82%; animation-delay: 2.4s; width: 7px; height: 7px; }
.gold-particle:nth-child(5) { top: 32%; left: 52%; animation-delay: 0.4s; width: 5px; height: 5px; }
.gold-particle:nth-child(6) { top: 80%; left: 38%; animation-delay: 1.2s; width: 6px; height: 6px; }
.gold-particle:nth-child(7) { top: 18%; left: 42%; animation-delay: 2s; width: 4px; height: 4px; }
.gold-particle:nth-child(8) { top: 58%; left: 88%; animation-delay: 0.6s; width: 8px; height: 8px; }
.gold-particle:nth-child(9) { top: 42%; left: 6%; animation-delay: 1.8s; width: 5px; height: 5px; }
.gold-particle:nth-child(10) { top: 88%; left: 62%; animation-delay: 2.8s; width: 6px; height: 6px; }
.gold-particle:nth-child(11) { top: 12%; left: 90%; animation-delay: 1s; width: 4px; height: 4px; }
.gold-particle:nth-child(12) { top: 72%; left: 18%; animation-delay: 2.2s; width: 7px; height: 7px; }
.gold-particle:nth-child(13) { top: 38%; left: 45%; animation-delay: 0.2s; width: 5px; height: 5px; }
.gold-particle:nth-child(14) { top: 95%; left: 48%; animation-delay: 3s; width: 4px; height: 4px; }
.gold-particle:nth-child(15) { top: 52%; left: 65%; animation-delay: 1.4s; width: 6px; height: 6px; }
@keyframes goldFloat {
    0%, 100% { transform: translateY(0) scale(1); opacity: 0.6; }
    50% { transform: translateY(-40px) scale(1.5); opacity: 1; }
}
.rings-hero-content { position: relative; z-index: 2; text-align: center; color: #fff; padding: 0 20px; }
.rings-hero-content .rings-badge {
    display: inline-block; background: rgba(212,175,55,0.2); border: 1px solid rgba(212,175,55,0.4);
    padding: 8px 28px; border-radius: 30px; font-size: 13px; letter-spacing: 3px;
    text-transform: uppercase; color: #d4af37; margin-bottom: 25px; font-weight: 500;
}
.rings-hero-content h1 {
    font-size: 70px; font-family: 'Playfair Display', serif; font-weight: 700; letter-spacing: 4px;
    background: linear-gradient(135deg, #f9d423, #d4af37, #c59b27, #f9d423); background-size: 300% 300%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: shimmer 4s ease-in-out infinite; margin-bottom: 15px;
}
@keyframes shimmer { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
.rings-hero-content p { font-size: 18px; color: rgba(255,255,255,0.85); font-weight: 300; letter-spacing: 1px; margin-bottom: 30px; }
.rings-hero-content .rings-btn { display: inline-block; padding: 16px 50px; background: linear-gradient(135deg, #d4af37, #c59b27); color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.4s; }
.rings-hero-content .rings-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
.rings-breadcrumb { max-width: 1300px; margin: 20px auto; padding: 0 40px; font-size: 14px; color: #888; }
.rings-breadcrumb a { color: #888; text-decoration: none; transition: color 0.3s; }
.rings-breadcrumb a:hover { color: #8b1c22; }
.rings-breadcrumb span { color: #333; font-weight: 500; }
.rings-categories { max-width: 1300px; margin: 0 auto; padding: 0 40px; }
.rings-cat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
.rings-cat-card { position: relative; cursor: pointer; overflow: hidden; border-radius: 10px; aspect-ratio: 1/1; transition: all 0.4s; }
.rings-cat-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.rings-cat-card:hover img { transform: scale(1.08); }
.rings-cat-card .cat-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px 15px; text-align: center; }
.rings-cat-card .cat-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.rings-cat-card .cat-overlay .gold-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.rings-products { max-width: 1300px; margin: 0 auto; padding: 0 40px 40px; }
.rings-style { background: linear-gradient(135deg, #1a0a0c 0%, #2d0d10 50%, #1a0a0c 100%); padding: 70px 40px; margin: 40px 0; }
.style-container { max-width: 1300px; margin: 0 auto; }
.style-container .section-title h2 { color: #fff; }
.style-container .section-title p { color: rgba(255,255,255,0.6); }
.style-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-top: 20px; }
.style-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(212,175,55,0.2); border-radius: 16px; padding: 36px 24px; text-align: center; transition: all 0.4s; backdrop-filter: blur(10px); }
.style-card:hover { background: rgba(255,255,255,0.08); border-color: rgba(212,175,55,0.5); transform: translateY(-5px); }
.style-card .style-icon { font-size: 48px; margin-bottom: 15px; display: block; color: #fff; }
.style-card h3 { font-size: 20px; font-family: 'Playfair Display', serif; color: #d4af37; margin-bottom: 5px; }
.style-card .style-name { font-size: 12px; color: rgba(212,175,55,0.8); letter-spacing: 2px; margin-bottom: 12px; font-weight: 500; text-transform: uppercase; }
.style-card p { font-size: 14px; color: rgba(255,255,255,0.7); line-height: 1.6; }
.style-card .style-btn { display: inline-block; margin-top: 20px; padding: 10px 30px; border: 1px solid #d4af37; color: #d4af37; background: transparent; border-radius: 30px; font-size: 13px; cursor: pointer; transition: all 0.3s; }
.style-card .style-btn:hover { background: #d4af37; color: #1a0a0c; }
.designer-picks { max-width: 1300px; margin: 40px auto; padding: 0 40px; }
.picks-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.pick-card { position: relative; overflow: hidden; border-radius: 12px; aspect-ratio: 3/2; cursor: pointer; }
.pick-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s; }
.pick-card:hover img { transform: scale(1.08); }
.pick-card .pick-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent 20%, rgba(0,0,0,0.85)); padding: 30px 20px 20px; text-align: center; }
.pick-card .pick-overlay h4 { color: #fff; font-size: 16px; font-weight: 500; letter-spacing: 1px; }
.pick-card .pick-overlay .gold-line-small { width: 30px; height: 2px; background: #d4af37; margin: 8px auto; }
.rings-features { display: grid; grid-template-columns: repeat(4, 1fr); max-width: 1300px; margin: 50px auto; padding: 0 40px; gap: 20px; }
.rings-cta { background: linear-gradient(135deg, #8b1c22 0%, #641820 50%, #3b1218 100%); text-align: center; padding: 70px 40px; margin: 40px 0; }
.rings-cta h2 { font-size: 36px; font-family: 'Playfair Display', serif; color: #fff; margin-bottom: 10px; }
.rings-cta p { color: rgba(255,255,255,0.7); font-size: 16px; margin-bottom: 25px; }
.rings-cta .cta-btn { display: inline-block; padding: 16px 50px; background: #d4af37; color: #1a0a0c; font-size: 16px; font-weight: 600; letter-spacing: 2px; text-decoration: none; border-radius: 4px; transition: all 0.3s; }
.rings-cta .cta-btn:hover { background: #f9d423; transform: translateY(-3px); box-shadow: 0 15px 40px rgba(212,175,55,0.3); }
@media(max-width: 1024px) {
    .rings-hero-content h1 { font-size: 52px; }
    .rings-cat-grid, .style-grid { grid-template-columns: repeat(4, 1fr); }
    .picks-grid { grid-template-columns: repeat(3, 1fr); }
}
@media(max-width: 768px) {
    .rings-hero { height: 380px; }
    .rings-hero-content h1 { font-size: 40px; }
    .rings-hero-content p { font-size: 15px; }
    .rings-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .rings-breadcrumb, .rings-categories { padding: 0 20px; }
    .rings-products { padding: 0 20px 20px; }
    .style-grid { grid-template-columns: repeat(2, 1fr); }
    .picks-grid { grid-template-columns: 1fr; }
    .rings-features { grid-template-columns: repeat(2, 1fr); padding: 0 20px; }
    .rings-cta h2 { font-size: 28px; }
}
@media(max-width: 480px) {
    .rings-hero { height: 320px; }
    .rings-hero-content h1 { font-size: 32px; }
    .rings-cat-grid { grid-template-columns: repeat(2, 1fr); }
    .style-grid, .rings-features { grid-template-columns: 1fr; }
}
</style>

<!-- RINGS HERO BANNER -->
<section class="rings-hero">
<div class="rings-hero-bg"></div>
<div class="rings-hero-particles">
    <div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div>
    <div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div>
<div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div><div class="gold-particle"></div>
</div>
<div class="rings-hero-content">
<div class="rings-badge">Exclusive Collection</div>
<h1>RINGS</h1>
<p>Discover our exquisite rings collection — Solitaire, Cocktail, Wedding & more • A ring for every finger, a style for every story</p>
<a href="#products" class="rings-btn">EXPLORE COLLECTION</a>
</div>
</section>

<!-- BREADCRUMB -->
<div class="rings-breadcrumb"><a href="index.php">Home</a> &nbsp;/&nbsp; <span>Rings</span></div>

<!-- RINGS CATEGORIES -->
<section class="rings-categories">
<div class="section-title"><h2>Shop by Style</h2><div class="gold-sep"></div><p>Browse our exquisite rings collection</p></div>
<div class="rings-cat-grid">
<?php foreach($rings_categories as $cat) { ?>
<div class="rings-cat-card"><img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>"><div class="cat-overlay"><div class="gold-line-small"></div><h4><?php echo $cat['name']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- RINGS PRODUCTS -->
<section class="rings-products" id="products">
<div class="section-title"><h2>Rings Collection</h2><div class="gold-sep"></div><p>Stunning rings crafted for every occasion</p></div>
<div class="product-grid">
<?php foreach($rings_products as $index => $product) { $pid = 'rings_' . $index; ?>
<div class="product-card" data-id="<?php echo $pid; ?>" data-name="<?php echo htmlspecialchars($product['title']); ?>" data-price="<?php echo htmlspecialchars($product['price']); ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>" data-weight="<?php echo htmlspecialchars($product['weight']); ?>">
<span class="product-badge">CERTIFIED</span>
<img class="product-img" src="<?php echo $product['image']; ?>" alt="<?php echo $product['title']; ?>">
<div class="product-info"><h4><?php echo $product['title']; ?></h4><div class="weight"><?php echo $product['weight']; ?></div><div class="price"><?php echo $product['price']; ?> <small>MRP</small></div><div class="product-actions"><button class="add-cart">ADD TO CART</button><div class="wishlist-btn">♡</div></div></div>
</div>
<?php } ?>
</div>
</section>

<!-- RINGS STYLE GUIDE -->
<section class="rings-style">
<div class="style-container">
<div class="section-title"><h2>Ring Style Guide</h2><div class="gold-sep"></div><p>Find the perfect ring style for your personality and occasion</p></div>
<div class="style-grid">
<?php foreach($rings_styles as $style) { ?>
<div class="style-card"><span class="style-icon"><?php echo $style['icon']; ?></span><h3><?php echo $style['style']; ?></h3><div class="style-name">Style Guide</div><p><?php echo $style['desc']; ?></p><button class="style-btn">Explore <?php echo explode(' ', $style['style'])[0]; ?></button></div>
<?php } ?>
</div>
</div>
</section>

<!-- DESIGNER PICKS -->
<section class="designer-picks">
<div class="section-title"><h2>Designer's Pick</h2><div class="gold-sep"></div><p>Curated collections by our master jewellers</p></div>
<div class="picks-grid">
<?php foreach($designer_picks as $pick) { ?>
<div class="pick-card"><img src="<?php echo $pick['image']; ?>" alt="<?php echo $pick['title']; ?>"><div class="pick-overlay"><div class="gold-line-small"></div><h4><?php echo $pick['title']; ?></h4></div></div>
<?php } ?>
</div>
</section>

<!-- FEATURES -->
<div class="rings-features">
<div class="feature-box"><div class="feature-icon">📜</div><h5>Certified Quality</h5><p>Hallmarked gold & certified diamonds</p></div>
<div class="feature-box"><div class="feature-icon">🔄</div><h5>Easy Exchange</h5><p>Hassle-free return & exchange</p></div>
<div class="feature-box"><div class="feature-icon">📦</div><h5>Free Shipping</h5><p>Complimentary insured delivery</p></div>
<div class="feature-box"><div class="feature-icon">💎</div><h5>Lifetime Care</h5><p>Free cleaning & polishing</p></div>
</div>

<!-- CTA BANNER -->
<section class="rings-cta">
<h2>Ready to Find Your Perfect Ring?</h2>
<p>Visit our store in Surat or browse our entire rings collection online</p>
<a href="index.php" class="cta-btn">VISIT HOMEPAGE</a>
</section>

<script>
document.querySelector('.rings-btn')?.addEventListener('click', function(e) { e.preventDefault(); const target = document.querySelector(this.getAttribute('href')); if(target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); } });
</script>

<?php include 'footer.php'; ?>
