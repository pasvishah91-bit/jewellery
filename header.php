<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('JEWELLERY_ACCESS')) {
    define('JEWELLERY_ACCESS', true);
}
if (!isset($_SESSION['wishlist'])) { $_SESSION['wishlist'] = []; }
if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }

// Determine current page for active nav
$current_page = basename($_SERVER['PHP_SELF']);

// Default page title & description (pages may override before including header.php)
if (!isset($page_title)) {
    $page_title = "PASVI Jewellery – Timeless Elegance";
}
if (!isset($page_description)) {
    $page_description = "PASVI Jewellery – Explore timeless elegance with our exclusive gold, diamond, and bridal jewellery collections. Shop new arrivals and trending pieces.";
}
if (!isset($json_ld)) {
    $json_ld = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="PASVI Jewellery">
<meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
<meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
<?php if (isset($og_image) && $og_image): ?>
<meta property="og:image" content="<?php echo htmlspecialchars($og_image); ?>">
<?php endif; ?>
<title><?php echo htmlspecialchars($page_title); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<?php if ($json_ld): ?>
<script type="application/ld+json">
<?php echo $json_ld; ?>
</script>
<?php endif; ?>
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
💎 &nbsp;
🏬 &nbsp;
<a href="track_order.php" style="text-decoration:none;color:#7d1d22;font-size:22px;" title="Track Order">🚚</a> &nbsp;
<a href="view_wishlist.php" class="wishlist-link" style="text-decoration:none;color:#7d1d22;position:relative;">
♡
<?php if(isset($_SESSION['wishlist']) && count($_SESSION['wishlist']) > 0) { ?>
<span class="wishlist-badge"><?php echo count($_SESSION['wishlist']); ?></span>
<?php } ?>
</a>
<a href="login.php" style="text-decoration:none;color:#7d1d22;">👤</a> &nbsp;
<a href="order.php" style="text-decoration:none;color:#7d1d22;font-size:22px;" title="My Orders">📋</a> &nbsp;
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
<a href="index.php" <?php echo $current_page == 'index.php' ? 'class="active"' : ''; ?>>✧ All Jewellery</a>
<a href="gold.php" <?php echo $current_page == 'gold.php' ? 'class="active"' : ''; ?>>♢ Gold</a>
<a href="diamond.php" <?php echo $current_page == 'diamond.php' ? 'class="active"' : ''; ?>>◇ Diamond</a>
<a href="earrings.php" <?php echo $current_page == 'earrings.php' ? 'class="active"' : ''; ?>>♕ Earrings</a>
<a href="rings.php" <?php echo $current_page == 'rings.php' ? 'class="active"' : ''; ?>>◉ Rings</a>
<a href="dailywear.php" <?php echo $current_page == 'dailywear.php' ? 'class="active"' : ''; ?>>♧ Daily Wear</a>
<a href="wedding.php" <?php echo $current_page == 'wedding.php' ? 'class="active"' : ''; ?>>◉ Wedding</a>
</nav>
</header>

