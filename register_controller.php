<?php
// Include database connection
require_once '../includes/db.php';
require_once '../includes/auth.php';

// Initialize the Auth class
$auth = new Auth();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3-20 characters and contain only letters, numbers, and underscores";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username or email already exists
    if (empty($errors)) {
        if ($auth->usernameExists($username)) {
            $errors[] = "Username already exists";
        }
        
        if ($auth->emailExists($email)) {
            $errors[] = "Email already exists";
        }
    }
    
    // If no errors, register the user
    if (empty($errors)) {
        $result = $auth->register($username, $email, $password);
        
        if ($result) {
            // Registration successful
            $_SESSION['success_message'] = "Registration successful! You can now log in.";
            header("Location: ../index.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
    
    // If there are errors, store them in session and redirect back to the registration form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'username' => $username,
            'email' => $email
        ];
        header("Location: ../index.php?action=register");
        exit();
    }
} else {
    // If someone tries to access this file directly, redirect to the home page
    header("Location: ../index.php");
    exit();
}
?>