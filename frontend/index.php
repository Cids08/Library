<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Modern Library Portal – Explore collections, borrow digital content, and join community events.">
    <meta name="keywords" content="library, books, e-books, research, community events">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/footer.css">

    <title>Modern Library Portal</title>
</head>
<body>
    <?php
    // Sample data - in a real application, this would come from a database
    $featuredCollections = [
        [
            "title" => "Fiction Bestsellers",
            "description" => "Explore our collection of award-winning fiction titles.",
            "image" => "../public/images/4.jpg"
        ],
        [
            "title" => "Science & Technology",
            "description" => "Discover the latest publications in science and technology.",
            "image" => "../public/images/5.jpg"
        ],
        [
            "title" => "Children's Books",
            "description" => "A world of imagination for our younger readers.",
            "image" => "../public/images/6.jpg"
        ],
        [
            "title" => "Digital Archives",
            "description" => "Access our extensive collection of digital archives and e-books.",
            "image" => "../public/images/7.jpg"
        ]
    ];

    // Get current date for the footer
    $currentYear = date("Y");
    ?>

    <?php include('../includes/header.php'); ?>

    <!-- Hero Section -->
    <section class="hero">
        <img src="../public/images/3.png" alt="Library Interior" class="hero-image">
    <div class="overlay"></div>
        <div class="hero-content">
            <h2>Welcome to Your Community Library</h2>
            <p>Discover thousands of books, resources, and digital content to expand your knowledge and imagination.</p>
            <form class="search-bar" method="GET" action="search.php">
                <input type="text" name="query" placeholder="Search for books, authors, or topics...">
                <button type="submit">Search</button>
            </form>

        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Library Services</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Digital Borrowing</h3>
                    <p>Access e-books, audiobooks, and digital magazines through our online portal and mobile app.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3>Learning Resources</h3>
                    <p>Utilize our extensive academic resources, study spaces, and research assistance.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎭</div>
                    <h3>Community Events</h3>
                    <p>Join book clubs, workshops, author readings, and family-friendly activities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="collections">
        <div class="container">
            <h2 class="section-title">Featured Collections</h2>
            <div class="collections-grid">
                <?php foreach ($featuredCollections as $collection): ?>
                <div class="collection-card">
                    <img src="<?php echo htmlspecialchars($collection['image']); ?>" alt="<?php echo htmlspecialchars($collection['title']); ?>">
                    <div class="collection-content">
                        <h3><?php echo htmlspecialchars($collection['title']); ?></h3>
                        <p><?php echo htmlspecialchars($collection['description']); ?></p>
                        <a href="#" class="btn">Explore</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php include('../includes/footer.php'); ?>
</body>
</html>