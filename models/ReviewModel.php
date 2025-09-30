<?php
class ReviewModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getReviewsForFarmer($farmerId) {
        $sql = "SELECT r.*, u.name AS customer_name, p.name AS product_name
                FROM Reviews r
                JOIN Users u ON r.user_id = u.user_id
                JOIN Products p ON r.product_id = p.product_id
                WHERE p.farmer_id = ?
                ORDER BY r.created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $farmerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    // hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh

    public function getReviewsForProduct($productId) {
        $sql = "SELECT r.*, u.name AS customer_name
                FROM Reviews r
                JOIN Users u ON r.user_id = u.user_id
                WHERE r.product_id = ?
                ORDER BY r.created_at DESC";

        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $productId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    public function addReview($productId, $userId, $rating, $comment) {
        // Generate review_id like R001
        $sql = "SELECT review_id FROM Reviews ORDER BY review_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $lastId = intval(substr($row['review_id'], 1));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        $reviewId = "R" . str_pad($newId, 3, "0", STR_PAD_LEFT);

        $sqlInsert = "INSERT INTO Reviews (review_id, product_id, user_id, rating, comment) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sqlInsert);
        mysqli_stmt_bind_param($stmt, "sssis", $reviewId, $productId, $userId, $rating, $comment);
        return mysqli_stmt_execute($stmt);
    }
    public function getAllReviews() {
        $sql = "SELECT r.*, p.name AS product_name, u.name AS customer_name
                FROM Reviews r
                JOIN Products p ON r.product_id = p.product_id
                JOIN Users u ON r.user_id = u.user_id
                ORDER BY r.created_at DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    // You can also add methods like deleteReview if not already added
    public function deleteReview($reviewId) {
        $sql = "DELETE FROM Reviews WHERE review_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $reviewId);
        return mysqli_stmt_execute($stmt);
    }

}
?>
