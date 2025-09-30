<?php
session_start();
if (!isset($_SESSION['user_id'], $_COOKIE['logged_in']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: ../../views/login.php");
    exit;
}

$section = isset($_GET['section']) ? $_GET['section'] : 'users';
require_once "../../controllers/AdminController.php";
$controller = new AdminController();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <!-- <link rel="stylesheet" href="../../public/css/Adminstyle.css?v=<?php echo time(); ?>"> -->
  <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>
<body>
  <header class="main-header">
    <nav class="navbar">
      <div class="nav-logo">
        <h3 class="nav-title"><span>Agri</span>Connect - Admin</h3>
      </div>
      <ul class="nav-links">
        <li><a href="../../index.php">Home</a></li>
        <li><a href="adminDashboard.php">Dashboard</a></li>
        <li><a href="../../controllers/C_logout.php">Logout</a></li>
      </ul>
    </nav>
  </header>

  <main class="dashboard-container">
    <aside class="sidebar">
      <ul class="sidebar-menu">
        <li><a href="?section=users" class="<?php echo ($section==='users')?'active':''; ?>"><i class="fa fa-users"></i> User Management</a></li>
        <li><a href="?section=products" class="<?php echo ($section==='products')?'active':''; ?>"><i class="fa fa-boxes"></i> Product Management</a></li>
        <li><a href="?section=orders" class="<?php echo ($section==='orders')?'active':''; ?>"><i class="fa fa-shopping-cart"></i> Orders & Cart</a></li>
        <li><a href="?section=payments" class="<?php echo ($section==='payments')?'active':''; ?>"><i class="fa fa-wallet"></i> Payments</a></li>
        <li><a href="?section=reviews" class="<?php echo ($section==='reviews')?'active':''; ?>"><i class="fa fa-comments"></i> Reviews</a></li>
        <li><a href="?section=queries" class="<?php echo ($section==='queries')?'active':''; ?>"><i class="fa fa-question-circle"></i> Queries</a></li>
        <li><a href="?section=admins" class="<?php echo ($section==='admins')?'active':''; ?>"><i class="fa fa-user-shield"></i> Admins</a></li>

      </ul>
    </aside>

    <section class="content-area">
      <?php
        $controller->handleActions();
        $controller->loadSection($section);
      ?>
    </section>
  </main>
</body>
</html>