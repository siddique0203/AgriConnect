<h2 class="Apage-title">Admins</h2>

<!-- Messages -->
<?php if(isset($_GET['error'])): ?>
    <?php if($_GET['error'] === 'wrong_super_pass'): ?>
        <p class="Amsg Aerror">Super admin password is incorrect!</p>
    <?php elseif($_GET['error'] === 'last_admin'): ?>
        <p class="Amsg Aerror">Cannot delete the last admin!</p>
    <?php elseif($_GET['error'] === 'delete_fail'): ?>
        <p class="Amsg Aerror">Failed to delete admin.</p>
    <?php endif; ?>
<?php endif; ?>

<?php if(isset($_GET['success']) && $_GET['success'] === 'delete'): ?>
    <p class="Amsg Asuccess">Admin deleted successfully.</p>
<?php endif; ?>

<!-- Add Admin Form -->
<form method="POST" action="adminDashboard.php" class="Aadmin-form">
    <input type="hidden" name="action" value="add_admin">
    <input type="text" name="name" placeholder="Admin Name" required class="Aform-input">
    <input type="email" name="email" placeholder="Admin Email" required class="Aform-input">
    <input type="text" name="password" placeholder="Password" required class="Aform-input">
    <button type="submit" class="Abtn Abtn-primary">Add Admin</button>
</form>

<?php if(isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
    <p class="Amsg Aerror">Email already exists!</p>
<?php endif; ?>

<!-- Admin List -->
<table class="Aadmin-table">
    <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Action</th>
    </tr>
    <?php foreach($admins as $admin): ?>
    <tr>
        <td><?= $admin['admin_id'] ?></td>
        <td><?= $admin['name'] ?></td>
        <td><?= $admin['email'] ?></td>
        <td>
            <form method="POST" class="Adelete-form">
                <input type="hidden" name="action" value="delete_admin">
                <input type="hidden" name="admin_id" value="<?= $admin['admin_id'] ?>">
                <input type="password" name="super_pass" placeholder="Super Admin Password" required class="Aform-input Asmall">
                <button type="submit" class="Abtn Abtn-danger">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
