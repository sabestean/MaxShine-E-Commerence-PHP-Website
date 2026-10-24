<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            /* Brand colors from CleanCart */
            --primary-color: #003366;
            --accent-color: #F5B027;
            --text-light: #ffffff;
            --sidebar-bg: #003366;
            --sidebar-dark: #001a33;
            --hover-bg: #004d99;
            --text-muted: #a8c5dd;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: #f0f4f8;
        }

        /* Solid background color instead of gradient */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background: var(--sidebar-bg);
            padding: 0;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0, 51, 102, 0.35);
            border-right: 3px solid var(--accent-color);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 4px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #ffc966;
            box-shadow: 0 0 8px rgba(245, 176, 39, 0.5);
        }

        .sidebar.collapsed {
            width: 90px;
        }

        /* Enhanced sidebar header with logo image */
        .sidebar-header {
            padding: 24px 20px;
            background: var(--sidebar-bg);
            border-bottom: 3px solid var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            gap: 12px;
        }

        .sidebar-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-color), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        /* Added styled logo box with shopping cart icon */
        .logo-box {
            width: 48px;
            height: 48px;
            background: var(--accent-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 4px 16px rgba(245, 176, 39, 0.3);
            position: relative;
        }

        .logo-box::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--accent-color), rgba(245, 176, 39, 0.6));
            z-index: -1;
            opacity: 0;
            transition: opacity 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .sidebar:hover .logo-box::before {
            opacity: 1;
        }

        .logo-box i {
            font-size: 26px;
            color: var(--primary-color);
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar:hover .logo-box {
            transform: translateY(-4px) scale(1.08);
            box-shadow: 0 8px 24px rgba(245, 176, 39, 0.45);
        }

        .sidebar:hover .logo-box i {
            transform: scale(1.15);
        }

        .sidebar.collapsed .logo-box {
            display: none;
        }

        .logo-text {
            font-size: 22px;
            font-weight: 900;
            color: var(--text-light);
            white-space: nowrap;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, var(--text-light), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Trebuchet MS', 'Lucida Grande', sans-serif;
            font-style: italic;
            animation: textGlow 3s ease-in-out infinite;
        }

        @keyframes textGlow {
            0%, 100% { filter: drop-shadow(0 0 0px rgba(245, 176, 39, 0.5)); }
            50% { filter: drop-shadow(0 0 12px rgba(245, 176, 39, 0.8)); }
        }

        .sidebar:hover .logo-text {
            letter-spacing: 3px;
            filter: drop-shadow(0 0 16px rgba(245, 176, 39, 0.9));
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            display: none;
            width: 0;
        }

        /* Better toggle button styling */
        .toggle-btn {
            background: rgba(245, 176, 39, 0.25);
            border: 2px solid var(--accent-color);
            color: var(--accent-color);
            font-size: 16px;
            cursor: pointer;
            padding: 8px 6px;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-btn:hover {
            background: var(--accent-color);
            color: var(--sidebar-bg);
            transform: scale(1.15) rotate(5deg);
            box-shadow: 0 4px 12px rgba(245, 176, 39, 0.4);
        }

        .toggle-btn:active {
            transform: scale(0.9);
        }

        .sidebar.collapsed .toggle-btn {
            transform: scaleX(-1);
        }

        .nav-list {
            list-style: none;
            padding: 20px 10px;
        }

        .nav-item {
            margin: 6px 0;
            transition: all 0.3s ease;
        }

        /* Enhanced nav links with better transitions and icon styling */
        .nav-link {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 14px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
            font-weight: 600;
            gap: 14px;
            border-left: 4px solid transparent;
            font-size: 14px;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(245, 176, 39, 0.12) 50%, transparent 100%);
            transform: translateX(-100%);
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            z-index: 0;
        }

        .nav-link:hover::before {
            transform: translateX(100%);
        }

        .nav-link:hover {
            background: rgba(245, 176, 39, 0.18);
            color: var(--accent-color);
            transform: translateX(8px);
            border-left: 4px solid var(--accent-color);
            box-shadow: inset 0 0 12px rgba(245, 176, 39, 0.08);
        }

        /* Active state styling with smooth color transitions */
        .nav-link.active {
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.2) 0%, rgba(245, 176, 39, 0.08) 100%);
            color: var(--accent-color);
            border-left: 4px solid var(--accent-color);
            box-shadow: inset 0 0 20px rgba(245, 176, 39, 0.12), 0 4px 16px rgba(245, 176, 39, 0.15);
            font-weight: 700;
        }

        .nav-link.active i {
            animation: activeIconPulse 2.2s ease-in-out infinite;
            color: var(--accent-color);
            text-shadow: 0 0 10px rgba(245, 176, 39, 0.6);
        }

        @keyframes activeIconPulse {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.2) rotate(5deg); }
        }

        /* Improved icon transitions and styling */
        .nav-link i {
            font-size: 20px;
            width: 24px;
            flex-shrink: 0;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: inherit;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link:hover i {
            transform: translateY(-2px) scale(1.18);
            filter: drop-shadow(0 0 8px rgba(245, 176, 39, 0.7));
        }

        .nav-link span {
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            z-index: 1;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            overflow: hidden;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 14px;
        }

        /* Enhanced logout section with better styling */
        .logout-section {
            position: absolute;
            bottom: 24px;
            left: 0;
            right: 0;
            padding: 0 10px;
        }

        .logout-link {
            background: rgba(239, 68, 68, 0.12);
            border: 2px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
            border-radius: 10px;
            border-left: 4px solid transparent;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: inset 0 0 12px rgba(239, 68, 68, 0.05);
        }

        .logout-link:hover {
            background: rgba(239, 68, 68, 0.22);
            border-color: rgba(239, 68, 68, 0.5);
            border-left: 4px solid #ef4444;
            color: #fecaca;
            box-shadow: inset 0 0 16px rgba(239, 68, 68, 0.12), 0 4px 16px rgba(239, 68, 68, 0.2);
            transform: translateX(6px);
        }

        .logout-link i {
            color: #f87171;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .logout-link:hover i {
            filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.6));
            transform: translateY(-2px) scale(1.18);
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

        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                transform: translateX(-100%);
                box-shadow: 2px 0 24px rgba(0, 51, 102, 0.4);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar.collapsed {
                width: 280px;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .mobile-toggle {
                display: flex !important;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 999;
                background: linear-gradient(135deg, var(--sidebar-bg), var(--primary-color));
                color: var(--text-light);
                border: 2px solid var(--accent-color);
                padding: 12px 14px;
                border-radius: 10px;
                font-size: 20px;
                cursor: pointer;
                box-shadow: 0 6px 20px rgba(0, 51, 102, 0.35);
                transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                align-items: center;
                justify-content: center;
            }

            .mobile-toggle:hover {
                background: linear-gradient(135deg, var(--hover-bg), var(--primary-color));
                transform: translateY(-3px);
                box-shadow: 0 10px 28px rgba(245, 176, 39, 0.35);
                border-color: var(--accent-color);
            }

            .mobile-toggle:active {
                transform: translateY(0);
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 51, 102, 0.75);
                z-index: 998;
                animation: fadeIn 0.3s ease;
                backdrop-filter: blur(2px);
            }

            .overlay.active {
                display: block;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>
</head>
<body>
    <button class="mobile-toggle" onclick="toggleMobileSidebar()" title="Toggle Menu">
        <i class="fas fa-bars"></i>
    </button>

    <div class="overlay" onclick="closeMobileSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <!-- Enhanced header with logo box and text -->
        <div class="sidebar-header">
            <div class="logo-container">
                <!-- Added styled logo box with shopping cart icon -->
                <div class="logo-box">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <span class="logo-text">MaxShine</span>
            </div>
            <button class="toggle-btn" onclick="toggleSidebar()" title="Collapse Sidebar">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <!-- Improved nav items with better icons -->
        <ul class="nav-list">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="addproduct.php" class="nav-link <?php echo ($current_page == 'add-product') ? 'active' : ''; ?>">
                    <i class="fas fa-circle-plus"></i>
                    <span>Add Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="manageproduct.php" class="nav-link <?php echo ($current_page == 'manage-product') ? 'active' : ''; ?>">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Manage Product</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="manageuser.php" class="nav-link <?php echo ($current_page == 'manage') ? 'active' : ''; ?>">
                    <i class="fas fa-sliders"></i>
                    <span>Manage Users</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="feedback.php" class="nav-link <?php echo ($current_page == 'feedback') ? 'active' : ''; ?>">
                    <i class="fas fa-star"></i>
                    <span>Feedback</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="orders.php" class="nav-link <?php echo ($current_page == 'orders') ? 'active' : ''; ?>">
                    <i class="fas fa-bag-shopping"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="managemail.php" class="nav-link <?php echo ($current_page == 'manage-mail') ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i>
                    <span>Manage Mail</span>
                </a>
            </li>
        </ul>

        <div class="logout-section">
            <a href="../login.php" class="nav-link logout-link" title="Sign out">
                <i class="fas fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            if(mainContent) {
                mainContent.classList.toggle('expanded');
            }
            
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

        window.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebarCollapsed && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                if(mainContent) {
                    mainContent.classList.add('expanded');
                }
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });

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
