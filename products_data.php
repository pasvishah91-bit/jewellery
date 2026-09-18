<?php
/**
 * Shared Product Data File
 * ---------------------------------------------------------------
 * Products are now stored in the database (`products` table) and are
 * managed from the Admin Panel (add / edit / delete).
 *
 * This file:
 *  1. Defines the built-in static product arrays (used as fallback if
 *     the database is unavailable, and used to seed the DB once).
 *  2. Loads the live product list from the `products` table into
 *     `$all_products`.
 *
 * Include this file when you need access to product data.
 */

// ==================================================================
// 1) STATIC FALLBACK PRODUCT DATA (also used for first-time seeding)
// ==================================================================
$static_products = [];

// Index page featured products
$index_products = [
    ["id" => "featured_0", "name" => "Elegant Gold Earrings", "price" => "₹42,800", "image" => "images/1.jpg", "weight" => "22K (8.2g)", "category" => "All Jewellery"],
    ["id" => "featured_1", "name" => "Diamond Solitaire Ring", "price" => "₹67,800", "image" => "images/2.jpg", "weight" => "0.50 Ct (18K)", "category" => "All Jewellery"],
    ["id" => "featured_2", "name" => "Modern Gold Necklace", "price" => "₹88,500", "image" => "images/3.jpg", "weight" => "22K (18.5g)", "category" => "All Jewellery"],
    ["id" => "featured_3", "name" => "Diamond Wedding Set", "price" => "₹1,12,999", "image" => "images/4.jpg", "weight" => "22K (14.6g)", "category" => "All Jewellery"],
    ["id" => "featured_4", "name" => "Gold Bracelet", "price" => "₹58,700", "image" => "images/5.jpg", "weight" => "22K (12.4g)", "category" => "All Jewellery"],
    ["id" => "featured_5", "name" => "Gold Jhumka Earrings", "price" => "₹38,999", "image" => "images/6.jpg", "weight" => "22K (8.2g)", "category" => "All Jewellery"],
    ["id" => "featured_6", "name" => "Diamond Stud Earrings", "price" => "₹45,999", "image" => "images/7.jpg", "weight" => "0.30 Ct (18K)", "category" => "All Jewellery"],
    ["id" => "featured_7", "name" => "Gold Pendant Set", "price" => "₹35,200", "image" => "images/8.jpg", "weight" => "22K (6.8g)", "category" => "All Jewellery"]
];
if (isset($index_products)) { $static_products = array_merge($static_products, $index_products); }

// Gold products
$gold_products = [
    ["id" => "gold_0", "name" => "Gold Bangle Set", "price" => "₹1,12,999", "image" => "images/1.jpg", "weight" => "22K (24.6g)", "category" => "Gold"],
    ["id" => "gold_1", "name" => "Gold Chain Necklace", "price" => "₹88,500", "image" => "images/2.jpg", "weight" => "22K (18.5g)", "category" => "Gold"],
    ["id" => "gold_2", "name" => "Gold Earring Set", "price" => "₹42,800", "image" => "images/3.jpg", "weight" => "22K (8.2g)", "category" => "Gold"],
    ["id" => "gold_3", "name" => "Gold Mangalsutra", "price" => "₹68,999", "image" => "images/4.jpg", "weight" => "22K (14.6g)", "category" => "Gold"],
    ["id" => "gold_4", "name" => "Gold Bracelet", "price" => "₹58,700", "image" => "images/5.jpg", "weight" => "22K (12.4g)", "category" => "Gold"],
    ["id" => "gold_5", "name" => "Gold Pendant", "price" => "₹35,200", "image" => "images/6.jpg", "weight" => "22K (6.8g)", "category" => "Gold"],
    ["id" => "gold_6", "name" => "Gold Kada", "price" => "₹1,85,600", "image" => "images/7.jpg", "weight" => "24K (32.5g)", "category" => "Gold"],
    ["id" => "gold_7", "name" => "Gold Nose Pin", "price" => "₹12,999", "image" => "images/8.jpg", "weight" => "22K (2.4g)", "category" => "Gold"],
    ["id" => "gold_8", "name" => "Gold Ring", "price" => "₹28,400", "image" => "images/9.jpg", "weight" => "22K (5.8g)", "category" => "Gold"],
    ["id" => "gold_9", "name" => "Gold Anklet", "price" => "₹72,500", "image" => "images/10.jpg", "weight" => "22K (16.2g)", "category" => "Gold"],
    ["id" => "gold_10", "name" => "Gold Locket", "price" => "₹22,800", "image" => "images/11.jpg", "weight" => "18K (4.2g)", "category" => "Gold"],
    ["id" => "gold_11", "name" => "Gold Toe Ring Set", "price" => "₹15,999", "image" => "images/12.jpg", "weight" => "22K (3.6g)", "category" => "Gold"]
];
if (isset($gold_products)) { $static_products = array_merge($static_products, $gold_products); }

