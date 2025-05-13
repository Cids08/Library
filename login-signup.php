<?php
session_start();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simple login/signup logic (in a real app, you'd use a database)
    if (isset($_POST['login'])) {
        // Login form submitted
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // In a real application, you would verify against database
        // This is just a simple demonstration
        $_SESSION['user'] = $username;
        $_SESSION['message'] = "Welcome back, $username!";
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['signup'])) {
        // Signup form submitted
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $student_number = $_POST['student_number'];
        $password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Check if robot verification was completed
        if (!isset($_POST['robot_check']) || $_POST['robot_check'] != 'verified') {
            $error = "Please verify that you are not a robot!";
        }
        // Simple validation
        else if ($password !== $confirm_password) {
            $error = "Passwords do not match!";
        } else {
            // In a real application, you would save to database
            // This is just a simple demonstration
            $_SESSION['user'] = $fullname;
            $_SESSION['message'] = "Account created successfully!";
            header("Location: index.php");
            exit();
        }
    }
}

// Check if user is logged in
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

// Set paths for your images - adjust these to match where you store the uploaded images
$image1_path = 'images/1.jpg'; 
$image2_path = 'images/2.jpg'; 
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('bg_image.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .container {
            display: flex;
            width: 80%;
            max-width: 1200px;
            height: 650px; /* Increased height to accommodate additional fields */
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }
        
        .image-section {
            flex: 1;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(22, 41, 70, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 40px;
        }
        
        .library-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .library-quote {
            font-size: 1.1rem;
            text-align: center;
            line-height: 1.6;
            max-width: 80%;
            font-style: italic;
            text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }
        
        .form-section {
            flex: 1;
            background-color: rgba(255, 255, 255, 0.97);
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto; /* Enable scrolling for longer form */
        }
        
        .tabs {
            display: flex;
            margin-bottom: 30px;
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
        }
        
        .tab.active {
            color: #162946;
            border-bottom: 3px solid #162946;
        }
        
        .form-content {
            display: none;
        }
        
        .form-content.active {
            display: block;
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #162946;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccd8e8;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s ease;
        }
        
        .input-group input:focus {
            outline: none;
            border-color: #162946;
            box-shadow: 0 0 0 3px rgba(22, 41, 70, 0.1);
        }
        
        .robot-verification {
            margin-bottom: 20px;
            padding: 12px;
            border: 1px solid #ccd8e8;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .robot-verification label {
            display: flex;
            align-items: center;
            color: #162946;
            font-weight: 500;
            cursor: pointer;
        }
        
        .robot-verification input[type="checkbox"] {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }
        
        .captcha-image {
            background-color: #f5f8fd;
            padding: 5px;
            border-radius: 6px;
            width: 150px;
            text-align: center;
            font-size: 0.9rem;
            color: #162946;
        }
        
        .btn {
            background-color: #275d8c;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn:hover {
            background-color: #162946;
        }
        
        .error-msg {
            color: #e74c3c;
            margin-top: 15px;
            font-size: 0.9rem;
            text-align: center;
        }
        
        .form-footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7c93;
        }
        
        .form-footer a {
            color: #275d8c;
            font-weight: 500;
            text-decoration: none;
        }
        
        .form-footer a:hover {
            text-decoration: underline;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                height: auto;
                width: 95%;
            }
            
            .image-section {
                height: 200px;
            }
            
            .form-section {
                padding: 30px;
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
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form class="form-content active" id="login-form" method="POST" action="">
                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn">Sign In</button>
                <div class="form-footer">
                    <p>Forgot your password? <a href="#">Reset it here</a></p>
                </div>
            </form>
            
            <!-- Signup Form with Updated Fields -->
            <form class="form-content" id="signup-form" method="POST" action="">
                <div class="input-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="student_number">Student Number</label>
                    <input type="text" id="student_number" name="student_number" required>
                </div>
                <div class="input-group">
                    <label for="new_password">Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <!-- Robot Verification -->
                <div class="robot-verification">
                    <label for="robot_check">
                        <input type="checkbox" id="robot_check" name="robot_check" value="verified" required>
                        I'm not a robot
                    </label>
                    <div class="captcha-image" id="captcha-challenge">
                        <span id="captcha-text">CAPTCHA</span>
                        <button type="button" onclick="refreshCaptcha()" style="background:none; border:none; cursor:pointer; color:#275d8c;">↻</button>
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
        
        // Generate a simple CAPTCHA
        function refreshCaptcha() {
            const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
            let captcha = '';
            for (let i = 0; i < 6; i++) {
                captcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('captcha-text').textContent = captcha;
        }
        
        // Initialize CAPTCHA on page load
        window.onload = function() {
            refreshCaptcha();
        };
    </script>
</body>
</html>