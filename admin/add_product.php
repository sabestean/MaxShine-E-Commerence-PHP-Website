<?php
session_start();
include '../conn.php';

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = array();

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $productName = trim($_POST['productName']);
    $description = trim($_POST['description']);
    $price = intval($_POST['price']);
    $ml = trim($_POST['ml']); // New ML field
    $category = trim($_POST['category']);
    
    // Validation
    $errors = array();
    
    // Validate product name
    if (empty($productName)) {
        $errors[] = "Product name is required.";
    } elseif (strlen($productName) > 255) {
        $errors[] = "Product name must be less than 255 characters.";
    }
    
    // Validate description
    if (empty($description)) {
        $errors[] = "Product description is required.";
    }
    
    // Validate price
    if ($price <= 0) {
        $errors[] = "Price must be greater than 0.";
    }
    
    // Validate ML
    if (empty($ml)) {
        $errors[] = "Volume (ML) is required.";
    } elseif (strlen($ml) > 50) {
        $errors[] = "Volume must be less than 50 characters.";
    }
    
    // Validate category
    $allowedCategories = ['home-product', 'kitchen-product', 'washroom-product', 'handwash-product'];
    if (empty($category) || !in_array($category, $allowedCategories)) {
        $errors[] = "Please select a valid category.";
    }
    
    // Validate image
    if (!isset($_FILES['productImage']) || $_FILES['productImage']['error'] == UPLOAD_ERR_NO_FILE) {
        $errors[] = "Product image is required.";
    }
    
    // If there are validation errors
    if (!empty($errors)) {
        $response['success'] = false;
        $response['message'] = implode(" ", $errors);
        echo json_encode($response);
        exit;
    }
    
    // Handle image upload
    $uploadDir = '../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $imageFile = $_FILES['productImage'];
    $imageName = '';
    
    if ($imageFile['error'] == UPLOAD_ERR_OK) {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $fileType = $imageFile['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
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
        $imageExtension = pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $imageName = 'product_' . time() . '_' . uniqid() . '.' . $imageExtension;
        $imagePath = $uploadDir . $imageName;
        
        // Move uploaded file
        if (!move_uploaded_file($imageFile['tmp_name'], $imagePath)) {
            $response['success'] = false;
            $response['message'] = "Failed to upload image.";
            echo json_encode($response);
            exit;
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error uploading image.";
        echo json_encode($response);
        exit;
    }
    
    // Determine which table to insert into based on category
    $tableName = '';
    switch ($category) {
        case 'home-product':
            $tableName = 'home_products';
            break;
        case 'kitchen-product':
            $tableName = 'kitchen_products';
            break;
        case 'washroom-product':
            $tableName = 'washroom_products';
            break;
        case 'handwash-product':
            $tableName = 'handwash_products';
            break;
        default:
            $response['success'] = false;
            $response['message'] = "Invalid category selected.";
            echo json_encode($response);
            exit;
    }
    
    // Prepare SQL statement to prevent SQL injection (NOW INCLUDING ML FIELD)
    $stmt = $conn->prepare("INSERT INTO $tableName (product_name, product_description, price, ml, image) VALUES (?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        // Delete uploaded image if database insert fails
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $conn->error;
        echo json_encode($response);
        exit;
    }
    
    // Bind parameters (NOW INCLUDING ML PARAMETER)
    $stmt->bind_param("ssiss", $productName, $description, $price, $ml, $imageName);
    
    // Execute the statement
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Product added successfully to " . ucfirst(str_replace('-', ' ', $category)) . " category!";
        $response['product_id'] = $stmt->insert_id;
        $response['category'] = $category;
    } else {
        // Delete uploaded image if database insert fails
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
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