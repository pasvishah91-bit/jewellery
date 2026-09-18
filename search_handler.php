<?php
session_start();
require_once 'db_config.php';
require_once 'products_data.php';

// Handle custom request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'custom_request') {
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $budget = isset($_POST['budget']) ? trim($_POST['budget']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $session_id = session_id();
    
    if (empty($product_name) || empty($description)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Product name and description are required.']);
        exit;
    }
    
    $conn = getDBConnection();
    if ($conn) {
        $stmt = $conn->prepare("INSERT INTO custom_requests (session_id, product_name, description, budget, name, phone, email, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssss", $session_id, $product_name, $description, $budget, $name, $phone, $email);
        
        if ($stmt->execute()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Your custom request has been submitted. Our team will contact you within 24-48 hours.']);
            $stmt->close();
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Database error. Please try again later.']);
            $stmt->close();
            exit;
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Could not connect to database. Please try again later.']);
        exit;
    }
}

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($query)) {
    $query_lower = strtolower($query);
    foreach ($all_products as $product) {
        $name_lower = strtolower($product['name']);
        $category_lower = strtolower($product['category']);
        
        // Check if query matches product name or category
        if (strpos($name_lower, $query_lower) !== false || strpos($category_lower, $query_lower) !== false) {
            $results[] = $product;
        }
        
        // Limit results
        if (count($results) >= 15) break;
    }
}

header('Content-Type: application/json');
echo json_encode(['results' => $results, 'count' => count($results)]);
?>

