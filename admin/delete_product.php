<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Debug logging
    error_log("Delete POST Data: " . print_r($_POST, true));
    
    if (isset($_POST['id']) && isset($_POST['table'])) {
        
        $productId = intval($_POST['id']);
        $tableName = trim($_POST['table']);
        
        // Validate table name
        $allowedTables = ['home_products', 'kitchen_products', 'washroom_products', 'handwash_products'];
        if (!in_array($tableName, $allowedTables)) {
            $response['success'] = false;
            $response['message'] = "Invalid table name: " . $tableName;
            echo json_encode($response);
            exit;
        }
        
        if ($productId <= 0) {
            $response['success'] = false;
            $response['message'] = "Invalid product ID.";
            echo json_encode($response);
            exit;
        }
        
        // First, get the image filename to delete it
        $getImageStmt = $conn->prepare("SELECT image FROM $tableName WHERE id = ?");
        
        if ($getImageStmt === false) {
            $response['success'] = false;
            $response['message'] = "Error preparing statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }
        
        $getImageStmt->bind_param("i", $productId);
        
        if (!$getImageStmt->execute()) {
            $response['success'] = false;
            $response['message'] = "Error fetching product: " . $getImageStmt->error;
            $getImageStmt->close();
            echo json_encode($response);
            exit;
        }
        
        $imageResult = $getImageStmt->get_result();
        
        $imageName = '';
        if ($imageResult->num_rows > 0) {
            $imageRow = $imageResult->fetch_assoc();
            $imageName = $imageRow['image'];
        } else {
            $response['success'] = false;
            $response['message'] = "Product not found.";
            $getImageStmt->close();
            echo json_encode($response);
            exit;
        }
        $getImageStmt->close();
        
        // Delete product from database
        $stmt = $conn->prepare("DELETE FROM $tableName WHERE id = ?");
        
        if ($stmt === false) {
            $response['success'] = false;
            $response['message'] = "Error preparing delete statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }
        
        $stmt->bind_param("i", $productId);
        
        if ($stmt->execute()) {
            
            if ($stmt->affected_rows > 0) {
                // Delete the image file
                if (!empty($imageName)) {
                    $imagePath = '../uploads/' . $imageName;
                    if (file_exists($imagePath)) {
                        if (unlink($imagePath)) {
                            error_log("Image deleted successfully: " . $imagePath);
                        } else {
                            error_log("Failed to delete image: " . $imagePath);
                        }
                    } else {
                        error_log("Image file not found: " . $imagePath);
                    }
                }
                
                $response['success'] = true;
                $response['message'] = "Product deleted successfully.";
            } else {
                $response['success'] = false;
                $response['message'] = "Product not found or already deleted.";
            }
            
        } else {
            $response['success'] = false;
            $response['message'] = "Error deleting product: " . $stmt->error;
        }
        
        $stmt->close();
        
    } else {
        $response['success'] = false;
        $response['message'] = "Missing required parameters (id or table).";
    }
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method. Expected POST.";
}

$conn->close();
echo json_encode($response);
?>