// Diamond products
$diamond_products = [
    ["id" => "diamond_0", "name" => "Solitaire Diamond Ring", "price" => "₹67,800", "image" => "images/5.jpg", "weight" => "0.50 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_1", "name" => "Diamond Pendant Set", "price" => "₹82,500", "image" => "images/2.jpg", "weight" => "0.75 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_2", "name" => "Diamond Stud Earrings", "price" => "₹45,999", "image" => "images/7.jpg", "weight" => "0.30 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_3", "name" => "Diamond Tennis Bracelet", "price" => "₹1,55,000", "image" => "images/8.jpg", "weight" => "2.00 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_4", "name" => "Diamond Nose Pin", "price" => "₹18,900", "image" => "images/3.jpg", "weight" => "0.15 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_5", "name" => "Diamond Drop Earrings", "price" => "₹62,400", "image" => "images/10.jpg", "weight" => "0.60 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_6", "name" => "Diamond Mangalsutra", "price" => "₹1,12,500", "image" => "images/4.jpg", "weight" => "1.00 Ct (22K)", "category" => "Diamond"],
    ["id" => "diamond_7", "name" => "Diamond Cocktail Ring", "price" => "₹1,38,200", "image" => "images/12.jpg", "weight" => "1.25 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_8", "name" => "Diamond Choker Necklace", "price" => "₹2,65,000", "image" => "images/6.jpg", "weight" => "3.00 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_9", "name" => "Diamond Bangle Set", "price" => "₹1,92,800", "image" => "images/9.jpg", "weight" => "2.50 Ct (22K)", "category" => "Diamond"],
    ["id" => "diamond_10", "name" => "Diamond Halo Ring", "price" => "₹95,600", "image" => "images/11.jpg", "weight" => "0.80 Ct (18K)", "category" => "Diamond"],
    ["id" => "diamond_11", "name" => "Diamond Bridal Set", "price" => "₹3,85,500", "image" => "images/1.jpg", "weight" => "4.00 Ct (18K)", "category" => "Diamond"]
];
if (isset($diamond_products)) { $static_products = array_merge($static_products, $diamond_products); }

