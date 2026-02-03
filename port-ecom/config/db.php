<?php


define('DB_HOST', 'localhost');
define('DB_NAME', 'np03cs4a240324');
define('DB_USER', 'np03cs4a240324');
define('DB_PASS', 'mIbnenqvWU');
define('DB_CHARSET', 'utf8mb4');


function getDB() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    return $pdo;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SITE_NAME', 'ÉLÉGANCE');
define('SITE_URL', 'https://student.heraldcollege.edu.np/~np03cs4a240324/public/');
define('ADMIN_URL', 'https://student.heraldcollege.edu.np/~np03cs4a240324/admin/');
define('ASSETS_URL', 'https://student.heraldcollege.edu.np/~np03cs4a240324/assets/');

