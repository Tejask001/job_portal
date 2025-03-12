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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-people me-2"></i> Manage Users</h1>

        <?php if (empty($users)): ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i> No users found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>User Type</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo html_escape($user['id']); ?></td>
                                <td>
                                    <?php
                                    $user_type = html_escape($user['user_type']);
                                    $user_type_class = '';

                                    switch ($user_type) {
                                        case 'admin':
                                            $user_type_class = 'badge bg-danger';
                                            break;
                                        case 'company':
                                            $user_type_class = 'badge bg-success';
                                            break;
                                        case 'jobseeker':
                                            $user_type_class = 'badge bg-info text-dark';
                                            break;
                                        default:
                                            $user_type_class = 'badge bg-secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="<?php echo $user_type_class; ?>"><?php echo $user_type; ?></span>
                                </td>
                                <td><?php echo html_escape($user['name']); ?></td>
                                <td><?php echo html_escape($user['email']); ?></td>
                                <td><?php echo html_escape($user['created_at']); ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="<?php echo generate_url('views/admin/view_user_details.php?id=' . html_escape($user['id']) . '&user_type=' . html_escape($user['user_type'])); ?>" class="btn btn-sm btn-info" title="View Details">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <?php if ($user['user_type'] !== 'admin'): ?>
                                            <a href="<?php echo generate_url('controllers/AdminController.php?action=delete_user&id=' . html_escape($user['id'])); ?>" class="btn btn-sm btn-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>