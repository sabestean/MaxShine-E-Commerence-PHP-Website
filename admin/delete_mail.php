<?php
session_start();
include '../conn.php';

// Check if mail_id is set
if (isset($_POST['mail_id']) && !empty($_POST['mail_id'])) {
    
    $mail_id = intval($_POST['mail_id']); // Convert to integer for security
    
    // Delete query
    $delete_query = "DELETE FROM newsletter WHERE id = $mail_id";
    
    if (mysqli_query($conn, $delete_query)) {
        if (mysqli_affected_rows($conn) > 0) {
            echo "success";
        } else {
            echo "not_found";
        }
    } else {
        echo "error";
    }
    
} else {
    echo "invalid_request";
}

mysqli_close($conn);
?>