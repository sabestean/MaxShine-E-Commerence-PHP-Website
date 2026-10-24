<?php
session_start();
include '../conn.php';

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array();

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if ID is provided
    if (isset($_POST['id']) && !empty($_POST['id'])) {
        
        $feedbackId = intval($_POST['id']);
        
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
        
        if ($stmt === false) {
            $response['success'] = false;
            $response['message'] = "Error preparing statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }
        
        // Bind parameter
        $stmt->bind_param("i", $feedbackId);
        
        // Execute the statement
        if ($stmt->execute()) {
            
            // Check if any row was actually deleted
            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = "Feedback deleted successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "No feedback found with this ID.";
            }
            
        } else {
            $response['success'] = false;
            $response['message'] = "Error executing query: " . $stmt->error;
        }
        
        // Close statement
        $stmt->close();
        
    } else {
        $response['success'] = false;
        $response['message'] = "Feedback ID is required.";
    }
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

// Close connection
$conn->close();

// Return JSON response
echo json_encode($response);
?>