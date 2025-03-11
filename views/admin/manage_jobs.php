<?php
$page_title = "Manage Jobs";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../controllers/AdminController.php';
$adminController = new AdminController($pdo);
$jobs = $adminController->getAllJobs();
?>

<h1>Manage Jobs</h1>

<table class="admin-dashboard">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Company</th>
            <th>Posting Type</th>
            <th>Approval</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($jobs as $job): ?>
            <tr>
                <td><?php echo html_escape($job['id']); ?></td>
                <td><?php echo html_escape($job['title']); ?></td>
                <td><?php echo html_escape($job['company_name']); ?></td>
                <td><?php echo html_escape($job['posting_type']); ?></td>
                <td>
                    <?php if ($job['admin_approval']): ?>
                        Approved
                    <?php else: ?>
                        Pending
                    <?php endif; ?>
                </td>
                <td><?php echo html_escape($job['created_at']); ?></td>
                <td>
                    <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($job['id'])); ?>" class="btn btn-view">View Details</a>
                    <?php if (!$job['admin_approval']): ?>
                        <a href="<?php echo generate_url('controllers/AdminController.php?action=approve_job&id=' . html_escape($job['id'])); ?>" class="btn btn-approve">Approve</a>
                    <?php else: ?>
                        <a href="<?php echo generate_url('controllers/AdminController.php?action=unapprove_job&id=' . html_escape($job['id'])); ?>" class="btn btn-reject">Unapprove</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../layouts/footer.php'; ?>