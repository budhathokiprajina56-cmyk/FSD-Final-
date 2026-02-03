<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('shop.php');
}

$product = getProduct((int)$_GET['id']);

if (!$product) {
    redirect('shop.php', 'Product not found', 'error');
}

$pageTitle = $product['name'];
require_once __DIR__ . '/../includes/header.php';

$db = getDB();
$stmt = $db->prepare("SELECT p.*, c.name as category_name FROM products p 
                      JOIN categories c ON p.category_id = c.id 
                      WHERE p.category_id = ? AND p.id != ? 
                      ORDER BY RAND() LIMIT 4");
$stmt->execute([$product['category_id'], $product['id']]);
$relatedProducts = $stmt->fetchAll();
?>


<nav class="breadcrumb">
    <a href="index.php">Home</a>
    <span>/</span>
    <a href="shop.php">Shop</a>
    <span>/</span>
    <a href="shop.php?category=<?php echo strtolower($product['category_name']); ?>"><?php echo sanitize($product['category_name']); ?></a>
    <span>/</span>
    <span class="current"><?php echo sanitize($product['name']); ?></span>
</nav>


<section class="product-detail">
    <div class="product-gallery">
        <div class="main-image">
            <img src="<?php echo getProductImage($product['image']); ?>" alt="<?php echo sanitize($product['name']); ?>" id="mainProductImage">
        </div>
    </div>
    
    <div class="product-info-detail">
        <span class="product-category-badge"><?php echo sanitize($product['category_name']); ?></span>
        <h1 class="product-title"><?php echo sanitize($product['name']); ?></h1>
        <div class="product-price-large"><?php echo formatPrice($product['price']); ?></div>
        
        <div class="product-description">
            <p><?php echo nl2br(sanitize($product['description'])); ?></p>
        </div>
        
        <div class="product-stock">
            <?php if ($product['stock'] > 0): ?>
                <span class="in-stock">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    In Stock (<?php echo $product['stock']; ?> available)
                </span>
            <?php else: ?>
                <span class="out-of-stock">Out of Stock</span>
            <?php endif; ?>
        </div>
        
        <div class="product-actions">
            <div class="quantity-selector">
                <button class="qty-btn qty-minus">−</button>
                <input type="number" value="1" min="1" max="<?php echo $product['stock']; ?>" id="productQty">
                <button class="qty-btn qty-plus">+</button>
            </div>
            
            <button class="btn btn-primary btn-lg btn-add-cart-detail" data-product-id="<?php echo $product['id']; ?>" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <path d="M16 10a4 4 0 0 1-8 0"></path>
                </svg>
                Add to Cart
            </button>
        </div>
        
        <div class="product-features">
            <div class="feature">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M5 12h14"></path>
                    <path d="M12 5l7 7-7 7"></path>
                </svg>
                <span>Free shipping over $150</span>
            </div>
            <div class="feature">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>30-day returns</span>
            </div>
            <div class="feature">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span>Secure payment</span>
            </div>
        </div>
    </div>
</section>


<?php if (!empty($relatedProducts)): ?>
<section class="related-products">
    <h2 class="section-title">You May Also Like</h2>
    <div class="products-grid products-grid-small">
        <?php foreach ($relatedProducts as $related): ?>
            <div class="product-card">
                <a href="product.php?id=<?php echo $related['id']; ?>" class="product-image-link">
                    <div class="product-image">
                        <img src="<?php echo getProductImage($related['image']); ?>" alt="<?php echo sanitize($related['name']); ?>">
                        <div class="product-overlay">
                            <span>Quick View</span>
                        </div>
                    </div>
                </a>
                <div class="product-info">
                    <span class="product-category"><?php echo sanitize($related['category_name']); ?></span>
                    <h3 class="product-name">
                        <a href="product.php?id=<?php echo $related['id']; ?>"><?php echo sanitize($related['name']); ?></a>
                    </h3>
                    <div class="product-footer">
                        <span class="product-price"><?php echo formatPrice($related['price']); ?></span>
                        <button class="btn-add-cart" data-product-id="<?php echo $related['id']; ?>">
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
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

