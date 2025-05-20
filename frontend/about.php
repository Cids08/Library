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
    <link rel="stylesheet" href="../public/css/aboutus.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/footer.css">
    <title>About Us | Modern Library </title>
</head>
<body>

 <?php
 include('../includes/header.php'); 
 ?>
<section class="container">
  <h2 class="section-title">About Us</h2>
  <div class="about-grid">
    <div class="about-card">
      <img src="../public/images/7.jpg" alt="Library Team">
      <h3>Our Mission</h3>
      <p>To foster a culture of learning by providing inclusive access to resources, programs, and community events.</p>
      <h3>Our Vision</h3>
      <p>To create a connected community where knowledge is open, exploration is encouraged, and discovery is limitless.</p>
    </div>
  </div>
</section>
<?php include('../includes/footer.php'); ?>
</body>
</html>
