<?php
// views/consumer/checkout.php
session_start();
require_once __DIR__ . "/../../models/db.php";
require_once __DIR__ . "/../../controllers/ConsumerController.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$controller = new ConsumerController($conn);
$cart = $controller->getCart($_SESSION['user_id']);
$total = $controller->getCartTotal($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $paymentMethod = $_POST['payment_method'] ?? 'cash_on_delivery';
    $orderId = $controller->placeOrder($_SESSION['user_id'], $paymentMethod);

    if ($orderId) {
        header("Location: consumerProfile.php?section=orders&success=1");
        exit;
    } else {
        $error = "Something went wrong. Try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<body>
    <h2>Checkout</h2>

    <?php if (!empty($cart)): ?>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <thead>
                <tr style="background:#2d6a4f; color:#fff;">
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): 
                    $subtotal = $item['quantity'] * $item['price'];
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td><?php echo intval($item['quantity']); ?></td>
                    <td><?php echo number_format($item['price'],2); ?></td>
                    <td><?php echo number_format($subtotal,2); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" align="right"><strong>Total:</strong></td>
                    <td><strong><?php echo number_format($total,2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <form method="POST" style="margin-top:20px;">
            <label><strong>Select Payment Method:</strong></label><br>
            <select name="payment_method" required>
                <option value="cash_on_delivery">Cash on Delivery</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="card">Card</option>
            </select><br><br>

            <button type="submit" name="confirm_order" 
                style="padding:10px 20px; background:#2d6a4f; color:#fff; border:none; border-radius:5px;">
                Confirm Order
            </button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
