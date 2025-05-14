<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About Us | Modern Library Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --primary-dark: #1a3a5f; --primary: #2c5282; --primary-light: #4299e1;
      --accent: #63b3ed; --light-blue: #bee3f8; --very-light-blue: #ebf8ff;
      --white: #ffffff; --gray: #e2e8f0; --dark-gray: #4a5568;
    }
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',sans-serif; }
    body { background:var(--very-light-blue); color:var(--primary-dark); line-height:1.6; }
    .container { max-width:1200px; margin:0 auto; padding:0 20px; }
    .section-title { text-align:center; margin:50px 0 20px; font-size:32px; color:var(--primary-dark); }
    .about-grid { display:grid; grid-template-columns:1fr; gap:30px; margin-bottom:40px; }
    .about-card { background:var(--white); border-radius:8px; padding:30px; text-align:center; box-shadow:0 4px 6px rgba(0,0,0,0.05); }
    .about-card img { width:150px; height:150px; border-radius:50%; object-fit:cover; margin-bottom:20px; }
    .about-card h3 { margin:15px 0 10px; color:var(--primary); font-size:24px; }
    .about-card p { color:var(--dark-gray); line-height:1.7; }
  </style>
</head>
<body>

 <?php
 include('header.php');
 ?>
<section class="container">
  <h2 class="section-title">About Us</h2>
  <div class="about-grid">
    <div class="about-card">
      <img src="https://via.placeholder.com/150" alt="Library Team">
      <h3>Our Mission</h3>
      <p>To foster a culture of learning by providing inclusive access to resources, programs, and community events.</p>
      <h3>Our Vision</h3>
      <p>To create a connected community where knowledge is open, exploration is encouraged, and discovery is limitless.</p>
    </div>
  </div>
</section>
 <?php
 include('footer.php');
 ?>
</body>
</html>
