<?php
session_start();
define('JEWELLERY_ACCESS', true);

$new_arrivals = [
    ["image" => "images/1.jpg", "title" => "Elegant Gold Earrings"],
    ["image" => "images/2.jpg", "title" => "Diamond Ring Collection"],
    ["image" => "images/3.jpg", "title" => "Modern Necklace"],
    ["image" => "images/4.jpg", "title" => "Wedding Jewellery"]
];

$categories = [
    ["Earrings", "6.jpg"],
    ["Finger Rings", "7.jpg"],
    ["Pendants", "8.jpg"],
    ["Mangalsutra", "9.jpg"],
    ["Bracelets", "10.jpg"],
    ["Bangles", "11.jpg"],
    ["Chains", "12.jpg"],
    ["View All", "4.jpg"]
];

// Featured products for the homepage with Add to Cart support
$featured_products = [
    ["image" => "images/1.jpg", "title" => "Elegant Gold Earrings", "weight" => "22K (8.2g)", "price" => "₹42,800"],
    ["image" => "images/2.jpg", "title" => "Diamond Solitaire Ring", "weight" => "0.50 Ct (18K)", "price" => "₹67,800"],
    ["image" => "images/3.jpg", "title" => "Modern Gold Necklace", "weight" => "22K (18.5g)", "price" => "₹88,500"],
    ["image" => "images/4.jpg", "title" => "Diamond Wedding Set", "weight" => "22K (14.6g)", "price" => "₹1,12,999"],
    ["image" => "images/5.jpg", "title" => "Gold Bracelet", "weight" => "22K (12.4g)", "price" => "₹58,700"],
    ["image" => "images/6.jpg", "title" => "Gold Jhumka Earrings", "weight" => "22K (8.2g)", "price" => "₹38,999"],
    ["image" => "images/7.jpg", "title" => "Diamond Stud Earrings", "weight" => "0.30 Ct (18K)", "price" => "₹45,999"],
    ["image" => "images/8.jpg", "title" => "Gold Pendant Set", "weight" => "22K (6.8g)", "price" => "₹35,200"]
];

$page_title = "PASVI Jewellery – Timeless Elegance";
$page_description = "PASVI Jewellery – Explore timeless elegance with our exclusive gold, diamond, and bridal jewellery collections. Shop new arrivals and trending pieces.";

include 'header.php';
?>

<!-- HERO SLIDER -->
<section class="slider">
<div class="slides">
<div class="slide active">
<img src="images/1.jpg" alt="PASVI Jewellery Banner - Elegant Gold Earrings">
<div class="banner-content">
<h1>PASVI</h1>
<h2>Stunning<br>Every <i>Ear</i></h2>
<button>SHOP NOW</button>
</div>
</div>
<div class="slide">
<img src="images/3.jpg" alt="PASVI Jewellery Banner - Diamond Collection">
<div class="banner-content">
<h1>PASVI</h1>
<h2>Dazzling<br><i>Diamond</i> Dreams</h2>
<button>SHOP NOW</button>
</div>
</div>
<div class="slide">
<img src="images/5.jpg" alt="PASVI Jewellery Banner - Wedding Collection">
<div class="banner-content">
<h1>PASVI</h1>
<h2>Timeless<br><i>Bridal</i> Elegance</h2>
<button>SHOP NOW</button>
</div>
</div>
</div>
</section>

<div class="dots">
<span data-index="0" class="active"></span>
<span data-index="1"></span>
<span data-index="2"></span>
</div>

<!-- COLLECTION -->
<section class="collection">
<h2>PASVI Collections</h2>
<p>Explore our newly launched collection</p>
<div class="main-banner">
<div class="big-card">
    <video autoplay muted loop playsinline>
        <source src="images/aeec68b749bd85b4228360683803a4c4_720w.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
<div class="small-cards">
<div>
    <video autoplay muted loop playsinline>
        <source src="images/fae61172654f40caf68fd7d27a272fd7_720w.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
<div>
    <video autoplay muted loop playsinline>
        <source src="images/b366ca4ce9d5db065ffa3ca12fa29429_720w.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>
</div>
</div>
</section>

