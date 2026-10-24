<?php
session_start();
include '../conn.php';

// Fetch statistics
$totalSalesQuery = "SELECT SUM(total) as total_sales FROM orders";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSales = 0;
if ($totalSalesResult && $totalSalesResult->num_rows > 0) {
    $row = $totalSalesResult->fetch_assoc();
    $totalSales = $row['total_sales'] ?? 0;
}

$totalOrdersQuery = "SELECT COUNT(*) as total_orders FROM orders";
$totalOrdersResult = $conn->query($totalOrdersQuery);
$totalOrders = 0;
if ($totalOrdersResult && $totalOrdersResult->num_rows > 0) {
    $row = $totalOrdersResult->fetch_assoc();
    $totalOrders = $row['total_orders'] ?? 0;
}

// Count total products from all tables
$totalProducts = 0;
$productTables = ['home_products', 'kitchen_products', 'washroom_products', 'handwash_products'];
foreach ($productTables as $table) {
    $countQuery = "SELECT COUNT(*) as count FROM $table";
    $countResult = $conn->query($countQuery);
    if ($countResult && $countResult->num_rows > 0) {
        $row = $countResult->fetch_assoc();
        $totalProducts += $row['count'];
    }
}

// Fetch recent orders (last 10)
$recentOrdersQuery = "SELECT * FROM orders ORDER BY order_date DESC LIMIT 10";
$recentOrdersResult = $conn->query($recentOrdersQuery);
$recentOrders = array();
if ($recentOrdersResult && $recentOrdersResult->num_rows > 0) {
    while ($row = $recentOrdersResult->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

// Calculate sales growth (comparing last 30 days with previous 30 days)
$currentMonthQuery = "SELECT SUM(total) as current_sales FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$currentMonthResult = $conn->query($currentMonthQuery);
$currentMonthSales = 0;
if ($currentMonthResult && $currentMonthResult->num_rows > 0) {
    $row = $currentMonthResult->fetch_assoc();
    $currentMonthSales = $row['current_sales'] ?? 0;
}

$previousMonthQuery = "SELECT SUM(total) as previous_sales FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND order_date < DATE_SUB(NOW(), INTERVAL 30 DAY)";
$previousMonthResult = $conn->query($previousMonthQuery);
$previousMonthSales = 0;
if ($previousMonthResult && $previousMonthResult->num_rows > 0) {
    $row = $previousMonthResult->fetch_assoc();
    $previousMonthSales = $row['previous_sales'] ?? 0;
}

$salesGrowth = 0;
if ($previousMonthSales > 0) {
    $salesGrowth = (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100;
}

// Calculate orders growth
$currentMonthOrdersQuery = "SELECT COUNT(*) as current_orders FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$currentMonthOrdersResult = $conn->query($currentMonthOrdersQuery);
$currentMonthOrders = 0;
if ($currentMonthOrdersResult && $currentMonthOrdersResult->num_rows > 0) {
    $row = $currentMonthOrdersResult->fetch_assoc();
    $currentMonthOrders = $row['current_orders'] ?? 0;
}

$previousMonthOrdersQuery = "SELECT COUNT(*) as previous_orders FROM orders WHERE order_date >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND order_date < DATE_SUB(NOW(), INTERVAL 30 DAY)";
$previousMonthOrdersResult = $conn->query($previousMonthOrdersQuery);
$previousMonthOrders = 0;
if ($previousMonthOrdersResult && $previousMonthOrdersResult->num_rows > 0) {
    $row = $previousMonthOrdersResult->fetch_assoc();
    $previousMonthOrders = $row['previous_orders'] ?? 0;
}

$ordersGrowth = 0;
if ($previousMonthOrders > 0) {
    $ordersGrowth = (($currentMonthOrders - $previousMonthOrders) / $previousMonthOrders) * 100;
}

$current_page = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MaxShine Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #003366;
            --accent-color: #F5B027;
            --text-light: #ffffff;
            --sidebar-bg: #003366;
            --sidebar-dark: #001a33;
            --hover-bg: #004d99;
            --text-muted: #a8c5dd;
            --bg-light: #f0f4f8;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            overflow-x: hidden;
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

        .welcome-section {
            margin-bottom: 40px;
            animation: slideInDown 0.6s ease-out;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-bg) 100%);
            color: var(--text-light);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 51, 102, 0.2);
            border: 2px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(245, 176, 39, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .welcome-subtitle {
            font-size: 16px;
            opacity: 0.95;
            color: rgba(255, 255, 255, 0.9);
        }

        .welcome-icon {
            position: absolute;
            right: 40px;
            top: 40px;
            font-size: 60px;
            opacity: 0.2;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 51, 102, 0.08);
            border: 2px solid rgba(245, 176, 39, 0.1);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
            animation: slideInUp 0.6s ease-out;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 51, 102, 0.15);
            border-color: var(--accent-color);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            font-size: 42px;
            margin-bottom: 16px;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: rgba(245, 176, 39, 0.12);
            color: var(--accent-color);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .stat-card:hover .stat-icon {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.15) rotate(5deg);
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .stat-change {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            border-radius: 8px;
            width: fit-content;
        }

        .stat-change.positive {
            background: rgba(34, 197, 94, 0.12);
            color: #22c55e;
        }

        .stat-change.negative {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        .stat-change.neutral {
            background: rgba(156, 163, 175, 0.12);
            color: #6b7280;
        }

        .recent-section {
            margin-top: 40px;
            animation: slideInUp 0.8s ease-out;
        }

        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 3px solid var(--accent-color);
            display: inline-block;
        }

        .recent-table-container {
            background: var(--card-bg);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 51, 102, 0.08);
            border: 2px solid rgba(245, 176, 39, 0.1);
            max-height: 500px;
            overflow-y: auto;
        }

        .recent-table-container::-webkit-scrollbar {
            width: 10px;
        }

        .recent-table-container::-webkit-scrollbar-track {
            background: #f5f7fa;
        }

        .recent-table-container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
            border: 2px solid #f5f7fa;
        }

        .recent-table-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #F5B027 0%, #003366 100%);
            box-shadow: 0 0 12px rgba(245, 176, 39, 0.4);
        }

        .recent-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .recent-table thead {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-bg) 100%);
            color: var(--text-light);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .recent-table th {
            padding: 16px 20px;
            font-weight: 700;
            text-align: left;
            letter-spacing: 0.5px;
        }

        .recent-table tbody tr {
            border-bottom: 1px solid rgba(245, 176, 39, 0.08);
            transition: all 0.3s ease;
        }

        .recent-table tbody tr:hover {
            background: rgba(245, 176, 39, 0.08);
        }

        .recent-table td {
            padding: 16px 20px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: fit-content;
        }

        .status-completed {
            background: rgba(34, 197, 94, 0.12);
            color: #22c55e;
        }

        .status-pending {
            background: rgba(245, 176, 39, 0.12);
            color: var(--accent-color);
        }

        .status-processing {
            background: rgba(59, 130, 246, 0.12);
            color: #3b82f6;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: var(--text-muted);
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

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .welcome-card {
                padding: 24px;
            }

            .welcome-title {
                font-size: 24px;
            }

            .welcome-icon {
                font-size: 40px;
                right: 20px;
                top: 20px;
            }

            .stats-section {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .section-title {
                font-size: 20px;
            }

            .stat-value {
                font-size: 28px;
            }

            .recent-table {
                font-size: 0.85rem;
            }

            .recent-table th,
            .recent-table td {
                padding: 12px 10px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 16px;
            }

            .welcome-card {
                padding: 20px;
            }

            .welcome-title {
                font-size: 20px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-card">
                <i class="fas fa-user-circle welcome-icon"></i>
                <div class="welcome-content">
                    <h1 class="welcome-title">Welcome back, Administrator</h1>
                    <p class="welcome-subtitle">Here's what's happening with your store today</p>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="stats-section">
            <!-- Total Sales Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-label">Total Sales</div>
                <div class="stat-value">Rs. <?php echo number_format($totalSales); ?></div>
                <div class="stat-change <?php echo $salesGrowth >= 0 ? 'positive' : 'negative'; ?>">
                    <i class="fas fa-arrow-trend-<?php echo $salesGrowth >= 0 ? 'up' : 'down'; ?>"></i>
                    <span><?php echo $salesGrowth >= 0 ? '+' : ''; ?><?php echo number_format($salesGrowth, 1); ?>% from last month</span>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bag-shopping"></i>
                </div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?php echo number_format($totalOrders); ?></div>
                <div class="stat-change <?php echo $ordersGrowth >= 0 ? 'positive' : 'negative'; ?>">
                    <i class="fas fa-arrow-trend-<?php echo $ordersGrowth >= 0 ? 'up' : 'down'; ?>"></i>
                    <span><?php echo $ordersGrowth >= 0 ? '+' : ''; ?><?php echo number_format($ordersGrowth, 1); ?>% from last month</span>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-label">Total Products</div>
                <div class="stat-value"><?php echo number_format($totalProducts); ?></div>
                <div class="stat-change neutral">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Active inventory</span>
                </div>
            </div>
        </div>

        <!-- Recent Orders Section -->
        <div class="recent-section">
            <h2 class="section-title">Recent Orders</h2>
            
            <?php if (count($recentOrders) > 0): ?>
            <div class="recent-table-container">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): 
                            $orderDate = date('M d, Y', strtotime($order['order_date']));
                            $orderId = '#' . str_pad($order['order_id'], 6, '0', STR_PAD_LEFT);
                            
                            // Determine status class
                            $statusClass = 'status-pending';
                            if ($order['status'] == 'Completed') {
                                $statusClass = 'status-completed';
                            } elseif ($order['status'] == 'Processing') {
                                $statusClass = 'status-processing';
                            }
                        ?>
                        <tr>
                            <td><?php echo $orderId; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td>Rs. <?php echo number_format($order['total']); ?></td>
                            <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                            <td><?php echo $orderDate; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No orders yet. Start selling to see orders here!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to sync sidebar state with main content
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.overlay');
            
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }

        // Restore sidebar state on page load
        window.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebarCollapsed && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }
        });

        // Handle responsive behavior
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });

        // Close mobile sidebar when navigation link is clicked
        document.querySelectorAll('.nav-link:not(.logout-link)').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    closeMobileSidebar();
                }
            });
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>