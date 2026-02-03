<?php


require_once __DIR__ . '/../config/db.php';


function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}


function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}


function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}


function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id, name, email, phone, role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}


function redirect($url, $message = '', $type = 'success') {
    if ($message) {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
    header("Location: $url");
    exit;
}


function flashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'] ?? 'success';
        unset($_SESSION['flash_message'], $_SESSION['flash_type']);
        return "<div class='flash-message flash-$type'>$message</div>";
    }
    return '';
}


function getCartCount() {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}


function getCartItems() {
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) return [];
    
    $db = getDB();
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    
    $stmt = $db->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();
    
    $items = [];
    foreach ($products as $product) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $items[] = $product;
    }
    
    return $items;
}


function getCartTotal() {
    $items = getCartItems();
    return array_sum(array_column($items, 'subtotal'));
}


function getCategories() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll();
}


function getFeaturedProducts($limit = 8) {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          WHERE p.featured = 1 
                          ORDER BY p.created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}


function getProducts($category = null, $search = null, $limit = 12, $offset = 0) {
    $db = getDB();
    $sql = "SELECT p.*, c.name as category_name FROM products p 
            JOIN categories c ON p.category_id = c.id WHERE 1=1";
    $params = [];
    
    if ($category) {
        $sql .= " AND c.slug = ?";
        $params[] = $category;
    }
    
    if ($search) {
        $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    
    $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


function getProduct($id) {
    $db = getDB();
    $stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function formatPrice($price) {
    return '$' . number_format($price, 2);
}


function getProductImage($image) {

    if (!empty($image)) {
        return $image;
    }

    return 'https://placehold.co/400x500/1a1a2e/8b5cf6?text=No+Image';
}

