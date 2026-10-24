<?php
session_start();
include 'conn.php';

header('Content-Type: application/json');

$response = array(
    'success' => false,
    'products' => array(),
    'count' => 0
);

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchQuery = mysqli_real_escape_string($conn, trim($_GET['query']));
    $searchQuery = '%' . $searchQuery . '%';
    
    $products = array();
    
    // Define tables and their display categories
    $tables = array(
        'home_products' => array('category' => 'Home', 'page' => 'home.php'),
        'kitchen_products' => array('category' => 'Kitchen', 'page' => 'kitchen.php'),
        'washroom_products' => array('category' => 'Bathroom', 'page' => 'bathroom.php'),
        'handwash_products' => array('category' => 'Hand Wash', 'page' => 'handwash.php')
    );
    
    // Search in each table
    foreach ($tables as $tableName => $info) {
        $sql = "SELECT id, product_name, product_description, price, image, created_at 
                FROM $tableName 
                WHERE product_name LIKE ? 
                   OR product_description LIKE ?
                ORDER BY created_at DESC 
                LIMIT 10";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ss", $searchQuery, $searchQuery);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                $products[] = array(
                    'id' => $row['id'],
                    'name' => $row['product_name'],
                    'description' => $row['product_description'],
                    'price' => $row['price'],
                    'image' => $row['image'],
                    'category' => $info['category'],
                    'category_page' => $info['page'],
                    'table' => $tableName
                );
            }
            
            $stmt->close();
        }
    }
    
    // Sort by relevance (products with name match first)
    $searchTerm = str_replace('%', '', $searchQuery);
    usort($products, function($a, $b) use ($searchTerm) {
        $aNameMatch = stripos($a['name'], $searchTerm) !== false;
        $bNameMatch = stripos($b['name'], $searchTerm) !== false;
        
        if ($aNameMatch && !$bNameMatch) return -1;
        if (!$aNameMatch && $bNameMatch) return 1;
        
        return 0;
    });
    
    // Limit to top 10 results
    $products = array_slice($products, 0, 10);
    
    $response['success'] = true;
    $response['products'] = $products;
    $response['count'] = count($products);
}

echo json_encode($response);
$conn->close();
?>