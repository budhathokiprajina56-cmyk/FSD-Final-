<?php

$pageTitle = 'Home';
require_once __DIR__ . '/../includes/header.php';

$featuredProducts = getFeaturedProducts(8);
$categories = getCategories();
?>


<section class="hero">
    <div class="hero-content">
        <span class="hero-tag">New Collection 2026</span>
        <h1 class="hero-title">THE CORE OF<br>FASHION</h1>
        <p class="hero-text">Discover timeless elegance with our curated collection of premium clothing and accessories.</p>
        <a href="shop.php" class="btn btn-hero">Explore Collection</a>
    </div>
    <div class="hero-visual">
        <div class="hero-image-grid">
            <div class="hero-img hero-img-1"></div>
            <div class="hero-img hero-img-2"></div>
            <div class="hero-img hero-img-3"></div>
        </div>
    </div>
</section>


<div class="marquee-banner">
    <div class="marquee-content">
        <span>★ FREE SHIPPING ON ORDERS OVER $150</span>
        <span>★ NEW ARRIVALS WEEKLY</span>
        <span>★ PREMIUM QUALITY GUARANTEED</span>
        <span>★ FREE SHIPPING ON ORDERS OVER $150</span>
        <span>★ NEW ARRIVALS WEEKLY</span>
        <span>★ PREMIUM QUALITY GUARANTEED</span>
    </div>
</div>


<section class="categories-section">
    <div class="section-header">
        <h2 class="section-title">Shop by Category</h2>
        <p class="section-subtitle">Explore our curated collections</p>
    </div>
    <div class="categories-grid">
        <?php foreach ($categories as $category): ?>
            <a href="shop.php?category=<?php echo $category['slug']; ?>" class="category-card">
                <div class="category-icon">
                    <?php if ($category['slug'] === 'men'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    <?php elseif ($category['slug'] === 'women'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="8" r="5"></circle>
                            <path d="M12 13v8"></path>
                            <path d="M9 18h6"></path>
                        </svg>
                    <?php elseif ($category['slug'] === 'kids'): ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2a3 3 0 0 0-3 3v1a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3z"></path>
                            <path d="M19 8H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2z"></path>
                        </svg>
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <h3><?php echo sanitize($category['name']); ?></h3>
                <span class="category-link">Shop Now →</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>


<section class="featured-section">
    <div class="section-header">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">Handpicked essentials for your wardrobe</p>
    </div>
    <div class="products-grid">
        <?php foreach ($featuredProducts as $product): ?>
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
    <div class="section-cta">
        <a href="shop.php" class="btn btn-outline">View All Products</a>
    </div>
</section>


<section class="about-banner">
    <div class="about-content">
        <h2>Timeless Elegance,<br>Modern Design</h2>
        <p>At <?php echo SITE_NAME; ?>, we believe fashion is an expression of individuality. Our collections blend classic sophistication with contemporary trends, creating pieces that transcend seasons.</p>
        <a href="contact.php" class="btn btn-white">Learn More</a>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

