<?php
require_once __DIR__ . "/../models/db.php";
require_once __DIR__ . "/../models/ContactModel.php";

if (session_status() === PHP_SESSION_NONE) session_start();

$contactModel = new ContactModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId     = $_SESSION['user_id'] ?? null;
    $guestName  = $_POST['name'] ?? null;
    $guestEmail = $_POST['email'] ?? null;
    $guestPhone = $_POST['phone'] ?? null;
    $subject    = $_POST['subject'] ?? null;
    $message    = $_POST['message'] ?? null;

    
    if (empty($guestName) || empty($guestEmail) || empty($subject) || empty($message)) {
        $_SESSION['contact_error'] = "Please fill in all required fields!";
        header("Location: ../views/contact.php");
        exit();
    }

    // check if still a query in progress
    $checkQuery = "SELECT * FROM Contact_Queries WHERE guest_email = ? AND status IN ('pending','in_progress')";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $guestEmail);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['contact_error'] = "You already have an active query with this email. Please wait for it to be resolved.";
        header("Location: ../views/contact.php");
        exit();
    }

    
    $success = $contactModel->addQuery($userId, $guestName, $guestEmail, $guestPhone, $subject, $message);

    if ($success) {
        $_SESSION['contact_success'] = "Your message has been sent successfully! our team will contact you very shortly";
    } else {
        $_SESSION['contact_error'] = "Failed to send your message. Please try again.";
    }

    header("Location: ../views/contact.php");
    exit;
}
