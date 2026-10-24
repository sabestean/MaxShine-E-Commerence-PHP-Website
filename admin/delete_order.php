<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

// Log the request for debugging
error_log("Delete Order Request - POST data: " . print_r($_POST, true));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_POST['order_id'])) {
        $response['success'] = false;
        $response['message'] = "Order ID is required.";
        echo json_encode($response);
        exit;
    }
    
    $orderId = intval($_POST['order_id']);
    
    // Validate order ID
    if ($orderId <= 0) {
        $response['success'] = false;
        $response['message'] = "Invalid order ID.";
        echo json_encode($response);
        exit;
    }
    
    // Check if order exists first
    $checkStmt = $conn->prepare("SELECT order_id, customer_name FROM orders WHERE order_id = ?");
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
    
    $orderData = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    // Delete the order
    $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
    
    if ($stmt === false) {
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = "Order deleted successfully.";
            error_log("Order #{$orderId} (Customer: {$orderData['customer_name']}) deleted successfully");
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to delete order. No rows affected.";
            error_log("Failed to delete order #{$orderId} - No rows affected");
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error deleting order: " . $stmt->error;
        error_log("Error deleting order #{$orderId}: " . $stmt->error);
    }
    
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method. Only POST is allowed.";
}

$conn->close();
echo json_encode($response);
?>