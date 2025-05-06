<?php
require_once 'config.php';

class Database {
    private $connection;
    
    // Constructor establishes a database connection
    public function __construct() {
        $this->connection = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
        
        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        
        // Set character set to UTF-8
        $this->connection->set_charset("utf8");
    }
    
    // Get database connection
    public function getConnection() {
        return $this->connection;
    }
    
    // Execute a query
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    // Prepare a SQL statement
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    // Escape string for security
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
    
    // Close database connection
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}

// Create database instance
$db = new Database();
?>