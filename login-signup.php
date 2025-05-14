<?php
session_start();

// FRONT-END DEVELOPMENT MODE - No redirects or database interactions
// Just display success messages for testing

$success_message = ""; // Variable to hold success messages

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simple login/signup logic for front-end testing
    if (isset($_POST['login'])) {
        // Login form submitted 
        $username = htmlspecialchars($_POST['username']);
        
        // Show success message instead of redirecting
        $success_message = "Login successful! Welcome back, $username!";
        
        // Clear any previous errors
        unset($error);
        
    } elseif (isset($_POST['signup'])) {
        // Signup form submitted
        $fullname = htmlspecialchars($_POST['fullname']);
        
        // Check if CAPTCHA was completed correctly (for testing purposes)
        if (!isset($_POST['captcha_input']) || strtolower($_POST['captcha_input']) !== strtolower($_SESSION['captcha_text'])) {
            $error = "CAPTCHA verification failed. Please try again!";
        }
        // Simple validation
        else if ($_POST['new_password'] !== $_POST['confirm_password']) {
            $error = "Passwords do not match!";
        } else {
            // Show success message instead of redirecting
            $success_message = "Account created successfully! Welcome, $fullname!";
            
            // Clear any previous errors
            unset($error);
        }
    }
}

// No redirect check - let the user view the forms

// Set paths for your images - using relative paths for better portability
$image1_path = 'images/1.jpg'; 
$image2_path = 'images/2.jpg';

// Fallback to default if images don't exist
if (!file_exists($image1_path)) $image1_path = 'https://source.unsplash.com/random/800x1200?library';
if (!file_exists($image2_path)) $image2_path = 'https://source.unsplash.com/random/800x1200?books';

// Generate a simpler CAPTCHA that's still effective but more user-friendly
function generateCaptcha() {
    // Dictionary of words that are readable but still effective
    $words = [
        'library', 'books', 'reading', 'student', 'campus',
        'learn', 'study', 'knowledge', 'wisdom', 'education',
        'course', 'lecture', 'chapter', 'volume', 'thesis',
        'degree', 'science', 'research', 'journal', 'academic',
        'scholar', 'professor', 'university', 'college', 'school'
    ];
    
    // Pick a random word
    $captcha_word = $words[array_rand($words)];
    
    // Add a number to make it more secure but still readable
    $captcha_word = $captcha_word . rand(10, 99);
    
    return $captcha_word;
}

