# ÉLÉGANCE - Clothing Store eCommerce System

A premium PHP/MySQL eCommerce platform for fashion retail with a modern, high-fashion aesthetic.

## Features

- **Browse-First Architecture**: Guests can browse products freely; authentication only required for cart/checkout
- **Responsive Design**: Fully responsive across all devices
- **Modern UI/UX**: Premium fashion-inspired design with elegant typography and smooth animations
- **AJAX Cart**: Real-time cart updates without page refresh
- **Admin Panel**: Complete dashboard for managing products, categories, and orders
- **Secure**: CSRF protection, prepared statements, password hashing

## Technology Stack

- **Backend**: PHP 7.4+ with PDO
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Fonts**: Playfair Display, Inter (Google Fonts)

## Installation

### 1. Database Setup

1. Open phpMyAdmin or MySQL command line
2. Run the schema file:
   ```sql
   source /path/to/port-ecom/config/schema.sql
   ```
   
   Or import `config/schema.sql` through phpMyAdmin

### 2. Configuration

Edit `config/db.php` if your database credentials differ:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'clothing_store');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Access the Site

Website URL: https://student.heraldcollege.edu.np/~np03cs4a240324/

### Default Admin Login

- **Email**: admin@elegance.com
- **Password**: admin123

## Project Structure

```
port-ecom/
├── admin/              # Admin panel
│   ├── index.php       # Admin login
│   ├── dashboard.php   # Dashboard
│   ├── categories.php  # Category management
│   ├── products.php    # Product management
│   └── orders.php      # Order management
├── assets/
│   ├── css/
│   │   ├── style.css   # Main styles
│   │   └── admin.css   # Admin styles
│   ├── js/
│   │   └── main.js     # Frontend JavaScript
│   └── images/
│       └── products/   # Product images
├── config/
│   ├── db.php          # Database config
│   └── schema.sql      # Database schema
├── includes/
│   ├── functions.php   # Utility functions
│   ├── header.php      # Header template
│   ├── footer.php      # Footer template
│   └── auth_modal.php  # Login/Register modal
└── public/
    ├── index.php       # Homepage
    ├── shop.php        # Product listing
    ├── product.php     # Product detail
    ├── cart.php        # Shopping cart
    ├── checkout.php    # Checkout
    ├── orders.php      # Order history
    ├── contact.php     # Contact page
    └── ajax/           # AJAX handlers
        ├── login.php
        ├── register.php
        ├── cart_actions.php
        └── logout.php
```

## User Features

- Browse products by category
- Search products
- View product details
- Add to cart (requires login)
- Checkout with shipping
- View order history

## Admin Features

- Dashboard with stats overview
- Manage product categories
- Add/Edit/Delete products
- Update order statuses
- View order details

## Security Features

- CSRF token protection on all forms
- XSS prevention via output sanitization
- SQL injection prevention via prepared statements
- Password hashing with bcrypt
- Session-based authentication

