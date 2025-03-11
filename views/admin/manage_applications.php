<?php
$page_title = "Manage Job Applications";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/AdminController.php';
$adminController = new AdminController($pdo);
$applications = $adminController->getAllJobApplications();
?>

<div class="container mt-4">
    <h1 class="mb-4">Manage Job Applications</h1>

    <?php if (empty($applications)): ?>
        <p class="alert alert-info">No applications found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Applicant Name</th>
                        <th>Job Title</th>
                        <th>Applied At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo html_escape($application['name']); ?></td>
                            <td><?php echo html_escape($application['title']); ?></td>
                            <td><?php echo html_escape($application['applied_at']); ?></td>
                            <td><?php echo html_escape($application['application_status']); ?></td>
                            <td>
                                <a href="<?php echo generate_url('views/admin/view_application.php?application_id=' . html_escape($application['id'])); ?>" class="btn btn-info btn-sm">View Application</a>
                                <?php if ($application['application_status'] === 'pending'): ?>
                                    <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>" class="btn btn-success btn-sm">Approve</a>
                                    <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>" class="btn btn-danger btn-sm">Reject</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>