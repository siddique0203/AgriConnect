<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
<title>Reset Password - AgriConnect</title>
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
        <?php
        if (!isset($_SESSION['fp_verified']) || !$_SESSION['fp_verified']) {
            $_SESSION['fp_error'] = "Unauthorized access.";
            header("Location: ../views/forgot_password.php");
            exit();
        }
        ?>

        <?php if (isset($_SESSION['fp_error'])): ?>
            <div id="server-error-message">
                <?php
                    echo $_SESSION['fp_error'];
                    unset($_SESSION['fp_error']);
                ?>
            </div>
        <?php endif; ?>

        <div id="reset-password-hero" style="background:rgba(197, 244, 193, 1);" >
            <div id="reset-Pass-Content">
                <h1>Reset Your Password</h1>
                <p style="margin-bottom: 25px;">Please Check Your E-mail for code</p>
                        
                <form action="../controllers/C_resetPassword.php" method="post">
                    <input style="margin: 20px 0;" class="inp-2" type="password" name="password" placeholder="New Password" required>
                    <input style="margin: 20px 0;" class="inp-2" type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <button class="reg-btn" type="submit">Update Password</button>
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
