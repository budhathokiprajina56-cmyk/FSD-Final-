<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('index.php');
}

$db = getDB();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $message = 'Invalid request';
        $messageType = 'error';
    } else {
        $id = (int)($_POST['id'] ?? 0);
        $status = sanitize($_POST['status'] ?? '');
        
        if ($id && in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            $stmt = $db->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $id]);
            $message = 'Order status updated';
            $messageType = 'success';
        }
    }
}

$orders = $db->query("SELECT o.*, u.name as customer_name, u.email as customer_email 
                      FROM orders o 
                      JOIN users u ON o.user_id = u.id 
                      ORDER BY o.created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
</head>
<body class="admin-body">
    <div class="admin-wrapper">
        
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <span>Admin Panel</span>
            </div>
            
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
                <a href="categories.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                    Categories
                </a>
                <a href="products.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    Products
                </a>
                <a href="orders.php" class="nav-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Orders
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="<?php echo SITE_URL; ?>index.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                    View Store
                </a>
                <a href="<?php echo SITE_URL; ?>ajax/logout.php" class="nav-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    Logout
                </a>
            </div>
        </aside>
        
        
        <main class="admin-main">
            <header class="admin-header">
                <h2>Orders</h2>
            </header>
            
            <div class="admin-content">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3>All Orders (<?php echo count($orders); ?>)</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($orders)): ?>
                            <p class="no-data">No orders yet</p>
                        <?php else: ?>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <?php
                                        $itemsStmt = $db->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ?");
                                        $itemsStmt->execute([$order['id']]);
                                        $itemCount = $itemsStmt->fetchColumn();
                                        ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td>
                                                <strong><?php echo sanitize($order['customer_name']); ?></strong><br>
                                                <small><?php echo sanitize($order['customer_email']); ?></small>
                                            </td>
                                            <td><?php echo $itemCount; ?> items</td>
                                            <td><?php echo formatPrice($order['total']); ?></td>
                                            <td>
                                                <form method="POST" class="status-form">
                                                    <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                    <select name="status" onchange="this.form.submit()" class="status-select status-<?php echo $order['status']; ?>">
                                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td><?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?></td>
                                            <td>
                                                <button class="btn btn-sm" onclick="toggleOrderDetails(<?php echo $order['id']; ?>)">View</button>
                                            </td>
                                        </tr>
                                        <tr class="order-details-row" id="order-<?php echo $order['id']; ?>" style="display:none;">
                                            <td colspan="7">
                                                <div class="order-details">
                                                    <div class="order-details-section">
                                                        <h4>Shipping Address</h4>
                                                        <p>
                                                            <?php echo sanitize($order['shipping_name']); ?><br>
                                                            <?php echo sanitize($order['shipping_address']); ?><br>
                                                            <?php echo sanitize($order['shipping_city']); ?>, <?php echo sanitize($order['shipping_zip']); ?><br>
                                                            Phone: <?php echo sanitize($order['shipping_phone']); ?>
                                                        </p>
                                                    </div>
                                                    <div class="order-details-section">
                                                        <h4>Order Items</h4>
                                                        <?php
                                                        $detailsStmt = $db->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                                        $detailsStmt->execute([$order['id']]);
                                                        $orderItems = $detailsStmt->fetchAll();
                                                        ?>
                                                        <ul class="order-items-list">
                                                            <?php foreach ($orderItems as $item): ?>
                                                                <li><?php echo sanitize($item['name']); ?> × <?php echo $item['quantity']; ?> = <?php echo formatPrice($item['price'] * $item['quantity']); ?></li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        function toggleOrderDetails(orderId) {
            const row = document.getElementById('order-' + orderId);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
</body>
</html>

