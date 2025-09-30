<h2 class="section-title">Payment Management</h2>

<div class="table-container">
  <table class="payment-table">
    <thead>
      <tr>
        <th>Payment ID</th>
        <th>Order ID</th>
        <th>Customer ID</th>
        <th>Customer Name</th>
        <th>Amount</th>
        <th>Method</th>
        <th>Status</th>
        <th>Payment Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($payments)): ?>
        <?php foreach($payments as $pay): ?>
          <tr>
            <td><?php echo $pay['payment_id']; ?></td>
            <td><?php echo $pay['order_id']; ?></td>
            <td><?php echo $pay['user_id']; ?></td>
            <td><?php echo htmlspecialchars($pay['customer_name']); ?></td>
            <td><?php echo number_format($pay['amount'], 2); ?></td>
            <td><?php echo ucfirst(str_replace('_',' ',$pay['method'])); ?></td>
            <td>
              <span class="payment-status <?php echo strtolower($pay['status']); ?>">
                <?php echo ucfirst($pay['status']); ?>
              </span>
            </td>
            <td><?php echo $pay['payment_date']; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" class="no-data">No payments found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
