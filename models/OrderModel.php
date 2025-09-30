<?php
class OrderModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get all orders made by a user
    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM Orders WHERE user_id=? ORDER BY order_date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }
    private function generateOrderId() {
        $sql = "SELECT order_id FROM Orders ORDER BY order_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $lastId = intval(substr($row['order_id'], 1));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        return "O" . str_pad($newId, 3, "0", STR_PAD_LEFT);
    }

    private function generateOrderItemId() {
        $sql = "SELECT order_item_id FROM Order_Items ORDER BY order_item_id DESC LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $lastId = intval(substr($row['order_item_id'], 2));
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }
        return "OI" . str_pad($newId, 3, "0", STR_PAD_LEFT);
    }

    public function createOrder($userId, $cartItems, $totalAmount) {
        if (empty($cartItems)) return false;

        $orderId = $this->generateOrderId();

        $sqlOrder = "INSERT INTO Orders (order_id, user_id, total_amount) VALUES (?, ?, ?)";
        $stmtOrder = mysqli_prepare($this->conn, $sqlOrder);
        mysqli_stmt_bind_param($stmtOrder, "ssd", $orderId, $userId, $totalAmount);
        mysqli_stmt_execute($stmtOrder);

        foreach ($cartItems as $item) {
            $orderItemId = $this->generateOrderItemId();
            $sqlItem = "INSERT INTO Order_Items (order_item_id, order_id, product_id, quantity, price) 
                        VALUES (?, ?, ?, ?, ?)";
            $stmtItem = mysqli_prepare($this->conn, $sqlItem);
            mysqli_stmt_bind_param($stmtItem, "sssii", $orderItemId, $orderId, $item['product_id'], $item['quantity'], $item['price']);
            mysqli_stmt_execute($stmtItem);
        }

        return $orderId;
    }


    public function getOrdersForFarmer($farmerId) {
        $sql = "SELECT o.* 
                FROM Orders o
                JOIN Order_Items oi ON o.order_id = oi.order_id
                JOIN Products p ON oi.product_id = p.product_id
                WHERE p.farmer_id = ?
                GROUP BY o.order_id
                ORDER BY o.order_date DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $farmerId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $orders = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orders[] = $row;
        }
        return $orders;
    }

    public function getOrderItems($orderId) {
        $sql = "SELECT oi.*, p.name AS product_name
                FROM Order_Items oi
                JOIN Products p ON oi.product_id = p.product_id
                WHERE oi.order_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $orderId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public function updateOrderStatus($orderId, $status) {
        $sql = "UPDATE Orders SET status=? WHERE order_id=?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $status, $orderId);
        return mysqli_stmt_execute($stmt);
    }

    // CART METHODS
    public function getCartByUser($userId) {
        $sql = "SELECT c.*, p.name AS product_name 
                FROM Cart c 
                JOIN Products p ON c.product_id = p.product_id 
                WHERE c.user_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $rows = [];
        while($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        return $rows;
    }
    

    // public function deleteCartItem($cartId) {
    //     $sql = "DELETE FROM Cart WHERE cart_id=?";
    //     $stmt = mysqli_prepare($this->conn, $sql);
    //     mysqli_stmt_bind_param($stmt, "s", $cartId);
    //     return mysqli_stmt_execute($stmt);
    // }

    // public function updateCartItem($cartId, $quantity) {
    //     $sql = "UPDATE Cart SET quantity=? WHERE cart_id=?";
    //     $stmt = mysqli_prepare($this->conn, $sql);
    //     mysqli_stmt_bind_param($stmt, "is", $quantity, $cartId);
    //     return mysqli_stmt_execute($stmt);
    // }

    // All orders
    public function getAllOrders() {
        $sql = "SELECT o.*, u.name AS customer_name 
                FROM Orders o 
                JOIN Users u ON o.user_id = u.user_id 
                ORDER BY o.order_date DESC";
        $result = mysqli_query($this->conn, $sql);
        $rows = [];
        while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
        return $rows;
    }
    // Update quantity of a cart item
public function updateCartQuantity($cartId, $quantity) {
    $sql = "UPDATE Cart SET quantity=? WHERE cart_id=?";
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $quantity, $cartId);
    return mysqli_stmt_execute($stmt);
}

// Delete a cart item
public function deleteCartItem($cartId) {
    $sql = "DELETE FROM Cart WHERE cart_id=?";
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $cartId);
    return mysqli_stmt_execute($stmt);
}
public function getOrdersForUser($userId) {
    $sql = "SELECT o.*, u.name AS customer_name 
            FROM Orders o
            JOIN Users u ON o.user_id = u.user_id
            WHERE o.user_id = ?
            ORDER BY o.order_date DESC";
    $stmt = mysqli_prepare($this->conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $orders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
    return $orders;
}

}
?>
