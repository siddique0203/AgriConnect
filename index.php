<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/style.css?v=<?php echo time(); ?>">
    <link rel="icon" href="public/images/logo1.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>AgriConnect</title>
</head>
<body>
<header>
    <nav id="navBar">
        <div class="nav-logo">
            <h3 class="nav-title"><span>Agri</span>Connect</h3>
        </div>
        <ul>
            <li><a href="views/consumer/consumerDashboard.php"><span>Buy</span></a></li>
            <li><a href="views/about.php">About</a></li>
            <li><a href="views/contact.php">Contact</a></li>
            <li><a href="views/register.php">Register</a></li>
        </ul>
    </nav>
    <div class="banner" style="background: url(public/images/main-slider.png) no-repeat center center; background-size: cover;">
        <div class="banner-content">
            <div class="banner-description">
                <p class="banner-head-para">WELCOME TO Agricultural Products Rural Entrepreneurship Management System.</p>
                <h1>AGRI-CONNECT HUB</h1>
                <p class="banner-foot-para">
                    Empowering Rural Dreams, Nurturing Agricultural Growth - AgriConnect Hub cultivates prosperity from the roots up
                </p>
            </div>
            <div class="buy-sell-btn">
                <button class="buy-btn btn"  onclick="window.location.href='./views/consumer/consumerDashboard.php'">Buy here</button>
                <button class="sell-btn btn" onclick="window.location.href='controllers/C_checkSellBtn.php'">Sell here</button>
            </div>
        </div>
    </div>
</header>
<main style="background: linear-gradient(135deg, #e8f9f1, #f9fff9);">
    <!-- Fresh Vegetables Section -->
    <section class="fresh-vegetables">
        <div class="section-header">
            <h2>🌿 Fresh & Organic Vegetables</h2>
            <p>Directly from farmers — clean, green, and healthy</p>
        </div>
        <div class="vegetable-grid">
            <div class="veg-card">
                <img src="public/images/index/tomato.jpg" alt="Tomato">
                <h3>Tomatoes</h3>
                <p>Rich in vitamins, naturally farmed, and always fresh.</p>
            </div>
            <div class="veg-card">
                <img src="public/images/index/carrot.jpg" alt="Carrot">
                <h3>Carrots</h3>
                <p>Crunchy, sweet, and packed with nutrients.</p>
            </div>
            <div class="veg-card">
                <img src="public/images/index/spinach.jpeg" alt="Spinach">
                <h3>Spinach</h3>
                <p>Farm-to-table leafy greens for healthy meals.</p>
            </div>
            <div class="veg-card">
                <img src="public/images/index/potato.jpeg" alt="Potato">
                <h3>Potatoes</h3>
                <p>Staple food with natural freshness and taste.</p>
            </div>
        </div>
    </section>

    <!-- ⭐ Customer Reviews Section -->
    <section class="reviews" style="background: linear-gradient(135deg, #e8f9f1, #f9fff9);">
        <div class="section-header">
            <h2>⭐ What Our Customers Say</h2>
            <p>Trusted by thousands of farmers and buyers</p>
        </div>
        <div class="review-grid">
            <div class="review-card">
                <div class="review-top">
                    <img src="public/images/index/mav1.jpg" alt="Rahim">
                    <div>
                        <h4>Rahim Uddin</h4>
                        <span>Dhaka</span>
                    </div>
                </div>
                <p>"AgriConnect made it so easy to get fresh vegetables at my doorstep. Quality is always top-notch!"</p>
            </div>
            <div class="review-card">
                <div class="review-top">
                    <img src="public/images/index/mav2.jpg" alt="Karim">
                    <div>
                        <h4>Karim Hossain</h4>
                        <span>Bogura</span>
                    </div>
                </div>
                <p>"As a farmer, I finally have a fair platform to sell directly to consumers. Highly recommend!"</p>
            </div>
            <div class="review-card">
                <div class="review-top">
                    <img src="public/images/index/wav.jpg" alt="Ayesha">
                    <div>
                        <h4>Ayesha Begum</h4>
                        <span>Chittagong</span>
                    </div>
                </div>
                <p>"Absolutely love the service. Healthy food and quick delivery!"</p>
            </div>
        </div>
    </section>
    <!-- Impact / Statistics Section -->
    <section class="impact-stats">
        <div class="section-header">
            <h2><i class="fa-solid fa-chart-line"></i> Our Growing Impact</h2>
            <p>Building stronger connections between farmers and consumers every day</p>
        </div>
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fa-solid fa-tractor"></i>
                <h3>5,000+</h3>
                <p>Farmers Empowered</p>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-basket-shopping"></i>
                <h3>20,000+</h3>
                <p>Orders Delivered</p>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-city"></i>
                <h3>30+</h3>
                <p>Cities Covered</p>
            </div>
            <div class="stat-card">
                <i class="fa-solid fa-star"></i>
                <h3>4.9/5</h3>
                <p>Customer Satisfaction</p>
            </div>
        </div>
    </section>

    <!-- 🌱 Why Choose Us Section -->
    <section class="why-choose">
        <div class="section-header">
            <h2>🌱 Why Choose AgriConnect?</h2>
            <p>We are committed to supporting farmers and serving fresh food to you</p>
        </div>
        <div class="why-grid">
            <div class="why-card">
                <i class="fa-solid fa-leaf"></i>
                <h3>Organic & Fresh</h3>
                <p>Directly sourced from local farmers ensuring natural freshness.</p>
            </div>
            <div class="why-card">
                <i class="fa-solid fa-truck-fast"></i>
                <h3>Fast Delivery</h3>
                <p>Quick and safe delivery to your doorstep, anywhere in Bangladesh.</p>
            </div>
            <div class="why-card">
                <i class="fa-solid fa-handshake"></i>
                <h3>Fair Trade</h3>
                <p>Empowering farmers with fair prices and better opportunities.</p>
            </div>
        </div>
    </section>
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
