<?php
session_start();

// Check if user is logged-in
if (!isset($_SESSION['student_number'])) {
    header("Location: auth.php");
    exit();
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $student_number = $_SESSION['student_number'];

    // Pagination setup
    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Get total borrowed books
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow_history WHERE student_number = ? AND return_date IS NULL");
    $stmt->execute([$student_number]);
    $total_borrowed = (int)$stmt->fetchColumn();

    // Get current borrowed books with pagination
    $stmt = $pdo->prepare("
        SELECT * FROM borrow_history 
        WHERE student_number = ? AND return_date IS NULL
        ORDER BY date DESC
        LIMIT ? OFFSET ?
    ");
    $stmt->bindValue(1, $student_number, PDO::PARAM_STR);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    $stmt->execute();
    $borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total recent activity
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM borrow_history WHERE student_number = ?");
    $stmt->execute([$student_number]);
    $total_activity = (int)$stmt->fetchColumn();

    // Get recent activity with pagination
    $activity_page = isset($_GET['activity_page']) ? (int)$_GET['activity_page'] : 1;
    $activity_offset = ($activity_page - 1) * $limit;

    $stmt = $pdo->prepare("SELECT * FROM borrow_history WHERE student_number = ? ORDER BY date DESC LIMIT ? OFFSET ?");
    $stmt->bindValue(1, $student_number, PDO::PARAM_STR);
    $stmt->bindValue(2, $limit, PDO::PARAM_INT);
    $stmt->bindValue(3, $activity_offset, PDO::PARAM_INT);
    $stmt->execute();
    $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Portal | Modern Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css "/>
    <style>
       /* Blue color palette matching existing site */
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
            line-height: 1.6;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Student portal layout */
        .portal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--light-blue);
            margin-bottom: 30px;
        }

        .portal-title h1 {
            color: var(--primary-dark);
            font-size: 28px;
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
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .student-info p {
            color: var(--dark-gray);
            font-size: 14px;
        }

        /* Portal navigation */
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
            color: var(--primary-dark);
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
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

        /* Dashboard content */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .dashboard-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--light-blue);
        }

        .card-title {
            font-size: 18px;
            color: var(--primary-dark);
        }

        .card-icon {
            font-size: 24px;
            color: var(--primary);
        }

        .btn {
            display: inline-block;
            background-color: var(--primary);
            color: var(--white);
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            margin-top: 15px;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background-color: var(--primary-light);
        }

        /* Book items styling */
        .book-item {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--gray);
        }

        .book-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .book-cover {
            width: 60px;
            height: 90px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .book-details h4 {
            color: var(--primary-dark);
            margin-bottom: 5px;
        }

        .book-details p {
            color: var(--dark-gray);
            font-size: 14px;
            margin-bottom: 5px;
        }

        .due-date {
            color: #e53e3e;
            font-weight: 500;
        }

        /* Search bar */
        .search-bar {
            display: flex;
            margin-bottom: 20px;
        }

        .search-bar input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid var(--gray);
            border-radius: 4px 0 0 4px;
            font-size: 16px;
        }

        .search-bar button {
            background-color: var(--accent);
            color: var(--white);
            border: none;
            padding: 12px 20px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: bold;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .portal-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .portal-nav {
                flex-direction: column;
            }

            .nav-item {
                border-bottom: 1px solid var(--gray);
            }

            .nav-item.active {
                border-bottom: 1px solid var(--gray);
                border-left: 3px solid var(--primary);
            }
        }
        .book-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--gray);
}

