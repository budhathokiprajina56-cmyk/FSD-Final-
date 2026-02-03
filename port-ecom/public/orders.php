<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect('index.php', 'Please sign in to view your orders', 'info');
}

$pageTitle = 'My Orders';
require_once __DIR__ . '/../includes/header.php';

$db = getDB();
$stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>


<section class="orders-header">
    <h1 class="orders-title">My Orders</h1>
    <p class="orders-subtitle">Track and manage your orders</p>
</section>


<section class="orders-content">
    <?php if (empty($orders)): ?>
        <div class="orders-empty">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <h2>No orders yet</h2>
            <p>When you place an order, it will appear here.</p>
            <a href="shop.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <?php

                $itemsStmt = $db->prepare("SELECT oi.*, p.name, p.image FROM order_items oi 
                                           JOIN products p ON oi.product_id = p.id 
                                           WHERE oi.order_id = ?");
                $itemsStmt->execute([$order['id']]);
                $orderItems = $itemsStmt->fetchAll();
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-info">
                            <h3>Order #<?php echo $order['id']; ?></h3>
                            <span class="order-date"><?php echo date('F j, Y', strtotime($order['created_at'])); ?></span>
                        </div>
                        <div class="order-status status-<?php echo $order['status']; ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <?php foreach ($orderItems as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo getProductImage($item['image']); ?>" alt="<?php echo sanitize($item['name']); ?>">
                                <div class="order-item-details">
                                    <h4><?php echo sanitize($item['name']); ?></h4>
                                    <span>Qty: <?php echo $item['quantity']; ?> × <?php echo formatPrice($item['price']); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-shipping">
                            <strong>Shipping to:</strong>
                            <span><?php echo sanitize($order['shipping_name']); ?>, <?php echo sanitize($order['shipping_city']); ?></span>
                        </div>
                        <div class="order-total">
                            <strong>Total:</strong>
                            <span><?php echo formatPrice($order['total']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

