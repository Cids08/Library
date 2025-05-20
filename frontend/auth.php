<?php
session_start();

// Configuration: Set to true for production, false for front-end development mode
$PRODUCTION_MODE = true;

if (isset($_SESSION['student_number']) && $PRODUCTION_MODE) {
    if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'superadmin') {
        header("Location: /Library/admin/AdminDashboard.php");
    } else {
        header("Location: /Library/user/student.php");
    }
    exit();
}


// Initialize variables
$error = '';
$success = '';

// 🛰️ Database configuration (used only in production mode)
if ($PRODUCTION_MODE) {
    $host = 'localhost';
    $dbname = 'libmas'; 
    $username = 'root';  
    $password = '';      
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Generate a simpler CAPTCHA that's still effective but more user-friendly
function generateCaptcha() {
    $words = [
        'library', 'books', 'reading', 'student', 'campus',
        'learn', 'study', 'knowledge', 'wisdom', 'education',
        'course', 'lecture', 'chapter', 'volume', 'thesis',
        'degree', 'science', 'research', 'journal', 'academic',
        'scholar', 'professor', 'university', 'college', 'school'
    ];
    $captcha_word = $words[array_rand($words)] . rand(10, 99);
    return $captcha_word;
}

// Generate or reuse CAPTCHA
if (!isset($_SESSION['captcha_text'])) {
    $_SESSION['captcha_text'] = generateCaptcha();
}
$captcha_text = $_SESSION['captcha_text'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $student_id = trim($_POST['username']);  
        $password = $_POST['password'];

        if ($PRODUCTION_MODE) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = ?");
            $stmt->execute([$student_id]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['student_number'] = $user['student_id'];  
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];    
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin' || $user['role'] === 'superadmin') {
                    header("Location: /Library/admin/AdminDashboard.php");
                } else {
                    header("Location: /Library/user/Student.php");
                }
                exit();
            } else {
                $error = "Invalid student number or password.";
            }
        } else {
            $success = "Login successful! Welcome back, " . htmlspecialchars($student_id) . "!";
        }
    }


    // SIGNUP FORM SUBMITTED
    elseif (isset($_POST['signup'])) {
        $fullname = trim($_POST['fullname']);
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $student_number = trim($_POST['student_number']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $captcha_input = isset($_POST['captcha_input']) ? trim($_POST['captcha_input']) : '';

        if (empty($fullname) || empty($email) || empty($student_number) || empty($new_password) || empty($confirm_password)) {
            $error = "All fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } elseif (strtolower($captcha_input) !== strtolower($_SESSION['captcha_text'])) {
            $error = "CAPTCHA verification failed. Please try again.";
        } elseif ($PRODUCTION_MODE) {
            $stmt = $pdo->prepare("SELECT * FROM libmas WHERE email = ? OR student_number = ?");
            $stmt->execute([$email, $student_number]);
            if ($stmt->rowCount() > 0) {
                $error = "Email or Student Number already taken.";
            } else {
                if (strlen($new_password) < 8) {
                    $error = "Password must be at least 8 characters long.";
                } elseif (!preg_match('/[A-Z]/', $new_password)) {
                    $error = "Password must contain at least one uppercase letter.";
                } elseif (!preg_match('/[0-9]/', $new_password)) {
                    $error = "Password must contain at least one number.";
                } elseif (!preg_match('/[^A-Za-z0-9]/', $new_password)) {
                    $error = "Password must contain at least one special character.";
                } else {
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO libmas (full_name, email, student_number, password_hash, role)
                                          VALUES (?, ?, ?, ?, 'student')");
                    if ($stmt->execute([$fullname, $email, $student_number, $password_hash])) {
                        $success = "Account created successfully. You can now log in.";
                        $_SESSION['captcha_text'] = generateCaptcha(); // Refresh CAPTCHA
                    } else {
                        $error = "An error occurred during registration.";
                    }
                }
            }
        } else {
            $success = "Account created successfully! Welcome, " . htmlspecialchars($fullname) . "!";
            $_SESSION['captcha_text'] = generateCaptcha(); // Refresh CAPTCHA
        }
    }
}

$image1_path = 'images/1.jpg'; 
$image2_path = 'images/2.jpg';

if (!file_exists($image1_path)) $image1_path = 'https://source.unsplash.com/random/800x1200?library ';
if (!file_exists($image2_path)) $image2_path = 'https://source.unsplash.com/random/800x1200?books ';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Luminous Library - Login/Signup</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="../public/css/auth.css">
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
            <?php if (!empty($error)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success-msg"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <!-- Login Form -->
            <form class="form-content active" id="login-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="input-group">
                    <label for="username">Student Number</label>
                    <input type="text" id="username" name="username" placeholder="Enter your student number" required autocomplete="username">
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
            <!-- Signup Form -->
            <form class="form-content" id="signup-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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
                    <input type="password" id="new_password" name="new_password" placeholder="Create a strong password" oninput="checkPasswordStrength(this.value)" required autocomplete="new-password">
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
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" oninput="checkPasswordMatch()" required autocomplete="new-password">
                    <small id="password-match-message" class="password-match-message"></small>
                </div>
                <!-- CAPTCHA Section -->
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
            document.querySelectorAll('.tab').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.form-content').forEach(item => item.classList.remove('active'));

            if (tab === 'login') {
                document.getElementById('login-form').classList.add('active');
                document.querySelector('.tab:nth-child(1)').classList.add('active');
            } else {
                document.getElementById('signup-form').classList.add('active');
                document.querySelector('.tab:nth-child(2)').classList.add('active');
            }
        }

        async function refreshCaptcha() {
            try {
                const response = await fetch('refresh_captcha.php');
                if (!response.ok) throw new Error("Failed to load CAPTCHA");
                const data = await response.text();
                document.querySelector('.captcha-text').textContent = data.trim();
            } catch (error) {
                console.error("CAPTCHA refresh error:", error);
                alert("Unable to refresh CAPTCHA. Reloading page...");
                location.reload();
            }
        }

        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength-bar');
            let strength = 0;

            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');

            lengthCheck.classList.remove('valid');
            uppercaseCheck.classList.remove('valid');
            numberCheck.classList.remove('valid');
            specialCheck.classList.remove('valid');

            if (password.length >= 8) { strength += 25; lengthCheck.classList.add('valid'); }
            if (/[A-Z]/.test(password)) { strength += 25; uppercaseCheck.classList.add('valid'); }
            if (/[0-9]/.test(password)) { strength += 25; numberCheck.classList.add('valid'); }
            if (/[^A-Za-z0-9]/.test(password)) { strength += 25; specialCheck.classList.add('valid'); }

            strengthBar.style.width = strength + '%';

            if (strength <= 25) strengthBar.style.background = '#e74c3c';
            else if (strength <= 50) strengthBar.style.background = '#f39c12';
            else if (strength <= 75) strengthBar.style.background = '#3498db';
            else strengthBar.style.background = '#2ecc71';

            if (document.getElementById('confirm_password').value.length > 0) checkPasswordMatch();
        }

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

        document.getElementById('signup-form').addEventListener('submit', function(event) {
            const captchaInput = document.getElementById('captcha_input').value;
            if (!captchaInput) {
                event.preventDefault();
                alert('Please complete the CAPTCHA verification.');
            }
        });
    </script>
</body>
</html>