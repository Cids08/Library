<?php
session_start();

if (!isset($_SESSION['student_number'])) {
    header("Location: auth.php");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$student_number = $_SESSION['student_number'];
$search_query = $_GET['q'] ?? '';

// Fetch borrowed book titles
$stmt = $pdo->prepare("SELECT title FROM borrow_history WHERE student_number = ? AND action = 'borrowed' AND return_date IS NULL");
$stmt->execute([$student_number]);
$borrowed_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
$borrowed_titles = array_map(fn($b) => strtolower(trim($b['title'])), $borrowed_books);

// Fetch books based on search query
if (!empty($search_query)) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR subject LIKE ?");
    $term = "%$search_query%";
    $stmt->execute([$term, $term, $term]);
} else {
    $stmt = $pdo->query("SELECT * FROM books ORDER BY title ASC");
}
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- [HTML/CSS from your template goes here] -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Catalog | Modern Library</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    <style>
                /* Same styles from your theme */
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

        .search-section {
            margin-bottom: 30px;
        }

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
            font-weight: bold;
            cursor: pointer;
        }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .book-card {
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 15px;
            transition: 0.3s;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }

        .book-cover {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .book-title {
            font-size: 16px;
            font-weight: bold;
            color: var(--primary-dark);
        }

        .book-author {
            font-size: 14px;
            color: var(--dark-gray);
            margin-bottom: 10px;
        }

        .btn {
            background-color: var(--primary);
            color: var(--white);
            padding: 8px 12px;
            text-align: center;
            display: inline-block;
            font-size: 14px;
            border-radius: 4px;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: var(--primary-light);
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
        }

    </style>
</head>
<body>
<div class="container">
    <!-- Header and Navigation as before -->
     <header class="portal-header">
            <div class="portal-title">
                <h1>Search Catalog</h1>
            </div>
            <div class="student-profile">
                <?php
                $default_avatar = "/api/placeholder/60/60";
                $profile_picture = $_SESSION['profile_picture'] ?? $default_avatar;
                $full_name = $_SESSION['full_name'] ?? 'Student';
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
            <a href="#" class="nav-item active"><i class="fas fa-search"></i> Search Catalog</a>
            <a href="book-history.php" class="nav-item"><i class="fas fa-history"></i> Book History</a>
            <a href="account-setting.php" class="nav-item"><i class="fas fa-user-cog"></i> Account Settings</a>
        </nav>

     

    <!-- Search Form -->
    <form class="search-bar" method="GET" action="search-catalog.php">
        <input type="text" name="q" id="searchInput" placeholder="Search by title, author or subject..." value="<?= htmlspecialchars($search_query) ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Book Grid -->
    <div class="book-grid" id="bookGrid">
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card"
                     data-title="<?= strtolower(htmlspecialchars($book['title'])) ?>"
                     data-author="<?= strtolower(htmlspecialchars($book['author'])) ?>"
                     data-subject="<?= strtolower(htmlspecialchars($book['subject'])) ?>">
                    <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="book-cover">
                    <p class="book-title"><?= htmlspecialchars($book['title']) ?></p>
                    <p class="book-author"><?= htmlspecialchars($book['author']) ?></p>

                    <?php if (in_array(strtolower(trim($book['title'])), $borrowed_titles)): ?>
                        <p style="color: #e53e3e; font-weight: bold;">Already Borrowed</p>
                    <?php else: ?>
                        <a href="borrow_book.php?title=<?= urlencode($book['title']) ?>&author=<?= urlencode($book['author']) ?>&cover_image=<?= urlencode($book['cover_image']) ?>" class="btn">Borrow</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No books found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const bookCards = document.querySelectorAll('.book-card');

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();

        bookCards.forEach(card => {
            const title = card.getAttribute('data-title');
            const author = card.getAttribute('data-author');
            const subject = card.getAttribute('data-subject');

            if (title.includes(query) || author.includes(query) || subject.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
</body>
</html>