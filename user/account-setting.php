<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: auth.php");
    exit();
}

$student_number = $_SESSION['student_number'];

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch user data
    $stmt = $pdo->prepare("SELECT * FROM libmas WHERE student_number = ?");
    $stmt->execute([$student_number]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }

    $messages = [];

    // Handle profile update
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);

        if (!$email) {
            $messages[] = ['type' => 'error', 'text' => 'Please enter a valid email address.'];
        } else {
            $full_name = "$first_name $last_name";
            $stmt = $pdo->prepare("UPDATE libmas SET first_name = ?, last_name = ?, full_name = ?, email = ? WHERE student_number = ?");
            $stmt->execute([$first_name, $last_name, $full_name, $email, $student_number]);

            // Update session
            $_SESSION['full_name'] = $full_name;
            $messages[] = ['type' => 'success', 'text' => 'Profile updated successfully.'];
        }
    }

    // Handle password change
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (!password_verify($current_password, $user['password_hash'])) {
            $messages[] = ['type' => 'error', 'text' => 'Current password is incorrect.'];
        } elseif ($new_password !== $confirm_password) {
            $messages[] = ['type' => 'error', 'text' => 'New passwords do not match.'];
        } elseif (strlen($new_password) < 6) {
            $messages[] = ['type' => 'error', 'text' => 'Password must be at least 6 characters.'];
        } else {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE libmas SET password_hash = ? WHERE student_number = ?");
            $stmt->execute([$hash, $student_number]);
            $messages[] = ['type' => 'success', 'text' => 'Password changed successfully.'];
        }
    }

    // Handle profile picture upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        $name = basename($_FILES['profile_picture']['name']);
        $upload_dir = 'uploads/';
        $target_file = '';

        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        if ($name && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $target_file = $upload_dir . uniqid('avatar_') . '_' . $name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $stmt = $pdo->prepare("UPDATE libmas SET profile_picture = ? WHERE student_number = ?");
                $stmt->execute([$target_file, $student_number]);

                // ✅ Save to session for immediate display
                $_SESSION['profile_picture'] = $target_file;
                $messages[] = ['type' => 'success', 'text' => 'Profile picture updated successfully.'];
            } else {
                $messages[] = ['type' => 'error', 'text' => 'Failed to upload profile picture.'];
            }
        } else {
            $messages[] = ['type' => 'error', 'text' => 'No image selected or upload error.'];
        }
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings | Modern Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css "/>
    <style>
        :root {
            --primary-dark: #1a3a5f;
            --primary: #2c5282;
            --primary-light: #4299e1;
            --accent: #63b3ed;
            --light-blue: #bee3f8;
            --very-light-blue: #ebf8ff;
            --white: #ffffff;
            --gray: #e2e8f0;
            --dark-gray: #4a5568;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--very-light-blue);
            color: var(--primary-dark);
        }

        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .portal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--light-blue);
            margin-bottom: 30px;
        }

        .portal-title h1 {
            font-size: 28px;
            color: var(--primary-dark);
        }

        .student-profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .student-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-light);
        }

        .student-info h3 {
            margin-bottom: 5px;
            color: var(--primary-dark);
        }

        .student-info p {
            color: var(--dark-gray);
            font-size: 14px;
        }

        .portal-nav {
            display: flex;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .nav-item {
            flex: 1;
            text-align: center;
            padding: 15px;
            text-decoration: none;
            color: var(--primary-dark);
            font-weight: 500;
            transition: 0.3s;
            border-bottom: 3px solid transparent;
        }

        .nav-item:hover {
            background-color: var(--very-light-blue);
        }

        .nav-item.active {
            background-color: var(--very-light-blue);
            border-bottom: 3px solid var(--primary);
            font-weight: 600;
        }

        .nav-item i {
            margin-right: 8px;
            color: var(--primary);
        }

        .settings-section {
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--gray);
            border-radius: 6px;
            background-color: var(--very-light-blue);
            font-size: 15px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .form-row .form-group {
            flex: 1;
        }

        .save-btn {
            background-color: var(--primary);
            color: var(--white);
            padding: 12px 24px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .save-btn:hover {
            background-color: var(--primary-light);
        }

        .message {
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
            font-weight: bold;
        }

        .success {
            background-color: #d1fae5;
            color: #2f855a;
        }

        .error {
            background-color: #fee2e2;
            color: #c53030;
        }

        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <header class="portal-header">
        <div class="portal-title">
            <h1>Account Settings</h1>
        </div>
        <div class="student-profile">
            <?php
            $default_avatar = "/api/placeholder/60/60";
            $profile_picture = $_SESSION['profile_picture'] ?? $default_avatar;
            $full_name = $_SESSION['full_name'] ?? 'Guest';
            $student_id = $_SESSION['student_number'] ?? 'N/A';
            ?>
            <img src="<?= htmlspecialchars($profile_picture) ?>?<?= time() ?>" alt="Student Profile" class="student-avatar">
            <div class="student-info">
                <h3><?= htmlspecialchars($full_name) ?></h3>
                <p>Student ID: <?= htmlspecialchars($student_id) ?></p>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="portal-nav">
        <a href="student.php" class="nav-item"><i class="fas fa-book"></i> My Borrowed Books</a>
        <a href="search-catalog.php" class="nav-item"><i class="fas fa-search"></i> Search Catalog</a>
        <a href="book-history.php" class="nav-item"><i class="fas fa-history"></i> Book History</a>
        <a href="#" class="nav-item active"><i class="fas fa-user-cog"></i> Account Settings</a>
    </nav>

    <!-- Settings Form -->
    <section class="settings-section">
        <!-- Profile Picture Upload -->
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
    <div class="form-group" style="text-align: center;">
        <label style="font-weight: 600; display: block; margin-bottom: 10px;">Profile Picture</label>
        <div style="margin-bottom: 15px;">
            <img id="profilePreview"
                 src="<?= htmlspecialchars($profile_picture) ?>?<?= time() ?>"
                 alt="Current Profile"
                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid var(--primary-light);">
        </div>
        <input type="file" id="profileImageInput" name="profile_picture" accept="image/*" style="display: none;" onchange="handleImageSelect()">
        <button type="button" class="save-btn" onclick="document.getElementById('profileImageInput').click()">Choose New Photo</button>
    </div>
</form>



        <!-- Profile Info Form -->
        <form method="POST">
            <h2>Update Profile Information</h2>
            <div class="form-row">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="first_name" value="<?= htmlspecialchars(explode(' ', $user['full_name'])[0] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="last_name" value="<?= htmlspecialchars(explode(' ', $user['full_name'])[1] ?? '') ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            <div class="form-group">
                <label for="student-id">Student ID</label>
                <input type="text" id="student-id" value="<?= htmlspecialchars($student_number) ?>" disabled>
            </div>
            <button type="submit" name="update_profile" class="save-btn">Save Changes</button>
        </form>

        <!-- Password Change -->
        <form method="POST" style="margin-top: 40px;">
            <h2>Change Password</h2>
            <div class="form-group">
                <label for="current-password">Current Password</label>
                <input type="password" id="current-password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="save-btn">Update Password</button>
        </form>

        <!-- Messages -->
        <?php foreach ($messages as $msg): ?>
            <div class="message <?= $msg['type'] ?>">
                <?= $msg['text'] ?>
            </div>
        <?php endforeach; ?>

    </section>
</div>

<!-- Image Preview Script -->
<script>
function previewImage(input) {
    const preview = document.getElementById('profilePreview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
<script>
function handleImageSelect() {
    const input = document.getElementById('profileImageInput');
    const preview = document.getElementById('profilePreview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Auto-submit the form after preview
        document.getElementById('uploadForm').submit();
    }
}
</script>

</body>
</html>