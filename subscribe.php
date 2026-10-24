<?php
// Include database connection
include 'conn.php';

// Check if the request is POST and email is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    
    // Get and sanitize email
    $email = trim($_POST['email']);
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "invalid_email";
        exit;
    }
    
    // Escape email to prevent SQL injection
    $email = mysqli_real_escape_string($conn, $email);
    
    // Check if email already exists in database
    $check_query = "SELECT id FROM newsletter WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (!$check_result) {
        echo "error";
        exit;
    }
    
    if (mysqli_num_rows($check_result) > 0) {
        echo "already_subscribed";
        exit;
    }
    
    // Insert new email into database
    $insert_query = "INSERT INTO newsletter (email) VALUES ('$email')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "success";
    } else {
        echo "error";
    }
    
} else {
    echo "invalid_request";
}

// Close database connection
mysqli_close($conn);
?>