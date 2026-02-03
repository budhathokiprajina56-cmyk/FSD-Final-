
<div class="modal-overlay" id="authModal">
    <div class="modal">
        <button class="modal-close" id="closeAuthModal">&times;</button>
        
        <div class="modal-tabs">
            <button class="modal-tab active" data-tab="login">Sign In</button>
            <button class="modal-tab" data-tab="register">Create Account</button>
        </div>
        
        
        <form class="modal-form active" id="loginForm" data-tab="login">
            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to access your cart and orders</p>
            </div>
            
            <div class="form-group">
                <label for="loginEmail">Email Address</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-message" id="loginMessage"></div>
            
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>
        
        
        <form class="modal-form" id="registerForm" data-tab="register">
            <div class="form-header">
                <h2>Create Account</h2>
                <p>Join us for exclusive deals and offers</p>
            </div>
            
            <div class="form-group">
                <label for="regName">Full Name</label>
                <input type="text" id="regName" name="name" required>
            </div>
            
            <div class="form-group">
                <label for="regEmail">Email Address</label>
                <input type="email" id="regEmail" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="regPhone">Phone Number (10 digits)</label>
                <input type="tel" id="regPhone" name="phone" pattern="[0-9]{10}" required>
            </div>
            
            <div class="form-group">
                <label for="regPassword">Password</label>
                <input type="password" id="regPassword" name="password" minlength="6" required>
            </div>
            
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-message" id="registerMessage"></div>
            
            <button type="submit" class="btn btn-primary btn-block">Create Account</button>
        </form>
    </div>
</div>

