<?php
class CartModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getCartForUser($userId) {
        $sql = "SELECT c.*, p.name AS product_name, p.price, p.image
                FROM Cart c
                JOIN Products p ON c.product_id = p.product_id
                WHERE c.user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items = [];
        while($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function addToCart($userId, $productId, $quantity) {
    // 1️⃣ Check if product already exists in user's cart
    $sqlCheck = "SELECT cart_id, quantity FROM Cart WHERE user_id=? AND product_id=?";
    $stmtCheck = mysqli_prepare($this->conn, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $userId, $productId);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if ($row = mysqli_fetch_assoc($resultCheck)) {
        // Product exists → update quantity
        $newQty = $row['quantity'] + $quantity;
        $sqlUpdate = "UPDATE Cart SET quantity=? WHERE cart_id=?";
        $stmtUpdate = mysqli_prepare($this->conn, $sqlUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "is", $newQty, $row['cart_id']);
        return mysqli_stmt_execute($stmtUpdate);
    } else {
        // Product not in cart → generate new cart_id safely
        $sqlCount = "SELECT COUNT(*) as total FROM Cart";
        $resultCount = mysqli_query($this->conn, $sqlCount);
        $rowCount = mysqli_fetch_assoc($resultCount);
        $newIdNum = $rowCount['total'] + 1;

        $cartId = "CD" . str_pad($newIdNum, 3, "0", STR_PAD_LEFT);

        // Insert new row
        $sqlInsert = "INSERT INTO Cart (cart_id, user_id, product_id, quantity) VALUES (?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($this->conn, $sqlInsert);
        mysqli_stmt_bind_param($stmtInsert, "sssi", $cartId, $userId, $productId, $quantity);
        return mysqli_stmt_execute($stmtInsert);
    }
    }

    public function getTotalPrice($userId) {
        $sql = "SELECT SUM(c.quantity * p.price) AS total
                FROM Cart c
                JOIN Products p ON c.product_id = p.product_id
                WHERE c.user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        return $row['total'] ?? 0;
    }

    public function clearCart($userId) {
        $sql = "DELETE FROM Cart WHERE user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        return mysqli_stmt_execute($stmt);
    }

    public function removeItem($cartId, $userId) {
        $sql = "DELETE FROM Cart WHERE cart_id=? AND user_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $cartId, $userId);
        return mysqli_stmt_execute($stmt);
    }

}
?>
