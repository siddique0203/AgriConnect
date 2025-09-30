<h2 class="section-title">Review & Feedback</h2>

<div class="table-container">
  <table class="feedback-table">
    <thead><tr><th>ID</th><th>Product</th><th>User</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr></thead>
    <tbody>
      <?php if(!empty($reviews)): foreach($reviews as $r): ?>
      <tr>
        <td><?php echo $r['review_id']; ?></td>
        <td><?php echo htmlspecialchars($r['product_name']); ?></td>
        <td><?php echo htmlspecialchars($r['customer_name']); ?></td>
        <td><?php echo $r['rating']; ?></td>
        <td><?php echo htmlspecialchars($r['comment']); ?></td>
        <td><?php echo $r['created_at']; ?></td>
        <td>
          <form method="POST" action="adminDashboard.php?section=reviews">
            <input type="hidden" name="action" value="delete_review">
            <input type="hidden" name="review_id" value="<?php echo $r['review_id']; ?>">
            <button type="submit" class="btn-delete">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="7">No reviews found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>