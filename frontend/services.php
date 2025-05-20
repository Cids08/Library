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
    <link rel="stylesheet" href="../public/css/services.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/footer.css">

    <title>Services | Modern Library </title>
</head>
<body>
    <?php include('../includes/header.php'); ?>

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

<?php include('../includes/footer.php'); ?>

</body>
</html>
