<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

// Log the request for debugging
error_log("Update Status Request - POST data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['order_id']) || !isset($_POST['status'])) {
        $response['success'] = false;
        $response['message'] = "Missing required parameters.";
        echo json_encode($response);
        exit;
    }
    
    $orderId = intval($_POST['order_id']);
    $status = trim($_POST['status']);
    
    // Validate status
    $allowedStatuses = ['Pending', 'Processing', 'Completed'];
    if (!in_array($status, $allowedStatuses)) {
        $response['success'] = false;
        $response['message'] = "Invalid status: " . $status;
        echo json_encode($response);
        exit;
    }
    
    // Check if order exists first
    $checkStmt = $conn->prepare("SELECT order_id FROM orders WHERE order_id = ?");
    $checkStmt->bind_param("i", $orderId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows === 0) {
        $response['success'] = false;
        $response['message'] = "Order not found.";
        $checkStmt->close();
        echo json_encode($response);
        exit;
    }
    $checkStmt->close();
    
    // Update the status
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param("si", $status, $orderId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = "Status updated successfully to " . $status;
            error_log("Status updated successfully for order #{$orderId} to {$status}");
        } else {
            $response['success'] = true;
            $response['message'] = "Status was already " . $status;
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error updating status: " . $stmt->error;
        error_log("Error updating status: " . $stmt->error);
    }
    
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>