<?php
session_start();
include '../conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - MaxShine Admin</title>
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

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 51, 102, 0.08);
            border: 1px solid #e8ecf1;
        }

        .form-section {
            margin-bottom: 40px;
        }

        .form-section h2 {
            font-size: 1.3rem;
            color: #003366;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #F5B027;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h2 i {
            color: #F5B027;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #003366;
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="email"],
        .form-group input[type="file"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e8ecf1;
            border-radius: 8px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: #003366;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #a8c5dd;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #F5B027;
            background-color: #fffef9;
            box-shadow: 0 0 0 3px rgba(245, 176, 39, 0.1), inset 0 0 8px rgba(245, 176, 39, 0.05);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .category-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .category-card {
            position: relative;
            cursor: pointer;
        }

        .category-card input[type="radio"] {
            display: none;
        }

        .category-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 20px;
            border: 2px solid #e8ecf1;
            border-radius: 10px;
            background: white;
            transition: all 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
            font-weight: 600;
            color: #4a5568;
            font-size: 0.95rem;
        }

        .category-card input[type="radio"]:checked + .category-label {
            background: linear-gradient(135deg, rgba(245, 176, 39, 0.2) 0%, rgba(245, 176, 39, 0.08) 100%);
            border-color: #F5B027;
            color: #003366;
            box-shadow: 0 0 20px rgba(245, 176, 39, 0.15), inset 0 0 12px rgba(245, 176, 39, 0.08);
            transform: translateY(-2px);
        }

        .category-label:hover {
            border-color: #F5B027;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.1);
        }

        .category-label i {
            font-size: 1.3rem;
            color: #F5B027;
        }

        .category-card input[type="radio"]:checked + .category-label i {
            animation: iconPulse 0.6s ease-out;
        }

        @keyframes iconPulse {
            0% { transform: scale(0.8); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .image-upload-area {
            border: 3px dashed #e8ecf1;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: #f9fafb;
            cursor: pointer;
        }

        .image-upload-area:hover {
            border-color: #F5B027;
            background: #fffef9;
            box-shadow: 0 0 15px rgba(245, 176, 39, 0.1);
        }

        .image-upload-area input[type="file"] {
            display: none;
        }

        .image-upload-area i {
            font-size: 2.5rem;
            color: #F5B027;
            margin-bottom: 10px;
        }

        .image-upload-area p {
            color: #4a5568;
            font-weight: 500;
            margin: 0;
        }

        .image-upload-area .help-text {
            font-size: 0.85rem;
            color: #a8c5dd;
            margin-top: 8px;
        }

        .image-preview {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
        }

        .preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
            background: #f0f4f8;
            border: 2px solid #F5B027;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e8ecf1;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #003366 0%, #004d99 100%);
            color: white;
            border: 2px solid #003366;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.3);
            background: linear-gradient(135deg, #004d99 0%, #006bb3 100%);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: white;
            color: #003366;
            border: 2px solid #e8ecf1;
        }

        .btn-secondary:hover {
            border-color: #003366;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.15);
            background: #f9fafb;
        }

        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 3px solid white;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .main-content.expanded {
                margin-left: 0;
            }

            .form-container {
                padding: 25px;
            }

            .page-header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }

            .page-header i {
                font-size: 2rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .category-options {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
                width: 100%;
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
            <i class="fas fa-plus-circle"></i>
            <div style="text-align: left;">
                <h1>Add New Product</h1>
                <p>Create and add a new product to your CleanCart store</p>
            </div>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Add Product Form -->
        <div class="form-container">
            <form id="addProductForm" enctype="multipart/form-data">
                <!-- Product Information Section -->
                <div class="form-section">
                    <h2><i class="fas fa-box"></i>Product Information</h2>

                    <div class="form-group">
                        <label for="productName">Product Name <span class="required">*</span></label>
                        <input type="text" id="productName" name="productName" placeholder="Enter product name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Product Description <span class="required">*</span></label>
                        <textarea id="description" name="description" placeholder="Write a detailed description about your product..." required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (PKR) <span class="required">*</span></label>
                            <input type="number" id="price" name="price" placeholder="0" step="1" min="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ml">Volume (ML) <span class="required">*</span></label>
                            <input type="text" id="ml" name="ml" placeholder="e.g., 200ml, 500ml, 1L" required>
                        </div>
                    </div>
                </div>

                <!-- Category Selection Section -->
                <div class="form-section">
                    <h2><i class="fas fa-tag"></i>Product Category</h2>

                    <div class="category-options">
                        <div class="category-card">
                            <input type="radio" id="home" name="category" value="home-product" required>
                            <label for="home" class="category-label">
                                <i class="fas fa-home"></i>
                                <span>Home Product</span>
                            </label>
                        </div>

                        <div class="category-card">
                            <input type="radio" id="kitchen" name="category" value="kitchen-product">
                            <label for="kitchen" class="category-label">
                                <i class="fas fa-utensils"></i>
                                <span>Kitchen Product</span>
                            </label>
                        </div>

                        <div class="category-card">
                            <input type="radio" id="washroom" name="category" value="washroom-product">
                            <label for="washroom" class="category-label">
                                <i class="fas fa-bath"></i>
                                <span>Washroom Product</span>
                            </label>
                        </div>

                        <div class="category-card">
                            <input type="radio" id="handwash" name="category" value="handwash-product">
                            <label for="handwash" class="category-label">
                                <i class="fas fa-hands-praying"></i>
                                <span>Hand Wash Product</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Section -->
                <div class="form-section">
                    <h2><i class="fas fa-image"></i>Product Image</h2>

                    <div class="image-upload-area" onclick="document.getElementById('productImage').click()">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <p>Click to upload or drag and drop</p>
                        <p class="help-text">PNG, JPG, GIF up to 10MB</p>
                        <input type="file" id="productImage" name="productImage" accept="image/*" required>
                    </div>

                    <div class="image-preview" id="imagePreview"></div>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-plus"></i>
                        Add Product
                    </button>
                    <button type="reset" class="btn btn-secondary" id="resetBtn">
                        <i class="fas fa-redo"></i>
                        Clear Form
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview functionality
        const imageInput = document.getElementById('productImage');
        const imagePreview = document.getElementById('imagePreview');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    showAlert('error', 'Image size must be less than 10MB.');
                    imageInput.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }

                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showAlert('error', 'Only JPG, PNG, and GIF images are allowed.');
                    imageInput.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.innerHTML = `
                        <div class="preview-item">
                            <img src="${event.target.result}" alt="Preview">
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.querySelector('.image-upload-area');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#F5B027';
            uploadArea.style.backgroundColor = '#fffef9';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#e8ecf1';
            uploadArea.style.backgroundColor = '#f9fafb';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#e8ecf1';
            uploadArea.style.backgroundColor = '#f9fafb';
            
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                const event = new Event('change', { bubbles: true });
                imageInput.dispatchEvent(event);
            }
        });

        // Form submission
        const form = document.getElementById('addProductForm');
        const submitBtn = document.getElementById('submitBtn');
        const resetBtn = document.getElementById('resetBtn');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Check if category is selected
            const categorySelected = document.querySelector('input[name="category"]:checked');
            if (!categorySelected) {
                showAlert('error', 'Please select a product category.');
                return;
            }

            // Check if image is selected
            if (!imageInput.files.length) {
                showAlert('error', 'Please select a product image.');
                return;
            }

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner"></div> Adding Product...';

            // Prepare form data
            const formData = new FormData(form);

            try {
                // Send AJAX request
                const response = await fetch('add_product.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Success message
                    showAlert('success', result.message);
                    
                    // Reset form
                    form.reset();
                    imagePreview.innerHTML = '';
                    
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Error message
                    showAlert('error', result.message);
                }

            } catch (error) {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while adding the product. Please try again.');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Product';
            }
        });

        // Reset form handler
        resetBtn.addEventListener('click', function() {
            imagePreview.innerHTML = '';
        });

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