<?php
require_once 'db.php';

class Auth {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    // Register a new user
    public function register($username, $password, $firstName, $lastName, $email) {
        // Validate input
        if (empty($username) || empty($password) || empty($email)) {
            return array('status' => false, 'message' => 'All fields are required');
        }
        
        // Check if username already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('status' => false, 'message' => 'Username already exists');
        }
        
        // Check if email already exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('status' => false, 'message' => 'Email already exists');
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert new user
        $stmt = $this->db->prepare("INSERT INTO users (username, password, first_name, last_name, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $hashedPassword, $firstName, $lastName, $email);
        
        if ($stmt->execute()) {
            return array('status' => true, 'message' => 'Registration successful');
        } else {
            return array('status' => false, 'message' => 'Registration failed');
        }
    }
    
    // Login a user
    public function login($username, $password, $rememberMe = false) {
        // Validate input
        if (empty($username) || empty($password)) {
            return array('status' => false, 'message' => 'Username and password are required');
        }
        
        // Get user by username
        $stmt = $this->db->prepare("SELECT id, username, password, first_name, last_name FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Start session
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['is_logged_in'] = true;
                
                // Handle remember me
                if ($rememberMe) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
                    
                    // Store token in database (you'd need to add a remember_tokens table)
                    // This is just a simple implementation
                }
                
                return array('status' => true, 'message' => 'Login successful');
            } else {
                return array('status' => false, 'message' => 'Invalid password');
            }
        } else {
            return array('status' => false, 'message' => 'User not found');
        }
    }
    
    // Check if user is logged in
    public function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
    }
    
    // Logout user
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Unset all session variables
        $_SESSION = array();
        
        // Delete the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();
        
        // Remove remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
    }
    
    // Get current user
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        $userId = $_SESSION['user_id'];
        $stmt = $this->db->prepare("SELECT id, username, first_name, last_name, email FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
}

// Create auth instance
$auth = new Auth($db->getConnection());
?>