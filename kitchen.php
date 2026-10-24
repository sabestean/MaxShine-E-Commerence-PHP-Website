<?php
// Start session
session_start();
include 'conn.php';

// Fetch all kitchen products from database
$sql = "SELECT * FROM kitchen_products ORDER BY created_at DESC";
$result = $conn->query($sql);

// Store products in array
$kitchenProducts = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $kitchenProducts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">   
    <title>MaxShine | Kitchen Cleaning Products</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }

        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
            margin-top: -40px;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .page-header h1 {
            color: #003366;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .product-count-badge {
            display: inline-block;
            background: linear-gradient(135deg, #F5B027 0%, #d4920e 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 15px;
        }

        /* Products Grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        /* Product Card */
        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 51, 102, 0.15);
        }

        .product-image-container {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .product-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image-container img {
            transform: scale(1.1);
        }

        .product-image-container.no-image::before {
            content: '🧼';
            font-size: 4rem;
            opacity: 0.3;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #F5B027;
            color: #003366;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .ml-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .product-content {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-name {
            color: #003366;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .view-details-text {
            color: #F5B027;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 15px;
            border-top: 2px solid #f0f0f0;
        }

        .product-price {
            color: #003366;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .price-label {
            font-size: 0.8rem;
            color: #999;
            display: block;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            animation: fadeIn 0.3s ease;
            overflow-y: auto;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background-color: white;
            border-radius: 15px;
            width: 90%;
            max-width: 900px;
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
            padding: 20px 30px;
            border-bottom: 2px solid #F5B027;
            background: linear-gradient(135deg, rgba(0, 51, 102, 0.05) 0%, rgba(245, 176, 39, 0.05) 100%);
        }

        .modal-header h2 {
            color: #003366;
            font-size: 1.8rem;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #666;
            transition: all 0.3s;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close-btn:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .modal-image-section {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-product-image {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-image-section.no-image {
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            border-radius: 12px;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            opacity: 0.3;
        }

        .modal-details-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-product-name {
            color: #003366;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .modal-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .modal-badge {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .modal-price-section {
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.1) 0%, rgba(245, 176, 39, 0.05) 100%);
            padding: 20px;
            border-radius: 12px;
            border: 2px solid rgba(245, 176, 39, 0.3);
        }

        .modal-price-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .modal-price {
            color: #003366;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .modal-description-section {
            flex-grow: 1;
        }

        .modal-description-title {
            color: #003366;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.8;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            background: #f8f9fa;
            padding: 10px 20px;
            border-radius: 10px;
        }

        .quantity-label {
            font-weight: 600;
            color: #003366;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: #003366;
            color: white;
            font-size: 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #F5B027;
            color: #003366;
            transform: scale(1.1);
        }

        .quantity-btn:active {
            transform: scale(0.95);
        }

        .quantity-value {
            font-size: 1.3rem;
            font-weight: bold;
            color: #003366;
            min-width: 40px;
            text-align: center;
        }

        .add-to-cart-btn {
            background: linear-gradient(135deg, #F5B027 0%, #d4920e 100%);
            color: #003366;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-grow: 1;
            justify-content: center;
        }

        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: #F5B027;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
        }

        .add-to-cart-btn:active {
            transform: translateY(0);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .empty-state-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h2 {
            color: #003366;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .modal-body {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .modal-footer {
                flex-direction: column;
            }

            .quantity-selector {
                width: 100%;
                justify-content: space-between;
            }

            .add-to-cart-btn {
                width: 100%;
            }

            .modal-product-name {
                font-size: 1.5rem;
            }

            .modal-price {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Loading animation */
        @keyframes fadeInCard {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-card {
            animation: fadeInCard 0.5s ease forwards;
        }

        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>

    <!-- NavBar Section -->  
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Premium Kitchen Cleaning Products</h1>
            <p>Discover our range of high-quality cleaning solutions for your kitchen</p>
            <span class="product-count-badge">
                <?php echo count($kitchenProducts); ?> Products Available
            </span>
        </div>

        <?php if (count($kitchenProducts) > 0): ?>
        <!-- Products Grid -->
        <div class="products-grid" id="productsGrid">
            <?php foreach ($kitchenProducts as $index => $product): 
                // Try multiple possible paths
                $possiblePaths = [
                    'admin/uploads/' . $product['image'],
                    'uploads/' . $product['image'],
                    '../uploads/' . $product['image']
                ];
                
                $imagePath = '';
                $imageExists = false;
                
                foreach ($possiblePaths as $path) {
                    if (!empty($product['image']) && file_exists($path)) {
                        $imagePath = $path;
                        $imageExists = true;
                        break;
                    }
                }
                
                $ml = isset($product['ml']) && !empty($product['ml']) ? $product['ml'] : 'N/A';
            ?>
            <div class="product-card" onclick="openProductModal(<?php echo htmlspecialchars(json_encode($product)); ?>, '<?php echo $imageExists ? htmlspecialchars($imagePath) : ''; ?>', <?php echo $imageExists ? 'true' : 'false'; ?>)">
                <div class="product-image-container <?php echo !$imageExists ? 'no-image' : ''; ?>">
                    <?php if ($imageExists): ?>
                        <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                    <?php else: ?>
                        <div style="font-size: 4rem; opacity: 0.3;">🧼</div>
                    <?php endif; ?>
                    
                    <?php if ($ml !== 'N/A'): ?>
                        <span class="ml-badge">
                            <i class="fas fa-flask"></i>
                            <?php echo htmlspecialchars($ml); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($index < 3): ?>
                        <span class="product-badge">
                            <?php 
                                $badges = ['New', 'Popular', 'Best Seller'];
                                echo $badges[$index % 3];
                            ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <div class="product-content">
                    <h3 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p class="product-description">
                        <?php echo htmlspecialchars($product['product_description']); ?>
                    </p>
                    <div class="view-details-text">
                        <i class="fas fa-info-circle"></i>
                        Click for more details
                    </div>
                    <div class="product-footer">
                        <div>
                            <span class="price-label">Price</span>
                            <div class="product-price">Rs. <?php echo number_format($product['price']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <h2>No Products Available</h2>
            <p>We're currently updating our kitchen products inventory. Please check back soon!</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-box-open"></i> Product Details</h2>
                <button class="close-btn" onclick="closeProductModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-image-section" id="modalImageSection">
                    <img id="modalProductImage" class="modal-product-image" src="" alt="Product Image">
                </div>
                <div class="modal-details-section">
                    <h3 class="modal-product-name" id="modalProductName"></h3>
                    <div class="modal-badges" id="modalBadges"></div>
                    <div class="modal-price-section">
                        <div class="modal-price-label">Price</div>
                        <div class="modal-price" id="modalProductPrice"></div>
                    </div>
                    <div class="modal-description-section">
                        <div class="modal-description-title">
                            <i class="fas fa-align-left"></i>
                            Description
                        </div>
                        <p class="modal-description" id="modalProductDescription"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="quantity-selector">
                    <span class="quantity-label">Quantity:</span>
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="decreaseQuantity()">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="quantity-value" id="quantityValue">1</span>
                        <button class="quantity-btn" onclick="increaseQuantity()">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <button class="add-to-cart-btn" onclick="addToCartFromModal()">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Add to Cart</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Footer Section -->    
    <?php include 'footer.php'; ?>

    <script>
        let currentProduct = null;
        let currentQuantity = 1;

        // Open product modal
        function openProductModal(product, imagePath, imageExists) {
            currentProduct = product;
            currentQuantity = 1;
            
            // Set product name
            document.getElementById('modalProductName').textContent = product.product_name;
            
            // Set product image
            const imageSection = document.getElementById('modalImageSection');
            const productImage = document.getElementById('modalProductImage');
            
            if (imageExists) {
                imageSection.classList.remove('no-image');
                productImage.src = imagePath;
                productImage.style.display = 'block';
            } else {
                imageSection.classList.add('no-image');
                imageSection.innerHTML = '<div style="font-size: 6rem; opacity: 0.3;">🧼</div>';
            }
            
            // Set badges
            const badgesContainer = document.getElementById('modalBadges');
            badgesContainer.innerHTML = '';
            
            // Add ML badge if available
            if (product.ml && product.ml !== 'N/A') {
                badgesContainer.innerHTML += `
                    <span class="modal-badge">
                        <i class="fas fa-flask"></i>
                        ${product.ml}
                    </span>
                `;
            }
            
            // Add category badge
            badgesContainer.innerHTML += `
                <span class="modal-badge">
                    <i class="fas fa-utensils"></i>
                    Kitchen Product
                </span>
            `;
            
            // Set price
            document.getElementById('modalProductPrice').textContent = 'Rs. ' + Number(product.price).toLocaleString();
            
            // Set description
            document.getElementById('modalProductDescription').textContent = product.product_description;
            
            // Reset quantity
            document.getElementById('quantityValue').textContent = '1';
            
            // Show modal
            document.getElementById('productModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close product modal
        function closeProductModal() {
            document.getElementById('productModal').classList.remove('show');
            document.body.style.overflow = 'auto';
            currentProduct = null;
            currentQuantity = 1;
        }

        // Increase quantity
        function increaseQuantity() {
            currentQuantity++;
            document.getElementById('quantityValue').textContent = currentQuantity;
        }

        // Decrease quantity
        function decreaseQuantity() {
            if (currentQuantity > 1) {
                currentQuantity--;
                document.getElementById('quantityValue').textContent = currentQuantity;
            }
        }

        // Add to cart from modal
        function addToCartFromModal() {
            if (!currentProduct) return;
            
            // Get existing cart from localStorage or create new
            let cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
            
            // Check if product already exists in cart
            const existingIndex = cart.findIndex(item => item.id === currentProduct.id);
            
            if (existingIndex > -1) {
                // Product exists, add to quantity
                cart[existingIndex].quantity += currentQuantity;
            } else {
                // Add new product to cart
                cart.push({
                    id: currentProduct.id,
                    name: currentProduct.product_name,
                    price: currentProduct.price,
                    quantity: currentQuantity,
                    category: 'Kitchen Product',
                    ml: currentProduct.ml || 'N/A'
                });
            }
            
            // Save updated cart to localStorage
            localStorage.setItem('cleanCartItems', JSON.stringify(cart));
            
            // Update cart count
            updateCartCount();
            
            // Show success message
            alert(`${currentQuantity} x "${currentProduct.product_name}" added to cart!`);
            
            // Close modal
            closeProductModal();
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            // Update cart badge if it exists
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = totalItems;
            }
        }

        // Close modal on outside click
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target === modal) {
                closeProductModal();
            }
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeProductModal();
            }
        });

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
</body>
</html>
<?php
$conn->close();
?>