<?php

$pageTitle = 'Contact Us';
require_once __DIR__ . '/../includes/header.php';

$submitted = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request';
    } else {
        $name = sanitize($_POST['name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $subject = sanitize($_POST['subject'] ?? '');
        $message = sanitize($_POST['message'] ?? '');
        
        if (empty($name)) $errors[] = 'Name is required';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required';
        if (empty($message)) $errors[] = 'Message is required';
        
        if (empty($errors)) {

            $submitted = true;
        }
    }
}
?>


<section class="contact-header">
    <h1 class="contact-title">Contact Us</h1>
    <p class="contact-subtitle">We'd love to hear from you</p>
</section>


<section class="contact-content">
    <div class="contact-info-cards">
        <div class="contact-card">
            <div class="contact-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <h3>Visit Us</h3>
            <p>Elegance</p>
        </div>
        
        <div class="contact-card">
            <div class="contact-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
            </div>
            <h3>Call Us</h3>
            <p>1234567890</p>
        </div>
        
        <div class="contact-card">
            <div class="contact-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h3>Email Us</h3>
            <p>contact@elegance.com<br>support@elegance.com</p>
        </div>
    </div>
    
    <div class="contact-form-wrapper">
        <?php if ($submitted): ?>
            <div class="contact-success">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <h2>Thank You!</h2>
                <p>Your message has been sent successfully. We'll get back to you within 24 hours.</p>
                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </div>
        <?php else: ?>
            <form method="POST" class="contact-form">
                <h2>Send us a Message</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="form-errors">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject">
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
                
                <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                
                <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

