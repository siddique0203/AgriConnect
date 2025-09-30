<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../public/images/logo1.png">
    <title>AgriConnect_Login</title>
</head>
<body>
    <header>
      <nav>
        <div class="nav-logo">
          <h3 class="nav-title"><span>Agri</span>Connect</h3>
        </div>
        <ul>
          <li><a href="../index.php"><span>Home</span></a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="register.php">Sign Up</a></li>
        </ul>
      </nav>
    </header>
    <main>
        <div id="login-hero" style="background:rgba(197, 244, 193, 1)">
            <div id="login-server-error-message" style="<?php echo isset($_SESSION['login-error_message']) ? '' : 'display:none;'; ?>">
                <?php
                        if (isset($_SESSION['login-error_message'])) {
                            echo $_SESSION['login-error_message'];
                            unset($_SESSION['login-error_message']); // Clear the message
                        } 
                ?>
            </div>
            <div id="login-content">
                <div id="login-form">
                    <div class="login-form-header">
                        <h1>Welcome Back</h1>
                        <span class="login-header-span">Enter your credentials to access your account</span>
                    </div>
                    <div class="main-login-form">
                        <!-- UPDATED FORM ACTION -->
                        <form action="../controllers/C_login.php" method="post">
                            <label for="userId">User ID</label><br>
                            <input type="text" id="userId" name="userId" class="login-inp" required placeholder="Write your User-id "><br>
                
                            <label for="password">Password</label><br>
                            <input type="password" id="password" name="password" class="login-inp" required placeholder="Write Your Password"><br>
                            <a href="forgot_password.php" class="forgotPassBtn">Forgot Password?</a>
                            
                            <button type="submit" class="login-btn">LOG IN</button> 
                        </form>
                    </div>
                    <div class="account-wrap">
                      <span class="have-acc">Don't have an Account?</span>
                      <a href="register.php" class="GoRegBtn">Sign Up</a>
                    </div>
                </div>
                <div id="login-description">
                    <div id="login-description-header">
                        <h1>Reach Your Target<br>Faster With Us.</h1>
                    </div>
                    <div id="login-description-img">
                        <img src="../public/images/register.png" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
      <div class="footer-container">
        <div class="footer-text">
          <div class="footer-logo">
            <!-- <img src="public/images/logo1.png" alt="" style="height:40px"> -->
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
          <i class="fa-brands fa-github"></i>
        </div>
        <hr />
        <div class="footer-copyright">
          <h3><p>&copy; 2025 AgriConnect. All Rights Reserved.</h3>
        </div>
      </div>
    </footer>
</body>
</html>