// Earrings products
$earrings_products = [
    ["id" => "earrings_0", "name" => "Gold Jhumka Earrings", "price" => "₹38,999", "image" => "images/7.jpg", "weight" => "22K (8.2g)", "category" => "Earrings"],
    ["id" => "earrings_1", "name" => "Diamond Stud Earrings", "price" => "₹45,500", "image" => "images/1.jpg", "weight" => "0.50 Ct (18K)", "category" => "Earrings"],
    ["id" => "earrings_2", "name" => "Gold Hoop Earrings", "price" => "₹28,700", "image" => "images/6.jpg", "weight" => "18K (5.8g)", "category" => "Earrings"],
    ["id" => "earrings_3", "name" => "Pearl Drop Earrings", "price" => "₹32,400", "image" => "images/4.jpg", "weight" => "18K (4.2g)", "category" => "Earrings"],
    ["id" => "earrings_4", "name" => "Gold Chandbali Earrings", "price" => "₹68,900", "image" => "images/2.jpg", "weight" => "22K (12.5g)", "category" => "Earrings"],
    ["id" => "earrings_5", "name" => "Gold Stud Earrings", "price" => "₹18,999", "image" => "images/10.jpg", "weight" => "18K (3.2g)", "category" => "Earrings"],
    ["id" => "earrings_6", "name" => "Diamond Drop Earrings", "price" => "₹72,800", "image" => "images/3.jpg", "weight" => "0.75 Ct (18K)", "category" => "Earrings"],
    ["id" => "earrings_7", "name" => "Gold Bali Earrings", "price" => "₹35,200", "image" => "images/5.jpg", "weight" => "22K (6.8g)", "category" => "Earrings"],
    ["id" => "earrings_8", "name" => "Gemstone Earrings", "price" => "₹42,500", "image" => "images/8.jpg", "weight" => "18K (5.2g)", "category" => "Earrings"],
    ["id" => "earrings_9", "name" => "Gold Earring Set", "price" => "₹52,999", "image" => "images/9.jpg", "weight" => "22K (9.5g)", "category" => "Earrings"],
    ["id" => "earrings_10", "name" => "Oxidised Silver Earrings", "price" => "₹12,800", "image" => "images/11.jpg", "weight" => "Silver (6.8g)", "category" => "Earrings"],
    ["id" => "earrings_11", "name" => "Gold Ear Cuff", "price" => "₹22,400", "image" => "images/12.jpg", "weight" => "18K (2.8g)", "category" => "Earrings"]
];
if (isset($earrings_products)) { $static_products = array_merge($static_products, $earrings_products); }

// Rings products
$rings_products = [
    ["id" => "rings_0", "name" => "Solitaire Diamond Ring", "price" => "₹67,800", "image" => "images/2.jpg", "weight" => "0.50 Ct (18K)", "category" => "Rings"],
    ["id" => "rings_1", "name" => "Cocktail Sapphire Ring", "price" => "₹58,700", "image" => "images/7.jpg", "weight" => "18K (6.8g)", "category" => "Rings"],
    ["id" => "rings_2", "name" => "Wedding Band Set", "price" => "₹72,900", "image" => "images/3.jpg", "weight" => "22K (8.2g)", "category" => "Rings"],
    ["id" => "rings_3", "name" => "Eternity Diamond Ring", "price" => "₹89,500", "image" => "images/1.jpg", "weight" => "0.75 Ct (18K)", "category" => "Rings"],
    ["id" => "rings_4", "name" => "Stackable Gold Ring", "price" => "₹28,500", "image" => "images/6.jpg", "weight" => "18K (3.2g)", "category" => "Rings"],
    ["id" => "rings_5", "name" => "Gemstone Cocktail Ring", "price" => "₹45,200", "image" => "images/10.jpg", "weight" => "18K (5.5g)", "category" => "Rings"],
    ["id" => "rings_6", "name" => "Pearl Statement Ring", "price" => "₹38,400", "image" => "images/4.jpg", "weight" => "18K (4.8g)", "category" => "Rings"],
    ["id" => "rings_7", "name" => "Bridal Diamond Ring", "price" => "₹1,85,000", "image" => "images/5.jpg", "weight" => "1.50 Ct (18K)", "category" => "Rings"],
    ["id" => "rings_8", "name" => "Rose Gold Eternity", "price" => "₹48,700", "image" => "images/8.jpg", "weight" => "18K (3.8g)", "category" => "Rings"],
    ["id" => "rings_9", "name" => "Vintage Emerald Ring", "price" => "₹63,500", "image" => "images/9.jpg", "weight" => "18K (6.2g)", "category" => "Rings"],
    ["id" => "rings_10", "name" => "Promise Ring Set", "price" => "₹22,800", "image" => "images/11.jpg", "weight" => "18K (2.8g)", "category" => "Rings"],
    ["id" => "rings_11", "name" => "Platinum Solitaire", "price" => "₹1,25,600", "image" => "images/12.jpg", "weight" => "1.00 Ct (Platinum)", "category" => "Rings"]
];
if (isset($rings_products)) { $static_products = array_merge($static_products, $rings_products); }

