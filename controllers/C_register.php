<?php
session_start();
require_once "../models/db.php";
require_once "../models/UserModel.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId   = trim($_POST['userId'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $userType = trim($_POST['userType'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $nid      = isset($_POST['nid']) && $_POST['nid'] !== '' ? trim($_POST['nid']) : null;

    //object create for userModel named "model"
    $model = new UserModel($conn);

    // Server-side minimal validation
    if ($userId === '' || $name === '' || $email === '' || $phone === '' || $password === '' || $userType === '' || $address === '') {
        $_SESSION['error_message'] = "Please fill all required fields.";
        header("Location: ../views/register.php");
        exit;
    }

    if ($model->userIdExists($userId)) {
        $_SESSION['error_message'] = "User ID already exists!";
        header("Location: ../views/register.php");
        exit;
    }

    if ($model->emailExists($email)) {
        $_SESSION['error_message'] = "Email already registered!";
        header("Location: ../views/register.php");
        exit;
    }

    // HASH PASSWORD
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $ok = $model->createUser($userId, $name, $email, $phone, $passwordHash, $userType, $address, $nid);

    if ($ok) {
        $_SESSION['success_message'] = "Registration successful! Please login.";
    } else {
        $_SESSION['error_message'] = "Registration failed. Please try again.";
    }

    header("Location: ../views/register.php");
    exit;
}
