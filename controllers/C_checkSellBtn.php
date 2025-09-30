<?php
session_start();

// If user is logged in within 5 minutes → go straight to their dashboard
if (isset($_SESSION['user_id']) && isset($_COOKIE['logged_in'])) {
    $userType = $_SESSION['user_type'] ?? '';

    switch ($userType) {
        case "Farmer":
            header("Location: ../views/farmer/farmerDashboard.php");
            exit;
        case "Consumer":
            header("Location: ../views/consumer/consumerDashboard.php");
            exit;
        case "Admin":
            header("Location: ../views/admin/adminDashboard.php");
            exit;
        default:
            session_unset();
            session_destroy();
            header("Location: ../views/login.php");
            exit;
    }
}

header("Location: ../views/login.php");
exit;
?>
