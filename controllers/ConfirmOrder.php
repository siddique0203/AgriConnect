<?php
session_start();
require_once "../../models/db.php";
require_once "../../controllers/ConsumerController.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../views/login.php");
    exit;
}

$controller = new ConsumerController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $paymentMethod = $_POST['payment_method'];

    $result = $controller->checkoutCart($userId, $paymentMethod);

    if ($result) {
        header("Location: orderSuccess.php");
        exit;
    } else {
        echo "Failed to place order!";
    }
}
?>
