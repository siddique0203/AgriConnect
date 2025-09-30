<?php
session_start();
require_once '../models/db.php';
require_once '../models/ForgotPasswordModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);

    if (!isset($_SESSION['reset_email'])) {
        $_SESSION['fp_error'] = "Session expired. Try again.";
        header("Location: ../views/forgot_password.php");
        exit();
    }

    $email = $_SESSION['reset_email'];
    $model = new ForgotPasswordModel($conn);

    if ($model->verifyToken($email, $code)) {
        $_SESSION['fp_verified'] = true;
        header("Location: ../views/reset_password.php");
        exit();
    } else {
        $_SESSION['fp_error'] = "Invalid or expired code.";
        header("Location: ../views/verify_code.php");
        exit();
    }
} else {
    header("Location: ../views/forgot_password.php");
    exit();
}
?>