.book-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.book-details {
    flex: 1;
    margin-left: 15px;
}
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <header class="portal-header">
        <div class="portal-title">
            <h1>Student Portal</h1>
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
            <a href="logout.php" class="btn" style="margin-left: 20px; background-color: #e53e3e;">Logout</a>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="portal-nav">
        <a href="#" class="nav-item active"><i class="fas fa-book"></i> My Borrowed Books</a>
        <a href="search-catalog.php" class="nav-item"><i class="fas fa-search"></i> Search Catalog</a>
        <a href="book-history.php" class="nav-item"><i class="fas fa-history"></i> Book History</a>
        <a href="account-setting.php" class="nav-item"><i class="fas fa-user-cog"></i> Account Settings</a>
    </nav>

    <!-- Dashboard Content -->
    <div class="dashboard-grid">
        <!-- Currently Borrowed Books -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Currently Borrowed</h3>
                <div class="card-icon"><i class="fas fa-book-reader"></i></div>
            </div>

            <?php if (!empty($borrowed_books)): ?>
                <?php foreach ($borrowed_books as $book): ?>
    <div class="book-item" style="align-items: center;">
        <img src="<?= htmlspecialchars($book['cover_image'] ?? '/api/placeholder/60/90') ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
        <div class="book-details" style="flex: 1;">
            <h4><?= htmlspecialchars($book['title']) ?></h4>
            <p><?= htmlspecialchars($book['author']) ?></p>
            <p class="due-date">Due: <?= htmlspecialchars($book['date'] ? date('M d, Y', strtotime($book['date'] . ' +14 days')) : '-') ?></p>
        </div>
        <form method="POST" action="return_book.php" style="margin-left: auto; text-align: right;">
            <input type="hidden" name="history_id" value="<?= $book['id'] ?>">
            <button type="submit" class="btn" style="background-color: #e53e3e;">Return</button>
        </form>
    </div>
<?php endforeach; ?>

                <!-- Pagination -->
                <div style="margin-top: 20px; text-align: center;">
                    <?php
                    $total_pages = ceil($total_borrowed / $limit);
                    for ($i = 1; $i <= $total_pages; $i++) {
                        echo "<a href='?page=$i' class='btn' style='margin-right: 10px; background-color: " . ($page == $i ? '#4299e1' : '') . ";'>" . $i . "</a>";
                    }
                    ?>
                </div>

            <?php else: ?>
                <p>No books currently borrowed.</p>
            <?php endif; ?>
            <a href="book-history.php" class="btn">View All History</a>
        </div>

        <!-- Quick Search -->
        <div class="dashboard-card">
            <div class="card-header">
                <h3 class="card-title">Quick Search</h3>
                <div class="card-icon"><i class="fas fa-search"></i></div>
            </div>
            <form class="search-bar" action="search-catalog.php" method="GET">
                <input type="text" name="q" placeholder="Search titles, authors, subjects...">
                <button type="submit" class="btn">Search</button>
            </form>
            <p>Popular subjects:</p>
            <div style="margin-top: 10px;">
                <a href="#" class="btn" style="background-color: var(--light-blue); color: var(--primary-dark);">Computer Science</a>
                <a href="#" class="btn" style="background-color: var(--light-blue); color: var(--primary-dark);">Fiction</a>
                <a href="#" class="btn" style="background-color: var(--light-blue); color: var(--primary-dark);">History</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="dashboard-card">
        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
            <div class="card-icon"><i class="fas fa-clock"></i></div>
        </div>

        <?php if (!empty($recent_activity)): ?>
            <?php foreach ($recent_activity as $activity): ?>
                <div class="book-item">
                    <img src="<?= htmlspecialchars($activity['cover_image'] ?? '/api/placeholder/60/90') ?>" alt="Book Cover" class="book-cover">
                    <div class="book-details">
                        <h4><?= htmlspecialchars($activity['action'] . ': ' . $activity['title']) ?></h4>
                        <p><?= htmlspecialchars($activity['author']) ?></p>
                        <p><?= htmlspecialchars($activity['date']) ?></p>
                        <?php if ($activity['return_date']): ?>
                            <p style="color: green; font-weight: bold;">Returned on: <?= htmlspecialchars($activity['return_date']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <div style="margin-top: 20px; text-align: center;">
                <?php
                $total_pages_activity = ceil($total_activity / $limit);
                for ($i = 1; $i <= $total_pages_activity; $i++) {
                    echo "<a href='?activity_page=$i' class='btn' style='margin-right: 10px; background-color: " . ($activity_page == $i ? '#4299e1' : '') . ";'>" . $i . "</a>";
                }
                ?>
            </div>

        <?php else: ?>
            <p>No recent activity found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>