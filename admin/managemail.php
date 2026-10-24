<?php
session_start();
include '../conn.php';

// Fetch all newsletter subscribers in ASCENDING order by ID
$query = "SELECT * FROM newsletter ORDER BY id ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Mail | MaxShine</title>
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
        }

        .page-header p {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .mail-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
        }

        .mail-container::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        .mail-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
        }

        .table-header {
            font-size: 1.1rem;
            color: #003366;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header i {
            color: #F5B027;
        }

        .subscriber-count {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.08) 0%, rgba(245, 176, 39, 0.05) 100%);
            border-bottom: 2px solid #F5B027;
        }

        thead th {
            padding: 16px;
            text-align: left;
            color: #003366;
            font-weight: 700;
            font-size: 0.95rem;
        }

        tbody tr {
            border-bottom: 1px solid #e8ecf1;
            transition: 0.3s;
        }

        tbody tr:hover {
            background: rgba(245, 176, 39, 0.06);
        }

        tbody td {
            padding: 16px;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .mail-id {
            font-weight: 700;
            color: #003366;
        }

        .email {
            color: #F5B027;
            font-weight: 500;
        }

        .action-btns {
            display: flex;
            gap: 10px;
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
        }

        .btn-delete {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.35);
            color: white;
            transform: translateY(-2px);
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
        }

        .empty-state p {
            font-size: 1.1rem;
            color: #4a5568;
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-20px); }
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        tbody tr {
            animation: slideDown 0.3s ease-out;
        }
    </style>
</head>
<body>

    <?php include 'sidebar.php'; ?>

    <div class="main-content" id="mainContent">
        <div class="page-header">
            <i class="fas fa-envelope"></i>
            <div>
                <h1>Manage Mail</h1>
                <p>View and Delete Newsletter Subscribers</p>
            </div>
        </div>

        <div class="mail-container">
            <div class="table-header">
                <div class="table-header-left">
                    <i class="fas fa-list"></i>
                    Newsletter Subscribers
                </div>
                <span class="subscriber-count">
                    <i class="fas fa-users"></i> 
                    Total: <?php echo mysqli_num_rows($result); ?>
                </span>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
            <table id="mailTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td class="mail-id">#<?php echo str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                        <td class="email"><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-delete" onclick="deleteMail(<?php echo $row['id']; ?>)" title="Delete Subscriber">
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
                <p>No subscribers found</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function deleteMail(mailId) {
            if (confirm('Are you sure you want to delete this subscriber?')) {
                const row = document.getElementById('row-' + mailId);
                
                // Send AJAX request to delete from database
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_mail.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText.trim() == "success") {
                            // Animate row removal
                            row.style.animation = 'fadeOut 0.3s ease-out';
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is empty
                                const tableBody = document.getElementById('tableBody');
                                if (tableBody && tableBody.querySelectorAll('tr').length === 0) {
                                    location.reload(); // Reload to show empty state
                                } else {
                                    // Update subscriber count
                                    updateSubscriberCount();
                                }
                            }, 300);
                        } else {
                            alert('Error deleting subscriber. Please try again.');
                        }
                    }
                };
                
                xhr.send("mail_id=" + mailId);
            }
        }

        function updateSubscriberCount() {
            const tableBody = document.getElementById('tableBody');
            if (tableBody) {
                const count = tableBody.querySelectorAll('tr').length;
                const countElement = document.querySelector('.subscriber-count');
                if (countElement) {
                    countElement.innerHTML = '<i class="fas fa-users"></i> Total: ' + count;
                }
            }
        }
    </script>

</body>
</html>
<?php mysqli_close($conn); ?>