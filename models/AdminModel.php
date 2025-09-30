<?php
class AdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Add Admin with unique ID
    public function addAdmin($name, $email, $password) {
        // Check if email already exists
        $sqlCheck = "SELECT * FROM Admin WHERE email=?";
        $stmtCheck = mysqli_prepare($this->conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $email);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        if (mysqli_num_rows($resultCheck) > 0) {
            return "email_exists";
        }

        // Generate unique admin ID: ADM + 4 random digits
        $adminId = "ADM" . str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);

        $sql = "INSERT INTO Admin (admin_id, name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $adminId, $name, $email, $password);
        return mysqli_stmt_execute($stmt);
    }

    // Delete Admin with check for last admin and super admin password
    public function deleteAdmin($adminId, $superAdminPass) {
        // Verify super admin password
        $sqlCheck = "SELECT * FROM super_admin WHERE pass=?";
        $stmtCheck = mysqli_prepare($this->conn, $sqlCheck);
        mysqli_stmt_bind_param($stmtCheck, "s", $superAdminPass);
        mysqli_stmt_execute($stmtCheck);
        $resultCheck = mysqli_stmt_get_result($stmtCheck);
        if (mysqli_num_rows($resultCheck) === 0) {
            return "wrong_super_pass";
        }

        // Count total admins
        $sqlCount = "SELECT COUNT(*) AS total FROM Admin";
        $resultCount = mysqli_query($this->conn, $sqlCount);
        $row = mysqli_fetch_assoc($resultCount);
        if ($row['total'] <= 1) {
            return "last_admin";
        }

        // Proceed to delete
        $sql = "DELETE FROM Admin WHERE admin_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $adminId);
        return mysqli_stmt_execute($stmt);
    }

    // Get all admins
    public function getAllAdmins() {
        $sql = "SELECT * FROM Admin ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }
}
?>
