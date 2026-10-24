<?php        
session_start();
include 'conn.php';

// ---------------------------
// LOGIN PROCESS (STEP 5)
// ---------------------------
if (isset($_POST['login_btn'])) {

    $email = mysqli_real_escape_string($conn, $_POST['login_email']);
    $password = mysqli_real_escape_string($conn, $_POST['login_password']);

    // Check admin login
    if ($email == "admin@gmail.com" && $password == "admin123") {
        $_SESSION['admin'] = true;
        header("Location: admin/dashboard.php");
        exit();
    }

    // Check normal user login
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_name'] = $row['name'];

        header("Location: index.php");
        exit();
    } else {
        $login_error = "Invalid email or password!";
    }
}



// ---------------------------
// SIGNUP PROCESS (STEP 4)
// ---------------------------
if (isset($_POST['signup_btn'])) {

    $name = mysqli_real_escape_string($conn, $_POST['signup_name']);
    $email = mysqli_real_escape_string($conn, $_POST['signup_email']);
    $password = mysqli_real_escape_string($conn, $_POST['signup_password']);

    // Insert into DB
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // redirect to home
        exit();
    } else {
        $signup_error = "Signup failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #FAF9F6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        .auth-container {
            display: flex;
            max-width: 900px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
            min-height: 550px;
        }

        /* Sliding Panel */
        .sliding-panel {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: #145da0;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            z-index: 10;
            transition: transform 0.7s ease-in-out;
            border-radius: 0 100px 100px 0;
        }

        .auth-container.signup-mode .sliding-panel {
            transform: translateX(100%);
            border-radius: 100px 0 0 100px;
        }

        .panel-content {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .panel-content h1 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .panel-content p {
            font-size: 15px;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .panel-btn {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 45px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .panel-btn:hover {
            background: white;
            color: #2E8BC0;
            transform: scale(1.05);
        }

        /* Form Sections */
        .form-section {
            flex: 1;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: all 0.7s ease-in-out;
            position: absolute;
            width: 50%;
            height: 100%;
        }

        .login-section {
            left: 50%;
            opacity: 1;
            pointer-events: all;
        }

        .signup-section {
            right: 0;
            opacity: 0;
            pointer-events: none;
        }

        .auth-container.signup-mode .login-section {
            left: 0;
            opacity: 0;
            pointer-events: none;
        }

        .auth-container.signup-mode .signup-section {
            right: 50%;
            opacity: 1;
            pointer-events: all;
        }

        .form-title {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
            text-align: center;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom input {
            width: 100%;
            padding: 14px 45px 14px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: #2E8BC0;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .input-group-custom input.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .input-group-custom input.is-valid {
            border-color: #28a745;
            background: #f0fff4;
        }

        .input-icon {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .input-icon.clickable {
            cursor: pointer;
        }

        .input-icon.clickable:hover {
            color: #2E8BC0;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
            display: none;
            margin-left: 5px;
        }

        .error-message.show {
            display: block;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            background: #e0e0e0;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            border-radius: 2px;
            transition: all 0.3s ease;
            width: 0;
        }

        .strength-weak { background: #dc3545; width: 33%; }
        .strength-medium { background: #ffc107; width: 66%; }
        .strength-strong { background: #28a745; width: 100%; }

        .forgot-password {
            text-align: right;
            margin-top: -8px;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #145da0;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #145da0;
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #145da0;
            border: none;
            border-radius: 50px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px #145da0;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
            color: #999;
            font-size: 13px;
            display: none;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before { left: 0; }
        .divider::after { right: 0; }

        .social-login {
            display: none;
            gap: 12px;
            justify-content: center;
        }

        .social-btn {
            width: 45px;
            height: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            color: #666;
            font-size: 18px;
        }

        .social-btn:hover {
            border-color: #2E8BC0;
            color: #2E8BC0;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .alert {
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            border: none;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
                max-width: 450px;
            }

            .sliding-panel {
                width: 100%;
                height: auto;
                position: relative;
                border-radius: 0;
                transform: none !important;
                padding: 30px;
            }

            .form-section {
                padding: 40px 30px;
                opacity: 1 !important;
                pointer-events: all !important;
                position: relative;
                width: 100%;
                left: 0 !important;
                right: 0 !important;
            }

            .signup-section {
                display: none;
            }

            .auth-container.signup-mode .login-section {
                display: none;
            }

            .auth-container.signup-mode .signup-section {
                display: flex;
            }

            .panel-content h1 {
                font-size: 28px;
            }

            .form-title {
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .form-section {
                padding: 30px 20px;
            }

            .panel-content h1 {
                font-size: 24px;
            }

            .form-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container" id="authContainer">
        <!-- Sliding Panel -->
        <div class="sliding-panel">
            <div class="panel-content" id="panelContent">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="panel-btn" onclick="toggleMode()">Sign Up</button>
            </div>
        </div>

        <!-- Login Form -->
        <div class="form-section login-section">
            <h2 class="form-title">Login</h2>
            <p class="form-subtitle">Welcome back! Please login to your account</p>
            
            <div id="loginAlert"></div>

            <form id="loginForm">
                <div class="input-group-custom">
                    <input type="text" id="loginUsername" placeholder="Username" required autocomplete="username">
                    <i class="fas fa-user input-icon"></i>
                    <div class="error-message" id="loginUsernameError"></div>
                </div>

                <div class="input-group-custom">
                    <input type="password" id="loginPassword" placeholder="Password" required autocomplete="current-password">
                    <i class="fas fa-eye-slash clickable input-icon" id="loginPasswordToggle" onclick="togglePassword('loginPassword', 'loginPasswordToggle')"></i>
                    <div class="error-message" id="loginPasswordError"></div>
                </div>

                <div class="forgot-password">
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Login</button>
            </form>
        </div>

        <!-- Signup Form -->
        <div class="form-section signup-section">
            <h2 class="form-title">Registration</h2>
            <p class="form-subtitle">Create your account to get started</p>
            
            <div id="signupAlert"></div>

            <form id="signupForm">
                <div class="input-group-custom">
                    <input type="text" id="signupUsername" placeholder="Username" required autocomplete="username">
                    <i class="fas fa-user input-icon"></i>
                    <div class="error-message" id="signupUsernameError"></div>
                </div>

                <div class="input-group-custom">
                    <input type="email" id="signupEmail" placeholder="Email" required autocomplete="email">
                    <i class="fas fa-envelope input-icon"></i>
                    <div class="error-message" id="signupEmailError"></div>
                </div>

                <div class="input-group-custom">
                    <input type="password" id="signupPassword" placeholder="Password" required autocomplete="new-password">
                    <i class="fas fa-eye-slash clickable input-icon" id="signupPasswordToggle" onclick="togglePassword('signupPassword', 'signupPasswordToggle')"></i>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="error-message" id="signupPasswordError"></div>
                </div>

                <div class="input-group-custom">
                    <input type="password" id="confirmPassword" placeholder="Confirm Password" required autocomplete="new-password">
                    <i class="fas fa-eye-slash clickable input-icon" id="confirmPasswordToggle" onclick="togglePassword('confirmPassword', 'confirmPasswordToggle')"></i>
                    <div class="error-message" id="confirmPasswordError"></div>
                </div>

                <button type="submit" class="btn-submit">Sign Up</button>
            </form>
        </div>
    </div>

    <script>
        let isSignupMode = false;

        // Toggle between login and signup
        function toggleMode() {
            const container = document.getElementById('authContainer');
            const panelContent = document.getElementById('panelContent');

            isSignupMode = !isSignupMode;

            if (isSignupMode) {
                container.classList.add('signup-mode');
                panelContent.innerHTML = `
                    <h1>Welcome Back!</h1>
                    <p>Already have an account?</p>
                    <button class="panel-btn" onclick="toggleMode()">Login</button>
                `;
            } else {
                container.classList.remove('signup-mode');
                panelContent.innerHTML = `
                    <h1>Hello, Welcome!</h1>
                    <p>Don't have an account?</p>
                    <button class="panel-btn" onclick="toggleMode()">Sign Up</button>
                `;
            }

            // Clear forms and errors
            document.getElementById('loginForm').reset();
            document.getElementById('signupForm').reset();
            clearErrors();
            document.getElementById('loginAlert').innerHTML = '';
            document.getElementById('signupAlert').innerHTML = '';
            resetPasswordToggles();
        }

        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Reset password toggles
        function resetPasswordToggles() {
            const toggles = ['loginPasswordToggle', 'signupPasswordToggle', 'confirmPasswordToggle'];
            const inputs = ['loginPassword', 'signupPassword', 'confirmPassword'];
            
            toggles.forEach((toggleId, index) => {
                const icon = document.getElementById(toggleId);
                const input = document.getElementById(inputs[index]);
                if (icon && input) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                    input.type = 'password';
                }
            });
        }

        // Clear errors
        function clearErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('input').forEach(el => {
                el.classList.remove('is-invalid', 'is-valid');
            });
            const strengthBar = document.getElementById('strengthBar');
            if (strengthBar) {
                strengthBar.className = 'strength-bar';
            }
        }

        // Show error
        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const error = document.getElementById(inputId + 'Error');
            input.classList.add('is-invalid');
            error.textContent = message;
            error.classList.add('show');
        }

        // Show success
        function showSuccess(inputId) {
            const input = document.getElementById(inputId);
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }

        // Email validation
        function validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Password strength checker
        document.getElementById('signupPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            
            if (password.length === 0) {
                strengthBar.className = 'strength-bar';
                return;
            }
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength === 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });

        // Login Form Validation + credential check for admin/admin123
     document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let username = document.getElementById('loginUsername').value;
    let password = document.getElementById('loginPassword').value;

    let formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);

    fetch("login_process.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        if (data === "admin") {
            window.location.href = "admin/dashboard.php";
        } 
        else if (data === "user") {
            window.location.href = "index.php";
        } 
        else {
            document.getElementById("loginAlert").innerHTML =
                '<div class="alert alert-danger">Invalid username or password</div>';
        }
    });
});

        // Signup Form Validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let username = document.getElementById('signupUsername').value;
    let email = document.getElementById('signupEmail').value;
    let password = document.getElementById('signupPassword').value;

    let formData = new FormData();
    formData.append("username", username);
    formData.append("email", email);
    formData.append("password", password);

    fetch("signup_process.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(data => {

        if (data === "exists") {
            document.getElementById("signupAlert").innerHTML =
                '<div class="alert alert-danger">User already exists</div>';
        } 
        else if (data === "success") {
            document.getElementById("signupAlert").innerHTML =
                '<div class="alert alert-success">Account created! Please login</div>';
            setTimeout(() => toggleMode(), 1500);
        } 
        else {
            document.getElementById("signupAlert").innerHTML =
                '<div class="alert alert-danger">Error creating account</div>';
        }
    });
});

    </script>
</body>
</html>