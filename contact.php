<?php
$currentYear = date("Y");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Contact Us | Modern Library Portal</title>
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
    .contact-grid { display:grid; place-items:center; margin-bottom:40px; }
    .contact-card { background:var(--white); padding:30px; border-radius:8px; max-width:600px;
      box-shadow:0 4px 6px rgba(0,0,0,0.05); width:100%; }
    .contact-card form { display:grid; gap:15px; }
    .contact-card input, .contact-card textarea {
      width:100%; padding:15px; border:1px solid #ccc; border-radius:5px; font-size:16px;
    }
    .contact-card button {
      padding:15px; background:var(--accent); color:var(--white); border:none;
      border-radius:5px; font-size:18px; cursor:pointer; transition:background 0.3s;
    }
    .contact-card button:hover { background:var(--primary-light); }

  </style>
</head>
<body>

 <?php
 include('header.php');
 ?>

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
 <?php
 include('footer.php');
 ?>
</body>
</html>
