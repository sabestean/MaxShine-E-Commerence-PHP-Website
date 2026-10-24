<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    if (isset($_GET['id']) && isset($_GET['table'])) {
        
        $productId = intval($_GET['id']);
        $tableName = trim($_GET['table']);
        
        // Validate table name
        $allowedTables = ['home_products', 'kitchen_products', 'washroom_products', 'handwash_products'];
        if (!in_array($tableName, $allowedTables)) {
            $response['success'] = false;
            $response['message'] = "Invalid table name.";
            echo json_encode($response);
            exit;
        }
        
        // Fetch product
        $stmt = $conn->prepare("SELECT * FROM $tableName WHERE id = ?");
        
        if ($stmt === false) {
            $response['success'] = false;
            $response['message'] = "Error preparing statement.";
            echo json_encode($response);
            exit;
        }
        
        $stmt->bind_param("i", $productId);
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                $response['success'] = true;
                $response['product'] = $product;
            } else {
                $response['success'] = false;
                $response['message'] = "Product not found.";
            }
        } else {
            $response['success'] = false;
            $response['message'] = "Error executing query.";
        }
        
        $stmt->close();
        
    } else {
        $response['success'] = false;
        $response['message'] = "Missing required parameters.";
    }
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>