<?php
// Start session
session_start();
include 'conn.php';

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array();

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Sanitize and validate input data
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $message = trim($_POST['message']);
    
    // Validation
    $errors = array();
    
    // Validate first name
    if (empty($firstName)) {
        $errors[] = "First name is required.";
    } elseif (strlen($firstName) > 100) {
        $errors[] = "First name must be less than 100 characters.";
    }
    
    // Validate last name
    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    } elseif (strlen($lastName) > 100) {
        $errors[] = "Last name must be less than 100 characters.";
    }
    
    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($email) > 255) {
        $errors[] = "Email must be less than 255 characters.";
    }
    
    // Validate message
    if (empty($message)) {
        $errors[] = "Message is required.";
    } elseif (strlen($message) < 10) {
        $errors[] = "Message must be at least 10 characters long.";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = implode(" ", $errors);
        echo json_encode($response);
        exit;
    }
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contacts (first_name, last_name, email, phone, message) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
        echo json_encode($response);
        exit;
    }
    
    // Bind parameters
    $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $message);
    
    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Thank you! Your message has been sent successfully. We'll get back to you soon.";
        
        // Optional: Send email notification to admin
        // $to = "support@cleancart.com";
        // $subject = "New Contact Form Submission from " . $firstName . " " . $lastName;
        // $emailBody = "Name: " . $firstName . " " . $lastName . "\n";
        // $emailBody .= "Email: " . $email . "\n";
        // $emailBody .= "Phone: " . $phone . "\n\n";
        // $emailBody .= "Message:\n" . $message;
        // $headers = "From: noreply@cleancart.com";
        // mail($to, $subject, $emailBody, $headers);
        
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $stmt->error;
    }
    
    // Close statement
    $stmt->close();
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Close connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>