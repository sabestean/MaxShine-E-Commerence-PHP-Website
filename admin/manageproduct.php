<?php
session_start();
include '../conn.php';

// Function to get all products from all tables
function getAllProducts($conn) {
    $products = array();
    
    // Tables to fetch from
    $tables = [
        'home_products' => 'Home Product',
        'kitchen_products' => 'Kitchen Product',
        'washroom_products' => 'Washroom Product',
        'handwash_products' => 'Hand Wash Product'
    ];
    
    foreach ($tables as $table => $categoryName) {
        $sql = "SELECT *, '$table' as source_table, '$categoryName' as category_display FROM $table ORDER BY created_at DESC";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
        }
    }
    
    // Sort by created_at descending
    usort($products, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    return $products;
}

$allProducts = getAllProducts($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - CleanCart Admin</title>
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

        .filters-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
        }

        .filter-title {
            font-size: 1.1rem;
            color: #003366;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-title i {
            color: #F5B027;
        }

        .category-filter {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .filter-btn {
            padding: 10px 16px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            background: white;
            color: #003366;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .filter-btn:hover {
            border-color: #F5B027;
            background: #fffef9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 176, 39, 0.15);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #F5B027 0%, #ffc966 100%);
            border-color: #F5B027;
            color: #003366;
            box-shadow: 0 4px 16px rgba(245, 176, 39, 0.3);
            transform: translateY(-2px);
        }

        .products-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
            overflow-x: auto;
            max-height: 70vh;
            overflow-y: auto;
        }

        .products-container::-webkit-scrollbar {
            width: 10px;
        }

        .products-container::-webkit-scrollbar-track {
            background: #f5f7fa;
            border-radius: 10px;
        }

        .products-container::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #003366 0%, #F5B027 100%);
            border-radius: 10px;
            border: 2px solid #f5f7fa;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .products-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #F5B027 0%, #003366 100%);
            box-shadow: 0 0 12px rgba(245, 176, 39, 0.4);
        }

        .products-container {
            scrollbar-color: #003366 #f5f7fa;
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
        }

        .table-header i {
            color: #F5B027;
        }

        .product-count {
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

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e8ecf1;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
        }

        tbody tr:hover .product-image {
            border-color: #F5B027;
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(245, 176, 39, 0.3);
        }

        .product-name {
            font-weight: 600;
            color: #003366;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .product-description {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: #6b7280;
            font-size: 0.9rem;
        }

        .category-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.15) 0%, rgba(245, 176, 39, 0.08) 100%);
            color: #003366;
            border: 1px solid rgba(245, 176, 39, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            white-space: nowrap;
        }

        tbody tr:hover .category-badge {
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.25) 0%, rgba(245, 176, 39, 0.15) 100%);
            border-color: #F5B027;
            transform: scale(1.05);
        }

        .price {
            color: #F5B027;
            font-weight: 700;
            font-size: 1.05rem;
        }

        .ml-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.15) 0%, rgba(0, 51, 102, 0.08) 100%);
            color: #003366;
            border: 1px solid rgba(0, 51, 102, 0.2);
            white-space: nowrap;
        }

        tbody tr:hover .ml-badge {
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.25) 0%, rgba(0, 51, 102, 0.15) 100%);
            border-color: #003366;
            transform: scale(1.05);
        }

        .datetime {
            font-weight: 500;
            color: #003366;
            white-space: nowrap;
            font-size: 0.9rem;
        }

        .datetime i {
            color: #F5B027;
            margin-right: 5px;
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

        .btn-edit {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.35) 0%, rgba(59, 130, 246, 0.2) 100%);
            border-color: #3b82f6;
            transform: translateY(-2px) scale(1.08);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
            color: #fff;
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

        /* Edit Modal */
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
            max-width: 600px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #003366;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #F5B027;
            box-shadow: 0 0 0 3px rgba(245, 176, 39, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
        }

        .btn-secondary {
            background: #e8ecf1;
            color: #003366;
        }

        .btn-secondary:hover {
            background: #d1d5db;
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

            .category-filter {
                grid-template-columns: repeat(2, 1fr);
            }

            .products-container {
                padding: 15px;
            }

            table {
                min-width: 1000px;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
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
            <i class="fas fa-boxes-stacked"></i>
            <div style="text-align: left;">
                <h1>Manage Products</h1>
                <p>View, edit, and delete your CleanCart products</p>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Category Filters -->
        <div class="filters-container">
            <div class="filter-title">
                <i class="fas fa-filter"></i>
                Filter by Category
            </div>
            <div class="category-filter">
                <button class="filter-btn active" onclick="filterProducts('all')">
                    <i class="fas fa-th"></i>
                    All Products
                </button>
                <button class="filter-btn" onclick="filterProducts('home_products')">
                    <i class="fas fa-home"></i>
                    Home
                </button>
                <button class="filter-btn" onclick="filterProducts('kitchen_products')">
                    <i class="fas fa-utensils"></i>
                    Kitchen
                </button>
                <button class="filter-btn" onclick="filterProducts('washroom_products')">
                    <i class="fas fa-bath"></i>
                    Washroom
                </button>
                <button class="filter-btn" onclick="filterProducts('handwash_products')">
                    <i class="fas fa-hands-praying"></i>
                    Hand Wash
                </button>
            </div>
        </div>

        <!-- Products Table -->
        <div class="products-container">
            <div class="table-header">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-list"></i>
                    <span>Product Inventory</span>
                </div>
                <span class="product-count"><?php echo count($allProducts); ?> Total</span>
            </div>

            <?php if (count($allProducts) > 0): ?>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Price (PKR)</th>
                        <th>Volume (ML)</th>
                        <th>Date/Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($allProducts as $product): 
                        $datetime = date('d M Y, h:i A', strtotime($product['created_at']));
                        $imagePath = '../uploads/' . $product['image'];
                        $ml = isset($product['ml']) && !empty($product['ml']) ? $product['ml'] : 'N/A';
                    ?>
                    <tr data-category="<?php echo $product['source_table']; ?>" id="row-<?php echo $product['id']; ?>-<?php echo $product['source_table']; ?>">
                        <td>
                            <img src="<?php echo file_exists($imagePath) ? $imagePath : '../uploads/placeholder.png'; ?>" 
                                 alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                 class="product-image"
                                 onclick="viewImage('<?php echo $imagePath; ?>')">
                        </td>
                        <td class="product-name" title="<?php echo htmlspecialchars($product['product_name']); ?>">
                            <?php echo htmlspecialchars($product['product_name']); ?>
                        </td>
                        <td><span class="category-badge"><?php echo htmlspecialchars($product['category_display']); ?></span></td>
                        <td class="product-description" title="<?php echo htmlspecialchars($product['product_description']); ?>">
                            <?php echo htmlspecialchars($product['product_description']); ?>
                        </td>
                        <td class="price">Rs. <?php echo number_format($product['price']); ?></td>
                        <td><span class="ml-badge"><?php echo htmlspecialchars($ml); ?></span></td>
                        <td class="datetime">
                            <i class="far fa-clock"></i>
                            <?php echo $datetime; ?>
                        </td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-icon btn-edit" 
                                        onclick="editProduct(<?php echo $product['id']; ?>, '<?php echo $product['source_table']; ?>')" 
                                        title="Edit Product">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-icon btn-delete" 
                                        onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo $product['source_table']; ?>')" 
                                        title="Delete Product">
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
                <p>No products found</p>
            </div>
            <?php endif; ?>

            <div id="emptyState" class="empty-state" style="display: none;">
                <i class="fas fa-inbox"></i>
                <p>No products found in this category</p>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Edit Product</h2>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" id="editProductId" name="productId">
                <input type="hidden" id="editProductTable" name="productTable">
                
                <div class="form-group">
                    <label for="editProductName">Product Name</label>
                    <input type="text" id="editProductName" name="productName" required>
                </div>
                
                <div class="form-group">
                    <label for="editProductDescription">Description</label>
                    <textarea id="editProductDescription" name="productDescription" required></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="editProductPrice">Price (PKR)</label>
                        <input type="number" id="editProductPrice" name="productPrice" min="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editProductML">Volume (ML)</label>
                        <input type="text" id="editProductML" name="productML" placeholder="e.g., 200ml, 500ml" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="editProductImage">Product Image (Optional)</label>
                    <div class="current-image" id="currentImageContainer">
                        <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 10px;">Current Image:</p>
                        <img id="currentImage" src="" alt="Current Product" style="max-width: 150px; border-radius: 8px; border: 2px solid #e8ecf1;">
                    </div>
                    <input type="file" id="editProductImage" name="productImage" accept="image/*" style="margin-top: 10px;">
                    <p style="color: #6b7280; font-size: 0.85rem; margin-top: 5px;">Leave empty to keep current image. Max 10MB.</p>
                    <div id="newImagePreview" style="margin-top: 10px;"></div>
                </div>
                
                <div class="modal-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Product filtering functionality
        function filterProducts(category) {
            const tableBody = document.getElementById('tableBody');
            const emptyState = document.getElementById('emptyState');
            const table = document.getElementById('productsTable');
            const rows = tableBody.querySelectorAll('tr');
            const filterBtns = document.querySelectorAll('.filter-btn');

            // Update active button
            filterBtns.forEach(btn => btn.classList.remove('active'));
            event.target.closest('.filter-btn').classList.add('active');

            let visibleCount = 0;

            rows.forEach(row => {
                if (category === 'all' || row.getAttribute('data-category') === category) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                table.style.display = 'none';
                emptyState.style.display = 'block';
            } else {
                table.style.display = 'table';
                emptyState.style.display = 'none';
            }

            updateProductCount();
        }

        // Update product count
        function updateProductCount() {
            const rows = document.querySelectorAll('#tableBody tr[style="display: none;"], #tableBody tr:not([style])');
            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
            const countElement = document.querySelector('.product-count');
            if (countElement) {
                countElement.textContent = visibleRows.length + ' Total';
            }
        }

        // View image in new tab
        function viewImage(imagePath) {
            window.open(imagePath, '_blank');
        }

        // Edit product functionality
        function editProduct(productId, productTable) {
            // Fetch product data via AJAX
            fetch(`get_product.php?id=${productId}&table=${productTable}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('editProductId').value = productId;
                        document.getElementById('editProductTable').value = productTable;
                        document.getElementById('editProductName').value = data.product.product_name;
                        document.getElementById('editProductDescription').value = data.product.product_description;
                        document.getElementById('editProductPrice').value = data.product.price;
                         // Set ML value
                          const mlValue = data.product.ml || '';
                         document.getElementById('editProductML').value = mlValue;
                        
                        // Display current image
                        const currentImage = document.getElementById('currentImage');
                        const imagePath = '../uploads/' + data.product.image;
                        currentImage.src = imagePath;
                        currentImage.onerror = function() {
                            this.src = '../uploads/placeholder.png';
                        };
                        
                        // Clear file input and preview
                        document.getElementById('editProductImage').value = '';
                        document.getElementById('newImagePreview').innerHTML = '';
                        
                        document.getElementById('editModal').classList.add('show');
                    } else {
                        showAlert('error', 'Error loading product data.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Error loading product data.');
                });
        }

        // Image preview for edit modal
        document.getElementById('editProductImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('newImagePreview');
            
            if (file) {
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showAlert('error', 'Image size must be less than 10MB.');
                    this.value = '';
                    previewContainer.innerHTML = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showAlert('error', 'Only JPG, PNG, and GIF images are allowed.');
                    this.value = '';
                    previewContainer.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    previewContainer.innerHTML = `
                        <p style="color: #003366; font-weight: 600; margin-bottom: 5px;">New Image Preview:</p>
                        <img src="${event.target.result}" alt="New Preview" style="max-width: 150px; border-radius: 8px; border: 2px solid #F5B027;">
                    `;
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.innerHTML = '';
            }
        });

        // Close edit modal
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // Handle edit form submission
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const updateBtn = document.getElementById('updateBtn');
            updateBtn.disabled = true;
            updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            
            // Create FormData from form
            const formData = new FormData(this);
            
            // Explicitly add the image file if selected
            const imageInput = document.getElementById('editProductImage');
            if (imageInput.files.length > 0) {
                formData.set('productImage', imageInput.files[0]);
            }
            
            try {
                const response = await fetch('update_product.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('success', result.message);
                    closeEditModal();
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('error', result.message);
                    updateBtn.disabled = false;
                    updateBtn.innerHTML = '<i class="fas fa-save"></i> Update Product';
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'Error updating product: ' + error.message);
                updateBtn.disabled = false;
                updateBtn.innerHTML = '<i class="fas fa-save"></i> Update Product';
            }
        });

        // Delete product functionality
        function deleteProduct(productId, productTable) {
            if (confirm('Are you sure you want to delete this product?')) {
                const row = document.getElementById(`row-${productId}-${productTable}`);
                
                row.style.animation = 'fadeOut 0.3s ease-out';
                
                fetch('delete_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${productId}&table=${productTable}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            row.remove();
                            
                            const tableBody = document.getElementById('tableBody');
                            if (tableBody.querySelectorAll('tr').length === 0) {
                                document.getElementById('productsTable').style.display = 'none';
                                document.getElementById('emptyState').style.display = 'block';
                            }
                            
                            updateProductCount();
                            showAlert('success', 'Product deleted successfully!');
                        }, 300);
                    } else {
                        showAlert('error', 'Error: ' + data.message);
                        row.style.animation = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'An error occurred while deleting product.');
                    row.style.animation = '';
                });
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
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
</body>
</html>
<?php
$conn->close();
?>