<!-- CATEGORY -->
<section class="categories">
<h2>Find Your Perfect Match</h2>
<p>Shop by Categories</p>
<div class="category-grid">
<?php foreach($categories as $cat){ ?>
<div class="category-box">
<img src="images/<?php echo $cat[1]; ?>" alt="<?php echo $cat[0]; ?>">
<h5><?php echo $cat[0]; ?></h5>
</div>
<?php } ?>
</div>
</section>

<!-- TRENDING -->
<section class="trending">
<h2>Trending Now</h2>
<p>Jewellery pieces everyone's eyeing right now</p>
<div class="trend-grid">
<div><img src="images/19.jpg" alt="Trending"><h5>Auspicious Occasion</h5></div>
<div><img src="images/20.jpg" alt="Trending"><h5>Gifting Jewellery</h5></div>
<div><img src="images/21.jpg" alt="Trending"><h5>Origami Edit</h5></div>
</div>
</section>

<!-- NEW ARRIVALS -->
<section class="new-arrivals">
<h2>New Arrivals</h2>
<p>Discover our latest collection crafted for you</p>
<div class="arrival-grid">
<?php foreach($new_arrivals as $item){ ?>
<div class="arrival-item">
<img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>">
<div class="overlay"><h4><?php echo $item['title']; ?></h4></div>
</div>
<?php } ?>
</div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="featured-products" style="width:90%;margin:50px auto;text-align:center;padding:30px 0;">
<h2 style="font-size:30px;color:#222;font-family:serif;">Featured Collection</h2>
<p style="font-size:14px;color:#777;margin-bottom:30px;">Handpicked jewellery pieces for you</p>
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:22px;max-width:1300px;margin:0 auto;">
<?php foreach($featured_products as $index => $product) { 
    $pid = 'featured_' . $index;
?>
<div class="product-card" 
    data-id="<?php echo $pid; ?>"
    data-name="<?php echo htmlspecialchars($product['title']); ?>"
    data-price="<?php echo htmlspecialchars($product['price']); ?>"
    data-image="<?php echo htmlspecialchars($product['image']); ?>"
    data-weight="<?php echo htmlspecialchars($product['weight']); ?>"
    style="background:#fff;border-radius:12px;overflow:hidden;transition:all 0.4s;cursor:pointer;border:1px solid #f0f0f0;position:relative;">
<div style="overflow:hidden;">
<img src="<?php echo $product['image']; ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" style="width:100%;aspect-ratio:1/1;object-fit:cover;transition:transform 0.6s;display:block;">
</div>
<div style="padding:16px 18px 20px;">
<h4 style="font-size:15px;font-weight:500;color:#222;margin-bottom:4px;"><?php echo htmlspecialchars($product['title']); ?></h4>
<div style="font-size:13px;color:#999;font-weight:300;"><?php echo htmlspecialchars($product['weight']); ?></div>
<div style="font-size:19px;font-weight:600;color:#8b1c22;margin-top:8px;"><?php echo htmlspecialchars($product['price']); ?></div>
<div style="display:flex;gap:10px;margin-top:14px;">
<button class="add-cart" style="flex:1;padding:10px 0;background:linear-gradient(135deg,#8b1c22,#641820);color:#fff;border:none;border-radius:6px;font-size:13px;font-weight:500;cursor:pointer;letter-spacing:1px;">ADD TO CART</button>
<div class="wishlist-btn" style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;border:1px solid #ddd;border-radius:6px;background:#fff;cursor:pointer;font-size:18px;transition:all 0.3s;">♡</div>
</div>
</div>
</div>
<?php } ?>
</div>
</section>

