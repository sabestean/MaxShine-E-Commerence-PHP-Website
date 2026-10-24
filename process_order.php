<?php
session_start();
include 'conn.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $customerName = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $deliveryAddress = trim($_POST['address']);
    $cartData = $_POST['cart_data'];
    
    // Decode cart data
    $cartItems = json_decode($cartData, true);
    
    // Validation
    if (empty($customerName) || empty($phone) || empty($deliveryAddress) || empty($cartItems)) {
        $_SESSION['order_error'] = "Please fill all required fields.";
        header("Location: cart.php");
        exit;
    }
    
    // Calculate order totals
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    $shippingFee = 200; // Flat Rs. 200
    $tax = $subtotal * 0.10; // 10% tax
    $total = $subtotal + $shippingFee + $tax;
    
    // Format cart items for database storage (WITH ML)
    $cartItemsFormatted = [];
    foreach ($cartItems as $item) {
        $cartItemsFormatted[] = [
            'name' => $item['name'],
            'category' => $item['category'],
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'ml' => isset($item['ml']) ? $item['ml'] : 'N/A', // ✅ Added ML field
            'subtotal' => $item['price'] * $item['quantity']
        ];
    }
    
    $cartItemsJson = json_encode($cartItemsFormatted);
    
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, delivery_address, cart_items, subtotal, shipping_fee, tax, total, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    
    if ($stmt === false) {
        $_SESSION['order_error'] = "Database error: " . $conn->error;
        header("Location: cart.php");
        exit;
    }
    
    $stmt->bind_param("ssssdddd", $customerName, $phone, $deliveryAddress, $cartItemsJson, $subtotal, $shippingFee, $tax, $total);
    
    if ($stmt->execute()) {
        $orderId = $stmt->insert_id;
        
        // Success - store order ID in session
        $_SESSION['order_success'] = true;
        $_SESSION['order_id'] = $orderId;
        $_SESSION['order_total'] = $total;
        
        $stmt->close();
        $conn->close();
        
        // Redirect to order confirmation page
        header("Location: order_confirmation.php");
        exit;
        
    } else {
        $_SESSION['order_error'] = "Error placing order: " . $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: cart.php");
        exit;
    }
    
} else {
    // If accessed directly, redirect to cart
    header("Location: cart.php");
    exit;
}
?>