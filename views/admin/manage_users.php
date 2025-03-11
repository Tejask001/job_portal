<?php
$page_title = "Manage Users";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../controllers/AdminController.php';
$adminController = new AdminController($pdo);
$users = $adminController->getAllUsers();
?>

<h1>Manage Users</h1>

<table class="admin-dashboard">
    <thead>
        <tr>
            <th>ID</th>
            <th>User Type</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo html_escape($user['id']); ?></td>
                <td><?php echo html_escape($user['user_type']); ?></td>
                <td><?php echo html_escape($user['name']); ?></td>
                <td><?php echo html_escape($user['email']); ?></td>
                <td><?php echo html_escape($user['created_at']); ?></td>
                <td>
                    <a href="<?php echo generate_url('views/admin/view_user_details.php?id=' . html_escape($user['id']) . '&user_type=' . html_escape($user['user_type'])); ?>" class="btn">View Details</a>
                    <?php if ($user['user_type'] !== 'admin'): ?>
                        <a href="<?php echo generate_url('controllers/AdminController.php?action=delete_user&id=' . html_escape($user['id'])); ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layouts/footer.php'; ?>