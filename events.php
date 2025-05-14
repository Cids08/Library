<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore the upcoming events and workshops at the Modern Library Portal.">
    <meta name="keywords" content="library events, workshops, book clubs, seminars">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Events | Modern Library Portal</title>
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
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

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

        nav ul li a:hover,
        nav ul li a.active {
            background-color: var(--primary-light);
        }

        .section-title {
            text-align: center;
            margin: 50px 0 20px;
            font-size: 32px;
            color: var(--primary-dark);
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .event-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .event-icon {
            font-size: 50px;
            color: var(--primary-light);
            margin-bottom: 15px;
        }

        .event-card h3 {
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .event-card p {
            color: var(--dark-gray);
        }

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
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
            color: var(--gray);
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
                margin-top: 10px;
            }

            nav ul li {
                margin: 10px;
            }

            .section-title {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <div class="container header-content">
        <div class="logo">
            <span class="logo-icon">📚</span>
            <h1>Modern Library Portal</h1>
        </div>
        <nav>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="catalog.php">Catalog</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="events.php" class="active">Events</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Events Section -->
<section class="container">
    <h2 class="section-title">Upcoming Events</h2>
    <div class="events-grid">
        <div class="event-card">
            <div class="event-icon">
                <i class="fa-solid fa-calendar"></i>
            </div>
            <h3>Library Book Club</h3>
            <p>Join our monthly book club discussions on the latest bestsellers and classic novels.</p>
            <p><strong>Date:</strong> June 10, 2025</p>
        </div>
        <div class="event-card">
            <div class="event-icon">
                <i class="fa-solid fa-chalkboard-teacher"></i>
            </div>
            <h3>Research Workshop</h3>
            <p>Attend our workshop on improving your research skills with guidance from experts.</p>
            <p><strong>Date:</strong> June 15, 2025</p>
        </div>
        <div class="event-card">
            <div class="event-icon">
                <i class="fa-solid fa-microphone"></i>
            </div>
            <h3>Author Talk</h3>
            <p>Listen to a live session with a famous author discussing their latest work.</p>
            <p><strong>Date:</strong> June 20, 2025</p>
        </div>
        <div class="event-card">
            <div class="event-icon">
                <i class="fa-solid fa-paint-brush"></i>
            </div>
            <h3>Creative Writing Workshop</h3>
            <p>Enhance your writing skills with this hands-on creative writing workshop.</p>
            <p><strong>Date:</strong> June 25, 2025</p>
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
                    <li>Mon-Fri: 9 AM - 8 PM</li>
                    <li>Sat: 10 AM - 6 PM</li>
                    <li>Sun: 12 PM - 5 PM</li>
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
                    <li><a href="#">Library Card</a></li>
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
