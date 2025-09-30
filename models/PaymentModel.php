<?php
class PaymentModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPaymentsForFarmer($farmerId) {
    $sql = "SELECT pay.*, o.user_id, u.name AS customer_name, p.name AS product_name, oi.quantity
            FROM Payments pay
            JOIN Orders o ON pay.order_id = o.order_id
            JOIN Users u ON o.user_id = u.user_id
            JOIN Order_Items oi ON o.order_id = oi.order_id
            JOIN Products p ON oi.product_id = p.product_id
            WHERE p.farmer_id = ?
            GROUP BY pay.payment_id
            ORDER BY pay.payment_date DESC";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $farmerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $payments = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $payments[] = $row;
    }
    return $payments;
}

    private function generatePaymentId() {
        $sql = "SELECT payment_id FROM Payments ORDER BY payment_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $lastId = intval(substr($row['payment_id'], 2));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        return "PM" . str_pad($newId, 3, "0", STR_PAD_LEFT);
    }

    public function createPayment($orderId, $amount, $method, $status = 'pending') {
        $paymentId = $this->generatePaymentId();
        $sql = "INSERT INTO Payments (payment_id, order_id, amount, method, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssdss", $paymentId, $orderId, $amount, $method, $status);
        mysqli_stmt_execute($stmt);
        return $paymentId;
    }
    public function getAllPayments() {
        $sql = "SELECT p.*, o.user_id AS customer_id, u.name AS customer_name
                FROM Payments p
                JOIN Orders o ON p.order_id = o.order_id
                JOIN Users u ON o.user_id = u.user_id
                ORDER BY p.payment_date DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }

    public function searchPaymentsByMethod($method) {
    $sql = "SELECT p.*, o.user_id AS customer_id, u.name AS customer_name
            FROM Payments p
            JOIN Orders o ON p.order_id = o.order_id
            JOIN Users u ON o.user_id = u.user_id
            WHERE p.method = ?
            ORDER BY p.payment_date DESC";

    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $method);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = [];
    while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
    return $rows;
}


}
?>
