<?php
// Start session
session_start();
include 'conn.php';

// Fetch products from database - 4 from each category
$featuredProducts = [];

// Kitchen products
$sql = "SELECT * FROM kitchen_products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts['kitchen'][] = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'category' => 'Kitchen',
            'description' => $row['product_description'],
            'ml' => $row['ml'] ?? 'N/A'
        ];
    }
}

// Home products
$sql = "SELECT * FROM home_products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts['home'][] = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'category' => 'Home',
            'description' => $row['product_description'],
            'ml' => $row['ml'] ?? 'N/A'
        ];
    }
}

// Bathroom products
$sql = "SELECT * FROM washroom_products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts['bathroom'][] = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'category' => 'Bathroom',
            'description' => $row['product_description'],
            'ml' => $row['ml'] ?? 'N/A'
        ];
    }
}

// Handwash products
$sql = "SELECT * FROM handwash_products ORDER BY created_at DESC LIMIT 4";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $featuredProducts['handwash'][] = [
            'id' => $row['id'],
            'name' => $row['product_name'],
            'price' => $row['price'],
            'image' => $row['image'],
            'category' => 'Hand Wash',
            'description' => $row['product_description'],
            'ml' => $row['ml'] ?? 'N/A'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MaxShine | Premium Cleaning Solutions</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    
    <style>
:root {
  --primary: #1a73e8;
  --primary-dark: #1557b0;
  --secondary: #34a853;
  --accent: #fbbc04;
  --accent-dark: #ea8600;
  --dark: #202124;
  --gray-50: #f8f9fa;
  --gray-100: #e8eaed;
  --gray-700: #5f6368;
  --white: #ffffff;
  --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
  --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
  --shadow-lg: 0 8px 32px rgba(0,0,0,0.16);
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  color: var(--dark);
  background-color: var(--white);
  line-height: 1.6;
  overflow-x: hidden;
}

/* ========== HERO SECTION ========== */
.hero-section {
  position: relative;
  min-height: 90vh;
  display: flex;
  align-items: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  overflow: hidden;
}

.hero-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
  opacity: 0.3;
}

.hero-content {
  position: relative;
  z-index: 2;
  animation: fadeInUp 0.8s ease-out;
}

.hero-title {
  font-family: 'Playfair Display', serif;
  font-size: 4rem;
  font-weight: 800;
  color: var(--white);
  margin-bottom: 1.5rem;
  line-height: 1.2;
  text-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.hero-subtitle {
  font-size: 1.5rem;
  color: rgba(255,255,255,0.95);
  margin-bottom: 2.5rem;
  font-weight: 300;
  max-width: 600px;
}

.hero-cta {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 2.5rem;
  background: var(--white);
  color: var(--primary);
  border-radius: 50px;
  font-weight: 600;
  text-decoration: none;
  transition: var(--transition);
  box-shadow: var(--shadow-lg);
}

.hero-cta:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 48px rgba(0,0,0,0.25);
  color: var(--primary-dark);
}

.hero-image {
  position: relative;
  z-index: 2;
  animation: floatAnimation 3s ease-in-out infinite;
}

.hero-image img {
  max-width: 100%;
  height: auto;
  filter: drop-shadow(0 20px 60px rgba(0,0,0,0.3));
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes floatAnimation {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-20px);
  }
}

/* ========== FEATURES SECTION ========== */
.features-section {
  padding: 5rem 0;
  background: var(--gray-50);
}

.feature-card {
  text-align: center;
  padding: 2.5rem 1.5rem;
  background: var(--white);
  border-radius: 16px;
  transition: var(--transition);
  border: 1px solid var(--gray-100);
  height: 100%;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-md);
}

.feature-icon {
  width: 80px;
  height: 80px;
  margin: 0 auto 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--primary), var(--primary-dark));
  border-radius: 20px;
  color: var(--white);
  font-size: 2rem;
}

.feature-title {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.75rem;
  color: var(--dark);
}

.feature-text {
  color: var(--gray-700);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* ========== CATEGORIES SECTION ========== */
.categories-section {
  padding: 5rem 0;
  background: var(--white);
}

.section-header {
  text-align: center;
  margin-bottom: 4rem;
}

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 1rem;
  position: relative;
  display: inline-block;
}

.section-title::after {
  content: '';
  position: absolute;
  bottom: -12px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary), var(--secondary));
  border-radius: 2px;
}

.section-subtitle {
  color: var(--gray-700);
  font-size: 1.1rem;
  max-width: 600px;
  margin: 1.5rem auto 0;
}

.category-card {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  transition: var(--transition);
  box-shadow: var(--shadow-sm);
  height: 280px;
  cursor: pointer;
}

