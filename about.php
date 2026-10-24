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
    <title>MaxShine | About Us</title>
    <link rel="stylesheet" href="css/about.css">
    <style>
        :root {
  --primary: #003366;
  --secondary: #f5f3f0;
  --accent: #F5B027;
  --text-dark: #1a1a1a;
  --text-light: #666666;
  --border-light: #e8e8e8;
  --white: #ffffff;
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
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  line-height: 1.6;
  color: var(--text-dark);
  background-color: var(--white);
  
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

/* Hero Section */
.hero {
  background: linear-gradient(135deg, var(--primary) 0%, #004d99 100%);
  color: var(--white);
  padding: 100px 0;
  text-align: center;

}

.hero-title {
  font-size: 3.5rem;
  font-weight: 700;
  margin-bottom: 15px;
  letter-spacing: -1px;
  text-balance: pretty;
}

.hero-subtitle {
  font-size: 1.3rem;
  font-weight: 300;
  opacity: 0.95;
}

/* Mission Section */
.mission {
  padding: 80px 0;
  background-color: var(--white);
}

.mission-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 60px;
  align-items: center;
}

.mission h2 {
  font-size: 2.5rem;
  margin-bottom: 25px;
  color: var(--primary);
  font-weight: 600;
}

.mission-text p {
  font-size: 1.1rem;
  color: var(--text-light);
  line-height: 1.8;
  margin-bottom: 15px;
}

.mission-highlight {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.stat {
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 25px 0;
  border-left: 3px solid var(--accent);
  padding-left: 20px;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary);
}

.stat-label {
  font-size: 0.95rem;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Vision & Values */
.vision-values {
  background-color: var(--secondary);
  padding: 80px 0;
}

.vision-values h2 {
  text-align: center;
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 60px;
  font-weight: 600;
}

.values-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 40px;
}

.value-card {
  background: var(--white);
  padding: 35px 25px;
  border-radius: 12px;
  text-align: center;
  border: 1px solid var(--border-light);
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.value-card:hover {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  transform: translateY(-5px);
  border-color: var(--accent);
}

.value-icon {
  font-size: 2.5rem;
  color: var(--accent);
  margin-bottom: 15px;
  font-weight: bold;
}

.value-card h3 {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 12px;
  font-weight: 600;
}

.value-card p {
  color: var(--text-light);
  line-height: 1.7;
  font-size: 0.95rem;
}

/* Team Section */
.team {
  padding: 80px 0;
  background-color: var(--white);
}

.team h2 {
  text-align: center;
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 15px;
  font-weight: 600;
}

.team-intro {
  text-align: center;
  font-size: 1.1rem;
  color: var(--text-light);
  margin-bottom: 50px;
}

.team-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 40px;
  max-width: 800px;
  margin: 0 auto;
}

.team-member {
  text-align: center;
  padding: 40px 30px;
  background-color: var(--secondary);
  border-radius: 12px;
  border: 1px solid var(--border-light);
  transition: all 0.3s ease;
}

.team-member:hover {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  transform: translateY(-5px);
  border-color: var(--accent);
}

.team-image {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin: 0 auto 20px;
  object-fit: cover;
  border: 4px solid var(--accent);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.team-member h3 {
  font-size: 1.3rem;
  color: var(--primary);
  margin-bottom: 5px;
  font-weight: 600;
}

.role {
  color: var(--accent);
  font-weight: 600;
  font-size: 0.95rem;
  margin-bottom: 12px;
}

.bio {
  color: var(--text-light);
  font-size: 0.9rem;
  line-height: 1.6;
}

/* Commitment Section */
.commitment {
  background-color: var(--secondary);
  padding: 80px 0;
}

.commitment h2 {
  text-align: center;
  font-size: 2.5rem;
  color: var(--primary);
  margin-bottom: 60px;
  font-weight: 600;
}

.commitment-items {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 40px;
}

.commitment-item {
  background: var(--white);
  padding: 30px;
  border-radius: 10px;
  border-top: 3px solid var(--accent);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.commitment-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.commitment-item h3 {
  font-size: 1.2rem;
  color: var(--primary);
  margin-bottom: 12px;
  font-weight: 600;
}

.commitment-item p {
  color: var(--text-light);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* CTA Section */
.cta {
  background: linear-gradient(135deg, var(--primary) 0%, #004d99 100%);
  color: var(--white);
  padding: 70px 0;
  text-align: center;
}

.cta h2 {
  font-size: 2.5rem;
  margin-bottom: 15px;
  font-weight: 600;
}

.cta p {
  font-size: 1.1rem;
  margin-bottom: 30px;
  opacity: 0.95;
}

.cta-btn {
  background-color: var(--accent);
  color: var(--primary);
  border: none;
  padding: 15px 45px;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.3s ease;
  letter-spacing: 0.5px;
}

.cta-btn:hover {
  background-color: #d99d1f;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(245, 176, 39, 0.4);
}

/* Responsive Design */
@media (max-width: 768px) {
  .hero-title {
    font-size: 2.2rem;
  }

  .hero-subtitle {
    font-size: 1rem;
  }

  .mission-content {
    grid-template-columns: 1fr;
    gap: 40px;
  }

  .mission h2,
  .vision-values h2,
  .commitment h2,
  .cta h2 {
    font-size: 2rem;
  }

  .hero {
    padding: 60px 0;
    margin-top: 80px;
  }

  .mission,
  .vision-values,
  .commitment,
  .cta,
  .team {
    padding: 50px 0;
  }

  .values-grid,
  .team-grid,
  .commitment-items {
    grid-template-columns: 1fr;
  }

  .stat-number {
    font-size: 1.5rem;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.8rem;
  }

  .mission h2,
  .vision-values h2,
  .commitment h2,
  .cta h2 {
    font-size: 1.6rem;
  }

  .value-card,
  .team-member,
  .commitment-item {
    padding: 20px;
  }

  .cta-btn {
    padding: 12px 30px;
    font-size: 0.9rem;
  }
}

    </style>
</head>
<body>
    <!-- NavBar Section -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Our Story</h1>
                <p class="hero-subtitle">Revolutionizing Online Shopping in Pakistan</p>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission">
        <div class="container">
            <div class="mission-content">
                <div class="mission-text">
                    <h2>Our Mission</h2>
                    <p>At MaxShine, we believe that online shopping should be simple, trustworthy, and accessible to everyone in Pakistan. We're committed to providing a seamless e-commerce experience with authentic products, transparent pricing, and exceptional customer service.</p>
                </div>
                <div class="mission-highlight">
                    <div class="stat">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">5 Years</span>
                        <span class="stat-label">Excellence</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Values Section -->
    <section class="vision-values">
        <div class="container">
            <h2>Why Choose CleanCart?</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">✓</div>
                    <h3>100% Authentic</h3>
                    <p>All products are genuine and sourced directly from authorized suppliers and trusted brands.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">✓</div>
                    <h3>Secure Payments</h3>
                    <p>Your payments are protected with industry-leading encryption and multiple payment options.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">✓</div>
                    <h3>Fast Delivery</h3>
                    <p>Quick and reliable shipping across Pakistan with real-time tracking on every order.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">✓</div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated customer service team is always ready to help with any questions or concerns.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="team">
        <div class="container">
            <h2>Meet Our Team</h2>
            <p class="team-intro">We're a passionate group of professionals dedicated to making online shopping better for Pakistan.</p>
            <div class="team-grid">
                <div class="team-member">
                    <img src="img/sabestean.png" alt="Sabestean" class="team-image">
                    <h3>Sabestean</h3>
                    <p class="role">CEO / Founder</p>
                    <p class="bio">Visionary entrepreneur with a passion for transforming Pakistan E-Commerce landscape. Sabestean founded MaxShine with the mission to make online shopping accessible, trustworthy, and convenient for every Pakistani household. </p>
                </div>
                <div class="team-member">
                    <img src="img/suleman.png" alt="Suleman" class="team-image">
                    <h3>Suleman</h3>
                    <p class="role">Technology Head</p>
                    <p class="bio">A skilled technology expert responsible for building and maintaining MaxShine robust digital infrastructure. Suleman specializes in creating secure, scalable, and user-friendly platforms that enhance the shopping experience. </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Commitment Section -->
    <section class="commitment">
        <div class="container">
            <h2>Our Commitment to You</h2>
            <div class="commitment-items">
                <div class="commitment-item">
                    <h3>Quality Assurance</h3>
                    <p>Every product undergoes strict quality checks before reaching your doorstep.</p>
                </div>
                <div class="commitment-item">
                    <h3>Customer Privacy</h3>
                    <p>Your personal information is protected with the highest security standards.</p>
                </div>
                <div class="commitment-item">
                    <h3>Fair Pricing</h3>
                    <p>Competitive prices without compromising on quality or authenticity.</p>
                </div>
                <div class="commitment-item">
                    <h3>Sustainability</h3>
                    <p>Eco-friendly packaging and responsible business practices.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Ready to Shop with Confidence?</h2>
            <p>Join thousands of satisfied customers exploring our carefully curated collection</p>
            <button class="cta-btn">Shop Now</button>
        </div>
    </section>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>
</body>
</html>