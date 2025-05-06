<?php
// Include database connection
require_once '../includes/db.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the database connection
    $db = new Database();
    $conn = $db->getConnection();
    
    // Get form data
    $user_id = $_SESSION['user_id'];
    $full_name = trim($_POST['full_name']);
    $age = (int)$_POST['age'];
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $family_details = trim($_POST['family_details']);
    $parent_occupation = trim($_POST['parent_occupation']);
    $income_range = trim($_POST['income_range']);
    $preferred_course = trim($_POST['preferred_course']);
    $statement_of_purpose = trim($_POST['statement_of_purpose']);
    
    $errors = [];
    
    // Validate data
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if ($age < 15 || $age > 100) {
        $errors[] = "Age must be between 15 and 100";
    }
    
    if (empty($gender)) {
        $errors[] = "Gender is required";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    if (empty($phone) || !preg_match("/^\d{10}$/", $phone)) {
        $errors[] = "Valid 10-digit phone number is required";
    }
    
    if (empty($preferred_course)) {
        $errors[] = "Preferred course is required";
    }
    
    if (empty($statement_of_purpose)) {
        $errors[] = "Statement of purpose is required";
    }
    
    // If no errors, save the application to the database
    if (empty($errors)) {
        try {
            // Check if application already exists for this user
            $check_sql = "SELECT id FROM applications WHERE user_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $user_id);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Update existing application
                $application = $result->fetch_assoc();
                $application_id = $application['id'];
                
                $update_sql = "UPDATE applications SET 
                              full_name = ?, 
                              age = ?, 
                              gender = ?, 
                              email = ?, 
                              phone = ?, 
                              family_details = ?, 
                              parent_occupation = ?, 
                              income_range = ?, 
                              preferred_course = ?, 
                              statement_of_purpose = ?, 
                              updated_at = NOW() 
                              WHERE id = ?";
                              
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("sissssssssi", 
                                        $full_name, 
                                        $age, 
                                        $gender, 
                                        $email, 
                                        $phone, 
                                        $family_details, 
                                        $parent_occupation, 
                                        $income_range, 
                                        $preferred_course, 
                                        $statement_of_purpose,
                                        $application_id);
                                        
                $result = $update_stmt->execute();
                
                if ($result) {
                    $_SESSION['success_message'] = "Application updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update application. Please try again.";
                }
                
            } else {
                // Insert new application
                $insert_sql = "INSERT INTO applications (user_id, full_name, age, gender, email, phone, family_details, parent_occupation, income_range, preferred_course, statement_of_purpose, created_at, updated_at) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
                              
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("isisssssss", 
                                       $user_id, 
                                       $full_name, 
                                       $age, 
                                       $gender, 
                                       $email, 
                                       $phone, 
                                       $family_details, 
                                       $parent_occupation, 
                                       $income_range, 
                                       $preferred_course, 
                                       $statement_of_purpose);
                                       
                $result = $insert_stmt->execute();
                
                if ($result) {
                    $_SESSION['success_message'] = "Application submitted successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to submit application. Please try again.";
                }
            }
            
            // Redirect back to the application form
            header("Location: ../application_form.php");
            exit();
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
            header("Location: ../application_form.php");
            exit();
        }
    } else {
        // If there are errors, store them in session and redirect back to the form
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: ../application_form.php");
        exit();
    }
} else {
    // If someone tries to access this file directly, redirect to the application form
    header("Location: ../application_form.php");
    exit();
}
?>