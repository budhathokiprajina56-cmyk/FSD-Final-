<?php

require_once __DIR__ . '/../includes/functions.php';

if (isAdmin()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request';
    } else {
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $db = getDB();
        $stmt = $db->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = 'admin'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            redirect('dashboard.php');
        } else {
            $error = 'Invalid credentials';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | <?php echo SITE_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/admin.css">
</head>
<body class="admin-login-page">
    <div class="admin-login-wrapper">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <h1><?php echo SITE_NAME; ?></h1>
                <p>Admin Dashboard</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="admin-login-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>
            
            <div class="admin-login-footer">
                <a href="<?php echo SITE_URL; ?>index.php">← Back to Store</a>
            </div>
        </div>
    </div>
</body>
</html>

