<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer - MaxShine</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            overflow-x: hidden;
            width: 100%;
        }

        html {
            overflow-x: hidden;
        }

        .footer-section {
            background: linear-gradient(135deg, #1a2847 0%, #1f2937 100%);
            color: #e5e7eb;
            padding: 80px 20px 40px;
            margin-top: 80px;
            width: 100%;
            overflow-x: hidden;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .newsletter-section {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 50px 30px;
            margin-bottom: 60px;
            text-align: center;
        }

        .newsletter-section h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #ffffff;
        }

        .newsletter-section p {
            font-size: 16px;
            color: #d1d5db;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
            max-width: 450px;
            margin: 0 auto;
            flex-wrap: wrap;
            justify-content: center;
        }

        .newsletter-form input {
            flex: 1;
            min-width: 200px;
            padding: 12px 18px;
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .newsletter-form input::placeholder {
            color: #9ca3af;
        }

        .newsletter-form input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: #3b82f6;
        }

        .newsletter-form button {
            padding: 12px 30px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .newsletter-form button:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .newsletter-form button:disabled {
            background: #6b7280;
            cursor: not-allowed;
            transform: none;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }

        .footer-column h6 {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 12px;
            border-bottom: 2px solid #3b82f6;
        }

        .footer-column p {
            font-size: 14px;
            line-height: 1.7;
            color: #d1d5db;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column li {
            margin-bottom: 12px;
        }

        .footer-column a {
            color: #d1d5db;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .footer-column a:hover {
            color: #3b82f6;
            padding-left: 4px;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .social-icons a {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            border-radius: 50%;
            color: #3b82f6;
            text-decoration: none;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-bottom p {
            font-size: 13px;
            color: #9ca3af;
            margin: 0;
        }

        .footer-bottom-links {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .footer-bottom-links a {
            font-size: 13px;
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-bottom-links a:hover {
            color: #3b82f6;
        }

        @media (max-width: 768px) {
            .footer-section {
                padding: 50px 20px 30px;
                margin-top: 60px;
            }

            .newsletter-section {
                padding: 40px 20px;
                margin-bottom: 40px;
            }

            .newsletter-section h3 {
                font-size: 22px;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .newsletter-form input,
            .newsletter-form button {
                width: 100%;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 30px;
                margin-bottom: 30px;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }

            .footer-bottom-links {
                justify-content: center;
                gap: 20px;
            }

            .footer-column h6 {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .footer-section {
                padding: 40px 16px 20px;
                margin-top: 40px;
            }

            .newsletter-section {
                padding: 30px 16px;
                border-radius: 8px;
                margin-bottom: 30px;
            }

            .newsletter-section h3 {
                font-size: 18px;
            }

            .newsletter-section p {
                font-size: 14px;
            }

            .newsletter-form input {
                min-width: 100%;
                padding: 10px 14px;
                font-size: 13px;
            }

            .newsletter-form button {
                padding: 10px 24px;
                font-size: 13px;
            }

            .social-icons {
                gap: 10px;
            }

            .social-icons a {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

    <footer class="footer-section">
        <div class="footer-container">
            <!-- Newsletter Section -->
            <div class="newsletter-section">
                <h3>Get Exclusive Offers & Updates</h3>
                <p>Subscribe to our newsletter and receive 15% off your first order. Plus, stay updated with new products and special promotions!</p>
                <form class="newsletter-form" id="newsletterForm">
                    <input type="email" id="newsletterEmail" placeholder="Enter your email address" required>
                    <button type="submit" id="subscribeBtn">Subscribe</button>
                </form>
            </div>

            <!-- Main Footer Content -->
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-column">
                    <h6>About MaxShine</h6>
                    <p>Premium cleaning products for a cleaner, healthier home. We're committed to providing eco-friendly and effective cleaning solutions that you can trust.</p>
                    <div class="social-icons">
                        <a href="#" title="Facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" title="Twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" title="Instagram" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" title="LinkedIn" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="footer-column">
                    <h6>Quick Links</h6>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Home</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Shop Products</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Cart</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Contact Us</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="footer-column">
                    <h6>Categories</h6>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Home Essentials</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Bathroom Essentials</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Kitchen Essentials</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Handwash Essentials</a></li>
                    </ul>
                </div>

                <!-- About US -->
                <div class="footer-column">
                    <h6>About US</h6>
                    <ul>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Address: Gulberg 3 Lahore, Pakistan 54660.</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Whatsapp: +923015397757</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right"></i> Email: maxshine@gmail.com</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; 2025 MaxShine. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookies</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById("newsletterForm").addEventListener("submit", function(event) {
            event.preventDefault();
            
            const email = document.getElementById("newsletterEmail").value;
            const submitBtn = document.getElementById("subscribeBtn");
            const originalText = submitBtn.textContent;
            
            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.textContent = "Subscribing...";
            
            // Create AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "subscribe.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    const response = this.responseText.trim();
                    
                    // Re-enable button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                    
                    if (response == "success") {
                        alert("🎉 Thank you for subscribing! Check your inbox for exclusive offers.");
                        document.getElementById("newsletterEmail").value = "";
                    } else if (response == "already_subscribed") {
                        alert("📧 You're already subscribed! Check your inbox for our latest offers.");
                    } else if (response == "invalid_email") {
                        alert("❌ Please enter a valid email address.");
                    } else {
                        alert("⚠️ Something went wrong. Please try again later.");
                    }
                }
            };
            
            xhr.onerror = function() {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                alert("⚠️ Network error. Please check your connection and try again.");
            };
            
            // Send the request
            xhr.send("email=" + encodeURIComponent(email));
        });
    </script>
</body>
</html>