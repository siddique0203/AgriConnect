<h2 class="section-title">Contact Queries</h2>

<div class="controls">
  <form method="GET" action="adminDashboard.php" class="search-form">
    <input type="hidden" name="section" value="queries">
    <select name="status">
      <option value="">All</option>
      <option value="pending">pending</option>
      <option value="in_progress">in_progress</option>
      <option value="resolved">resolved</option>
      <option value="closed">closed</option>
    </select>
    <button type="submit">Filter</button>
  </form>
</div>

<div class="table-container">
  <table class="query-table">
    <thead><tr><th>ID</th><th>User</th><th>Email</th><th>Subject</th><th>Message</th><th>Status</th><th>Response</th><th>Action</th></tr></thead>
    <tbody>
      <?php if(!empty($queries)): foreach($queries as $q): ?>
      <tr>
        <td><?php echo $q['query_id']; ?></td>
        <td><?php echo htmlspecialchars($q['user_name'] ?? $q['guest_name']); ?></td>
        <td><?php echo htmlspecialchars($q['guest_email']); ?></td>
        <td><?php echo htmlspecialchars($q['subject']); ?></td>
        <td><?php echo htmlspecialchars($q['message']); ?></td>
        <td><?php echo $q['status']; ?></td>
        <td><?php echo htmlspecialchars($q['admin_response']); ?></td>
        <td>
          <button onclick="document.getElementById('respond-<?php echo $q['query_id']; ?>').style.display='block'">Respond</button>
          <div id="respond-<?php echo $q['query_id']; ?>" class="modal" style="display:none;">
            <form method="POST" action="adminDashboard.php?section=queries">
              <input type="hidden" name="action" value="respond_query">
              <input type="hidden" name="query_id" value="<?php echo $q['query_id']; ?>">
              <label>Response</label>
              <textarea name="response"><?php echo htmlspecialchars($q['admin_response']); ?></textarea>
              <label>Status</label>
              <select name="status">
                <option value="pending">pending</option>
                <option value="in_progress">in_progress</option>
                <option value="resolved">resolved</option>
                <option value="closed">closed</option>
              </select>
              <button type="submit">Send</button>
              <button type="button" onclick="document.getElementById('respond-<?php echo $q['query_id']; ?>').style.display='none'">Cancel</button>
            </form>
          </div>
        </td>
      </tr>
      <?php endforeach; else: ?>
      <tr><td colspan="8">No queries found</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>