<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

if (isset($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();
            $response['success'] = true;
            $response['order'] = $order;
        } else {
            $response['success'] = false;
            $response['message'] = "Order not found.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error executing query.";
    }
    
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Order ID is required.";
}

$conn->close();
echo json_encode($response);
?>