.category-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.7) 100%);
  z-index: 1;
  transition: var(--transition);
}

.category-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-lg);
}

.category-card:hover::before {
  background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.5) 100%);
}

.category-image-bg {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.category-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 2rem;
  z-index: 2;
  color: var(--white);
}

.category-name {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.category-count {
  font-size: 0.95rem;
  opacity: 0.9;
}

/* ========== PRODUCTS SECTION ========== */
.products-section {
  padding: 5rem 0;
  background: var(--gray-50);
}

.product-tabs {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 3rem;
  flex-wrap: wrap;
}

.tab-btn {
  padding: 0.75rem 2rem;
  background: var(--white);
  border: 2px solid var(--gray-100);
  border-radius: 50px;
  font-weight: 600;
  color: var(--gray-700);
  cursor: pointer;
  transition: var(--transition);
}

.tab-btn:hover {
  border-color: var(--primary);
  color: var(--primary);
}

.tab-btn.active {
  background: var(--primary);
  border-color: var(--primary);
  color: var(--white);
}

.product-card {
  background: var(--white);
  border-radius: 16px;
  overflow: hidden;
  transition: var(--transition);
  box-shadow: var(--shadow-sm);
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-md);
}

.product-image {
  position: relative;
  width: 100%;
  height: 280px;
  overflow: hidden;
  background: var(--gray-50);
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition);
}

.product-image.no-image {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
}

.product-image.no-image::before {
  content: '📦';
  font-size: 4rem;
  opacity: 0.3;
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  padding: 0.4rem 1rem;
  background: var(--accent);
  color: var(--white);
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
}

.product-info {
  padding: 1.5rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.product-category {
  font-size: 0.8rem;
  color: var(--primary);
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
}

.product-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 0.75rem;
}

.product-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--primary);
  margin-bottom: 1rem;
}

.product-actions {
  display: flex;
  gap: 0.75rem;
  margin-top: auto;
}

.btn-view-details {
  flex: 1;
  padding: 0.75rem;
  background: var(--primary);
  color: var(--white);
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-view-details:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
  color: var(--white);
}

/* ========== CTA SECTION ========== */
.cta-section {
  padding: 5rem 0;
  background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
  color: var(--white);
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-section::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -10%;
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, rgba(255,255,255,0.1), transparent);
  border-radius: 50%;
}

.cta-title {
  font-family: 'Playfair Display', serif;
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
}

.cta-text {
  font-size: 1.25rem;
  margin-bottom: 2.5rem;
  opacity: 0.95;
}

