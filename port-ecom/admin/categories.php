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
        $action = $_POST['action'] ?? '';
        
        if ($action === 'add') {
            $name = sanitize($_POST['name'] ?? '');
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $name));
            
            if (!empty($name)) {
                try {
                    $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
                    $stmt->execute([$name, $slug]);
                    $message = 'Category added successfully';
                    $messageType = 'success';
                } catch (PDOException $e) {
                    $message = 'Category already exists';
                    $messageType = 'error';
                }
            }
        } elseif ($action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            $name = sanitize($_POST['name'] ?? '');
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $name));
            
            if ($id && !empty($name)) {
                $stmt = $db->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
                $stmt->execute([$name, $slug, $id]);
                $message = 'Category updated successfully';
                $messageType = 'success';
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {

                $stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
                $stmt->execute([$id]);
                if ($stmt->fetchColumn() > 0) {
                    $message = 'Cannot delete category with products';
                    $messageType = 'error';
                } else {
                    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
                    $stmt->execute([$id]);
                    $message = 'Category deleted';
                    $messageType = 'success';
                }
            }
        }
    }
}

$categories = $db->query("SELECT c.*, COUNT(p.id) as product_count 
                          FROM categories c 
                          LEFT JOIN products p ON c.id = p.category_id 
                          GROUP BY c.id 
                          ORDER BY c.name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | Admin</title>
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
                <a href="categories.php" class="nav-item active">
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
                <h2>Categories</h2>
            </header>
            
            <div class="admin-content">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3>Add Category</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="inline-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            <div class="form-group">
                                <input type="text" name="name" placeholder="Category name" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>
                </div>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3>All Categories</h3>
                    </div>
                    <div class="card-body">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Products</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['id']; ?></td>
                                        <td>
                                            <form method="POST" class="inline-edit-form">
                                                <input type="hidden" name="action" value="edit">
                                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                <input type="text" name="name" value="<?php echo sanitize($cat['name']); ?>" class="inline-input">
                                                <button type="submit" class="btn btn-sm">Save</button>
                                            </form>
                                        </td>
                                        <td><?php echo sanitize($cat['slug']); ?></td>
                                        <td><?php echo $cat['product_count']; ?></td>
                                        <td>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

