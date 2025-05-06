<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Check if user is already logged in
if ($auth->isLoggedIn()) {
    header("Location: application_form.php");
    exit;
}

$pageTitle = "Login/Registration";
$extraCss = [];
$extraJs = ["assets/js/validation.js"];

require_once 'includes/header.php';
?>

<h2>Login/Registration</h2>

<div class="auth-container">
    <!-- Login Form (Left Side) -->
    <div class="form-container">
        <h3 class="form-title">RETURNING USER</h3>
        <form id="login-form" method="POST" action="controllers/login_controller.php">
            <div class="form-group">
                <label for="login-username">Name:</label>
                <input type="text" id="login-username" name="username" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <label for="login-password">Password:</label>
                <input type="password" id="login-password" name="password" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group checkbox-group">
                <input type="checkbox" id="remember-me" name="remember_me">
                <label for="remember-me">Remember me?</label>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-red">Login</button>
            </div>
            
            <div class="or-divider">
                <span>OR</span>
            </div>
            
            <a href="#" class="social-login">Sign in with facebook</a>
        </form>
    </div>
    
    <!-- Divider -->
    <div class="form-divider">
        <div class="divider-line"></div>
        <div class="divider-or">
            <img src="assets/images/lock-icon.png" alt="Lock Icon" class="lock-icon">
        </div>
    </div>
    
    <!-- Registration Form (Right Side) -->
    <div class="form-container">
        <h3 class="form-title">NEW USER</h3>
        <form id="register-form" method="POST" action="controllers/register_controller.php">
            <div class="form-group">
                <label for="first-name">First Name:</label>
                <input type="text" id="first-name" name="first_name" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <label for="last-name">Last Name:</label>
                <input type="text" id="last-name" name="last_name" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Id:</label>
                <input type="email" id="email" name="email" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <label for="register-password">Password:</label>
                <input type="password" id="register-password" name="password" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
                <div class="form-error"></div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>