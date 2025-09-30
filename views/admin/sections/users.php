<h2 class="section-title">User Management</h2>

<div class="controls">
  <form method="GET" action="adminDashboard.php" class="search-form">
    <input type="hidden" name="section" value="users">
    <select name="user_type">
      <option value="All">All</option>
      <option value="Farmer">Farmer</option>
      <option value="Consumer">Consumer</option>
      <option value="Admin">Admin</option>
    </select>
    <input type="hidden" name="action" value="search_user">
    <input type="text" name="keyword" placeholder="Search users...">
    <button type="submit" class="btn-search">Search</button>
  </form>
</div>

<div class="table-container">
  <table class="user-table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Type</th><th>Phone</th><th>Address</th><th>Action</th></tr></thead>
    <tbody>
      <?php if (!empty($users)): foreach($users as $u): ?>
      <tr>
        <td><?php echo $u['user_id']; ?></td>
        <td><?php echo htmlspecialchars($u['name']); ?></td>
        <td><?php echo htmlspecialchars($u['email']); ?></td>
        <td><?php echo $u['user_type']; ?></td>
        <td><?php echo htmlspecialchars($u['phone']); ?></td>
        <td><?php echo htmlspecialchars($u['address']); ?></td>
        <td>
          <button class="btn-edit" onclick="document.getElementById('edit-<?php echo $u['user_id']; ?>').style.display='block'">Update</button>
          <form method="POST" action="adminDashboard.php?section=users" style="display:inline-block">
            <input type="hidden" name="action" value="delete_user">
            <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
            <button type="submit" class="btn-delete">Delete</button>
          </form>

          <!-- Edit modal (simple inline form) -->
          <div id="edit-<?php echo $u['user_id']; ?>" class="modal" style="display:none;">
            <form method="POST" action="adminDashboard.php?section=users" class="modal-form">
              <input type="hidden" name="action" value="update_user">
              <input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
              <label>Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($u['name']); ?>">
              <label>Phone</label>
              <input type="text" name="phone" value="<?php echo htmlspecialchars($u['phone']); ?>">
              <label>Address</label>
              <textarea name="address"><?php echo htmlspecialchars($u['address']); ?></textarea>
              <label>NID</label>
              <input type="text" name="nid" value="<?php echo htmlspecialchars($u['nid']); ?>">
              <button type="submit">Save</button>
              <button type="button" onclick="document.getElementById('edit-<?php echo $u['user_id']; ?>').style.display='none'">Cancel</button>
            </form>
          </div>

        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7" class="no-data">No users found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>