<?php
// Database Configuration for Jewellery Cart
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'jewellery_cart');

function getDBConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            error_log("DB Connection failed: " . $conn->connect_error);
            return null;
        }
        $conn->set_charset("utf8mb4");
        
        // Create tables if they don't exist
        createTables($conn);
    }
    return $conn;
}

function createTables($conn) {
    // Create cart table
    $sql_cart = "CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL,
        product_id VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(50) NOT NULL,
        image VARCHAR(500) NOT NULL,
        weight VARCHAR(100) DEFAULT '',
        quantity INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_cart_item (session_id, product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    // Create orders table
    $sql_orders = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_group_id VARCHAR(100) NOT NULL,
        session_id VARCHAR(255) NOT NULL,
        product_id VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(50) NOT NULL,
        image VARCHAR(500) NOT NULL,
        weight VARCHAR(100) DEFAULT '',
        quantity INT NOT NULL DEFAULT 1,
        item_total DECIMAL(12,2) NOT NULL DEFAULT 0.00,
        order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return_requested', 'returned') NOT NULL DEFAULT 'pending',
        cancel_reason VARCHAR(255) DEFAULT '',
        return_reason VARCHAR(255) DEFAULT '',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_order_group (order_group_id),
        INDEX idx_session (session_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    // Create order_customers table for storing customer details
    $sql_customers = "CREATE TABLE IF NOT EXISTS order_customers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_group_id VARCHAR(100) NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        email VARCHAR(255) DEFAULT '',
        address TEXT NOT NULL,
        city VARCHAR(100) NOT NULL,
        pincode VARCHAR(10) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_order_customer (order_group_id),
        INDEX idx_order_group (order_group_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Create users table for login/registration
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        phone VARCHAR(20) DEFAULT '',
        password VARCHAR(255) NOT NULL,
        role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Create wishlist table
    $sql_wishlist = "CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL,
        product_id VARCHAR(100) NOT NULL,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(50) NOT NULL,
        image VARCHAR(500) NOT NULL,
        weight VARCHAR(100) DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_wishlist_item (session_id, product_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    // Create custom_requests table for search "not found" requests
    $sql_custom_requests = "CREATE TABLE IF NOT EXISTS custom_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        session_id VARCHAR(255) NOT NULL,
        product_name VARCHAR(255) DEFAULT '',
        description TEXT NOT NULL,
        budget VARCHAR(100) DEFAULT '',
        name VARCHAR(255) DEFAULT '',
        phone VARCHAR(20) DEFAULT '',
        email VARCHAR(255) DEFAULT '',
        status VARCHAR(50) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_session (session_id),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    // Create products table for admin CRUD
    $sql_products = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_code VARCHAR(100) NOT NULL UNIQUE,
        name VARCHAR(255) NOT NULL,
        price VARCHAR(50) NOT NULL,
        image VARCHAR(500) NOT NULL,
        weight VARCHAR(100) DEFAULT '',
        category VARCHAR(100) NOT NULL,
        description TEXT DEFAULT '',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_category (category)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    // Create settings table for storing simple key/value flags (e.g. seed marker)
    $sql_settings = "CREATE TABLE IF NOT EXISTS settings (
        setting_key VARCHAR(100) PRIMARY KEY,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    
    $conn->query($sql_cart);
    $conn->query($sql_orders);
    $conn->query($sql_customers);
    $conn->query($sql_users);
    $conn->query($sql_wishlist);
    $conn->query($sql_custom_requests);
    $conn->query($sql_products);
    $conn->query($sql_settings);

    // ---- Schema migration for existing installations ----
    // The `users` table may already exist from an older schema that did NOT
    // include the `role` column. `CREATE TABLE IF NOT EXISTS` does nothing for
    // existing tables, so we must add the missing column here to avoid
    // "prepare() on false" errors when the admin seed runs below.
    $role_col = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($role_col && $role_col->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN role ENUM('customer','admin') NOT NULL DEFAULT 'customer' AFTER password");
    }

    // ---- Schema migration: add `order_status` to `orders` if missing ----
    // Older installations created the `orders` table without the `order_status`
    // column. `CREATE TABLE IF NOT EXISTS` does nothing for existing tables,
    // so we must add the column to allow order status tracking (admin/orders.php,
    // order.php).
    $status_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'order_status'");
    if ($status_col && $status_col->num_rows === 0) {
        $conn->query("ALTER TABLE orders ADD COLUMN order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return_requested', 'returned') NOT NULL DEFAULT 'pending' AFTER item_total");
    }

    // ---- Schema migration: add `cancel_reason` / `return_reason` columns ----
    $cancel_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'cancel_reason'");
    if ($cancel_col && $cancel_col->num_rows === 0) {
        $conn->query("ALTER TABLE orders ADD COLUMN cancel_reason VARCHAR(255) DEFAULT '' AFTER order_status");
    }
    $return_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'return_reason'");
    if ($return_col && $return_col->num_rows === 0) {
        $conn->query("ALTER TABLE orders ADD COLUMN return_reason VARCHAR(255) DEFAULT '' AFTER cancel_reason");
    }

    // ---- Schema migration: expand order_status enum with return statuses ----
    $enum_col = $conn->query("SHOW COLUMNS FROM orders LIKE 'order_status'");
    if ($enum_col && $enum_col->num_rows > 0) {
        $col_info = $enum_col->fetch_assoc();
        $type = strtolower($col_info['Type']);
        if (strpos($type, 'return_requested') === false) {
            $conn->query("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return_requested', 'returned') NOT NULL DEFAULT 'pending'");
        }
    }

    // ---- Create order_returns table for tracking return requests ----
    $sql_order_returns = "CREATE TABLE IF NOT EXISTS order_returns (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_group_id VARCHAR(100) NOT NULL,
        session_id VARCHAR(255) NOT NULL,
        reason VARCHAR(255) DEFAULT '',
        details TEXT DEFAULT '',
        status ENUM('requested', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'requested',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_order_group (order_group_id),
        UNIQUE KEY unique_return (order_group_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $conn->query($sql_order_returns);

    // Seed default admin user if not exists
    $admin_email = 'admin@pasvi.com';
    $check_admin = $conn->prepare("SELECT id FROM users WHERE email = ?");
    if ($check_admin) {
        $check_admin->bind_param("s", $admin_email);
        $check_admin->execute();
        $check_admin->store_result();

        if ($check_admin->num_rows === 0) {
            $admin_password = password_hash('Admin@123', PASSWORD_DEFAULT);
            $admin_name = 'PASVI Admin';
            $stmt_admin = $conn->prepare("INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, '', ?, 'admin')");
            if ($stmt_admin) {
                $stmt_admin->bind_param("sss", $admin_name, $admin_email, $admin_password);
                $stmt_admin->execute();
                $stmt_admin->close();
            } else {
                error_log("DB: Failed to prepare admin seed insert: " . $conn->error);
            }
        }
        $check_admin->close();
    } else {
        error_log("DB: Failed to prepare admin check: " . $conn->error);
    }
}
?>