// Dailywear products
$dailywear_products = [
    ["id" => "dailywear_0", "name" => "Daily Wear Gold Necklace", "price" => "₹45,999", "image" => "images/1.jpg", "weight" => "22K (8.2g)", "category" => "Daily Wear"],
    ["id" => "dailywear_1", "name" => "Light Gold Hoop Earrings", "price" => "₹28,500", "image" => "images/6.jpg", "weight" => "18K (4.5g)", "category" => "Daily Wear"],
    ["id" => "dailywear_2", "name" => "Simple Diamond Pendant", "price" => "₹32,400", "image" => "images/3.jpg", "weight" => "18K (3.2g)", "category" => "Daily Wear"],
    ["id" => "dailywear_3", "name" => "Gold Chain for Daily", "price" => "₹38,600", "image" => "images/10.jpg", "weight" => "22K (6.8g)", "category" => "Daily Wear"],
    ["id" => "dailywear_4", "name" => "Gold Stud Earrings", "price" => "₹18,999", "image" => "images/7.jpg", "weight" => "18K (2.8g)", "category" => "Daily Wear"],
    ["id" => "dailywear_5", "name" => "Gold Bangle Set", "price" => "₹1,12,500", "image" => "images/2.jpg", "weight" => "22K (18.5g)", "category" => "Daily Wear"],
    ["id" => "dailywear_6", "name" => "Gold Ring", "price" => "₹24,800", "image" => "images/5.jpg", "weight" => "22K (4.2g)", "category" => "Daily Wear"],
    ["id" => "dailywear_7", "name" => "Gold Nose Pin Set", "price" => "₹8,999", "image" => "images/8.jpg", "weight" => "18K (1.2g)", "category" => "Daily Wear"],
    ["id" => "dailywear_8", "name" => "Gold Anklet", "price" => "₹58,700", "image" => "images/4.jpg", "weight" => "22K (10.5g)", "category" => "Daily Wear"],
    ["id" => "dailywear_9", "name" => "Gold Bracelet", "price" => "₹32,500", "image" => "images/9.jpg", "weight" => "18K (5.8g)", "category" => "Daily Wear"],
    ["id" => "dailywear_10", "name" => "Gold Earring Set", "price" => "₹42,200", "image" => "images/11.jpg", "weight" => "22K (7.2g)", "category" => "Daily Wear"],
    ["id" => "dailywear_11", "name" => "Daily Wear Ring", "price" => "₹22,999", "image" => "images/12.jpg", "weight" => "18K (3.5g)", "category" => "Daily Wear"]
];
if (isset($dailywear_products)) { $static_products = array_merge($static_products, $dailywear_products); }

