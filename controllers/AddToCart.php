<?php
session_start();
require_once "../models/db.php";
require_once "ConsumerController.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'];
    $quantity = intval($_POST['quantity']);

    $controller = new ConsumerController($conn);
    $controller->addToCart($userId, $productId, $quantity);

    header("Location: ../views/consumer/productDetails.php?id=" . $productId);
    exit;
} else {
    echo "Invalid request.";
}
?>
