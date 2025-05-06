<?php
// Include database connection and authentication class
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Initialize the Auth class
$auth = new Auth();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? $_POST['remember'] : false;
    
    $errors = [];
    
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    
    // If no validation errors, attempt to login
    if (empty($errors)) {
        $user = $auth->login($username, $password);
        
        if ($user) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;
            
            // Set remember me cookie if selected
            if ($remember) {
                $token = $auth->generateRememberToken($user['id']);
                if ($token) {
                    setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
                }
            }
            
            // Check if there is a redirect URL
            if (isset($_SESSION['redirect_url'])) {
                $redirect = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
                header("Location: " . $redirect);
                exit();
            } else {
                // Redirect to application form page
                header("Location: ../application_form.php");
                exit();
            }
        } else {
            // Login failed
            $errors[] = "Invalid username or password";
        }
    }
    
    // If there are errors, store them in session and redirect back to login form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'username' => $username
        ];
        header("Location: ../index.php");
        exit();
    }
} else {
    // If someone tries to access this file directly, redirect to login page
    header("Location: ../index.php");
    exit();
}

/**
 * Helper function to validate and sanitize input
 * @param string $data
 * @return string
 */
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>