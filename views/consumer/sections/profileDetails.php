<?php
?>
<h2 class="section-title">Profile Details</h2>

<?php if(isset($_GET['success'])): ?>
  <p class="success-msg">Profile updated successfully!</p>
<?php endif; ?>

<form method="POST" action="consumerProfile.php?section=profile" class="profile-form">
  <input type="hidden" name="action" value="update_profile">

  <div class="form-group">
    <label for="name">Name:</label>
    <input id="name" type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
  </div>

  <div class="form-group">
    <label for="email">Email:</label>
    <!-- readonly so email cannot be changed via this form -->
    <input id="email" type="email" name="email" readonly value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
  </div>

  <div class="form-group">
    <label for="phone">Phone:</label>
    <input id="phone" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
  </div>

  <div class="form-group">
    <label for="address">Address:</label>
    <textarea id="address" name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
  </div>

  <div class="form-group">
    <label for="nid">NID (optional):</label>
    <input id="nid" type="text" name="nid" value="<?php echo htmlspecialchars($user['nid'] ?? ''); ?>">
  </div>

  <button type="submit" class="btn-submit">Update</button>
</form>
