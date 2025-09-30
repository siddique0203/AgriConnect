<?php
// views/consumer/sections/orderDetails.php
?>
<h2 class="section-title">Order Details</h2>

<?php if(!empty($orders)): ?>
<table class="order-table" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#2d6a4f; color:#fff;">
            <th style="padding:8px;">Order ID</th>
            <th style="padding:8px;">Total Amount</th>
            <th style="padding:8px;">Status</th>
            <th style="padding:8px;">Order Date</th>
            <th style="padding:8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order): ?>
        <tr>
            <td style="padding:8px;"><?php echo htmlspecialchars($order['order_id']); ?></td>
            <td style="padding:8px;"><?php echo number_format($order['total_amount'],2); ?></td>
            <td style="padding:8px;"><?php echo ucfirst($order['status']); ?></td>
            <td style="padding:8px;"><?php echo $order['order_date']; ?></td>
            <td style="padding:8px;">
                <a href="../consumer/consumerOrderDetails.php?order_id=<?php echo urlencode($order['order_id']); ?>" class="btn-view">View Items</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p class="no-data">No orders found.</p>
<?php endif; ?>
