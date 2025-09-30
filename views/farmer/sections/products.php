<h2 class="section-title">Product Management</h2>

<!-- Add Product -->
<form method="POST" action="farmerDashboard.php?section=products" enctype="multipart/form-data" class="product-form">
  <input type="hidden" name="action" value="add_product">

  <div class="form-group">
    <label for="product-name">Product Name</label>
    <input id="product-name" type="text" name="name" placeholder="Product Name" required>
  </div>

  <div class="form-group">
    <label for="product-desc">Product Description</label>
    <textarea id="product-desc" name="description" placeholder="Product Description" required></textarea>
  </div>

  <div class="form-group">
    <label for="product-price">Price</label>
    <input id="product-price" type="number" step="0.01" name="price" placeholder="Price" required>
  </div>

  <div class="form-group">
    <label for="product-stock">Stock</label>
    <input id="product-stock" type="number" name="stock" placeholder="Stock" required>
  </div>

  <div class="form-group">
    <label for="product-category">Category</label>
    <select id="product-category" name="category" required>
      <option value="vegetable">Vegetable</option>
      <option value="fruit">Fruit</option>
      <option value="grain">Grain</option>
      <option value="dairy">Dairy</option>
      <option value="meat">Meat</option>
      <option value="fish">Fish</option>
      <option value="grocery">Grocery</option>
    </select>
  </div>

  <div class="form-group">
    <label for="product-image">Upload Image</label>
    <input id="product-image" type="file" name="image" accept="image/*" required>
  </div>

  <button type="submit" class="btn-submit">Add Product</button>
</form>

<hr class="divider">

<!-- Search -->
<form method="GET" action="farmerDashboard.php" class="search-form">
  <input type="hidden" name="section" value="products">
  <input type="hidden" name="action" value="search_product">

  <input type="text" name="keyword" placeholder="Search product" class="search-input">
  <button type="submit" class="btn-search">Search</button>
</form>

<hr class="divider">

<!-- Product Table -->
<div class="table-container">
  <table class="product-table">
    <thead>
      <tr>
        <th>Product ID</th><th>Name</th><th>Description</th><th>Price</th>
        <th>Stock</th><th>Category</th><th>Image</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($products)) { ?>
        <?php foreach($products as $p): ?>
          <tr>
            <td><?php echo $p['product_id']; ?></td>
            <td><?php echo htmlspecialchars($p['name']); ?></td>
            <td><?php echo htmlspecialchars($p['description']); ?></td>
            <td><?php echo $p['price']; ?></td>
            <td><?php echo $p['stock']; ?></td>
            <td><?php echo $p['category']; ?></td>
            <td>
              <?php if (!empty($p['image'])): ?>
                <img src="../../uploads/<?php echo $p['image']; ?>" class="product-img">
              <?php else: ?>
                No Image
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="farmerDashboard.php?section=products" class="inline-form">
                <input type="hidden" name="action" value="delete_product">
                <input type="hidden" name="product_id" value="<?php echo $p['product_id']; ?>">
                <button type="submit" class="btn-delete">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php } else { ?>
        <tr><td colspan="8" class="no-data">No products found</td></tr>
      <?php } ?>
    </tbody>
  </table>
</div>