.cta-buttons {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-cta-primary {
  padding: 1rem 2.5rem;
  background: var(--white);
  color: var(--primary);
  border: none;
  border-radius: 50px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: var(--shadow-md);
}

.btn-cta-primary:hover {
  transform: translateY(-4px);
  box-shadow: var(--shadow-lg);
}

.btn-cta-secondary {
  padding: 1rem 2.5rem;
  background: transparent;
  color: var(--white);
  border: 2px solid var(--white);
  border-radius: 50px;
  font-weight: 700;
  font-size: 1.1rem;
  cursor: pointer;
  transition: var(--transition);
}

.btn-cta-secondary:hover {
  background: var(--white);
  color: var(--primary);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 992px) {
  .hero-title {
    font-size: 3rem;
  }
  
  .hero-subtitle {
    font-size: 1.25rem;
  }
  
  .section-title {
    font-size: 2.5rem;
  }
  
  .cta-title {
    font-size: 2.5rem;
  }
}

@media (max-width: 768px) {
  .hero-section {
    min-height: 70vh;
    text-align: center;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .hero-subtitle {
    font-size: 1.1rem;
    margin: 0 auto 2rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .cta-title {
    font-size: 2rem;
  }
  
  .product-tabs {
    gap: 0.5rem;
  }
  
  .tab-btn {
    padding: 0.6rem 1.5rem;
    font-size: 0.9rem;
  }
}

/* ========== UTILITIES ========== */
.mb-5 {
  margin-bottom: 3rem;
}

.text-center {
  text-align: center;
}

.d-none {
  display: none;
}
    </style>
</head>
<body>
    <!-- NavBar Section -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">Welcome to MaxShine</h1>
                        <p class="hero-subtitle">Premium cleaning solutions for every corner of your home. Experience professional-grade products at affordable prices.</p>
                        <?php if ($isLoggedIn): ?>
                            <p class="text-white mb-4">Hello, <strong><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></strong>!</p>
                        <?php endif; ?>
                        <a href="#products" class="hero-cta">
                            <span>Shop Now</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center">
                        <img src="img/clean.jpg" alt="MaxShine Products" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="feature-title">Free Delivery</h3>
                        <p class="feature-text">On orders over Rs. 2000</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">100% Secure</h3>
                        <p class="feature-text">Safe & encrypted payments</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3 class="feature-title">Premium Quality</h3>
                        <p class="feature-text">Professional-grade products</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="feature-title">24/7 Support</h3>
                        <p class="feature-text">Always here to help</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Shop by Category</h2>
                <p class="section-subtitle">Discover our complete range of cleaning solutions tailored for every space in your home</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="category-card" onclick="location.href='kitchen.php'">
                        <img src="https://images.unsplash.com/photo-1556911220-bff31c812dba?w=800&q=80" alt="Kitchen" class="category-image-bg" onerror="this.style.background='linear-gradient(135deg, #667eea 0%, #764ba2 100%)'">
                        <div class="category-content">
                            <h3 class="category-name">Kitchen</h3>
                            <p class="category-count">15+ Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="category-card" onclick="location.href='home.php'">
                        <img src="https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800&q=80" alt="Home" class="category-image-bg" onerror="this.style.background='linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'">
                        <div class="category-content">
                            <h3 class="category-name">Home</h3>
                            <p class="category-count">20+ Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="category-card" onclick="location.href='bathroom.php'">
                        <img src="https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800&q=80" alt="Bathroom" class="category-image-bg" onerror="this.style.background='linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'">
                        <div class="category-content">
                            <h3 class="category-name">Bathroom</h3>
                            <p class="category-count">12+ Products</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="category-card" onclick="location.href='handwash.php'">
                        <img src="https://images.unsplash.com/photo-1584305574647-0cc949a2bb9f?w=800&q=80" alt="Hand Wash" class="category-image-bg" onerror="this.style.background='linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'">
                        <div class="category-content">
                            <h3 class="category-name">Hand Wash</h3>
                            <p class="category-count">8+ Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="products-section" id="products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked selection of our best-selling cleaning solutions</p>
            </div>

            <!-- Product Tabs -->
            <div class="product-tabs">
                <button class="tab-btn active" data-category="all">All Products</button>
                <button class="tab-btn" data-category="kitchen">Kitchen</button>
                <button class="tab-btn" data-category="home">Home</button>
                <button class="tab-btn" data-category="bathroom">Bathroom</button>
                <button class="tab-btn" data-category="handwash">Hand Wash</button>
            </div>

            <!-- Products Grid -->
            <div class="row g-4" id="productsGrid">
                <?php 
                $categoryPages = [
                    'kitchen' => 'kitchen.php',
                    'home' => 'home.php',
                    'bathroom' => 'bathroom.php',
                    'handwash' => 'handwash.php'
                ];
                
                foreach ($featuredProducts as $category => $products): 
                    if (!empty($products)):
                        foreach ($products as $product): 
                            // Try multiple possible paths for images
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
                ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 product-item" data-category="<?php echo $category; ?>">
                            <div class="product-card">
                                <div class="product-image <?php echo !$imageExists ? 'no-image' : ''; ?>">
                                    <?php if ($imageExists): ?>
                                        <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php endif; ?>
                                    <span class="product-badge">Featured</span>
                                </div>
                                <div class="product-info">
                                    <span class="product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                    <div class="product-price">Rs. <?php echo number_format($product['price']); ?></div>
                                    <div class="product-actions">
                                        <a href="<?php echo $categoryPages[$category]; ?>" class="btn-view-details">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php 
                        endforeach;
                    endif;
                endforeach; 
                ?>
            </div>

            <div class="text-center mt-5">
                <a href="kitchen.php" class="btn-cta-primary">View All Products</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Ready to Experience Clean Like Never Before?</h2>
            <p class="cta-text">Join thousands of satisfied customers who trust MaxShine for their cleaning needs</p>
            <div class="cta-buttons">
                <button class="btn-cta-primary" onclick="location.href='kitchen.php'">Start Shopping</button>
                <button class="btn-cta-secondary" onclick="location.href='about.php'">Learn More</button>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Product filtering
    const tabBtns = document.querySelectorAll('.tab-btn');
    const productItems = document.querySelectorAll('.product-item');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active tab
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter products
            productItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Smooth scroll for hero CTA
    const heroCTA = document.querySelector('.hero-cta');
    if (heroCTA) {
        heroCTA.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }

    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards and sections
    const animatedElements = document.querySelectorAll('.feature-card, .category-card, .product-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease-out';
        observer.observe(el);
    });

    // Add transition for product items
    productItems.forEach(item => {
        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
    </script>
</body>
</html>