<h2 class="section-title">Product Management</h2>

<div class="controls">
  <form method="GET" action="adminDashboard.php" class="search-form">
    <input type="hidden" name="section" value="products">
    <select name="category">
      <option value="">All</option>
      <option value="vegetable" <?php if(($category ?? '')=='vegetable') echo 'selected'; ?>>Vegetable</option>
      <option value="fruit" <?php if(($category ?? '')=='fruit') echo 'selected'; ?>>Fruit</option>
      <option value="grain" <?php if(($category ?? '')=='grain') echo 'selected'; ?>>Grain</option>
      <option value="dairy" <?php if(($category ?? '')=='dairy') echo 'selected'; ?>>Dairy</option>
      <option value="meat" <?php if(($category ?? '')=='meat') echo 'selected'; ?>>Meat</option>
      <option value="fish" <?php if(($category ?? '')=='fish') echo 'selected'; ?>>Fish</option>
      <option value="grocery" <?php if(($category ?? '')=='grocery') echo 'selected'; ?>>Grocery</option>
    </select>
    <input type="hidden" name="action" value="search_product">
    <input type="text" name="keyword" placeholder="Search products..." value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    <button type="submit">Search</button>
  </form>
</div>

<div class="table-container">
  <table class="product-table">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Category</th><th>Farmer</th><th>Price</th><th>Stock</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($products)): foreach($products as $p): ?>
      <tr>
        <td><?php echo $p['product_id']; ?></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td><?php echo ucfirst($p['category']); ?></td>
        <td><?php echo htmlspecialchars($p['farmer_name']); ?></td>
        <td><?php echo $p['price']; ?></td>
        <td><?php echo $p['stock']; ?></td>
        <td>
          <form method="POST" action="adminDashboard.php?section=products" style="display:inline-block;">
            <input type="hidden" name="action" value="delete_product">
            <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
            <button type="submit" class="btn-delete">Remove</button>
          </form>

          <button class="btn-edit" onclick="document.getElementById('stock-<?php echo $p['product_id']; ?>').style.display='block'">Update Stock</button>

          <div id="stock-<?php echo $p['product_id']; ?>" class="modal" style="display:none;">
            <form method="POST" action="adminDashboard.php?section=products">
              <input type="hidden" name="action" value="update_stock">
              <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
              <label>Stock</label>
              <input type="number" name="stock" value="<?php echo $p['stock']; ?>">
              <button type="submit">Save</button>
              <button type="button" onclick="document.getElementById('stock-<?php echo $p['product_id']; ?>').style.display='none'">Cancel</button>
            </form>
          </div>

        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7" class="no-data">No products found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
