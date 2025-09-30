<?php
class ContactModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Accept optional $status to filter queries
    public function getAllQueries($status = '') {
        if (!empty($status)) {
            // Prepared statement to filter by status
            $sql = "SELECT cq.*, u.name AS user_name 
                    FROM Contact_Queries cq 
                    LEFT JOIN Users u ON cq.user_id = u.user_id 
                    WHERE cq.status = ?
                    ORDER BY cq.created_at DESC";
            $stmt = mysqli_prepare($this->conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $status);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            // No filter
            $sql = "SELECT cq.*, u.name AS user_name 
                    FROM Contact_Queries cq 
                    LEFT JOIN Users u ON cq.user_id = u.user_id 
                    ORDER BY cq.created_at DESC";
            $result = mysqli_query($this->conn, $sql);
        }

        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function respondToQuery($queryId, $response, $status) {
        $sql = "UPDATE Contact_Queries 
                SET admin_response = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE query_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $response, $status, $queryId);
        return mysqli_stmt_execute($stmt);
    }

    public function addQuery($userId, $guestName, $guestEmail, $guestPhone, $subject, $message) {
    $sql = "INSERT INTO Contact_Queries 
            (query_id, user_id, guest_name, guest_email, guest_phone, subject, message) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($this->conn, $sql);

    $queryId = uniqid("QRY_");

    mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $queryId,
        $userId,
        $guestName,
        $guestEmail,
        $guestPhone,
        $subject,
        $message
    );

    return mysqli_stmt_execute($stmt);
}


}