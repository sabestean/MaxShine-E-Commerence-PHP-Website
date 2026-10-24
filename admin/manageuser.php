<?php
session_start();
require_once '../conn.php';

// Optional: restrict access to admins only
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    // If you want admins only, uncomment the next two lines:
    // header('Location: /login.php');
    // exit;
}

// Fetch users from database INCLUDING password
$stmt = $conn->prepare("SELECT id, username, email, password FROM users ORDER BY id ASC");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - MaxShine Admin</title>
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

        .users-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
        }

        /* Custom scrollbar styling for users table */
        .users-container::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .users-container::-webkit-scrollbar-track {
            background: rgba(0, 51, 102, 0.05);
            border-radius: 10px;
        }

        .users-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .users-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #002244 0%, #d4920e 100%);
            box-shadow: 0 0 8px rgba(245, 176, 39, 0.3);
        }

        /* Firefox scrollbar styling */
        .users-container {
            scrollbar-color: #003366 rgba(0, 51, 102, 0.05);
            scrollbar-width: thin;
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

        .user-id {
            font-weight: 700;
            color: #003366;
        }

        .username {
            font-weight: 600;
            color: #003366;
        }

        .email {
            color: #F5B027;
            font-weight: 500;
        }

        .password-cell {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #6b7280;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .password-text {
            filter: blur(4px);
            transition: filter 0.3s ease;
            user-select: none;
        }

        .password-text.revealed {
            filter: blur(0);
            user-select: text;
        }

        .btn-toggle-password {
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.1) 0%, rgba(245, 176, 39, 0.05) 100%);
            border: 1px solid rgba(0, 51, 102, 0.2);
            color: #003366;
            padding: 4px 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-toggle-password:hover {
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.15) 0%, rgba(245, 176, 39, 0.1) 100%);
            border-color: #F5B027;
            transform: scale(1.05);
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
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            font-weight: 600;
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
            color: #fff;
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

        @media (max-width: 1024px) {
            .main-content {
                padding: 20px;
            }

            .users-container {
                overflow-x: auto;
            }

            table {
                font-size: 0.85rem;
            }

            thead th,
            tbody td {
                padding: 12px 10px;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .page-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }

            .users-container {
                padding: 15px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                min-width: 600px;
            }

            .action-btns {
                flex-direction: column;
                gap: 5px;
            }

            .btn-icon {
                width: 35px;
                height: 35px;
                font-size: 0.85rem;
            }

            .password-cell {
                max-width: 150px;
            }
        }
    </style>
</head>
<body>

     <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>

     <div class="main-content" id="mainContent">
        <div class="page-header">
            <i class="fas fa-users"></i>
            <div style="text-align: left;">
                <h1>Manage Users</h1>
                <p>View, manage, and delete CleanCart users</p>
            </div>
        </div>

        <div class="users-container">
            <div class="table-header">
                <i class="fas fa-list"></i>
                User Directory
            </div>

            <table id="usersTable" aria-live="polite">
                <thead>
                    <tr>
                        <th style="width: 90px">ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th style="width: 250px">Password</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (count($users) === 0): ?>
                        <tr id="noUsersRow">
                            <td colspan="5" style="padding:40px;text-align:center;color:#6b7280;">No users found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $row): ?>
                            <tr data-user-id="<?php echo (int)$row['id']; ?>">
                                <td class="user-id">#<?php echo str_pad((int)$row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                <td class="username"><?php echo htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="email"><?php echo htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <div class="password-cell">
                                        <span class="password-text" id="password-<?php echo (int)$row['id']; ?>">
                                            <?php echo htmlspecialchars($row['password'], ENT_QUOTES, 'UTF-8'); ?>
                                        </span>
                                        <button 
                                            class="btn-toggle-password" 
                                            onclick="togglePassword(<?php echo (int)$row['id']; ?>)"
                                            id="btn-<?php echo (int)$row['id']; ?>">
                                            <i class="fas fa-eye"></i> Show
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div id="emptyState" class="empty-state" style="display: <?= (count($users) === 0) ? 'block' : 'none' ?>;">
                <i class="fas fa-inbox"></i>
                <p>No users found</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(userId) {
            const passwordSpan = document.getElementById('password-' + userId);
            const btn = document.getElementById('btn-' + userId);
            
            if (passwordSpan.classList.contains('revealed')) {
                passwordSpan.classList.remove('revealed');
                btn.innerHTML = '<i class="fas fa-eye"></i> Show';
            } else {
                passwordSpan.classList.add('revealed');
                btn.innerHTML = '<i class="fas fa-eye-slash"></i> Hide';
            }
        }

    </script>
</body>
</html>