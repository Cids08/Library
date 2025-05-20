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
    <link rel="stylesheet" href="../public/css/contact.css">
    <link rel="stylesheet" href="../public/css/header.css">
    <link rel="stylesheet" href="../public/css/footer.css">

    <title>Contact | Modern Library </title>
</head>
<body>

  <?php include('../includes/header.php'); ?>

<section class="container">
  <h2 class="section-title">Contact Us</h2>
  <div class="contact-grid">
    <div class="contact-card">
      <form action="contact-process.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" required />
        <input type="email" name="email" placeholder="Your Email" required />
        <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>
  </div>
</section>
    <?php include('../includes/footer.php'); ?>
</body>
</html>
