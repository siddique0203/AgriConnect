<h2 class="section-title">Payment Management</h2>

<div class="controls">
  <form method="GET" action="adminDashboard.php" class="search-form">
    <input type="hidden" name="section" value="payments">
    <select name="method">
      <option value="">All</option>
      <option value="cash_on_delivery">Cash</option>
      <option value="bkash">bKash</option>
      <option value="nagad">Nagad</option>
      <option value="card">Card</option>
    </select>
    <input type="hidden" name="action" value="search_payment">
    <button type="submit">Filter</button>
  </form>
</div>

<div class="table-container">
  <table class="payment-table">
    <thead><tr><th>Payment ID</th><th>Order</th><th>User</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
      <?php if(!empty($payments)): foreach($payments as $p): ?>
      <tr>
        <td><?php echo $p['payment_id']; ?></td>
        <td><?php echo $p['order_id']; ?></td>
        <td><?php echo htmlspecialchars($p['customer_name'] ?? ''); ?></td>
        <td><?php echo number_format($p['amount'],2); ?></td>
        <td><?php echo ucfirst(str_replace('_',' ',$p['method'])); ?></td>
        <td><?php echo ucfirst($p['status']); ?></td>
        <td><?php echo $p['payment_date']; ?></td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7">No payments found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>