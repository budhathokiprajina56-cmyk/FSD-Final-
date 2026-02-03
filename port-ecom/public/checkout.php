<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect('index.php', 'Please sign in to checkout', 'info');
}

$cartItems = getCartItems();
$cartTotal = getCartTotal();

if (empty($cartItems)) {
    redirect('cart.php', 'Your cart is empty', 'info');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        redirect('checkout.php', 'Invalid request', 'error');
    }
    
    $name = sanitize($_POST['name'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $zip = sanitize($_POST['zip'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');

    $errors = [];
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($address)) $errors[] = 'Address is required';
    if (empty($city)) $errors[] = 'City is required';
    if (empty($zip)) $errors[] = 'ZIP code is required';
    if (empty($phone) || !preg_match('/^\d{10}$/', $phone)) $errors[] = 'Valid 10-digit phone is required';
    
    if (empty($errors)) {
        $db = getDB();
        
        try {
            $db->beginTransaction();

            $shipping = $cartTotal >= 150 ? 0 : 10;
            $finalTotal = $cartTotal + $shipping;

            $stmt = $db->prepare("INSERT INTO orders (user_id, total, shipping_name, shipping_address, shipping_city, shipping_zip, shipping_phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $finalTotal, $name, $address, $city, $zip, $phone]);
            $orderId = $db->lastInsertId();

            $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);

                $updateStock = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $updateStock->execute([$item['quantity'], $item['id']]);
            }
            
            $db->commit();

            unset($_SESSION['cart']);
            
            redirect('orders.php', 'Order placed successfully! Order #' . $orderId, 'success');
            
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = 'An error occurred. Please try again.';
        }
    }
}

$pageTitle = 'Checkout';
$user = getCurrentUser();
require_once __DIR__ . '/../includes/header.php';
?>


<section class="checkout-header">
    <h1 class="checkout-title">Checkout</h1>
</section>


<section class="checkout-content">
    <form method="POST" class="checkout-form">
        <div class="checkout-main">
            <div class="checkout-section">
                <h2>Shipping Information</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="form-errors">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo $user['name'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number (10 digits)</label>
                    <input type="tel" id="phone" name="phone" pattern="[0-9]{10}" value="<?php echo $user['phone'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="address">Street Address</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="zip">ZIP Code</label>
                        <input type="text" id="zip" name="zip" required>
                    </div>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            </div>
        </div>
        
        <div class="checkout-summary">
            <div class="checkout-summary-content">
                <h3>Order Summary</h3>
                
                <div class="checkout-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="checkout-item">
                            <img src="<?php echo getProductImage($item['image']); ?>" alt="<?php echo sanitize($item['name']); ?>">
                            <div class="checkout-item-info">
                                <h4><?php echo sanitize($item['name']); ?></h4>
                                <span class="qty">Qty: <?php echo $item['quantity']; ?></span>
                            </div>
                            <span class="checkout-item-price"><?php echo formatPrice($item['subtotal']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="summary-divider"></div>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span><?php echo formatPrice($cartTotal); ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span><?php echo $cartTotal >= 150 ? 'Free' : formatPrice(10); ?></span>
                </div>
                
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span><?php echo formatPrice($cartTotal >= 150 ? $cartTotal : $cartTotal + 10); ?></span>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-lg">Place Order</button>
                
                <p class="checkout-note">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    Your payment information is secure
                </p>
            </div>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

