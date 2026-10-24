<?php
session_start();
include '../conn.php';

// Fetch all orders from database
$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);

$orders = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - MaxShine Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: margin-left 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            min-height: 100vh;
        }

        .main-content.expanded {
            margin-left: 90px;
        }

        .page-header {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            color: white;
            border-left: 5px solid #F5B027;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .page-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header i {
            font-size: 2.5rem;
            color: #F5B027;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .page-header p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 5px 0 0 0;
        }

        .orders-count-badge {
            background: #F5B027;
            color: #003366;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 700;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .orders-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
        }

        .orders-container::-webkit-scrollbar {
            width: 10px;
        }

        .orders-container::-webkit-scrollbar-track {
            background: #f5f7fa;
            border-radius: 10px;
        }

        .orders-container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
            border: 2px solid #f5f7fa;
        }

        .orders-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #F5B027 0%, #003366 100%);
            box-shadow: 0 0 12px rgba(245, 176, 39, 0.4);
        }

        .table-header {
            font-size: 1.1rem;
            color: #003366;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header i {
            color: #F5B027;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.08) 0%, rgba(245, 176, 39, 0.05) 100%);
            border-top: 2px solid #e8ecf1;
            border-bottom: 2px solid #F5B027;
        }

        thead th {
            padding: 16px;
            text-align: left;
            color: #003366;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #e8ecf1;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        tbody tr:hover {
            background: rgba(245, 176, 39, 0.06);
            box-shadow: inset 0 0 12px rgba(245, 176, 39, 0.05);
        }

        tbody td {
            padding: 16px;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .order-id {
            font-weight: 700;
            color: #003366;
            cursor: pointer;
        }

        .order-id:hover {
            color: #F5B027;
            text-decoration: underline;
        }

        .customer-name {
            font-weight: 600;
            color: #003366;
        }

        .order-total {
            color: #F5B027;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .items-count {
            background: #f0f0f0;
            padding: 4px 10px;
            border-radius: 15px;
            font-weight: 600;
            color: #003366;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .status-badge.Completed {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(34, 197, 94, 0.08) 100%);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.3);
        }

        .status-badge.Processing {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.15) 0%, rgba(59, 130, 246, 0.08) 100%);
            color: #3b82f6;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .status-badge.Pending {
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.15) 0%, rgba(245, 176, 39, 0.08) 100%);
            color: #f59e0b;
            border-color: rgba(245, 176, 39, 0.3);
        }

        tbody tr:hover .status-badge {
            transform: scale(1.08);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .action-btns {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.3s;
            font-weight: 600;
        }

        .btn-view {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(34, 197, 94, 0.1) 100%);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .btn-view:hover {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.35) 0%, rgba(34, 197, 94, 0.2) 100%);
            border-color: #22c55e;
            transform: translateY(-2px) scale(1.08);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25);
        }

        .btn-delete {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.1) 100%);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.35) 0%, rgba(239, 68, 68, 0.2) 100%);
            border-color: #ef4444;
            transform: translateY(-2px) scale(1.08);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        /* Status dropdown */
        .status-select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 2px solid #e8ecf1;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
            color: #003366;
        }

        .status-select:focus {
            outline: none;
            border-color: #F5B027;
            box-shadow: 0 0 8px rgba(245, 176, 39, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #a8c5dd;
        }

        .empty-state i {
            font-size: 3.5rem;
            color: #e8ecf1;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #4a5568;
            margin-top: 15px;
        }

        /* Order Details Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideUp 0.3s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F5B027;
        }

        .modal-header h2 {
            color: #003366;
            font-size: 1.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #4a5568;
            transition: all 0.3s;
        }

        .close-btn:hover {
            color: #ef4444;
            transform: rotate(90deg);
        }

        .order-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e8ecf1;
        }

        .order-detail-label {
            font-weight: 600;
            color: #003366;
        }

        .order-detail-value {
            color: #666;
            text-align: right;
        }

        .cart-items-list {
            margin-top: 20px;
        }

        .cart-items-list h3 {
            color: #003366;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #F5B027;
        }

        .cart-item-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e8ecf1;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            font-weight: 600;
            color: #003366;
        }

        .cart-item-category {
            font-size: 0.9rem;
            color: #666;
        }

        .cart-item-price {
            color: #F5B027;
            font-weight: 600;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .orders-container {
                padding: 15px;
            }

            table {
                min-width: 1000px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left">
                <i class="fas fa-shopping-bag"></i>
                <div>
                    <h1>Manage Orders</h1>
                    <p>View and manage all customer orders</p>
                </div>
            </div>
            <div class="orders-count-badge">
                <?php echo count($orders); ?> Total Orders
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Orders Table -->
        <div class="orders-container">
            <div class="table-header">
                <i class="fas fa-list"></i>
                Order Management
            </div>

            <?php if (count($orders) > 0): ?>
            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Delivery Address</th>
                        <th>Total (PKR)</th>
                        <th>Items</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($orders as $order): 
                        $cartItems = json_decode($order['cart_items'], true);
                        $itemCount = array_sum(array_column($cartItems, 'quantity'));
                        $datetime = date('d M Y', strtotime($order['order_date']));
                    ?>
                    <tr id="row-<?php echo $order['order_id']; ?>">
                        <td class="order-id" onclick="viewOrder(<?php echo $order['order_id']; ?>)">
                            #<?php echo str_pad($order['order_id'], 6, '0', STR_PAD_LEFT); ?>
                        </td>
                        <td class="customer-name"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td><?php echo htmlspecialchars(substr($order['delivery_address'], 0, 30)) . (strlen($order['delivery_address']) > 30 ? '...' : ''); ?></td>
                        <td class="order-total">Rs. <?php echo number_format($order['total']); ?></td>
                        <td><span class="items-count"><?php echo $itemCount; ?> items</span></td>
                        <td><?php echo $datetime; ?></td>
                        <td>
                            <select class="status-select" onchange="updateStatus(this, <?php echo $order['order_id']; ?>)">
                                <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Processing" <?php echo $order['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-view" onclick="viewOrder(<?php echo $order['order_id']; ?>)" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-icon btn-delete" onclick="deleteOrder(<?php echo $order['order_id']; ?>)" title="Delete Order">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No orders found</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-receipt"></i> Order Details</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div id="modalBody">
                <!-- Order details will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    // View order details
    function viewOrder(orderId) {
        fetch(`get_order_details.php?order_id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const order = data.order;
                    const cartItems = JSON.parse(order.cart_items);
                    
                    let modalHTML = `
                        <div class="order-detail-row">
                            <span class="order-detail-label">Order ID:</span>
                            <span class="order-detail-value">#${String(order.order_id).padStart(6, '0')}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Customer Name:</span>
                            <span class="order-detail-value">${order.customer_name}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Phone:</span>
                            <span class="order-detail-value">${order.phone}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Delivery Address:</span>
                            <span class="order-detail-value">${order.delivery_address}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Order Date:</span>
                            <span class="order-detail-value">${new Date(order.order_date).toLocaleString()}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Status:</span>
                            <span class="order-detail-value"><span class="status-badge ${order.status}">${order.status}</span></span>
                        </div>
                        
                        <div class="cart-items-list">
                            <h3>Ordered Items</h3>
                    `;
                    
                    cartItems.forEach(item => {
                        // ✅ Create ML badge if available
                        const mlBadge = item.ml && item.ml !== 'N/A' ? 
                            `<span style="display: inline-flex; align-items: center; gap: 4px; background: linear-gradient(135deg, #F5B027 0%, #d4920e 100%); color: #003366; padding: 3px 10px; border-radius: 15px; font-size: 0.8rem; font-weight: 700; margin-left: 8px; white-space: nowrap;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                </svg>
                                ${item.ml}
                            </span>` : '';
                        
                        modalHTML += `
                            <div class="cart-item-row">
                                <div class="cart-item-info">
                                    <div class="cart-item-name">
                                        ${item.name}
                                        ${mlBadge}
                                    </div>
                                    <div class="cart-item-category">${item.category} • Qty: ${item.quantity}</div>
                                </div>
                                <div class="cart-item-price">Rs. ${item.subtotal.toLocaleString()}</div>
                            </div>
                        `;
                    });
                    
                    modalHTML += `
                        </div>
                        <div class="order-detail-row" style="margin-top: 20px;">
                            <span class="order-detail-label">Subtotal:</span>
                            <span class="order-detail-value">Rs. ${parseFloat(order.subtotal).toLocaleString()}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Shipping Fee:</span>
                            <span class="order-detail-value">Rs. ${parseFloat(order.shipping_fee).toLocaleString()}</span>
                        </div>
                        <div class="order-detail-row">
                            <span class="order-detail-label">Tax:</span>
                            <span class="order-detail-value">Rs. ${parseFloat(order.tax).toLocaleString()}</span>
                        </div>
                        <div class="order-detail-row" style="border-top: 2px solid #003366; margin-top: 10px; padding-top: 15px;">
                            <span class="order-detail-label" style="font-size: 1.2rem;">Total:</span>
                            <span class="order-detail-value" style="color: #F5B027; font-size: 1.3rem; font-weight: 700;">Rs. ${parseFloat(order.total).toLocaleString()}</span>
                        </div>
                    `;
                    
                    document.getElementById('modalBody').innerHTML = modalHTML;
                    document.getElementById('orderModal').classList.add('show');
                } else {
                    showAlert('error', 'Failed to load order details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Error loading order details');
            });
    }

    // Close modal
    function closeModal() {
        document.getElementById('orderModal').classList.remove('show');
    }

    // Update order status
    function updateStatus(selectElement, orderId) {
        const newStatus = selectElement.value;
        
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', `Order status updated to ${newStatus}`);
            } else {
                showAlert('error', 'Failed to update status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error updating status');
        });
    }

    // Delete order
    function deleteOrder(orderId) {
        if (confirm('Are you sure you want to delete this order?')) {
            const row = document.getElementById(`row-${orderId}`);
            row.style.animation = 'fadeOut 0.3s ease-out';
            
            fetch('delete_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        row.remove();
                        const tableBody = document.getElementById('tableBody');
                        if (tableBody.querySelectorAll('tr').length === 0) {
                            location.reload();
                        }
                        showAlert('success', 'Order deleted successfully');
                    }, 300);
                } else {
                    showAlert('error', 'Failed to delete order');
                    row.style.animation = '';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Error deleting order');
                row.style.animation = '';
            });
        }
    }

    // Show alert
    function showAlert(type, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        alertContainer.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            </div>
        `;
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }

    // Close modal on outside click
    window.onclick = function(event) {
        const modal = document.getElementById('orderModal');
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
</body>
</html>
<?php
$conn->close();
?>