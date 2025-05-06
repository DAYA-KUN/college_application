-- Create database
CREATE DATABASE IF NOT EXISTS student_application_system;
USE student_application_system;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_email (email)
);

-- Applications table
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    family_details TEXT,
    parent_occupation VARCHAR(100),
    income_range VARCHAR(50),
    preferred_course VARCHAR(50) NOT NULL,
    statement_of_purpose TEXT,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Reviewed', 'Approved', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create admin user (password: admin123)
INSERT INTO users (username, password, first_name, last_name, email)
VALUES ('admin', '$2y$10$8tGIUzKM.hTyY9Wh3wkSW.8CjX6Tyn9vFqxAlpAFB8oH7SHHoGJTm', 'Admin', 'User', 'admin@example.com')
ON DUPLICATE KEY UPDATE username = username;