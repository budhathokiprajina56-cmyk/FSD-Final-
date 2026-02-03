<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isAdmin()) {
    redirect('index.php');
}

$db = getDB();

$totalProducts = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $db->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalRevenue = $db->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

$recentOrders = $db->query("SELECT o.*, u.name as customer_name FROM orders o 
                            JOIN users u ON o.user_id = u.id 
                            ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
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
                <a href="dashboard.php" class="nav-item active">
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
                <a href="orders.php" class="nav-item">
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
                <h2>Dashboard</h2>
                <div class="admin-user">
                    <span>Welcome, <?php echo sanitize($_SESSION['user_name']); ?></span>
                </div>
            </header>
            
            <div class="admin-content">
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon stat-blue">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value"><?php echo $totalProducts; ?></span>
                            <span class="stat-label">Products</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon stat-green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value"><?php echo $totalOrders; ?></span>
                            <span class="stat-label">Orders</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon stat-purple">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value"><?php echo $totalUsers; ?></span>
                            <span class="stat-label">Customers</span>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon stat-gold">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="stat-info">
                            <span class="stat-value"><?php echo formatPrice($totalRevenue); ?></span>
                            <span class="stat-label">Revenue</span>
                        </div>
                    </div>
                </div>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Recent Orders</h3>
                        <a href="orders.php" class="btn btn-sm">View All</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentOrders)): ?>
                            <p class="no-data">No orders yet</p>
                        <?php else: ?>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo sanitize($order['customer_name']); ?></td>
                                            <td><?php echo formatPrice($order['total']); ?></td>
                                            <td><span class="status status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
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
</body>
</html>