<style>
/* ===== INDEX PAGE SPECIFIC STYLES ===== */
.slider {
    width: 82%;
    margin: 10px auto;
    height: 590px;
    overflow: hidden;
    border-radius: 15px;
    position: relative;
}
.slides {
    position: relative;
    width: 100%;
    height: 100%;
}
.slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
}
.slide.active {
    opacity: 1;
    visibility: visible;
}
.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.banner-content {
    position: absolute;
    right: 8%;
    top: 25%;
    text-align: center;
    color: white;
}
.banner-content h1 {
    font-size: 55px;
    font-family: serif;
    letter-spacing: 5px;
}
.banner-content h2 {
    font-size: 40px;
    font-weight: 300;
    margin-top: 20px;
}
.banner-content i {
    font-family: cursive;
    font-size: 55px;
}
.banner-content button {
    margin-top: 40px;
    padding: 15px 60px;
    background: white;
    border: none;
    font-size: 18px;
    letter-spacing: 2px;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
}
.banner-content button:hover {
    background: #8b1c22;
    color: white;
}
.dots {
    text-align: center;
    margin-top: 25px;
}
.dots span {
    height: 12px;
    width: 12px;
    background: #ddd;
    display: inline-block;
    border-radius: 50%;
    margin: 8px;
    cursor: pointer;
}
.dots .active {
    background: #8b1c22;
}
.big-card video{
    width:100%;
    height:480px;
    object-fit:cover;
    border-radius:5px;
    display:block;
}
.collection, .categories, .trending, .new-arrivals {
    width: 90%;
    margin: 50px auto;
    text-align: center;
    padding: 30px 0;
}
.main-banner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 5px;
}
.small-cards {
    display: grid;
    grid-template-rows: 1fr 1fr;
    gap: 5px;
}
.small-cards video{
    width:100%;
    height:235px;
    object-fit:cover;
    border-radius:5px;
    display:block;
}
.category-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}
.category-box {
    cursor: pointer;
    transition: transform 0.3s;
}
.category-box:hover {
    transform: translateY(-5px);
}
.category-box img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 5px;
}
.category-box h5 {
    font-size: 13px;
    font-weight: 500;
    margin-top: 8px;
    color: #333;
}
.trend-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}
.trend-grid img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 6px;
}
.trend-grid h5 {
    font-size: 13px;
    font-weight: 500;
    margin-top: 8px;
    color: #444;
}
.arrival-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    max-width: 900px;
    margin: 0 auto;
}
.arrival-item {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    aspect-ratio: 1 / 1;
    cursor: pointer;
    transition: transform 0.3s;
}
.arrival-item:hover {
    transform: scale(1.03);
}
.arrival-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.arrival-item .overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
    padding: 15px;
    text-align: left;
}
.arrival-item .overlay h4 {
    color: #fff;
    font-size: 15px;
    font-weight: 500;
}
.product-card .add-cart { background: linear-gradient(135deg,#8b1c22,#641820); color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; letter-spacing: 1px; }
.product-card .add-cart:hover { background: linear-gradient(135deg,#a5222a,#7d1d22); }
@media(max-width:1024px){.slider{width:92%;height:400px}.banner-content h1{font-size:40px}.banner-content h2{font-size:30px}.banner-content i{font-size:40px}.banner-content button{padding:12px 40px;font-size:16px}}
@media(max-width:768px){.slider{width:96%;height:320px;border-radius:10px}.banner-content{right:5%;top:20%}.banner-content h1{font-size:32px}.banner-content h2{font-size:24px}.banner-content i{font-size:30px}.banner-content button{padding:10px 30px;font-size:14px;margin-top:25px}.main-banner{grid-template-columns:1fr}.big-card video{height:300px}.small-cards video{height:180px}.category-grid{grid-template-columns:repeat(2,1fr)}.trend-grid{grid-template-columns:1fr}.trend-grid img{height:220px}.arrival-grid{grid-template-columns:repeat(2,1fr)}.featured-products>div{grid-template-columns:repeat(2,1fr)!important}}
@media(max-width:480px){.slider{height:250px}.banner-content h1{font-size:24px;letter-spacing:3px}.banner-content h2{font-size:18px}.banner-content i{font-size:22px}.banner-content button{padding:8px 20px;font-size:12px;margin-top:15px}.arrival-grid{grid-template-columns:1fr 1fr;gap:10px}.featured-products>div{grid-template-columns:1fr!important}}
</style>

<!-- SLIDER JAVASCRIPT -->
<script>
(function() {
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dots span');
    let currentIndex = 0;
    let interval;

    function goToSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        slides.forEach((s, i) => { s.classList.toggle('active', i === index); });
        dots.forEach((d, i) => { d.classList.toggle('active', i === index); });
        currentIndex = index;
    }

    function nextSlide() { goToSlide(currentIndex + 1); }
    function startAutoPlay() { interval = setInterval(nextSlide, 4000); }
    function stopAutoPlay() { clearInterval(interval); }

    dots.forEach((dot) => {
        dot.addEventListener('click', function() {
            stopAutoPlay();
            goToSlide(parseInt(this.getAttribute('data-index')));
            startAutoPlay();
        });
    });

    goToSlide(0);
    startAutoPlay();
})();
</script>

<?php include 'footer.php'; ?>
