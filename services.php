<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore the services offered by the Modern Library Portal.">
    <meta name="keywords" content="library services, borrowing books, digital access, research assistance">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Services | Modern Library Portal</title>
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

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .service-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            font-size: 50px;
            color: var(--primary-light);
            margin-bottom: 15px;
        }

        .service-card h3 {
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .service-card p {
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
  <?php
   include('header.php');
  ?>
<!-- Services Section -->
<section class="container">
    <h2 class="section-title">Our Services</h2>
    <div class="services-grid">
        <div class="service-card">
            <div class="service-icon">
                <i class="fa-solid fa-book"></i>
            </div>
            <h3>Book Borrowing</h3>
            <p>Borrow physical and digital books from our vast collection for free. Enjoy extended access with a valid library card.</p>
        </div>
        <div class="service-card">
            <div class="service-icon">
                <i class="fa-solid fa-laptop"></i>
            </div>
            <h3>Digital Resources</h3>
            <p>Access e-books, audiobooks, research papers, and more, from the comfort of your home using our digital portal.</p>
        </div>
        <div class="service-card">
            <div class="service-icon">
                <i class="fa-solid fa-search"></i>
            </div>
            <h3>Research Assistance</h3>
            <p>Our experts offer research support for students, academics, and enthusiasts looking for specific information.</p>
        </div>
        <div class="service-card">
            <div class="service-icon">
                <i class="fa-solid fa-person-chalkboard"></i>
            </div>
            <h3>Workshops & Training</h3>
            <p>Join our various workshops on research skills, technology, and creative writing, conducted by professionals.</p>
        </div>
    </div>
</section>

 <?php include('footer.php'); ?>

</body>
</html>
