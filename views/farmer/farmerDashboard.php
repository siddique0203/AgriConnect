<?php
session_start();
if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'farmer') {
    header("Location: ../../views/login.php");
    exit;
}

$section = isset($_GET['section']) ? $_GET['section'] : 'profile';
require_once "../../controllers/FarmerController.php";
$controller = new FarmerController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Farmer Dashboard</title>
  <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>
  <!-- Header -->
  <header class="main-header">
    <nav class="navbar">
      <div class="nav-logo">
        <h3 class="nav-title"><span>Agri</span>Connect</h3>
      </div>
      <ul class="nav-links">
        <li><a href="../../index.php">Home</a></li>
        <li><a href="farmerDashboard.php">Dashboard</a></li>
        <li><a href="../../controllers/C_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <!-- Main -->
  <main class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
      <ul class="sidebar-menu">
        <li><a href="?section=profile" class="<?php echo ($section==='profile')?'active':''; ?>"><i class="fa fa-user"></i> Profile Details</a></li>
        <li><a href="?section=products" class="<?php echo ($section==='products')?'active':''; ?>"><i class="fa fa-box"></i> Product Management</a></li>
        <li><a href="?section=orders" class="<?php echo ($section==='orders')?'active':''; ?>"><i class="fa fa-shopping-cart"></i> Order Management</a></li>
        <li><a href="?section=payments" class="<?php echo ($section==='payments')?'active':''; ?>"><i class="fa fa-wallet"></i> Payments</a></li>
        <li><a href="?section=feedback" class="<?php echo ($section==='feedback')?'active':''; ?>"><i class="fa fa-comments"></i> Customer Feedback</a></li>
      </ul>
    </aside>

    <!-- Main Content -->
    <section class="content-area">
      <?php
        $controller->handleActions();
        $controller->loadSection($section);
      ?>
    </section>
  </main>
</body>
</html>
