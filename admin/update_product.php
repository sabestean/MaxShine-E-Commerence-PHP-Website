<?php
session_start();
include '../conn.php';

header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Debug: Log all POST and FILES data
    error_log("POST Data: " . print_r($_POST, true));
    error_log("FILES Data: " . print_r($_FILES, true));
    
    // Get form data
    $productId = isset($_POST['productId']) ? intval($_POST['productId']) : 0;
    $tableName = isset($_POST['productTable']) ? trim($_POST['productTable']) : '';
    $productName = isset($_POST['productName']) ? trim($_POST['productName']) : '';
    $productDescription = isset($_POST['productDescription']) ? trim($_POST['productDescription']) : '';
    $productPrice = isset($_POST['productPrice']) ? intval($_POST['productPrice']) : 0;
    $productML = isset($_POST['productML']) ? trim($_POST['productML']) : ''; // NEW: Get ML field
    
    // Validation
    $errors = array();
    
    // Validate table name
    $allowedTables = ['home_products', 'kitchen_products', 'washroom_products', 'handwash_products'];
    if (!in_array($tableName, $allowedTables)) {
        $errors[] = "Invalid table name: " . $tableName;
    }
    
    if ($productId <= 0) {
        $errors[] = "Invalid product ID.";
    }
    
    // Validate product name
    if (empty($productName)) {
        $errors[] = "Product name is required.";
    } elseif (strlen($productName) > 255) {
        $errors[] = "Product name must be less than 255 characters.";
    }
    
    // Validate description
    if (empty($productDescription)) {
        $errors[] = "Product description is required.";
    }
    
    // Validate price
    if ($productPrice <= 0) {
        $errors[] = "Price must be greater than 0.";
    }
    
    // NEW: Validate ML
    if (empty($productML)) {
        $errors[] = "Volume (ML) is required.";
    } elseif (strlen($productML) > 50) {
        $errors[] = "Volume must be less than 50 characters.";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = implode(" ", $errors);
        echo json_encode($response);
        exit;
    }
    
    // Handle image upload if provided
    $newImageName = null;
    $oldImageName = null;
    $uploadDir = '../uploads/';
    
    // Check if image file was uploaded
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        
        // Get old image name first
        $getOldImageStmt = $conn->prepare("SELECT image FROM $tableName WHERE id = ?");
        if ($getOldImageStmt) {
            $getOldImageStmt->bind_param("i", $productId);
            $getOldImageStmt->execute();
            $oldImageResult = $getOldImageStmt->get_result();
            if ($oldImageResult->num_rows > 0) {
                $oldImageRow = $oldImageResult->fetch_assoc();
                $oldImageName = $oldImageRow['image'];
            }
            $getOldImageStmt->close();
        }
        
        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $imageFile = $_FILES['productImage'];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $fileType = $imageFile['type'];
        
        // Also check by extension
        $imageExtension = strtolower(pathinfo($imageFile['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($fileType, $allowedTypes) && !in_array($imageExtension, $allowedExtensions)) {
            $response['success'] = false;
            $response['message'] = "Only JPG, PNG, and GIF images are allowed.";
            echo json_encode($response);
            exit;
        }
        
        // Validate file size (10MB max)
        if ($imageFile['size'] > 10 * 1024 * 1024) {
            $response['success'] = false;
            $response['message'] = "Image size must be less than 10MB.";
            echo json_encode($response);
            exit;
        }
        
        // Generate unique filename
        $newImageName = 'product_' . time() . '_' . uniqid() . '.' . $imageExtension;
        $imagePath = $uploadDir . $newImageName;
        
        // Move uploaded file
        if (!move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
            $response['success'] = false;
            $response['message'] = "Failed to upload new image.";
            echo json_encode($response);
            exit;
        }
    }
    
    // Update product in database
    if ($newImageName !== null) {
        // Update with new image (NOW INCLUDING ML)
        $stmt = $conn->prepare("UPDATE $tableName SET product_name = ?, product_description = ?, price = ?, ml = ?, image = ? WHERE id = ?");
        if ($stmt === false) {
            // Delete newly uploaded image if statement preparation fails
            if ($newImageName && file_exists($uploadDir . $newImageName)) {
                unlink($uploadDir . $newImageName);
            }
            $response['success'] = false;
            $response['message'] = "Error preparing statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }
        $stmt->bind_param("ssissi", $productName, $productDescription, $productPrice, $productML, $newImageName, $productId);
    } else {
        // Update without changing image (NOW INCLUDING ML)
        $stmt = $conn->prepare("UPDATE $tableName SET product_name = ?, product_description = ?, price = ?, ml = ? WHERE id = ?");
        if ($stmt === false) {
            $response['success'] = false;
            $response['message'] = "Error preparing statement: " . $conn->error;
            echo json_encode($response);
            exit;
        }
        $stmt->bind_param("ssisi", $productName, $productDescription, $productPrice, $productML, $productId);
    }
    
    if ($stmt->execute()) {
        
        // Delete old image if new image was uploaded successfully
        if ($newImageName !== null && $oldImageName !== null && !empty($oldImageName)) {
            $oldImagePath = $uploadDir . $oldImageName;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        
        $response['success'] = true;
        $response['message'] = "Product updated successfully!";
        
    } else {
        // Delete newly uploaded image if database update fails
        if ($newImageName && file_exists($uploadDir . $newImageName)) {
            unlink($uploadDir . $newImageName);
        }
        $response['success'] = false;
        $response['message'] = "Error updating product: " . $stmt->error;
    }
    
    $stmt->close();
    
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method.";
}

$conn->close();
echo json_encode($response);
?>