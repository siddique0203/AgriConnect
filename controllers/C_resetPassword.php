<?php
session_start();
require_once '../models/db.php';
require_once '../models/ForgotPasswordModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['fp_verified']) || !$_SESSION['fp_verified'] || !isset($_SESSION['reset_email'])) {
        $_SESSION['fp_error'] = "Unauthorized access.";
        header("Location: ../views/forgot_password.php");
        exit();
    }

    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['fp_error'] = "Passwords do not match.";
        header("Location: ../views/reset_password.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];
    $model = new ForgotPasswordModel($conn);

    if ($model->updatePassword($email, $hashedPassword)) {
        unset($_SESSION['fp_verified']);
        unset($_SESSION['reset_email']);
        $_SESSION['fp_message'] = "Password updated successfully. You can now login.";
        header("Location: ../views/login.php");
        exit();
    } else {
        $_SESSION['fp_error'] = "Failed to update password. Try again.";
        header("Location: ../views/reset_password.php");
        exit();
    }
} else {
    header("Location: ../views/forgot_password.php");
    exit();
}
?>
