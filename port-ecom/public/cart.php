<?php

require_once __DIR__ . '/../includes/functions.php';

if (!isLoggedIn()) {
    redirect('index.php', 'Please sign in to view your cart', 'info');
}

$pageTitle = 'Shopping Cart';
require_once __DIR__ . '/../includes/header.php';

$cartItems = getCartItems();
$cartTotal = getCartTotal();
?>


<section class="cart-header">
    <h1 class="cart-title">Shopping Cart</h1>
    <p class="cart-subtitle"><?php echo count($cartItems); ?> items in your cart</p>
</section>


<section class="cart-content">
    <?php if (empty($cartItems)): ?>
        <div class="cart-empty">
            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <path d="M16 10a4 4 0 0 1-8 0"></path>
            </svg>
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <a href="shop.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="cart-items">
            <div class="cart-table-header">
                <span>Product</span>
                <span>Price</span>
                <span>Quantity</span>
                <span>Subtotal</span>
                <span></span>
            </div>
            
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-product-id="<?php echo $item['id']; ?>">
                    <div class="cart-item-product">
                        <img src="<?php echo getProductImage($item['image']); ?>" alt="<?php echo sanitize($item['name']); ?>">
                        <div class="cart-item-info">
                            <h4><a href="product.php?id=<?php echo $item['id']; ?>"><?php echo sanitize($item['name']); ?></a></h4>
                        </div>
                    </div>
                    
                    <div class="cart-item-price">
                        <?php echo formatPrice($item['price']); ?>
                    </div>
                    
                    <div class="cart-item-quantity">
                        <div class="quantity-selector quantity-selector-small">
                            <button class="qty-btn qty-minus" data-action="decrease">−</button>
                            <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="cart-qty-input" data-product-id="<?php echo $item['id']; ?>">
                            <button class="qty-btn qty-plus" data-action="increase">+</button>
                        </div>
                    </div>
                    
                    <div class="cart-item-subtotal">
                        <?php echo formatPrice($item['subtotal']); ?>
                    </div>
                    
                    <div class="cart-item-remove">
                        <button class="btn-remove" data-product-id="<?php echo $item['id']; ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="cart-summary">
            <div class="cart-summary-content">
                <h3>Order Summary</h3>
                
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="cartSubtotal"><?php echo formatPrice($cartTotal); ?></span>
                </div>
                
                <div class="summary-row">
                    <span>Shipping</span>
                    <span><?php echo $cartTotal >= 150 ? 'Free' : formatPrice(10); ?></span>
                </div>
                
                <div class="summary-row summary-total">
                    <span>Total</span>
                    <span id="cartTotal"><?php echo formatPrice($cartTotal >= 150 ? $cartTotal : $cartTotal + 10); ?></span>
                </div>
                
                <a href="checkout.php" class="btn btn-primary btn-block">Proceed to Checkout</a>
                
                <a href="shop.php" class="btn btn-outline btn-block">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

