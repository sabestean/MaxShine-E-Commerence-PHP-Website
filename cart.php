<?php
// Start session
session_start();
include 'conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxShine | Shopping Cart</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8f8f8;
            color: #003366;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            margin-top: 10px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #003366;
            margin-top: 50px;
        }

        .checkout-wrapper {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* ===== PRODUCT ITEMS SECTION ===== */
        .cart-items {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .items-header {
            font-size: 18px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F5B027;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-count {
            background: #F5B027;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 14px;
        }

        .cart-item {
            display: flex;
            gap: 20px;
            padding: 20px 0;
            border-bottom: 1px solid #e8e8e8;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #F5B027 0%, #f0a000 100%);
            border-radius: 6px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 14px;
            text-align: center;
            padding: 10px;
            font-weight: 600;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 16px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
        }

        /* ML Badge Styles - Add this to your cart.php <style> section */

.item-name-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.ml-badge-inline {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: linear-gradient(135deg, #F5B027 0%, #d4920e 100%);
    color: #003366;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.3px;
    box-shadow: 0 2px 5px rgba(245, 176, 39, 0.3);
    white-space: nowrap;
}

.ml-badge-inline svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .item-name-wrapper {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .ml-badge-inline {
        font-size: 0.75rem;
        padding: 3px 10px;
    }
}

        .item-category {
            font-size: 13px;
            color: #666;
            margin-bottom: 12px;
            background: #f5f5f5;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
        }

        .item-price {
            font-size: 16px;
            font-weight: 700;
            color: #F5B027;
            margin-bottom: 15px;
        }

        .item-subtotal {
            font-size: 14px;
            color: #003366;
            margin-top: 10px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            background: #f5f5f5;
            border-radius: 4px;
            padding: 4px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #003366;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .quantity-btn:hover {
            background: #F5B027;
            color: #fff;
            border-radius: 3px;
        }

        .quantity-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            color: #003366;
            font-weight: 600;
        }

        .remove-btn {
            color: #e74c3c;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.2s;
            margin-top: 10px;
            display: inline-block;
        }

        .remove-btn:hover {
            color: #c0392b;
        }

        /* ===== ORDER SUMMARY SIDEBAR ===== */
        .order-summary {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            height: fit-content;
            position: sticky;
            top: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .summary-title {
            font-size: 18px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F5B027;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .summary-row.total {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #e8e8e8;
            font-size: 18px;
            font-weight: 700;
            color: #003366;
        }

        .summary-value {
            font-weight: 600;
            color: #F5B027;
        }

        .checkout-btn {
            width: 100%;
            padding: 14px;
            background: #F5B027;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 25px;
            transition: all 0.3s;
        }

        .checkout-btn:hover {
            background: #e6a31d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 176, 39, 0.3);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .clear-cart-btn {
            width: 100%;
            padding: 12px;
            background: transparent;
            color: #e74c3c;
            border: 2px solid #e74c3c;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: all 0.3s;
        }

        .clear-cart-btn:hover {
            background: #e74c3c;
            color: white;
        }

        /* Empty Cart State */
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 8px;
        }

        .empty-cart-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-cart h2 {
            color: #003366;
            margin-bottom: 10px;
        }

        .empty-cart p {
            color: #666;
            margin-bottom: 25px;
        }

        .continue-shopping-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #F5B027;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .continue-shopping-btn:hover {
            background: #e6a31d;
            transform: translateY(-2px);
        }

        /* ===== ADDRESS SECTION ===== */
        .address-section {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 22px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #F5B027;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: #F5B027;
            border-radius: 2px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-grid.full {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #003366;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea {
            padding: 12px 14px;
            border: 2px solid #e8e8e8;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            color: #003366;
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
            min-height: 80px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .checkout-wrapper {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .order-summary {
                position: static;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 24px;
            }

            .cart-item {
                flex-direction: column;
            }

            .item-image {
                width: 100%;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- NavBar Section -->
    <?php include 'navbar.php'; ?>

    <div class="container">
        <!-- Page Title -->
        <h1 class="page-title">Shopping Cart</h1>

        <!-- Main Checkout Grid -->
        <div id="cartContainer">
            <!-- Cart content will be loaded here by JavaScript -->
        </div>

        <!-- Customer Address Form (Hidden initially) -->
        <div class="address-section" id="addressSection" style="display: none;">
            <h2 class="section-title">Delivery Address</h2>

            <form method="POST" action="process_order.php" id="checkoutForm">
                <input type="hidden" name="cart_data" id="cartData">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="customer-name">Customer Name</label>
                        <input type="text" id="customer-name" name="customer_name" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-grid full">
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" name="address" required>
                    </div>
                </div>

                

                <button type="submit" class="checkout-btn">Place Order</button>
            </form>
        </div>
    </div>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <script>
      // Load and display cart
function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
    const container = document.getElementById('cartContainer');
    
    if (cart.length === 0) {
        container.innerHTML = `
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Add some products to your cart to get started!</p>
                <a href="kitchen.php" class="continue-shopping-btn">Continue Shopping</a>
            </div>
        `;
        document.getElementById('addressSection').style.display = 'none';
        return;
    }

    // Calculate totals
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 0 ? 200 : 0; // Rs. 200 flat shipping
    const tax = subtotal * 0.10; // 10% tax
    const total = subtotal + shipping + tax;

    // Build cart HTML
    let cartHTML = `
        <div class="checkout-wrapper">
            <div class="cart-items">
                <div class="items-header">
                    <span>Cart Items</span>
                    <span class="cart-count">${cart.length} ${cart.length === 1 ? 'item' : 'items'}</span>
                </div>
    `;

    cart.forEach((item, index) => {
        const itemSubtotal = item.price * item.quantity;
        
        // ✅ Create ML badge if available
        const mlBadge = item.ml && item.ml !== 'N/A' ? 
            `<span class="ml-badge-inline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                </svg>
                ${item.ml}
            </span>` : '';
        
        cartHTML += `
            <div class="cart-item" id="cart-item-${index}">
                <div class="item-image">${item.category.split(' ')[0]}</div>
                <div class="item-details">
                    <div class="item-name-wrapper">
                        <div class="item-name">${item.name}</div>
                        ${mlBadge}
                    </div>
                    <div class="item-category">${item.category}</div>
                    <div class="item-price">Rs. ${item.price.toLocaleString()}</div>
                    
                    <div class="quantity-control">
                        <button class="quantity-btn" onclick="decreaseQty(${index})" ${item.quantity <= 1 ? 'disabled' : ''}>−</button>
                        <input type="number" class="quantity-input" value="${item.quantity}" readonly>
                        <button class="quantity-btn" onclick="increaseQty(${index})">+</button>
                    </div>

                    <div class="item-subtotal">Subtotal: Rs. ${itemSubtotal.toLocaleString()}</div>

                    <a href="#" class="remove-btn" onclick="removeItem(${index}); return false;">Remove</a>
                </div>
            </div>
        `;
    });

    cartHTML += `
            </div>
            
            <div class="order-summary">
                <div class="summary-title">Order Summary</div>

                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span class="summary-value">Rs. ${subtotal.toLocaleString()}</span>
                </div>

                <div class="summary-row">
                    <span>Shipping:</span>
                    <span class="summary-value">Rs. ${shipping.toLocaleString()}</span>
                </div>

                <div class="summary-row">
                    <span>Tax (10%):</span>
                    <span class="summary-value">Rs. ${tax.toLocaleString()}</span>
                </div>

                <div class="summary-row total">
                    <span>Total:</span>
                    <span class="summary-value">Rs. ${total.toLocaleString()}</span>
                </div>

                <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
                <button class="clear-cart-btn" onclick="clearCart()">Clear Cart</button>
            </div>
        </div>
    `;

    container.innerHTML = cartHTML;
    document.getElementById('addressSection').style.display = 'none';
}

// Increase quantity
function increaseQty(index) {
    let cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
    if (cart[index]) {
        cart[index].quantity += 1;
        localStorage.setItem('cleanCartItems', JSON.stringify(cart));
        loadCart();
        updateCartCount();
        showNotification('Quantity updated', 'success');
    }
}

// Decrease quantity
function decreaseQty(index) {
    let cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
    if (cart[index] && cart[index].quantity > 1) {
        cart[index].quantity -= 1;
        localStorage.setItem('cleanCartItems', JSON.stringify(cart));
        loadCart();
        updateCartCount();
        showNotification('Quantity updated', 'success');
    }
}

// Remove item
function removeItem(index) {
    if (confirm('Remove this item from cart?')) {
        let cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
        const itemName = cart[index].name;
        cart.splice(index, 1);
        localStorage.setItem('cleanCartItems', JSON.stringify(cart));
        loadCart();
        updateCartCount();
        showNotification(`"${itemName}" removed from cart`, 'info');
    }
}

// Clear entire cart
function clearCart() {
    if (confirm('Are you sure you want to clear your entire cart?')) {
        localStorage.removeItem('cleanCartItems');
        loadCart();
        updateCartCount();
        showNotification('Cart cleared', 'info');
    }
}

// Proceed to checkout
function proceedToCheckout() {
    const addressSection = document.getElementById('addressSection');
    addressSection.style.display = 'block';
    addressSection.scrollIntoView({ behavior: 'smooth' });
    
    // Store cart data in hidden field (WITH ML)
    const cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
    document.getElementById('cartData').value = JSON.stringify(cart);
}

// Update cart count in navbar
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartBadge = document.querySelector('.cart-badge');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
    }
}

// Show notification
function showNotification(message, type = 'info') {
    let container = document.getElementById('notificationContainer');
    
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificationContainer';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
        `;
        document.body.appendChild(container);
    }
    
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1';
    const textColor = type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460';
    
    notification.style.cssText = `
        background: ${bgColor};
        color: ${textColor};
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    container.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Load cart on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCart();
    updateCartCount();
});
    </script>
</body>
</html>
<?php
$conn->close();
?>