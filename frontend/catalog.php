<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=libmas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get filter and pagination values
    $subject_filter = $_GET['subject'] ?? '';
    $page = isset($_GET['page']) ? max((int)$_GET['page'], 1) : 1;
    $limit = 8; // Books per page
    $offset = ($page - 1) * $limit;

    // Count total filtered books
    $count_query = "SELECT COUNT(*) FROM books WHERE available = 1";
    if (!empty($subject_filter)) {
        $count_query .= " AND subject = ?";
    }
    $stmt = $pdo->prepare($count_query);
    if (!empty($subject_filter)) {
        $stmt->execute([$subject_filter]);
    } else {
        $stmt->execute();
    }
    $total_books = $stmt->fetchColumn();
    $total_pages = ceil($total_books / $limit);

    // Fetch books with optional subject filter
    $query = "SELECT * FROM books WHERE available = 1";
    if (!empty($subject_filter)) {
        $query .= " AND subject = ?";
    }
    $query .= " ORDER BY title ASC LIMIT ? OFFSET ?";
    
    $stmt = $pdo->prepare($query);
    if (!empty($subject_filter)) {
        $stmt->bindValue(1, $subject_filter);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
    } else {
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<?php $currentYear = date("Y"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog | Modern Library Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css ">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/footer.css">
    <style>
        /* Your existing styles */
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
        }

        .filter-button {
            padding: 8px 16px;
            background-color: #f3f4f6;
            border: none;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-button.active {
            background-color: #2c5282;
            color: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 40px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #2c5282;
        }

        .pagination .active {
            background-color: #2c5282;
            color: white;
            border-color: #2c5282;
        }

        .pagination-disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .search-bar {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .search-bar input {
            width: 100%;
            max-width: 400px;
            padding: 10px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<?php include('../includes/header.php'); ?>

<!-- Catalog Section -->
<section class="container">
    <h2 class="section-title">Library Catalog</h2>

    <!-- Subject Filters -->
    <div class="filters">
        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="filter-button <?= empty($subject_filter) ? 'active' : '' ?>">All</a>
        <?php
        // Get all available subjects from database
        $subjects = $pdo->query("SELECT DISTINCT subject FROM books ORDER BY subject")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($subjects as $subject):
            $active = ($subject === $subject_filter) ? 'active' : '';
            echo "<a href='{$_SERVER['PHP_SELF']}?subject=" . urlencode($subject) . "' class='filter-button $active'>$subject</a>";
        endforeach;
        ?>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" placeholder="Search books, authors, topics..." id="bookSearchInput">
    </div>

    <!-- Book Grid -->
    <div class="features-grid" id="bookGrid">
        <?php if ($books): ?>
            <?php foreach ($books as $book): ?>
                <div class="feature-card" data-title="<?= htmlspecialchars(strtolower($book['title'])) ?>"
                                         data-author="<?= htmlspecialchars(strtolower($book['author'])) ?>"
                                         data-subject="<?= htmlspecialchars(strtolower($book['subject'])) ?>">
                    <img src="<?= htmlspecialchars($book['cover_image'] ?: '../public/images/default.jpg') ?>"
                         alt="<?= htmlspecialchars($book['title']) ?>"
                         class="feature-image">
                    <h3><?= htmlspecialchars($book['title']) ?></h3>
                    <p><?= htmlspecialchars($book['author']) ?></p>
                    <small style="color:#6b7280"><?= htmlspecialchars($book['subject']) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No books found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>?subject=<?= urlencode($subject_filter) ?>&page=<?= $page - 1 ?>">
                    ← Previous
                </a>
            <?php else: ?>
                <span class="pagination-disabled">← Previous</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>?subject=<?= urlencode($subject_filter) ?>&page=<?= $i ?>"
                   class="<?= $i == $page ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>?subject=<?= urlencode($subject_filter) ?>&page=<?= $page + 1 ?>">
                    Next →
                </a>
            <?php else: ?>
                <span class="pagination-disabled">Next →</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php include('../includes/footer.php'); ?>

<script>
    const cards = document.querySelectorAll('.feature-card');
    const searchInput = document.getElementById('bookSearchInput');

    function filterBooks() {
        const query = searchInput.value.trim().toLowerCase();

        cards.forEach(card => {
            const title = card.getAttribute('data-title');
            const author = card.getAttribute('data-author');
            const subject = card.getAttribute('data-subject');

            if (
                title.includes(query) ||
                author.includes(query) ||
                subject.includes(query)
            ) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterBooks);
</script>

</body>
</html>