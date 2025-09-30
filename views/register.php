<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../public/images/logo1.png">
    <title>AgriConnect Registration</title>
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
            <li><a href="login.php">Log In</a></li>
        </ul>
    </nav>
</header>

<main>
    <div id="registration-hero" style="background:rgba(197, 244, 193, 1);padding: 130px;">
        <!-- Server Messages -->
        <div id="server-error-message" style="<?php echo (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])) ? '' : 'display:none;'; ?>">
            <?php
                if (isset($_SESSION['error_message'])) {
                    echo $_SESSION['error_message'];
                    unset($_SESSION['error_message']); 
                } 
                elseif (isset($_SESSION['success_message'])) {
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                }
            ?>
        </div>

        <div id="registration-content">
            <!-- Registration Form -->
            <div id="registration-form">
                <div class="form-header"><h1>Get Started Now</h1></div>
                <div class="main-form">
                    <!-- Form action points to controller -->
                    <form action="../controllers/C_register.php" method="post" onsubmit="return validateForm()">
                        <label for="userId">User ID</label><br>
                        <input type="text" class="inp" id="userId" name="userId" placeholder="Hello123" required><br>
                        <div id="userId-error" class="mbT"></div>

                        <label for="name">Name</label><br>
                        <input type="text" class="inp" id="name" name="name" placeholder="Mr. X" required><br>
                        <div id="name-error" class="mbT"></div>

                        <label for="email">E-Mail</label><br>
                        <input type="email" class="inp" id="email" name="email" placeholder="abc@example.com" required><br>
                        <div id="email-error" class="mbT"></div>

                        <label for="phone">Phone Number</label><br>
                        <input type="text" class="inp" id="phone" name="phone" placeholder="01711111111" required><br>
                        <div id="phone-error" class="mbT"></div>

                        <label for="password">Password</label><br>
                        <input type="password" class="inp" id="password" name="password" required><br>
                        <div id="password-error" class="mbT"></div>

                        <label for="userType">User Type</label><br>
                        <select name="userType" class="inp" id="userType" onchange="toggleNid()">
                            <option value="">-------Select-------</option>
                            <option value="consumer">Consumer</option>
                            <option value="farmer">Farmer</option>
                        </select><br>
                        <div id="userType-error" class="mbT"></div>

                        <label for="address">Address</label><br>
                        <textarea name="address" class="address-inp" id="address" required></textarea><br>
                        <div id="address-error" class="mbT"></div>

                        <div id="nidField" style="display: none;">
                            <label for="nid">NID Number</label><br>
                            <input type="text" class="inp" id="nid" name="nid">
                        </div>
                        <div id="nid-error" class="mbT"></div>

                        <button type="submit" class="reg-btn">Register</button>
                    </form>
                </div>
                <!-- <label class="have-acc" for="">Have an Account?</label>
                <a href="login.php" style="font-size: 20px; margin-left: 5px;">Sign In</a> -->
                <div class="account-wrap">
                      <span class="have-acc">Have an Account?</span>
                      <a href="login.php" class="GoRegBtn">Sign In</a>
                    </div>
            </div>

            <!-- Registration Description -->
            <div id="registration-description">
                <div id="registration-description-header">
                    <h1>Reach Your Target<br>Faster With Us.</h1>
                </div>
                <div id="registration-description-img">
                    <img src="../public/images/register.png" alt="img">
                </div>
            </div>
        </div>
    </div>
</main>
<script src="../public/validation/registration_validation.js"></script>

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
