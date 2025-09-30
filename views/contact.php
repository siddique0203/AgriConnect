<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
  <link rel="icon" href="../public/images/logo1.png">
  <title>Contact Us-AgriConnect</title>
</head>
<body>
<header>
  <nav>
    <div class="nav-logo">
      <h3 class="nav-title"><span>Agri</span>Connect</h3>
    </div>
    <ul>
      <li><a href="../index.php">Home</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="register.php">Sign Up</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="contact-container">
    <h2>Contact Us</h2>

    <?php if(isset($_SESSION['contact_success'])): ?>
      <p class="success-msg"><?php echo $_SESSION['contact_success']; unset($_SESSION['contact_success']); ?></p>
    <?php elseif(isset($_SESSION['contact_error'])): ?>
      <p class="error-msg"><?php echo $_SESSION['contact_error']; unset($_SESSION['contact_error']); ?></p>
    <?php endif; ?>

    <form method="POST" action="../controllers/ContactController.php" class="contact-form">
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" required placeholder="Enter your name">

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" required placeholder="Enter your email">

      <label for="phone">Phone Number</label>
      <input type="text" id="phone" name="phone" placeholder="Optional">

      <label for="subject">Subject</label>
      <input type="text" id="subject" name="subject" required placeholder="Message subject">

      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" required placeholder="Write your message..."></textarea>

      <button type="submit" class="contact-btn">Send Message</button>
    </form>
  </div>
</main>

<footer>
  <div class="footer-container">
    <div class="footer-text">
      <div class="footer-logo">
        <h3>Agri<span>Connect</span></h3>
      </div>
      <p>AgriConnect is a localized platform that connects farmers, suppliers, and consumers, making agriculture more accessible, efficient, and sustainable.</p>
    </div>
    <hr/>
    <div class="footer-copyright">
      <p>&copy; 2025 AgriConnect. All Rights Reserved.</p>
    </div>
  </div>
</footer>
</body>
</html>
