<?php
session_start();
require_once '../models/db.php';
require_once '../models/ForgotPasswordModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $model = new ForgotPasswordModel($conn);

    if ($model->emailExists($email)) {
        $token = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        if ($model->saveToken($email, $token, $expiry)) {
            if ($model->sendEmail($email, $token)) {
                $_SESSION['fp_message'] = "Verification code sent to your email.";
                $_SESSION['reset_email'] = $email;
                header("Location: ../views/verify_code.php");
                exit();
            } else {
                $_SESSION['fp_error'] = "Failed to send email. Please try again.";
                header("Location: ../views/forgot_password.php");
                exit();
            }
        } else {
            $_SESSION['fp_error'] = "Failed to save token. Please try again.";
            header("Location: ../views/forgot_password.php");
            exit();
        }
    } else {
        $_SESSION['fp_error'] = "Email not found.";
        header("Location: ../views/forgot_password.php");
        exit();
    }
} else {
    header("Location: ../views/forgot_password.php");
    exit();
}
?>
