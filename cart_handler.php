<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');
require_once 'db_config.php';

// Initialize cart and wishlist if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Get session ID for database tracking
$session_id = session_id();

$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'add_to_cart':
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? trim($_POST['price']) : '';
        $image = isset($_POST['image']) ? trim($_POST['image']) : '';
        $weight = isset($_POST['weight']) ? trim($_POST['weight']) : '';
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($quantity < 1) $quantity = 1;
        if ($quantity > 99) $quantity = 99;

        if ($id && $name && $price) {
            // --- Session operation ---
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $id,
                    'name' => $name,
                    'price' => $price,
                    'image' => $image,
                    'weight' => $weight,
                    'quantity' => $quantity
                ];
            }

            // --- MySQL INSERT/UPDATE ---
            $conn = getDBConnection();
            if ($conn) {
                $qty = $_SESSION['cart'][$id]['quantity'];
                $stmt = $conn->prepare("INSERT INTO cart (session_id, product_id, name, price, image, weight, quantity) 
                    VALUES (?, ?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE quantity = VALUES(quantity), price = VALUES(price), name = VALUES(name), image = VALUES(image), weight = VALUES(weight)");
                $stmt->bind_param("ssssssi", $session_id, $id, $name, $price, $image, $weight, $qty);
                $stmt->execute();
                $stmt->close();
            }

            echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart']), 'message' => 'Added to cart!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product data']);
        }
        break;

    case 'remove_from_cart':
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        if ($id && isset($_SESSION['cart'][$id])) {
            // --- Session operation ---
            unset($_SESSION['cart'][$id]);

            // --- MySQL DELETE ---
            $conn = getDBConnection();
            if ($conn) {
                $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
                $stmt->bind_param("ss", $session_id, $id);
                $stmt->execute();
                $stmt->close();
            }

            echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        }
        break;

    case 'update_quantity':
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
        if ($id && isset($_SESSION['cart'][$id])) {
            // --- Session operation ---
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$id]);
                $conn = getDBConnection();
                if ($conn) {
                    $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ? AND product_id = ?");
                    $stmt->bind_param("ss", $session_id, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            } else {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
                $conn = getDBConnection();
                if ($conn) {
                    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE session_id = ? AND product_id = ?");
                    $stmt->bind_param("iss", $quantity, $session_id, $id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        }
        break;

    case 'get_cart_count':
        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        break;

    case 'clear_cart':
        $_SESSION['cart'] = [];
        $conn = getDBConnection();
        if ($conn) {
            $stmt = $conn->prepare("DELETE FROM cart WHERE session_id = ?");
            $stmt->bind_param("s", $session_id);
            $stmt->execute();
            $stmt->close();
        }
        echo json_encode(['success' => true, 'cart_count' => 0]);
        break;

    case 'add_to_wishlist':
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? trim($_POST['price']) : '';
        $image = isset($_POST['image']) ? trim($_POST['image']) : '';
        $weight = isset($_POST['weight']) ? trim($_POST['weight']) : '';

        if ($id && $name && $price) {
            // --- Session operation ---
            $_SESSION['wishlist'][$id] = [
                'id' => $id,
                'name' => $name,
                'price' => $price,
                'image' => $image,
                'weight' => $weight
            ];

            // --- MySQL INSERT/UPDATE ---
            $conn = getDBConnection();
            if ($conn) {
                $stmt = $conn->prepare("INSERT INTO wishlist (session_id, product_id, name, price, image, weight) 
                    VALUES (?, ?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), image = VALUES(image), weight = VALUES(weight)");
                $stmt->bind_param("ssssss", $session_id, $id, $name, $price, $image, $weight);
                $stmt->execute();
                $stmt->close();
            }

            echo json_encode(['success' => true, 'wishlist_count' => count($_SESSION['wishlist']), 'message' => 'Added to wishlist!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid product data']);
        }
        break;

    case 'remove_from_wishlist':
        $id = isset($_POST['id']) ? trim($_POST['id']) : '';
        if ($id && isset($_SESSION['wishlist'][$id])) {
            unset($_SESSION['wishlist'][$id]);
            $conn = getDBConnection();
            if ($conn) {
                $stmt = $conn->prepare("DELETE FROM wishlist WHERE session_id = ? AND product_id = ?");
                $stmt->bind_param("ss", $session_id, $id);
                $stmt->execute();
                $stmt->close();
            }
            echo json_encode(['success' => true, 'wishlist_count' => count($_SESSION['wishlist'])]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found in wishlist']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>

