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
        
        if ($action === 'add' || $action === 'edit') {
            $id = (int)($_POST['id'] ?? 0);
            $name = sanitize($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $description = sanitize($_POST['description'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            $featured = isset($_POST['featured']) ? 1 : 0;
            $image = sanitize($_POST['image'] ?? 'placeholder.jpg');
            
            if (!empty($name) && $category_id && $price > 0) {
                if ($action === 'add') {
                    $stmt = $db->prepare("INSERT INTO products (category_id, name, description, price, stock, featured, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$category_id, $name, $description, $price, $stock, $featured, $image]);
                    $message = 'Product added successfully';
                } else {
                    $stmt = $db->prepare("UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, stock = ?, featured = ?, image = ? WHERE id = ?");
                    $stmt->execute([$category_id, $name, $description, $price, $stock, $featured, $image, $id]);
                    $message = 'Product updated successfully';
                }
                $messageType = 'success';
            } else {
                $message = 'Please fill all required fields';
                $messageType = 'error';
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id) {
                $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Product deleted';
                $messageType = 'success';
            }
        }
    }
}

$categories = getCategories();
$products = $db->query("SELECT p.*, c.name as category_name FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        ORDER BY p.created_at DESC")->fetchAll();

$editProduct = null;
if (isset($_GET['edit'])) {
    $editProduct = getProduct((int)$_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Admin</title>
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
                <a href="products.php" class="nav-item active">
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
                <h2>Products</h2>
            </header>
            
            <div class="admin-content">
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                <?php endif; ?>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3><?php echo $editProduct ? 'Edit Product' : 'Add Product'; ?></h3>
                        <?php if ($editProduct): ?>
                            <a href="products.php" class="btn btn-sm">Cancel</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="product-form">
                            <input type="hidden" name="action" value="<?php echo $editProduct ? 'edit' : 'add'; ?>">
                            <?php if ($editProduct): ?>
                                <input type="hidden" name="id" value="<?php echo $editProduct['id']; ?>">
                            <?php endif; ?>
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Product Name *</label>
                                    <input type="text" name="name" value="<?php echo $editProduct ? sanitize($editProduct['name']) : ''; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Category *</label>
                                    <select name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?php echo $cat['id']; ?>" <?php echo ($editProduct && $editProduct['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                                <?php echo sanitize($cat['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" rows="3"><?php echo $editProduct ? sanitize($editProduct['description']) : ''; ?></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Price *</label>
                                    <input type="number" name="price" step="0.01" min="0" value="<?php echo $editProduct ? $editProduct['price'] : ''; ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" name="stock" min="0" value="<?php echo $editProduct ? $editProduct['stock'] : '0'; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label>Image URL</label>
                                    <input type="url" name="image" value="<?php echo $editProduct ? sanitize($editProduct['image']) : ''; ?>" placeholder="https://example.com/image.jpg">
                                </div>
                            </div>
                            
                            <div class="form-group checkbox-group">
                                <label>
                                    <input type="checkbox" name="featured" <?php echo ($editProduct && $editProduct['featured']) ? 'checked' : ''; ?>>
                                    Featured Product
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><?php echo $editProduct ? 'Update Product' : 'Add Product'; ?></button>
                        </form>
                    </div>
                </div>
                
                
                <div class="admin-card">
                    <div class="card-header">
                        <h3>All Products (<?php echo count($products); ?>)</h3>
                    </div>
                    <div class="card-body">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $prod): ?>
                                    <tr>
                                        <td><?php echo $prod['id']; ?></td>
                                        <td>
                                            <img src="<?php echo getProductImage($prod['image']); ?>" alt="" class="table-product-image">
                                        </td>
                                        <td><?php echo sanitize($prod['name']); ?></td>
                                        <td><?php echo sanitize($prod['category_name']); ?></td>
                                        <td><?php echo formatPrice($prod['price']); ?></td>
                                        <td><?php echo $prod['stock']; ?></td>
                                        <td><?php echo $prod['featured'] ? '★' : '-'; ?></td>
                                        <td>
                                            <a href="products.php?edit=<?php echo $prod['id']; ?>" class="btn btn-sm">Edit</a>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo $prod['id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this product?')">Delete</button>
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

