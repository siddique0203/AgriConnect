<h2 class="section-title">Orders & Carts</h2>

<div class="controls">
  <form method="GET" action="adminDashboard.php" class="search-form">
    <input type="hidden" name="section" value="orders">
    <input type="text" name="user_id" placeholder="Enter user id to view cart" value="<?php echo htmlspecialchars($_GET['user_id'] ?? ''); ?>">
    <input type="hidden" name="action" value="view_cart">
    <button type="submit">View Cart</button>
  </form>
</div>

<?php if(!empty($orders)): ?>
<h3>Order History</h3>
<div class="table-container">
  <table class="order-table">
    <thead>
      <tr>
        <th>Order ID</th>
        <th>User</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
        <th>Items</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $o): ?>
      <tr>
        <td><?php echo $o['order_id']; ?></td>
        <td><?php echo htmlspecialchars($o['customer_name']); ?></td>
        <td><?php echo $o['total_amount']; ?></td>
        <td>
          <form method="POST" action="adminDashboard.php?section=orders">
            <input type="hidden" name="action" value="update_order_status">
            <input type="hidden" name="order_id" value="<?php echo $o['order_id']; ?>">
            <select name="status">
              <?php 
              $statuses = ['pending','confirmed','shipped','delivered','cancelled']; 
              foreach($statuses as $s){
                  $sel = ($s==$o['status'])?'selected':''; 
                  echo "<option value='$s' $sel>$s</option>";
              }
              ?>
            </select>
        </td>
        <td><?php echo $o['order_date']; ?></td>
        <td>
          <ul>
            <?php $items = $this->orderModel->getOrderItems($o['order_id']); 
            foreach($items as $it){
                echo '<li>'.htmlspecialchars($it['product_name']).' (Qty: '.$it['quantity'].')</li>';
            } ?>
          </ul>
        </td>
        <td><button type="submit" class="btn-search">Update</button></td>
          </form>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php else: ?>
<p>No order history found.</p>
<?php endif; ?>

<?php if(isset($cart)): ?>
<h3>User Cart</h3>
<div class="cart-container table-container">
  <table class="cart-table">
    <thead>
      <tr class="cart-header-row">
        <th class="cart-col-id">Cart ID</th>
        <th class="cart-col-product">Product</th>
        <th class="cart-col-qty">Qty</th>
        <th class="cart-col-date">Added At</th>
        <th class="cart-col-actions">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($cart)): foreach($cart as $c): ?>
        <tr class="cart-row">
          <td class="cart-id"><?php echo $c['cart_id']; ?></td>
          <td class="cart-product"><?php echo htmlspecialchars($c['product_name']); ?></td>
          <td class="cart-quantity">
            <form method="POST" action="adminDashboard.php?section=orders" class="cart-qty-form">
              <input type="hidden" name="action" value="update_cart">
              <input type="hidden" name="cart_id" value="<?php echo $c['cart_id']; ?>">
              <input type="number" name="quantity" value="<?php echo $c['quantity']; ?>" min="1" class="cart-qty-input">
              <button type="submit" class="cart-btn update-btn">Update</button>
            </form>
          </td>
          <td class="cart-date"><?php echo $c['added_at']; ?></td>
          <td class="cart-actions">
            <form method="POST" action="adminDashboard.php?section=orders" onsubmit="return confirm('Delete this cart item?');" class="cart-delete-form">
              <input type="hidden" name="action" value="delete_cart">
              <input type="hidden" name="cart_id" value="<?php echo $c['cart_id']; ?>">
              <button type="submit" class="cart-btn delete-btn">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; else: ?>
        <tr class="cart-row">
          <td colspan="5" class="no-cart-data">No cart items found for this user</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<?php endif; ?>
