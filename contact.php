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
    <title>MaxShine | Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #F5B027;
            --secondary: #003366;
            --light: #fff;
            --text-dark: #333;
            --border-light: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Contact Header */
        .contact-header {
            background: linear-gradient(135deg, var(--secondary) 0%, rgba(0, 51, 102, 0.95) 100%);
            color: var(--light);
            padding: 80px 0;
            text-align: center;
            margin-bottom: 60px;
        }

        .contact-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .contact-header p {
            font-size: 1.25rem;
            color: rgba(245, 176, 39, 0.9);
            margin-bottom: 0;
        }

        /* Contact Cards Section */
        .contact-info-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 80px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid var(--primary);
        }

        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .info-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
            display: block;
        }

        .info-card h3 {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .info-card p {
            color: #666;
            margin-bottom: 0;
            line-height: 1.6;
        }

        .info-card a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .info-card a:hover {
            color: var(--secondary);
        }

        /* Contact Form Section */
        .contact-form-section {
            background: white;
            border-radius: 12px;
            padding: 60px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 80px;
        }

        .contact-form-section h2 {
            color: var(--secondary);
            font-weight: 700;
            margin-bottom: 50px;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            color: var(--secondary);
            font-weight: 600;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(245, 176, 39, 0.25);
            color: var(--secondary);
        }

        .form-control::placeholder {
            color: #999;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Submit Button */
        .btn-submit {
            background-color: var(--primary);
            color: var(--secondary);
            border: none;
            padding: 14px 40px;
            font-size: 1.05rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit:hover {
            background-color: #e6a01f;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 176, 39, 0.3);
            color: var(--secondary);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Message Display */
        .alert-container {
            margin-bottom: 30px;
            border-radius: 8px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .contact-header {
                padding: 60px 20px;
            }

            .contact-header h1 {
                font-size: 2rem;
            }

            .contact-form-section {
                padding: 40px 20px;
            }

            .contact-form-section h2 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .spinner-border-custom {
            color: var(--primary);
        }

        /* Success Animation */
        @keyframes slideIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-message {
            animation: slideIn 0.5s ease;
        }
    </style>
</head>
<body>
    <!-- NavBar Section -->
    <?php include 'navbar.php'; ?>

    <!-- Contact Header -->
    <section class="contact-header">
        <div class="container">
            <h1>Get in Touch</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="container">
        <div class="contact-info-cards">
            <div class="info-card">
                <i class="fas fa-phone"></i>
                <h3>Call Us</h3>
                <p>
                    <a href="tel:+1234567890">+92 3027575971</a><br>
                    <small>Monday - Friday, 9AM - 6PM</small>
                </p>
            </div>

            <div class="info-card">
                <i class="fas fa-envelope"></i>
                <h3>Email Us</h3>
                <p>
                    <a href="mailto:support@maxshine.com">support@maxshine.com</a><br>
                    <small>We'll respond within 24 hours</small>
                </p>
            </div>

            <div class="info-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Visit Us</h3>
                <p>
                    Gulberg 3 Lahore<br>
                    Pakistan
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="container">
        <div class="contact-form-section">
            <h2>Send us a Message</h2>

            <div id="responseMessage"></div>

            <form id="contactForm" method="POST" action="process_contact.php">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="firstName">First Name *</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required placeholder="Suleman">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="lastName">Last Name *</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required placeholder="Zubair">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="suleman@gmail.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="+92 3027575971">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Message *</label>
                    <textarea class="form-control" id="message" name="message" required placeholder="Tell us more about your inquiry..."></textarea>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- Footer Section -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
       // Form Validation & Submission
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const responseMessage = document.getElementById('responseMessage');
    const form = this;

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

    // Validate form
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
        return;
    }

    // Prepare form data
    const formData = new FormData(form);

    try {
        // Send AJAX request
        const response = await fetch('process_contact.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Success message
            responseMessage.innerHTML = `
                <div class="alert alert-success alert-container success-message" role="alert">
                    <i class="fas fa-check-circle"></i> <strong>Success!</strong> ${result.message}
                </div>
            `;
            form.reset();
            form.classList.remove('was-validated');
        } else {
            // Error message
            responseMessage.innerHTML = `
                <div class="alert alert-danger alert-container" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong> ${result.message}
                </div>
            `;
        }

        // Reset button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';

        // Scroll to message
        responseMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Hide message after 5 seconds
        setTimeout(() => {
            responseMessage.innerHTML = '';
        }, 5000);

    } catch (error) {
        console.error('Error:', error);
        responseMessage.innerHTML = `
            <div class="alert alert-danger alert-container" role="alert">
                <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong> Something went wrong. Please try again later.
            </div>
        `;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
    }
});

// Email validation
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    if (email && !emailRegex.test(email)) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Phone number formatting (for Pakistani numbers)
document.getElementById('phone').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 0) {
        // Format as: 0300-1234567 or +92-300-1234567
        if (value.startsWith('92')) {
            // International format
            if (value.length <= 2) {
                value = '+' + value;
            } else if (value.length <= 5) {
                value = '+' + value.slice(0, 2) + '-' + value.slice(2);
            } else {
                value = '+' + value.slice(0, 2) + '-' + value.slice(2, 5) + '-' + value.slice(5, 12);
            }
        } else {
            // Local format
            if (value.length <= 4) {
                value = value;
            } else {
                value = value.slice(0, 4) + '-' + value.slice(4, 11);
            }
        }
    }
    this.value = value;
});
    </script>
</body>
</html>
