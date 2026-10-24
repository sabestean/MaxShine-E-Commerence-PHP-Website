<?php
// navbar.php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Robust username detection
$username = '';
if (!empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
} elseif (!empty($_SESSION['user_name'])) {
    $username = $_SESSION['user_name'];
} elseif (!empty($_SESSION['user'])) {
    $username = $_SESSION['user'];
}

$isLoggedIn = !empty($_SESSION['user_id']) || $username !== '';
$role = $_SESSION['role'] ?? 'user';
?>

<style>
    :root {
      --primary-color: #004080;
      --text-dark: #333;
      --gold-color: #F5B027;
    }

    /* Navbar - Enhanced with Smooth Transitions */
    .navbar {
      padding: 0.9rem 0;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
      background: #fff;
      position: sticky;
      top: 0;
      z-index: 1030;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .navbar.scrolled {
      padding: 0.5rem 0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
    }

    .navbar-brand {
      font-weight: 700;
      color: var(--text-dark) !important;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      transition: transform 0.3s ease;
    }

    .navbar-brand:hover {
      transform: scale(1.05);
    }

    .logo-icon {
      width: 40px;
      height: 40px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--primary-color), #003366);
      color: #fff;
      transition: all 0.3s ease;
    }

    .navbar-brand:hover .logo-icon {
      transform: rotate(10deg);
      box-shadow: 0 4px 12px rgba(0, 64, 128, 0.3);
    }

    /* Enhanced Nav Links with Smooth Transitions */
    .nav-link {
      color: var(--text-dark) !important;
      font-weight: 500;
      position: relative;
      padding: 0.5rem 1rem !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      width: 0;
      height: 2px;
      background: linear-gradient(90deg, var(--primary-color), #003366);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      transform: translateX(-50%);
    }

    .nav-link:hover::before,
    .nav-link.active::before {
      width: 80%;
    }

    .nav-link:hover {
      color: var(--primary-color) !important;
      transform: translateY(-2px);
    }

    .nav-link.active {
      color: var(--primary-color) !important;
      font-weight: 600;
    }

    .nav-link i {
      transition: all 0.3s ease;
      margin-right: 0.3rem;
    }

    .nav-link:hover i {
      transform: scale(1.2);
    }

    /* Enhanced Action Buttons */
    .btn-link {
      position: relative;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      padding: 0.5rem;
      border-radius: 50%;
    }

    .btn-link:hover {
      color: var(--primary-color) !important;
      transform: scale(1.15);
      background: rgba(0, 64, 128, 0.1);
    }

    .btn-link i {
      font-size: 1.2rem;
      transition: all 0.3s ease;
    }

    .btn-link:hover i {
      filter: drop-shadow(0 2px 4px rgba(0, 64, 128, 0.3));
    }

    /* Cart Badge - Enhanced with Red Background and Initial 0 */
    .cart-icon-wrapper {
      position: relative;
      display: inline-block;
    }

    .cart-badge {
      position: absolute;
      top: -8px;
      right: -8px;
      background: linear-gradient(135deg, #e74c3c, #c0392b);
      color: white;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 11px;
      font-weight: 700;
      border: 2px solid white;
      box-shadow: 0 2px 8px rgba(231, 76, 60, 0.4);
      animation: pulse 2s infinite;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .cart-badge.has-items {
      animation: bounce 0.5s ease;
    }

    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.1);
      }
    }

    @keyframes bounce {
      0%, 100% {
        transform: scale(1);
      }
      50% {
        transform: scale(1.3);
      }
    }

    .cart-icon-wrapper:hover .cart-badge {
      transform: scale(1.2);
      box-shadow: 0 4px 12px rgba(231, 76, 60, 0.6);
    }

    /* User Dropdown Enhancement */
    .nav-item.dropdown {
      position: relative;
    }

    .nav .dropdown-toggle {
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 0.4rem 0.8rem;
      background: #fff;
      color: var(--text-dark) !important;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 0.3rem;
    }

    .nav .dropdown-toggle:hover {
      border-color: var(--primary-color);
      background: linear-gradient(135deg, rgba(0, 64, 128, 0.05), rgba(0, 64, 128, 0.02));
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 64, 128, 0.15);
    }

    .nav .dropdown-toggle::after {
      transition: transform 0.3s ease;
      margin-left: 0.3rem;
    }

    .dropdown.show .dropdown-toggle::after {
      transform: rotate(180deg);
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      left: auto;
      min-width: 200px;
      border: 1px solid #e0e0e0;
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
      padding: 0.5rem 0;
      margin-top: 0.5rem !important;
      background: #fff;
      z-index: 1050;
      display: none;
      overflow: hidden;
    }

    .dropdown-menu.show {
      display: block;
      animation: dropdownFadeIn 0.3s ease;
    }

    @keyframes dropdownFadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .dropdown-header {
      color: var(--primary-color);
      font-weight: 700;
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
      background: linear-gradient(135deg, rgba(0, 64, 128, 0.08), rgba(0, 64, 128, 0.03));
    }

    .dropdown-item {
      padding: 0.6rem 1.2rem;
      color: var(--text-dark);
      transition: all 0.3s ease;
      font-weight: 500;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .dropdown-item i {
      width: 20px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .dropdown-item::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 3px;
      background: var(--primary-color);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }

    .dropdown-item:hover {
      background: linear-gradient(90deg, rgba(0, 64, 128, 0.08), transparent);
      color: var(--primary-color);
      padding-left: 1.5rem;
    }

    .dropdown-item:hover::before {
      transform: scaleY(1);
    }

    .dropdown-divider {
      margin: 0.5rem 0;
      border-color: rgba(0, 64, 128, 0.1);
    }

    /* Search modal - Enhanced */
    .search-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      z-index: 1050;
      align-items: center;
      justify-content: center;
      animation: fadeIn 0.3s ease;
    }

    .search-modal.active { 
        display: flex; 
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .search-box {
      width: 90%;
      max-width: 700px;
      background: #fff;
      padding: 2rem;
      border-radius: 16px;
      position: relative;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      animation: slideUp 0.3s ease;
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

    .search-input {
      width: 100%;
      padding: 1rem 1.25rem;
      border: 2px solid var(--primary-color);
      border-radius: 50px;
      font-size: 1.05rem;
      outline: none;
      transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: #003366;
        box-shadow: 0 0 0 4px rgba(0, 64, 128, 0.1);
    }

    #searchResults {
        max-height: 400px;
        overflow-y: auto;
        margin-top: 20px;
    }

    #searchResults::-webkit-scrollbar {
        width: 8px;
    }

    #searchResults::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #searchResults::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }

    .search-result-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        background: #fff;
    }

    .search-result-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(0, 64, 128, 0.15);
        transform: translateY(-2px);
    }

    .search-result-image {
        width: 80px;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid #f0f0f0;
    }

    .search-result-info {
        flex: 1;
    }

    .search-result-name {
        font-weight: 600;
        font-size: 1.05rem;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .search-result-category {
        display: inline-block;
        background: linear-gradient(135deg, rgba(0, 64, 128, 0.1), rgba(0, 64, 128, 0.05));
        color: var(--primary-color);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-result-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .search-result-price {
        color: #f6b33a;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .search-loading {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .search-no-results {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .search-no-results i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .btn-close {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
    }

    /* Notification Toast */
    .cart-notification {
      position: fixed;
      top: 80px;
      right: 20px;
      background: linear-gradient(135deg, #2ecc71, #27ae60);
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(46, 204, 113, 0.4);
      z-index: 10000;
      animation: slideInRight 0.4s ease, slideOutRight 0.4s ease 2.6s;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }

    @keyframes slideInRight {
      from {
        transform: translateX(400px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOutRight {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }

    @media (max-width: 768px) {
      .navbar-nav { margin-top: 0.5rem; }
      .logo-icon { width: 36px; height: 36px; }
      
      .search-box {
          width: 95%;
          padding: 1.5rem;
      }
      
      .search-result-item {
          flex-direction: column;
          text-align: center;
      }
      
      .search-result-image {
          width: 100%;
          height: 150px;
      }

      .cart-notification {
        right: 10px;
        left: 10px;
        width: auto;
      }
    }
</style>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <div class="logo-icon"><i class="fas fa-shopping-cart"></i></div>
            MaxShine
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php" data-page="index.php"><i class="fas fa-home me-1"></i>HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php" data-page="about.php"><i class="fas fa-info-circle me-1"></i>ABOUT</a></li>
                <li class="nav-item"><a class="nav-link" href="kitchen.php" data-page="kitchen.php"><i class="fas fa-utensils me-1"></i>KITCHEN</a></li>
                <li class="nav-item"><a class="nav-link" href="bathroom.php" data-page="bathroom.php"><i class="fas fa-bath me-1"></i>BATHROOM</a></li>
                <li class="nav-item"><a class="nav-link" href="home.php" data-page="home.php"><i class="fas fa-couch me-1"></i>HOUSE</a></li>
                <li class="nav-item"><a class="nav-link" href="handwash.php" data-page="handwash.php"><i class="fas fa-hand-sparkles me-1"></i>HANDWASH</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php" data-page="contact.php"><i class="fas fa-envelope me-1"></i>CONTACT</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-dark p-0" id="searchBtn" title="Search"><i class="fas fa-search"></i></button>
                
                <!-- Enhanced Cart Icon with Badge -->
                <div class="cart-icon-wrapper">
                    <a href="cart.php" class="btn btn-link text-dark p-0" title="Cart">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <span class="cart-badge" id="cartBadge">0</span>
                </div>

                <!-- User menu -->
                <ul class="nav mb-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#"
                           id="userMenu"
                           role="button"
                           aria-expanded="false">
                            <i class="fas fa-user me-2"></i>
                            <?php echo $isLoggedIn ? htmlspecialchars($username, ENT_QUOTES, 'UTF-8') : 'Login'; ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <?php if ($isLoggedIn): ?>
                                <li><h6 class="dropdown-header mb-0"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></h6></li>
                                <?php if (strtolower($role) === 'admin'): ?>
                                    <li><a class="dropdown-item" href="admin/dashboard.php"><i class="fas fa-user-shield me-2"></i>Admin Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="login.php"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Search Modal -->
<div class="search-modal" id="searchModal" aria-hidden="true">
    <div class="search-box">
        <button type="button" class="btn-close" id="closeSearch" aria-label="Close"></button>
        <h5 class="mb-3"><i class="fas fa-search me-2"></i>Search Products</h5>
        <input id="searchInput" class="search-input" type="text" placeholder="Search for products (e.g., kitchen, soap, cleaner)..." autocomplete="off" />
        <div id="searchResults"></div>
    </div>
</div>

<script>
(function () {
    // Add scroll effect to navbar
    let lastScroll = 0;
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // Highlight current nav item
    const navLinks = document.querySelectorAll('.nav-link[data-page]');
    const current = window.location.pathname.split('/').pop() || 'index.php';
    navLinks.forEach(link => {
        const page = link.getAttribute('data-page');
        link.classList.toggle('active', page === current);
    });

    // Initialize Bootstrap dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof bootstrap !== 'undefined') {
            const dropdownElementList = document.querySelectorAll('.dropdown-toggle');
            const dropdownList = [...dropdownElementList].map(dropdownToggleEl => {
                return new bootstrap.Dropdown(dropdownToggleEl, {
                    autoClose: true,
                    boundary: 'viewport'
                });
            });
        }
    });

    // Handle dropdown clicks
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            const isOpen = dropdown.classList.contains('show');
            
            document.querySelectorAll('.dropdown.show').forEach(d => {
                if (d !== dropdown) {
                    d.classList.remove('show');
                    d.querySelector('.dropdown-menu')?.classList.remove('show');
                }
            });
            
            dropdown.classList.toggle('show');
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.classList.toggle('show');
            }
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown.show').forEach(dropdown => {
                dropdown.classList.remove('show');
                dropdown.querySelector('.dropdown-menu')?.classList.remove('show');
            });
        }
    });

    // Search modal handlers
    const searchBtn = document.getElementById('searchBtn');
    const searchModal = document.getElementById('searchModal');
    const closeSearch = document.getElementById('closeSearch');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    let searchTimeout;

    if (searchBtn && searchModal) {
        searchBtn.addEventListener('click', () => {
            searchModal.classList.add('active');
            searchModal.setAttribute('aria-hidden', 'false');
            searchInput.focus();
        });
    }

    if (closeSearch) {
        closeSearch.addEventListener('click', () => {
            searchModal.classList.remove('active');
            searchModal.setAttribute('aria-hidden', 'true');
            searchInput.value = '';
            searchResults.innerHTML = '';
        });
    }

    if (searchModal) {
        searchModal.addEventListener('click', (e) => {
            if (e.target === searchModal) {
                searchModal.classList.remove('active');
                searchModal.setAttribute('aria-hidden', 'true');
                searchInput.value = '';
                searchResults.innerHTML = '';
            }
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchModal.classList.contains('active')) {
            searchModal.classList.remove('active');
            searchModal.setAttribute('aria-hidden', 'true');
            searchInput.value = '';
            searchResults.innerHTML = '';
        }
    });

    // Real-time product search with AJAX
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchResults.innerHTML = '';
                return;
            }
            
            searchResults.innerHTML = '<div class="search-loading"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
            
            searchTimeout = setTimeout(() => {
                fetch(`search_products.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.products.length > 0) {
                            displaySearchResults(data.products);
                        } else {
                            searchResults.innerHTML = `
                                <div class="search-no-results">
                                    <i class="fas fa-search"></i>
                                    <p>No products found for "${query}"</p>
                                    <small>Try searching for: Kitchen, Home, Bathroom, Hand Wash</small>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = `
                            <div class="search-no-results">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p>Error searching products</p>
                            </div>
                        `;
                    });
            }, 300);
        });
    }

    function displaySearchResults(products) {
        let html = '';
        
        products.forEach(product => {
            const imagePath = product.image ? `uploads/${product.image}` : 'uploads/placeholder.png';
            const price = parseFloat(product.price).toLocaleString();
            const description = product.description.length > 100 
                ? product.description.substring(0, 100) + '...' 
                : product.description;
            
            html += `
                <a href="${product.category_page}?product=${product.id}" class="search-result-item">
                    <img src="${imagePath}" alt="${product.name}" class="search-result-image" onerror="this.src='uploads/placeholder.png'">
                    <div class="search-result-info">
                        <div class="search-result-name">${product.name}</div>
                        <span class="search-result-category">${product.category}</span>
                        <div class="search-result-description">${description}</div>
                        <div class="search-result-price">Rs. ${price}</div>
                    </div>
                </a>
            `;
        });
        
        searchResults.innerHTML = html;
    }

    // ✅ Enhanced Cart Counter Function with Animation
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('cleanCartItems')) || [];
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const cartBadge = document.getElementById('cartBadge');
        
        if (cartBadge) {
            const previousCount = parseInt(cartBadge.textContent) || 0;
            cartBadge.textContent = totalItems;
            
            // Add bounce animation when items are added
            if (totalItems > previousCount) {
                cartBadge.classList.add('has-items');
                setTimeout(() => cartBadge.classList.remove('has-items'), 500);
            }
        }
    }

    // ✅ Show notification when item is added to cart
    window.showCartNotification = function(productName) {
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.innerHTML = `
            <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
            <span>${productName} added to cart!</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    };

    // Initialize cart count on page load
    updateCartCount();

    // ✅ Listen for storage changes (when cart is updated)
    window.addEventListener('storage', function(e) {
        if (e.key === 'cleanCartItems') {
            updateCartCount();
        }
    });

    // ✅ Make updateCartCount globally accessible
    window.updateCartCount = updateCartCount;
})();
</script>