// Generate the CAPTCHA and store in session
$captcha_text = generateCaptcha();
$_SESSION['captcha_text'] = $captcha_text;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luminous Library - Login/Signup</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f8fd;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(245, 248, 253, 0.92), rgba(245, 248, 253, 0.92)), url('bg_image.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }
        
        .container {
            display: flex;
            width: 90%;
            max-width: 1100px;
            min-height: 600px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background-color: #fff;
        }
        
        .image-section {
            flex: 1.1;
            background-size: cover;
            background-position: center;
            position: relative;
            transition: all 0.5s ease;
            min-height: 300px;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(22, 41, 70, 0.8), rgba(22, 41, 70, 0.7));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
            transition: all 0.3s ease;
        }
        
        .library-title {
            font-size: 2.5rem;
            margin-bottom: 30px;
            font-weight: 700;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
            text-align: center;
        }
        
        .library-quote {
            font-size: 1.1rem;
            text-align: center;
            line-height: 1.7;
            max-width: 85%;
            font-style: italic;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }
        
        .form-section {
            flex: 1;
            background-color: white;
            padding: 40px 35px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }
        
        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-bottom: 1px solid #eaeef5;
        }
        
        .tab {
            flex: 1;
            text-align: center;
            padding: 15px 0;
            cursor: pointer;
            font-weight: 600;
            color: #5d89b7;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            font-size: 1.05rem;
            letter-spacing: 0.5px;
        }
        
        .tab.active {
            color: #162946;
            border-bottom: 3px solid #275d8c;
        }
        
        .form-content {
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
        }
        
        .form-content.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #162946;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #dce6f2;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #f9fbfd;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #275d8c;
            box-shadow: 0 0 0 3px rgba(39, 93, 140, 0.12);
            background-color: #fff;
        }
        
        /* More compact password guidelines */
        .password-guidelines {
            margin-top: 8px;
            font-size: 0.8rem;
            color: #5d7185;
            background-color: #f5f9fc;
            padding: 8px 10px;
            border-radius: 6px;
            border-left: 3px solid #275d8c;
        }
        
        .password-guidelines ul {
            margin-left: 18px;
            margin-top: 4px;
            margin-bottom: 0;
            columns: 2;
        }
        
        .password-guidelines li {
            margin-bottom: 3px;
            line-height: 1.5;
        }
        
        .password-guidelines li.valid {
            color: #2ecc71;
        }
        
        /* Improved CAPTCHA container */
        .captcha-container {
            margin-bottom: 20px;
        }
        
        .captcha-title {
            font-weight: 500;
            color: #162946;
            font-size: 0.9rem;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        
        .captcha-display {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #f0f5fa 0%, #e2eaf3 100%);
            padding: 12px 15px;
            border: 1px solid #dce6f2;
            border-radius: 8px;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }
        
        .captcha-display::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path d="M30,10 L70,90 M10,30 L90,70" stroke="rgba(100,120,150,0.08)" stroke-width="1"/></svg>');
            background-size: 12px 12px;
            pointer-events: none;
            z-index: 1;
        }
        
        .captcha-text {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            font-size: 1.5rem;
            color: #162946;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
            width: 100%;
            text-align: center;
            text-shadow: 1px 1px 1px rgba(255,255,255,0.7), 
                       -1px -1px 1px rgba(0,0,0,0.1);
            transform: skew(-2deg, 1deg);
        }
        
        .captcha-refresh {
            background: #275d8c;
            border: none;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            z-index: 3;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        
        .captcha-refresh:hover {
            background-color: #1d4c76;
            transform: rotate(90deg);
        }
        
        .btn {
            background-color: #275d8c;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 5px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 10px rgba(39, 93, 140, 0.2);
        }
        
        .btn:hover {
            background-color: #1d4c76;
            box-shadow: 0 5px 12px rgba(39, 93, 140, 0.25);
            transform: translateY(-2px);
        }
        
        .btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(39, 93, 140, 0.2);
        }
        
        .error-msg {
            color: #e74c3c;
            margin: 0 0 20px;
            font-size: 0.9rem;
            text-align: center;
            background-color: rgba(231, 76, 60, 0.08);
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #e74c3c;
        }
        
        .success-msg {
            color: #27ae60;
            margin: 0 0 20px;
            font-size: 0.9rem;
            text-align: center;
            background-color: rgba(39, 174, 96, 0.08);
            padding: 10px;
            border-radius: 6px;
            border-left: 3px solid #27ae60;
        }
        
        .form-footer {
            margin-top: 25px;
            text-align: center;
            color: #5d7185;
            font-size: 0.9rem;
        }
        
        .form-footer a {
            color: #275d8c;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .form-footer a:hover {
            color: #162946;
            text-decoration: underline;
        }
        
        /* Password strength indicator */
        .password-strength {
            height: 4px;
            margin-top: 8px;
            border-radius: 2px;
            width: 100%;
            background: #e1e8f0;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s ease, background 0.3s ease;
        }
        
        /* Improved password match message */
        .password-match-message {
            font-size: 0.85rem;
            margin-top: 5px;
            transition: all 0.3s ease;
            display: none;
        }
        
        /* Responsive design improvements */
        @media (max-width: 992px) {
            .container {
                width: 95%;
            }
            
            .library-title {
                font-size: 2.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
                width: 95%;
                max-height: none;
            }
            
            .image-section {
                height: 200px;
            }
            
            .form-section {
                padding: 30px 25px;
            }
            
            .library-title {
                font-size: 2rem;
                margin-bottom: 15px;
            }
            
            .library-quote {
                font-size: 0.95rem;
                max-width: 95%;
            }
            
            .password-guidelines ul {
                columns: 1;
            }
        }
        
        @media (max-width: 480px) {
            .container {
                width: 100%;
                border-radius: 0;
                box-shadow: none;
            }
            
            body {
                padding: 0;
                background: white;
            }
            
            .library-title {
                font-size: 1.8rem;
            }
            
            .form-section {
                padding: 25px 20px;
            }
            
            .captcha-text {
                font-size: 1.3rem;
            }
            
            .btn {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section" style="background-image: url('<?php echo isset($_GET['img']) && $_GET['img'] == 2 ? $image2_path : $image1_path; ?>');">
            <div class="image-overlay">
                <h1 class="library-title">Luminous Library</h1>
                <p class="library-quote">"A library is not a luxury but one of the necessities of life." — Henry Ward Beecher</p>
            </div>
        </div>
        <div class="form-section">
            <div class="tabs">
                <div class="tab active" onclick="switchTab('login')">Login</div>
                <div class="tab" onclick="switchTab('signup')">Sign Up</div>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form class="form-content active" id="login-form" method="POST" action="">
                <?php if (isset($success_message) && isset($_POST['login'])): ?>
                    <div class="success-msg"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>
                <button type="submit" name="login" class="btn">Sign In</button>
                <div class="form-footer">
                    <p>Forgot your password? <a href="#">Reset it here</a></p>
                </div>
            </form>
            
            <!-- Signup Form with Updated Fields -->
            <form class="form-content" id="signup-form" method="POST" action="">
                <?php if (isset($success_message) && isset($_POST['signup'])): ?>
                    <div class="success-msg"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Enter your full name" required autocomplete="name">
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required autocomplete="email">
                </div>
                <div class="input-group">
                    <label for="student_number">Student Number</label>
                    <input type="text" id="student_number" name="student_number" placeholder="Enter your student number" required>
                </div>
                <div class="input-group">
                    <label for="new_password">Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Create a strong password" 
                           oninput="checkPasswordStrength(this.value)" required autocomplete="new-password">
                    <div class="password-strength">
                        <div class="password-strength-bar" id="password-strength-bar"></div>
                    </div>
                    <div class="password-guidelines" id="password-guidelines">
                        <div>Your password should have:</div>
                        <ul>
                            <li id="length-check">At least 8 characters</li>
                            <li id="uppercase-check">At least 1 uppercase letter</li>
                            <li id="number-check">At least 1 number</li>
                            <li id="special-check">At least 1 special character</li>
                        </ul>
                    </div>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           placeholder="Confirm your password" 
                           oninput="checkPasswordMatch()" required autocomplete="new-password">
                    <small id="password-match-message" class="password-match-message"></small>
                </div>
                
                <!-- Simplified CAPTCHA Verification -->
                <div class="captcha-container">
                    <label class="captcha-title" for="captcha_input">Verify you're human</label>
                    <div class="captcha-display">
                        <div class="captcha-text"><?php echo htmlspecialchars($captcha_text); ?></div>
                        <button type="button" class="captcha-refresh" onclick="refreshCaptcha()">↻</button>
                    </div>
                    <div class="input-group" style="margin-top: 8px; margin-bottom: 10px;">
                        <input type="text" id="captcha_input" name="captcha_input" placeholder="Enter the text shown above" required>
                    </div>
                </div>
                
                <button type="submit" name="signup" class="btn">Create Account</button>
                <div class="form-footer">
                    <p>Already have an account? <a href="#" onclick="switchTab('login')">Login here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Update tabs
            document.querySelectorAll('.tab').forEach(item => {
                item.classList.remove('active');
            });
            
            document.querySelectorAll('.form-content').forEach(item => {
                item.classList.remove('active');
            });
            
            if (tab === 'login') {
                document.getElementById('login-form').classList.add('active');
                document.querySelectorAll('.tab')[0].classList.add('active');
            } else {
                document.getElementById('signup-form').classList.add('active');
                document.querySelectorAll('.tab')[1].classList.add('active');
            }
        }
        
        // Refresh CAPTCHA
        function refreshCaptcha() {
            // In a real implementation, you would make an AJAX call to the server
            // For this demo, we'll just reload the page
            location.reload();
        }
        
        // Check password strength with more detailed feedback
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;
            
            // Update checklist items
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');
            
            // Reset classes
            lengthCheck.classList.remove('valid');
            uppercaseCheck.classList.remove('valid');
            numberCheck.classList.remove('valid');
            specialCheck.classList.remove('valid');
            
            // Check criteria
            if (password.length >= 8) {
                strength += 25;
                lengthCheck.classList.add('valid');
            }
            if (password.match(/[A-Z]/)) {
                strength += 25;
                uppercaseCheck.classList.add('valid');
            }
            if (password.match(/[0-9]/)) {
                strength += 25;
                numberCheck.classList.add('valid');
            }
            if (password.match(/[^A-Za-z0-9]/)) {
                strength += 25;
                specialCheck.classList.add('valid');
            }
            
            strengthBar.style.width = strength + '%';
            
            // Color based on strength
            if (strength <= 25) {
                strengthBar.style.background = '#e74c3c'; // weak
            } else if (strength <= 50) {
                strengthBar.style.background = '#f39c12'; // medium
            } else if (strength <= 75) {
                strengthBar.style.background = '#3498db'; // good
            } else {
                strengthBar.style.background = '#2ecc71'; // strong
            }
            
            // Update password match if confirm password has content
            if (document.getElementById('confirm_password').value.length > 0) {
                checkPasswordMatch();
            }
        }
        
        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const message = document.getElementById('password-match-message');
            
            if (confirmPassword.length > 0) {
                message.style.display = 'block';
                
                if (password === confirmPassword) {
                    message.textContent = "✓ Passwords match";
                    message.style.color = "#2ecc71";
                } else {
                    message.textContent = "✗ Passwords do not match";
                    message.style.color = "#e74c3c";
                }
            } else {
                message.style.display = 'none';
            }
        }
        
        // Handle form submission validation
        document.getElementById('signup-form').addEventListener('submit', function(event) {
            const password = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const captchaInput = document.getElementById('captcha_input').value;
            
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match. Please try again.');
            }
            
            if (!captchaInput) {
                event.preventDefault();
                alert('Please complete the CAPTCHA verification.');
            }
        });
    </script>
</body>
</html>