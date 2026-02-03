<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo SITE_NAME; ?> - Premium Fashion & Clothing Store">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/style.css">
</head>
<body>
    
    <header class="main-header">
        <nav class="navbar">
            <div class="nav-left">
                <a href="<?php echo SITE_URL; ?>index.php" class="nav-link">Home</a>
                <a href="<?php echo SITE_URL; ?>shop.php" class="nav-link">Shop</a>
                <a href="<?php echo SITE_URL; ?>contact.php" class="nav-link">Contact Us</a>
                <?php if (isLoggedIn()): ?>
                    <a href="<?php echo SITE_URL; ?>orders.php" class="nav-link">My Orders</a>
                <?php endif; ?>
            </div>
            
            <a href="<?php echo SITE_URL; ?>index.php" class="logo"><?php echo SITE_NAME; ?></a>
            
            <div class="nav-right">
                
                <div class="search-wrapper">
                    <button class="search-toggle" id="searchToggle">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                    <form class="search-form" id="searchForm" action="<?php echo SITE_URL; ?>shop.php" method="GET">
                        <input type="text" name="search" placeholder="Search..." class="search-input">
                    </form>
                </div>
                
                <?php if (isLoggedIn()): ?>
                    
                    <a href="<?php echo SITE_URL; ?>cart.php" class="cart-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                        <span class="cart-count" id="cartCount"><?php echo getCartCount(); ?></span>
                    </a>
                    
                    
                    <div class="user-dropdown">
                        <button class="user-toggle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span><?php echo sanitize($_SESSION['user_name']); ?></span>
                        </button>
                        <div class="dropdown-menu">
                            <a href="<?php echo SITE_URL; ?>orders.php">My Orders</a>
                            <a href="<?php echo SITE_URL; ?>ajax/logout.php" class="logout-btn">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <button class="btn-auth" id="openAuthModal">Sign In</button>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    
    
    <?php echo flashMessage(); ?>
    
    <main class="main-content">

