<?php
// Library Homepage with Blue Color Palette
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Library Portal</title>
    <style>
        /* Blue color palette */
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
            padding: 0 20px;
        }

        /* Header styles */
        header {
            background-color: var(--primary);
            padding: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo h1 {
            color: var(--white);
            font-size: 24px;
            margin-left: 10px;
        }

        .logo-icon {
            font-size: 28px;
            color: var(--white);
        }

        /* Navigation */
        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: var(--white);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        nav ul li a:hover {
            background-color: var(--primary-light);
        }

        /* Hero section */
        .hero {
            position: relative;
            height: 400px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(28, 58, 95, 0.7);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: var(--white);
            padding: 20px;
            max-width: 800px;
        }

        .hero-content h2 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .search-bar {
            display: flex;
            max-width: 600px;
            margin: 0 auto;
        }

        .search-bar input {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 4px 0 0 4px;
            font-size: 16px;
        }

        .search-bar button {
            background-color: var(--accent);
            color: var(--white);
            border: none;
            padding: 15px 25px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .search-bar button:hover {
            background-color: var(--primary-light);
        }

        /* Features section */
        .features {
            padding: 60px 0;
            background-color: var(--white);
        }

        .section-title {
            text-align: center;
            margin-bottom: 40px;
            color: var(--primary-dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background-color: var(--very-light-blue);
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 40px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .feature-card h3 {
            margin-bottom: 15px;
            color: var(--primary-dark);
        }

        /* Collections section */
        .collections {
            padding: 60px 0;
            background-color: var(--light-blue);
        }

        .collections-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .collection-card {
            background-color: var(--white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .collection-card:hover {
            transform: translateY(-5px);
        }

        .collection-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .collection-content {
            padding: 20px;
        }

        .collection-content h3 {
            margin-bottom: 10px;
            color: var(--primary-dark);
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
            margin-top: 10px;
        }

        .btn:hover {
            background-color: var(--primary-light);
        }

        /* Footer */
        footer {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 40px 0 20px;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .footer-column h3 {
            font-size: 18px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--primary-light);
            padding-bottom: 10px;
            display: inline-block;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column ul li {
            margin-bottom: 10px;
        }

        .footer-column ul li a {
            color: var(--light-blue);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-column ul li a:hover {
            color: var(--white);
        }

        .copyright {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
            }

            nav ul {
                margin-top: 20px;
            }

            nav ul li {
                margin: 0 10px;
            }

            .hero-content h2 {
                font-size: 32px;
            }

            .features-grid, .collections-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // Sample data - in a real application, this would come from a database
    $featuredCollections = [
        [
            "title" => "Fiction Bestsellers",
            "description" => "Explore our collection of award-winning fiction titles.",
            "image" => "images/4.jpg"
        ],
        [
            "title" => "Science & Technology",
            "description" => "Discover the latest publications in science and technology.",
            "image" => "images/5.jpg"
        ],
        [
            "title" => "Children's Books",
            "description" => "A world of imagination for our younger readers.",
            "image" => "images/6.jpg"
        ],
        [
            "title" => "Digital Archives",
            "description" => "Access our extensive collection of digital archives and e-books.",
            "image" => "images/7.jpg"
        ]
    ];

    // Get current date for the footer
    $currentYear = date("Y");
    ?>

    <!-- Header -->
    <header>
        <div class="container header-content">
            <div class="logo">
                <span class="logo-icon">📚</span>
                <h1>Modern Library Portal</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Catalog</a></li>
                    <li><a href="#">Services</a></li>
                    <li><a href="#">Events</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <img src="images/3.png" alt="Library Interior" class="hero-image">
        <div class="overlay"></div>
        <div class="hero-content">
            <h2>Welcome to Your Community Library</h2>
            <p>Discover thousands of books, resources, and digital content to expand your knowledge and imagination.</p>
            <div class="search-bar">
                <input type="text" placeholder="Search for books, authors, or topics...">
                <button type="submit">Search</button>
            </div>
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

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>Hours & Location</h3>
                    <ul>
                        <li>Monday - Friday: 9:00 AM - 8:00 PM</li>
                        <li>Saturday: 10:00 AM - 6:00 PM</li>
                        <li>Sunday: 12:00 PM - 5:00 PM</li>
                        <li>123 Library Street, Booktown</li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Resources</h3>
                    <ul>
                        <li><a href="#">Online Catalog</a></li>
                        <li><a href="#">Research Databases</a></li>
                        <li><a href="#">Interlibrary Loan</a></li>
                        <li><a href="#">Digital Archives</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Library Card Application</a></li>
                        <li><a href="#">Donate</a></li>
                        <li><a href="#">Volunteer</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Connect</h3>
                    <ul>
                        <li><a href="#">Newsletter</a></li>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">Instagram</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                &copy; <?php echo $currentYear; ?> Modern Library Portal. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>