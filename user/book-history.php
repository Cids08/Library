<?php
session_start();

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Fetch ALL borrow history records with student names from libmas table
$stmt = $pdo->query("
    SELECT borrow_history.*, libmas.full_name, libmas.student_number 
    FROM borrow_history
    LEFT JOIN libmas ON borrow_history.student_number = libmas.student_number
    ORDER BY borrow_history.date DESC
");
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book History | Modern Library</title>
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
            --success: #38a169;
            --danger: #e53e3e;
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

        /* Table Section */
        .history-section {
            background-color: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray);
        }

        th {
            background-color: var(--light-blue);
            color: var(--primary-dark);
        }

        td {
            background-color: var(--white);
        }

        .status-returned {
            color: var(--success);
            font-weight: bold;
        }

        .status-overdue {
            color: var(--danger);
            font-weight: bold;
        }

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
                border-left: 3px solid var(--primary);
                border-bottom: none;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Header -->
    <header class="portal-header">
        <div class="portal-title">
            <h1>Book Borrowing History</h1>
        </div>
        <div class="student-profile">
            <?php
            // Set default avatar
            $default_avatar = "/api/placeholder/60/60";
            $profile_picture = $_SESSION['profile_picture'] ?? $default_avatar;
            $full_name = $_SESSION['full_name'] ?? 'Guest';
            $student_id = $_SESSION['student_number'] ?? 'N/A';
            ?>
            <img src="<?= htmlspecialchars($profile_picture) ?>" alt="Student Profile" class="student-avatar">
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
        <a href="#" class="nav-item active"><i class="fas fa-history"></i> Book History</a>
        <a href="account-setting.php" class="nav-item"><i class="fas fa-user-cog"></i> Account Settings</a>
    </nav>

    <!-- Book History Section -->
    <section class="history-section">
        <h2>All Borrowing Records</h2>
        <?php if (!empty($history)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Borrowed On</th>
                        <th>Returned On</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $entry): ?>
                        <tr>
                            <td><?= htmlspecialchars($entry['full_name'] ?? $entry['student_number']) ?></td>
                            <td><?= htmlspecialchars($entry['student_number']) ?></td>
                            <td><?= htmlspecialchars($entry['title']) ?></td>
                            <td><?= htmlspecialchars($entry['author']) ?></td>
                            <td><?= htmlspecialchars($entry['date']) ?></td>
                            <td>
                                <?php if ($entry['return_date']): ?>
                                    <?= htmlspecialchars($entry['return_date']) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="<?= $entry['return_date'] ? 'status-returned' : 'status-overdue' ?>">
                                <?= $entry['return_date'] ? 'Returned' : 'Borrowed' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No borrowing records found.</p>
        <?php endif; ?>
    </section>
</div>
</body>
</html>