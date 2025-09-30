<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_COOKIE['logged_in'])) {
    header("Location: ../../views/login.php");
    exit;
}

require_once "../../models/db.php";
require_once "../../models/OrderModel.php";

$orderModel = new OrderModel($conn);

$orderId = $_GET['order_id'] ?? '';

if (!$orderId) {
    echo "<p>Invalid Order ID</p>";
    exit;
}

// Fetch order items
$items = $orderModel->getOrderItems($orderId);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../../public/css/style.css?v=<?php echo time(); ?>">
    <style>
        table { border-collapse: collapse; width: 70%; padding-top: 20px; margin: auto; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #2d6a4f; color: white; }
        h2 { margin: 20px 50px; }
        .back-link { display:inline-block; margin: 20px 50px; text-decoration:none; color:#2d6a4f; font-weight:bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Order Details for Order ID: <?php echo htmlspecialchars($orderId); ?></h2>

        <?php if(empty($items)): ?>
            <p>No items found for this order.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($items as $item): 
                        $subtotal = $item['quantity'] * $item['price'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'],2); ?></td>
                            <td><?php echo number_format($subtotal,2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:bold;">Total:</td>
                        <td><?php echo number_format($total,2); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="consumerProfile.php?section=orders" class="back-link">← Back to Orders</a>
    </div>
</body>
</html>
