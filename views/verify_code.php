<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
<title>Verify Code - AgriConnect</title>
</head>
<body>
    <header>
        <nav>
            <div class="nav-logo">
                <h3 class="nav-title"><span>Agri</span>Connect</h3>
            </div>
            <ul>
                <li><a href="../index.php"><span>Home</span></a></li>
            </ul>
        </nav>
    </header>
    <main>
       <!-- Server Messages -->
        <?php if (isset($_SESSION['fp_error'])): ?>
            <div id="server-error-message">
                <?php
                    echo $_SESSION['fp_error'];
                    unset($_SESSION['fp_error']); // Clear the message
                ?>
            </div>
        <?php endif; ?>
        
        <div id="forgot-password-hero" style="background:rgba(197, 244, 193, 1);" >
        
            <div id="forgot-Pass-Content">
                <h1>Verification Code</h1><br>
                <p style="margin-bottom: 25px;">Please Check Your E-mail for code</p>
                        
                <form action="../controllers/C_verifyCode.php" method="post">
                    <input style="margin: 40px 0;" class="inp-2" type="text" name="code" placeholder="Enter code" required>
                    <button class="reg-btn" type="submit">Verify Code</button>
                </form>
            
            </div>
        </div>
    </main>   
    <footer>
      <div class="footer-container">
        <div class="footer-text">
          <div class="footer-logo">
            <img src="public/images/logo1.png" alt="" style="height:40px">
            <h3>Agri<span>Connect</span></h3>
          </div>
          <p>
            AgriConnect is a localized platform that connects farmers, suppliers, and consumers, making agriculture more accessible, efficient, and sustainable.
          </p>
        </div>
        <div class="footer-icon">
          <i class="fa-brands fa-twitter"></i>
          <i class="fa-brands fa-facebook"></i>
          <i class="fa-brands fa-instagram"></i>
          <!-- <i class="fa-brands fa-github"></i> -->
        </div>
        <hr />
        <div class="footer-copyright">
          <h3><p>&copy; 2025 AgriConnect. All Rights Reserved.</h3>
        </div>
      </div>
    </footer>
</body>
</html>
