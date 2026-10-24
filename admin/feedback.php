<?php
session_start();
include '../conn.php';

// Check if admin is logged in (optional - add your authentication check here)
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php');
//     exit;
// }

// Get sorting parameters
$orderBy = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$orderDir = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';

// Validate sort column to prevent SQL injection
$allowedColumns = ['id', 'first_name', 'last_name', 'email', 'phone', 'created_at'];
if (!in_array($orderBy, $allowedColumns)) {
    $orderBy = 'id';
}

// Validate sort direction
$orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';

// Fetch all feedback from database with sorting
$sql = "SELECT * FROM contacts ORDER BY $orderBy $orderDir";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Feedback - MaxShine Admin</title>
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

        .feedback-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
        }

        .feedback-container::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .feedback-container::-webkit-scrollbar-track {
            background: rgba(0, 51, 102, 0.05);
            border-radius: 10px;
        }

        .feedback-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .feedback-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #002244 0%, #d4920e 100%);
            box-shadow: 0 0 8px rgba(245, 176, 39, 0.3);
        }

        .feedback-container {
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
            justify-content: space-between;
            gap: 10px;
        }

        .table-header i {
            color: #F5B027;
        }

        .feedback-count {
            background: linear-gradient(135deg, #F5B027 0%, #d4920e 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
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

        .feedback-id {
            font-weight: 700;
            color: #003366;
        }

        .name {
            font-weight: 600;
            color: #003366;
        }

        .email {
            color: #F5B027;
            font-weight: 500;
        }

        .phone {
            color: #4a5568;
            font-weight: 500;
        }

        .datetime {
            font-weight: 500;
            color: #003366;
            white-space: nowrap;
        }

        .datetime i {
            color: #F5B027;
            margin-right: 5px;
        }

        .message {
            color: #6b7280;
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-style: italic;
            cursor: pointer;
            position: relative;
        }

        .message:hover {
            color: #003366;
        }

        .message-full {
            display: none;
            position: fixed;
            background: white;
            border: 3px solid #F5B027;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            min-width: 300px;
            max-width: 500px;
            white-space: normal;
            word-wrap: break-word;
            color: #4a5568;
            font-style: normal;
            line-height: 1.6;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .message-full strong {
            color: #003366;
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
            border-bottom: 2px solid #F5B027;
            padding-bottom: 8px;
        }

        .message:hover .message-full {
            display: block;
        }

        .message-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .message:hover ~ .message-overlay,
        .message-overlay:hover {
            display: block;
        }

        /* Sortable Headers */
        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 25px !important;
        }

        .sortable:hover {
            background: rgba(245, 176, 39, 0.1);
        }

        .sortable::after {
            content: '\f0dc';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 8px;
            opacity: 0.3;
            font-size: 0.8rem;
        }

        .sortable.asc::after {
            content: '\f0de';
            opacity: 1;
            color: #F5B027;
        }

        .sortable.desc::after {
            content: '\f0dd';
            opacity: 1;
            color: #F5B027;
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

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
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

        @media (max-width: 1024px) {
            .main-content {
                padding: 20px;
            }

            .feedback-container {
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

            .feedback-container {
                padding: 15px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            table {
                min-width: 900px;
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

            .message {
                max-width: 100px;
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
            <i class="fas fa-comments"></i>
            <div style="text-align: left;">
                <h1>Manage Feedback</h1>
                <p>View, manage, and delete customer feedback</p>
            </div>
        </div>

        <!-- Alert Messages -->
        <div id="alertContainer"></div>

        <!-- Feedback Table -->
        <div class="feedback-container">
            <div class="table-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-list"></i>
                    <span>Customer Feedback</span>
                </div>
                <span class="feedback-count">
                    <?php echo $result->num_rows; ?> Total
                </span>
            </div>

            <?php if ($result->num_rows > 0): ?>
            <table id="feedbackTable">
                <thead>
                    <tr>
                        <th class="sortable <?php echo ($orderBy == 'id') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('id')">ID</th>
                        <th class="sortable <?php echo ($orderBy == 'first_name') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('first_name')">First Name</th>
                        <th class="sortable <?php echo ($orderBy == 'last_name') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('last_name')">Last Name</th>
                        <th class="sortable <?php echo ($orderBy == 'email') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('email')">Email</th>
                        <th class="sortable <?php echo ($orderBy == 'phone') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('phone')">Phone</th>
                        <th class="sortable <?php echo ($orderBy == 'created_at') ? strtolower($orderDir) : ''; ?>" onclick="sortTable('created_at')">Date/Time</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php 
                    $counter = 1;
                    while($row = $result->fetch_assoc()): 
                        // Format the date and time
                        $datetime = date('d M Y, h:i A', strtotime($row['created_at']));
                    ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td class="feedback-id"><?php echo $row['id']; ?></td>
                        <td class="name"><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td class="name"><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td class="email"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td class="phone"><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td class="datetime">
                            <i class="far fa-clock"></i>
                            <?php echo $datetime; ?>
                        </td>
                        <td>
                            <div class="message" onclick="showMessage(<?php echo $row['id']; ?>)">
                                <?php echo htmlspecialchars(substr($row['message'], 0, 50)) . (strlen($row['message']) > 50 ? '...' : ''); ?>
                                <span class="message-full" id="message-<?php echo $row['id']; ?>">
                                    <strong><i class="fas fa-envelope-open-text"></i> Full Message:</strong>
                                    <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-delete" onclick="deleteFeedback(<?php echo $row['id']; ?>)" title="Delete Feedback">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>No feedback found</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Sort table function
        function sortTable(column) {
            const urlParams = new URLSearchParams(window.location.search);
            const currentSort = urlParams.get('sort');
            const currentDir = urlParams.get('dir');
            
            let newDir = 'ASC';
            
            // Toggle direction if clicking the same column
            if (currentSort === column && currentDir === 'ASC') {
                newDir = 'DESC';
            }
            
            // Redirect with new sorting parameters
            window.location.href = `feedback.php?sort=${column}&dir=${newDir}`;
        }

        // Show message in modal
        function showMessage(id) {
            event.stopPropagation();
            const messageElement = document.getElementById('message-' + id);
            
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'message-overlay';
            overlay.style.display = 'block';
            overlay.onclick = function() {
                messageElement.style.display = 'none';
                overlay.remove();
            };
            
            document.body.appendChild(overlay);
            messageElement.style.display = 'block';
        }

        // Delete feedback function
        function deleteFeedback(feedbackId) {
            if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
                const row = document.getElementById('row-' + feedbackId);
                
                // Add fade out animation
                row.style.animation = 'fadeOut 0.3s ease-out';
                
                // Send AJAX request to delete from database
                fetch('delete_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + feedbackId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row after animation
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is empty
                            const tableBody = document.getElementById('tableBody');
                            if (tableBody && tableBody.querySelectorAll('tr').length === 0) {
                                document.querySelector('.feedback-container').innerHTML = `
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <p>No feedback found</p>
                                    </div>
                                `;
                            }
                            
                            // Update feedback count
                            updateFeedbackCount();
                            
                            // Show success message
                            showAlert('success', 'Feedback deleted successfully!');
                        }, 300);
                    } else {
                        showAlert('error', 'Error: ' + data.message);
                        row.style.animation = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred while deleting feedback.');
                    row.style.animation = '';
                });
            }
        }

        // Update feedback count
        function updateFeedbackCount() {
            const tableBody = document.getElementById('tableBody');
            const countElement = document.querySelector('.feedback-count');
            if (tableBody && countElement) {
                const count = tableBody.querySelectorAll('tr').length;
                countElement.textContent = count + ' Total';
            }
        }

        // Show alert messages
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
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>