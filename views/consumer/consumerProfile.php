<?php
// views/consumer/consumerProfile.php
session_start();
if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'consumer') {
    header("Location: ../../views/login.php");
    exit;
}

// include DB connection and controller
require_once "../../models/db.php";                 
require_once "../../controllers/ConsumerController.php";

$controller = new ConsumerController($conn);
$section = isset($_GET['section']) ? $_GET['section'] : 'profile';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Consumer Profile</title>
    <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>

<!-- header -->
<header class="main-header">
  <nav class="navbar">
    <div class="nav-logo"><h3 class="nav-title"><span>Agri</span>Connect</h3></div>
    <ul>
      <li><a href="../../index.php">Home</a></li>
      <li><a href="../consumer/consumerDashboard.php">Dashboard</a></li>
      <li><a href="consumerProfile.php?section=profile">Profile</a></li>
      <li><a href="consumerProfile.php?section=orders">Orders</a></li>
      <li><a href="consumerProfile.php?section=cart">Cart</a></li>
      <li><a href="../../controllers/C_logout.php">Logout</a></li>
    </ul>
  </nav>
</header>

<!-- main -->
<main class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="?section=profile" class="<?php echo ($section==='profile')?'active':''; ?>"><i class="fa fa-user"></i> Profile Details</a></li>
            <li><a href="?section=orders" class="<?php echo ($section==='orders')?'active':''; ?>"><i class="fa fa-shopping-cart"></i> Order Details</a></li>
            <li><a href="?section=cart" class="<?php echo ($section==='cart')?'active':''; ?>"><i class="fa fa-cart-shopping"></i> Cart List</a></li>
        </ul>
    </aside>

    <!-- Content -->
    <section class="content-area">
        <?php
            $controller->handleActions();
            $controller->loadSection($section);
        ?>
    </section>
</main>

</body>
</html>
