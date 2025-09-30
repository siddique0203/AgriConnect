<?php
session_start();
// require_once "../models/db.php";
// require_once "../models/UserModel.php";
require_once __DIR__ . "/../models/db.php";
require_once __DIR__ . "/../models/UserModel.php";
// require_once __DIR__ . "/../models/OrderModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = trim($_POST['userId'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $model    = new UserModel($conn);

    // --- Admin login ---
    $admin = $model->findAdminByCredentials($userId, $password);
    if ($admin) {
        $_SESSION['user_id']    = $admin['admin_id'];
        $_SESSION['user_type']  = "Admin";
        $_SESSION['user_name']  = $admin['name'] ?? "Administrator";
        $_SESSION['login_time'] = time();

        setcookie("logged_in", "1", time() + 3600, "/");
        header("Location: ../views/admin/adminDashboard.php");
        exit;
    }

    // --- Normal user login ---
    $user = $model->findUserById($userId); // get user by ID
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']     = $user['user_id'];
        $_SESSION['user_type']   = strtolower($user['user_type']);
        $_SESSION['user_name']   = $user['name'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_phone']  = $user['phone'];
        $_SESSION['user_address']= $user['address'];
        $_SESSION['user_nid']    = $user['nid'];
        $_SESSION['login_time']  = time();

        setcookie("logged_in", "1", time() + 3600, "/");

        if ($_SESSION['user_type'] === "farmer") {
            header("Location: ../views/farmer/farmerDashboard.php");
        } else {
            header("Location: ../views/consumer/consumerDashboard.php");
        }
        exit;
    }

    // --- Invalid credentials ---
    $_SESSION['login-error_message'] = "Invalid Log In, Please Enter Valid Credential";
    header("Location: ../views/login.php");
    exit;
}
