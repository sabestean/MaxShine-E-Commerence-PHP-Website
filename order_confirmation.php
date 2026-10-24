<?php
session_start();
include 'conn.php';

// Check if order was successful
if (!isset($_SESSION['order_success']) || !$_SESSION['order_success']) {
    header("Location: cart.php");
    exit;
}

$orderId = $_SESSION['order_id'];
$orderTotal = $_SESSION['order_total'];

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

// Clear order session data
unset($_SESSION['order_success']);
unset($_SESSION['order_id']);
unset($_SESSION['order_total']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - CleanCart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #003366;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }

        .confirmation-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        h1 {
            color: #003366;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .order-number {
            font-size: 1.2rem;
            color: #F5B027;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .order-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-top: 30px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #003366;
        }

        .detail-value {
            color: #666;
        }

        .total-row {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #003366;
        }

        .total-row .detail-value {
            color: #F5B027;
            font-size: 1.3rem;
            font-weight: 700;
        }

        .cart-items-section {
            margin-top: 25px;
            text-align: left;
        }

        .cart-items-section h3 {
            color: #003366;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #F5B027;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #003366;
        }

        .item-category {
            font-size: 0.9rem;
            color: #666;
        }

        .item-price {
            color: #F5B027;
            font-weight: 600;
        }

        .action-buttons {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-primary {
            background: #F5B027;
            color: white;
        }

        .btn-primary:hover {
            background: #e6a31d;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: white;
            color: #003366;
            border: 2px solid #003366;
        }

        .btn-secondary:hover {
            background: #003366;
            color: white;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            background: #ffc107;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="confirmation-card">
            <div class="success-icon">✓</div>
            <h1>Order Placed Successfully!</h1>
            <p style="color: #666; margin-bottom: 10px;">Thank you for your order</p>
            <div class="order-number">Order #<?php echo str_pad($orderId, 6, '0', STR_PAD_LEFT); ?></div>
            <span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span>

            <?php if ($order): ?>
            <div class="order-details">
                <div class="detail-row">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['customer_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['phone']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Delivery Address:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($order['delivery_address']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value"><?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></span>
                </div>
                
                <div class="cart-items-section">
                    <h3>Ordered Items</h3>
                    <?php 
                    $cartItems = json_decode($order['cart_items'], true);
                    foreach ($cartItems as $item): 
                    ?>
                    <div class="item">
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div class="item-category"><?php echo htmlspecialchars($item['category']); ?> • Qty: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="item-price">Rs. <?php echo number_format($item['subtotal']); ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="detail-row" style="margin-top: 20px;">
                    <span class="detail-label">Subtotal:</span>
                    <span class="detail-value">Rs. <?php echo number_format($order['subtotal']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Shipping Fee:</span>
                    <span class="detail-value">Rs. <?php echo number_format($order['shipping_fee']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tax (10%):</span>
                    <span class="detail-value">Rs. <?php echo number_format($order['tax']); ?></span>
                </div>
                <div class="detail-row total-row">
                    <span class="detail-label">Total:</span>
                    <span class="detail-value">Rs. <?php echo number_format($order['total']); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="kitchen.php" class="btn btn-primary">Continue Shopping</a>
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        // Clear cart from localStorage after successful order
        localStorage.removeItem('cleanCartItems');
        
        // Update cart count
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = '0';
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>