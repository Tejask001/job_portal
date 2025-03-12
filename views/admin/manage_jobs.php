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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-briefcase me-2"></i> Manage Jobs</h1>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i> No jobs found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Posting Type</th>
                            <th>Approval Status</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo html_escape($job['id']); ?></td>
                                <td><?php echo html_escape($job['title']); ?></td>
                                <td><?php echo html_escape($job['company_name']); ?></td>
                                <td>
                                    <?php
                                    $posting_type = html_escape($job['posting_type']);
                                    $formatted_posting_type = ucwords(str_replace('_', ' ', $posting_type)); // Format the posting type
                                    echo $formatted_posting_type;
                                    ?>
                                </td>
                                <td>
                                    <?php if ($job['admin_approval']): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($job['created_at']); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($job['id'])); ?>" class="btn btn-info btn-sm" title="View Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if (!$job['admin_approval']): ?>
                                        <a href="<?php echo generate_url('controllers/AdminController.php?action=approve_job&id=' . html_escape($job['id'])); ?>" class="btn btn-success btn-sm" title="Approve Job">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo generate_url('controllers/AdminController.php?action=unapprove_job&id=' . html_escape($job['id'])); ?>" class="btn btn-danger btn-sm" title="Unapprove Job">
                                            <i class="bi bi-x-lg"></i> Unapprove
                                        </a>
                                    <?php endif; ?>
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