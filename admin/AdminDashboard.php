<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['student_number']) || 
   ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'superadmin')) {
    header("Location: /Library/frontend/auth.php");
    exit();
}


try {
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total Books
    $stmt_books = $pdo->query("SELECT COUNT(*) FROM books");
    $total_books = $stmt_books->fetchColumn();

    // Total Members
    $stmt_members = $pdo->query("SELECT COUNT(*) FROM libmas WHERE role = 'student'");
    $total_members = $stmt_members->fetchColumn();

    // Borrowed Today
    $today = date('Y-m-d');
    $stmt_borrowed_today = $pdo->prepare("SELECT COUNT(*) FROM borrow_history WHERE date = ?");
    $stmt_borrowed_today->execute([$today]);
    $borrowed_today = $stmt_borrowed_today->fetchColumn();

    // Overdue Books
    $stmt_overdue = $pdo->prepare("SELECT COUNT(*) FROM borrow_history WHERE return_date IS NULL AND DATE_ADD(date, INTERVAL 14 DAY) < CURDATE()");
    $stmt_overdue->execute();
    $overdue_books = $stmt_overdue->fetchColumn();

    // Recent Borrowed Books (last 10 entries)
$stmt_recent = $pdo->query("SELECT bh.title, bh.author, u.first_name, u.last_name, bh.date, bh.return_date 
                            FROM borrow_history bh
                            JOIN libmas u ON bh.student_number = u.student_number
                            ORDER BY bh.date DESC LIMIT 10");
$recent_books = $stmt_recent->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css ">
    <link rel="stylesheet" href="../public/css/admindashboard.css">
</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <?php include('../includes/adminsidebar.php'); ?>
    <!-- Topbar -->
    <header class="topbar">
        <h2 class="page-title">Admin Dashboard</h2>
    </header>
    <!-- Mobile Sidebar -->
    <div id="mobile-sidebar" class="sidebar-overlay" style="display: none;">
        <div class="mobile-sidebar">
            <div class="mobile-sidebar-header">
                <h1>Library Admin</h1>
                <button class="close-button" id="close-sidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <div class="sidebar-section">
                        <p class="sidebar-section-title">Main</p>
                        <a href="AdminDashboard.php" class="sidebar-link">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="books.php" class="sidebar-link">
                            <i class="fas fa-book"></i>
                            <span>Books</span>
                        </a>
                        <a href="members.php" class="sidebar-link">
                            <i class="fas fa-users"></i>
                            <span>Members</span>
                        </a>
                    </div>
                    <div class="sidebar-section">
                        <p class="sidebar-section-title">Transactions</p>
                        <a href="borrowed.php" class="sidebar-link">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Borrowed</span>
                        </a>
                        <a href="overdue.php" class="sidebar-link">
                            <i class="fas fa-clock"></i>
                            <span>Overdue</span>
                        </a>
                        <a href="history.php" class="sidebar-link">
                            <i class="fas fa-history"></i>
                            <span>History</span>
                        </a>
                    </div>
                    <div class="sidebar-section">
                        <p class="sidebar-section-title">System</p>
                        <a href="reports.php" class="sidebar-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Reports</span>
                        </a>
                        <a href="settings.php" class="sidebar-link">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                        </a>
                    </div>
                </nav>

            </div>
            <div class="sidebar-footer">
                <div class="profile">
                    <img class="profile-image" src="/api/placeholder/40/40" alt="Admin profile picture">
                    <div class="profile-info">
                        <p class="profile-name">John Doe</p>
                        <p class="profile-role">Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>  

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="card">
            <div class="card-content">
                <div class="card-icon blue"><i class="fas fa-book"></i></div>
                <div class="card-info">
                    <p class="card-label">Total Books</p>
                    <p class="card-value"><?= $total_books ?></p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div class="card-icon green"><i class="fas fa-users"></i></div>
                <div class="card-info">
                    <p class="card-label">Members</p>
                    <p class="card-value"><?= $total_members ?></p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div class="card-icon yellow"><i class="fas fa-exchange-alt"></i></div>
                <div class="card-info">
                    <p class="card-label">Borrowed Today</p>
                    <p class="card-value"><?= $borrowed_today ?></p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-content">
                <div class="card-icon red"><i class="fas fa-clock"></i></div>
                <div class="card-info">
                    <p class="card-label">Overdue</p>
                    <p class="card-value"><?= $overdue_books ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Borrowed Books Table -->
    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Recent Borrowed Books</h3>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Borrower</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_books as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['title']) ?></td>
                            <td><?= htmlspecialchars($book['borrower']) ?></td>
                            <td><?= htmlspecialchars($book['date']) ?></td>
                            <td>
                                <?= date('Y-m-d', strtotime($book['date'] . ' +14 days')) ?>
                            </td>
                            <td>
                                <?php
                                if ($book['return_date']):
                                    echo '<span class="status-badge status-returned">Returned</span>';
                                elseif (strtotime($book['date'] . ' +14 days') < strtotime(date('Y-m-d'))):
                                    echo '<span class="status-badge status-overdue">Overdue</span>';
                                else:
                                    echo '<span class="status-badge status-active">Active</span>';
                                endif;
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>