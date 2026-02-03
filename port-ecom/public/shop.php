<?php

$pageTitle = 'Shop';
require_once __DIR__ . '/../includes/header.php';

$category = isset($_GET['category']) ? sanitize($_GET['category']) : null;
$search = isset($_GET['search']) ? sanitize($_GET['search']) : null;
$products = getProducts($category, $search, 24);
$categories = getCategories();

$currentCategoryName = 'All Products';
if ($category) {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $category) {
            $currentCategoryName = $cat['name'];
            break;
        }
    }
}
?>


<section class="shop-header">
    <div class="shop-header-content">
        <h1 class="shop-title"><?php echo $search ? 'Search Results' : $currentCategoryName; ?></h1>
        <?php if ($search): ?>
            <p class="shop-subtitle">Showing results for "<?php echo $search; ?>"</p>
        <?php endif; ?>
    </div>
</section>


<section class="shop-content">
    
    <aside class="shop-sidebar">
        <div class="filter-section">
            <h3 class="filter-title">Categories</h3>
            <ul class="filter-list">
                <li>
                    <a href="shop.php" class="<?php echo !$category ? 'active' : ''; ?>">
                        All Products
                    </a>
                </li>
                <?php foreach ($categories as $cat): ?>
                    <li>
                        <a href="shop.php?category=<?php echo $cat['slug']; ?>" 
                           class="<?php echo $category === $cat['slug'] ? 'active' : ''; ?>">
                            <?php echo sanitize($cat['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="filter-section">
            <h3 class="filter-title">Search</h3>
            <form action="shop.php" method="GET" class="sidebar-search">
                <?php if ($category): ?>
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                <?php endif; ?>
                <input type="text" name="search" placeholder="Search products..." value="<?php echo $search; ?>">
                <button type="submit">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </form>
        </div>
    </aside>
    
    
    <div class="shop-main">
        <?php if (empty($products)): ?>
            <div class="no-products">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter to find what you're looking for.</p>
                <a href="shop.php" class="btn btn-primary">View All Products</a>
            </div>
        <?php else: ?>
            <div class="products-count">
                <span><?php echo count($products); ?> products</span>
            </div>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="product-image-link">
                            <div class="product-image">
                                <img src="<?php echo getProductImage($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?>">
                                <div class="product-overlay">
                                    <span>Quick View</span>
                                </div>
                            </div>
                        </a>
                        <div class="product-info">
                            <span class="product-category"><?php echo sanitize($product['category_name']); ?></span>
                            <h3 class="product-name">
                                <a href="product.php?id=<?php echo $product['id']; ?>"><?php echo sanitize($product['name']); ?></a>
                            </h3>
                            <div class="product-footer">
                                <span class="product-price"><?php echo formatPrice($product['price']); ?></span>
                                <button class="btn-add-cart" data-product-id="<?php echo $product['id']; ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                        <line x1="3" y1="6" x2="21" y2="6"></line>
                                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

