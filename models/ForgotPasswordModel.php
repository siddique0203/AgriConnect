<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require '../vendor/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/src/SMTP.php';
require '../vendor/phpmailer/src/Exception.php';

class ForgotPasswordModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Check if email exists in Users table
    public function emailExists($email) {
        $sql = "SELECT email FROM Users WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    // Insert or update token in Reset_Password table
    public function saveToken($email, $token, $expiry) {
        $sql = "SELECT user_email FROM Reset_Password WHERE user_email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $sql = "UPDATE Reset_Password SET token=?, expiry=? WHERE user_email=?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $token, $expiry, $email);
        } else {
            $sql = "INSERT INTO Reset_Password (user_email, token, expiry) VALUES (?,?,?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sss", $email, $token, $expiry);
        }
        return $stmt->execute();
    }

    // Send verification email using PHPMailer
    public function sendEmail($email, $token) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ahalif.optional26@gmail.com';    // Replace with your Gmail
            $mail->Password = 'uldgmldlkqrazowz';       // Replace with Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ahalif.optional26@gmail.com', 'AgriConnect Support');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'AgriConnect Password Reset Code';
            $mail->Body = "Your password reset code is: <b>$token</b><br>
                           It will expire in 15 minutes.";

            return $mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    // // Verify token
    // public function verifyToken($email, $token) {
    //     $sql = "SELECT user_email FROM Reset_Password WHERE user_email=? AND token=? AND expiry > NOW()";
    //     $stmt = $this->conn->prepare($sql);
    //     $stmt->bind_param("ss", $email, $token);
    //     $stmt->execute();
    //     $stmt->store_result();
    //     return $stmt->num_rows > 0;
    // }

    public function verifyToken($email, $token) {
    $sql = "SELECT user_email, expiry FROM Reset_Password WHERE user_email=? AND token=?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $expiryTime = strtotime($result['expiry']);
        $currentTime = time();
        return $currentTime <= $expiryTime;
    }
    return false;
}


    // Update user password
    public function updatePassword($email, $hashedPassword) {
        $sql = "UPDATE Users SET password=? WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $hashedPassword, $email);

        if ($stmt->execute()) {
            $sql2 = "DELETE FROM Reset_Password WHERE user_email=?";
            $stmt2 = $this->conn->prepare($sql2);
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            return true;
        }
        return false;
    }
}
?>
