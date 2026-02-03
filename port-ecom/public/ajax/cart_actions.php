<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../includes/functions.php';

$response = ['success' => false, 'message' => '', 'cartCount' => 0];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

if (!isLoggedIn()) {
    $response['message'] = 'Please sign in to manage your cart';
    $response['requireLogin'] = true;
    echo json_encode($response);
    exit;
}

$action = $_POST['action'] ?? '';
$productId = (int)($_POST['product_id'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 1);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':

        $product = getProduct($productId);
        if (!$product) {
            $response['message'] = 'Product not found';
            break;
        }

        $currentQty = $_SESSION['cart'][$productId] ?? 0;
        if ($currentQty + $quantity > $product['stock']) {
            $response['message'] = 'Not enough stock available';
            break;
        }

        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        
        $response['success'] = true;
        $response['message'] = 'Added to cart!';
        break;
        
    case 'update':
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$productId]);
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        $response['success'] = true;
        $response['message'] = 'Cart updated';
        break;
        
    case 'remove':
        unset($_SESSION['cart'][$productId]);
        $response['success'] = true;
        $response['message'] = 'Item removed';
        break;
        
    case 'clear':
        $_SESSION['cart'] = [];
        $response['success'] = true;
        $response['message'] = 'Cart cleared';
        break;
        
    default:
        $response['message'] = 'Invalid action';
}

$response['cartCount'] = getCartCount();
$response['cartTotal'] = formatPrice(getCartTotal());

echo json_encode($response);