// Wedding products
$wedding_products = [
    ["id" => "wedding_0", "name" => "Bridal Gold Necklace Set", "price" => "₹2,89,999", "image" => "images/35.jpg", "weight" => "22K (42.5g)", "category" => "Wedding"],
    ["id" => "wedding_1", "name" => "Diamond Engagement Ring", "price" => "₹1,25,000", "image" => "images/36.jpg", "weight" => "1.00 Ct (18K)", "category" => "Wedding"],
    ["id" => "wedding_2", "name" => "Bridal Choker Set", "price" => "₹1,85,600", "image" => "images/37.jpg", "weight" => "22K (28.8g)", "category" => "Wedding"],
    ["id" => "wedding_3", "name" => "Wedding Earrings Set", "price" => "₹1,12,500", "image" => "images/38.jpg", "weight" => "22K (18.2g)", "category" => "Wedding"],
    ["id" => "wedding_4", "name" => "Engagement Ring Set", "price" => "₹94,800", "image" => "images/39.jpg", "weight" => "0.75 Ct (18K)", "category" => "Wedding"],
    ["id" => "wedding_5", "name" => "Wedding Bangles Set", "price" => "₹3,12,999", "image" => "images/40.jpg", "weight" => "22K (52.5g)", "category" => "Wedding"],
    ["id" => "wedding_6", "name" => "Bridal Tiara", "price" => "₹78,500", "image" => "images/41.jpg", "weight" => "22K (12.8g)", "category" => "Wedding"],
    ["id" => "wedding_7", "name" => "Wedding Mangalsutra", "price" => "₹1,42,000", "image" => "images/42.jpg", "weight" => "22K (18.5g)", "category" => "Wedding"],
    ["id" => "wedding_8", "name" => "Bridal Pendant Set", "price" => "₹96,700", "image" => "images/43.jpg", "weight" => "22K (16.2g)", "category" => "Wedding"],
    ["id" => "wedding_9", "name" => "Wedding Nose Pin", "price" => "₹42,300", "image" => "images/44.jpg", "weight" => "18K (3.5g)", "category" => "Wedding"],
    ["id" => "wedding_10", "name" => "Bridal Anklet", "price" => "₹1,35,600", "image" => "images/45.jpg", "weight" => "22K (22.8g)", "category" => "Wedding"],
    ["id" => "wedding_11", "name" => "Wedding Bracelet", "price" => "₹88,999", "image" => "images/46.jpg", "weight" => "22K (15.2g)", "category" => "Wedding"]
];
if (isset($wedding_products)) { $static_products = array_merge($static_products, $wedding_products); }

// ==================================================================
// 2) LOAD LIVE PRODUCTS FROM DATABASE (admin-managed)
// ==================================================================
$all_products = [];
$db_loaded = false;

require_once __DIR__ . '/db_config.php';
$conn = getDBConnection();

if ($conn) {
    // --- First-time seed: insert static products into DB (once only) ---
    $seed_check = $conn->query("SELECT setting_value FROM settings WHERE setting_key = 'products_seeded'");
    $seeded = false;
    if ($seed_check && $seed_check->num_rows > 0) {
        $seeded = ($seed_check->fetch_assoc()['setting_value'] === '1');
    }

    if (!$seeded) {
        $stmt = $conn->prepare("INSERT INTO products (product_code, name, price, image, weight, category, description) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), image = VALUES(image), weight = VALUES(weight), category = VALUES(category)");
        if ($stmt) {
            foreach ($static_products as $sp) {
                $code = $sp['id'];
                $name = $sp['name'];
                $price = $sp['price'];
                $image = $sp['image'];
                $weight = isset($sp['weight']) ? $sp['weight'] : '';
                $category = $sp['category'];
                $desc = '';
                $stmt->bind_param("sssssss", $code, $name, $price, $image, $weight, $category, $desc);
                $stmt->execute();
            }
            $stmt->close();
        }
        // Mark as seeded so admin deletions are respected
        $conn->query("INSERT INTO settings (setting_key, setting_value) VALUES ('products_seeded', '1') ON DUPLICATE KEY UPDATE setting_value = '1'");
    }

    // --- Load all products from DB ---
    $result = $conn->query("SELECT product_code, name, price, image, weight, category, description FROM products ORDER BY category ASC, id ASC");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $all_products[] = [
                'id' => $row['product_code'],
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image'],
                'weight' => $row['weight'],
                'category' => $row['category'],
                'description' => $row['description']
            ];
        }
        $db_loaded = true;
    }
}

// Fallback to static arrays if DB unavailable or empty
if (!$db_loaded || empty($all_products)) {
    $all_products = $static_products;
}

/**
 * Get product by ID
 * @param string $id The product ID to search for
 * @return array|null The product data or null if not found
 */
function getProductById($id) {
    global $all_products;
    foreach ($all_products as $product) {
        if ($product['id'] === $id) {
            return $product;
        }
    }
    return null;
}

/**
 * Get products by category (case-insensitive)
 * @param string $category
 * @return array
 */
function getProductsByCategory($category) {
    global $all_products;
    $cat_lower = strtolower($category);
    $found = [];
    foreach ($all_products as $product) {
        if (strtolower($product['category']) === $cat_lower) {
            $found[] = $product;
        }
    }
    return $found;
}
?>

