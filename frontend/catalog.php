<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse the full catalog of the Modern Library Portal.">
    <meta name="keywords" content="library catalog, books, e-books, reading, borrow">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="..public/css/footer.css" />  
    <title>Library Catalog | Modern Library Portal</title>
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

        .section-title {
            text-align: center;
            margin: 50px 0 20px;
            font-size: 32px;
            color: var(--primary-dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .feature-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .feature-image {
            width: 80px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 15px;
            background-color: #e2e8f0;
            border-radius: 8px;
        }

        .feature-card h3 {
            margin-bottom: 10px;
            color: var(--primary-dark);
        }

        .feature-card p {
            color: var(--dark-gray);
        }

        .search-bar {
            text-align: center;
            margin: 30px 0 60px;
        }

        .search-bar input {
            padding: 14px;
            width: 70%;
            max-width: 500px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <?php include('../includes/header.php'); ?>
<!-- Catalog Section -->
<section class="container">
    <h2 class="section-title">Library Catalog</h2>
    <div class="search-bar">
        <input type="text" placeholder="Search books, authors, topics...">
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <img src="../public/images/sample.jpg" alt="The Great Gatsby" class="feature-image">
            <h3>The Great Gatsby</h3>
            <p>Classic American novel by F. Scott Fitzgerald set in the Jazz Age.</p>
        </div>
        <div class="feature-card">
            <img src="images/sample.jpg" alt="The Great Gatsby" class="feature-image">
            <h3>Atomic Habits</h3>
            <p>James Clear's guide to building good habits and breaking bad ones.</p>
        </div>
        <div class="feature-card">
            <img src="../public/images/sample.jpg" alt="The Great Gatsby" class="feature-image">
            <h3>1984</h3>
            <p>George Orwell's dystopian tale of surveillance and government control.</p>
        </div>
        <div class="feature-card">
            <img src="../public/images/sample.jpg" alt="The Great Gatsby" class="feature-image">
            <h3>Educated</h3>
            <p>A memoir by Tara Westover about learning, family, and resilience.</p>
        </div>
    </div>
</section>
 <?php
    include('../includes/footer.php');
 ?>

</body>
</html>
