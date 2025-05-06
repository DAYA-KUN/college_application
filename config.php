<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'student_application_system');

// Base URL for the application
define('BASE_URL', 'http://localhost/student-application-system');

// Application settings
define('APP_NAME', 'Student Application System');
define('APP_VERSION', '1.0.0');

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>