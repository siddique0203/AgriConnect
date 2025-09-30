<?php
class UserModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function findAdminByCredentials($adminId, $passwordPlain) {
        $sql = "SELECT * FROM Admin WHERE admin_id=? AND password=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $adminId, $passwordPlain);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }

    public function userIdExists($userId) {
        $sql = "SELECT user_id FROM Users WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }

    public function emailExists($email) {
        $sql = "SELECT email FROM Users WHERE email=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        return mysqli_stmt_num_rows($stmt) > 0;
    }

    public function createUser($userId, $name, $email, $phone, $passwordPlain, $userType, $address, $nidNullable) {
        $sql = "INSERT INTO Users (user_id, name, email, phone, password, user_type, address, nid)
                VALUES (?,?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $userId, $name, $email, $phone, $passwordPlain, $userType, $address, $nidNullable);
        return mysqli_stmt_execute($stmt);
    }

    public function findUserById($userId) {
        $sql = "SELECT * FROM Users WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result) ?: null;
    }

    public function updateUser($userId, $name, $email, $phone, $address, $nid) {
    if ($email) {
        $sql = "UPDATE Users SET name=?, email=?, phone=?, address=?, nid=? WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $address, $nid, $userId);
    } else {
        $sql = "UPDATE Users SET name=?, phone=?, address=?, nid=? WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $phone, $address, $nid, $userId);
    }
    return mysqli_stmt_execute($stmt);
}


    public function getUsersByType($type = 'All') {
        if ($type === 'All') {
            $sql = "SELECT * FROM Users ORDER BY created_at DESC";
            $result = mysqli_query($this->conn, $sql);
        } else {
            $sql = "SELECT * FROM Users WHERE user_type = ? ORDER BY created_at DESC";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $type);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function searchUsers($userType = '', $keyword = '') {
        $sql = "SELECT * FROM Users WHERE 1=1";
        $params = [];
        if ($userType !== '' && $userType !== 'All') { $sql .= " AND user_type = ?"; $params[] = $userType; }
        if ($keyword !== '') { $sql .= " AND (name LIKE ? OR email LIKE ? OR user_id LIKE ?)"; $params[] = "%$keyword%"; $params[] = "%$keyword%"; $params[] = "%$keyword%"; }
        $sql .= " ORDER BY created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!empty($params)) { $types = str_repeat('s', count($params)); mysqli_stmt_bind_param($stmt, $types, ...$params); }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM Users WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        return mysqli_stmt_execute($stmt);
    }
    

}
?>
