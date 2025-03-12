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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i> Manage Job Applications</h1>

        <?php if (empty($applications)): ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i> No applications found.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Applicant Name</th>
                            <th>Job Title</th>
                            <th>Company Name</th>
                            <th>Applied At</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td><?php echo html_escape($application['seeker_name']); ?></td>
                                <td><?php echo html_escape($application['title']); ?></td>
                                <td><?php echo html_escape($application['company_name']); ?></td>
                                <td><?php echo html_escape($application['applied_at']); ?></td>
                                <td>
                                    <?php
                                    $status = html_escape($application['application_status']);
                                    $formatted_status = ucfirst($status); // Capitalize the first letter
                                    $status_class = '';

                                    switch ($status) {
                                        case 'pending':
                                            $status_class = 'badge bg-warning text-dark';
                                            break;
                                        case 'approved':
                                            $status_class = 'badge bg-success';
                                            break;
                                        case 'rejected':
                                            $status_class = 'badge bg-danger';
                                            break;
                                        default:
                                            $status_class = 'badge bg-secondary';
                                            break;
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>"><?php echo $formatted_status; ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo generate_url('views/admin/view_application.php?application_id=' . html_escape($application['id'])); ?>" class="btn btn-info btn-sm" title="View Application">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <?php if ($application['application_status'] === 'pending'): ?>
                                        <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>" class="btn btn-success btn-sm" title="Approve Application">
                                            <i class="bi bi-check-circle"></i> Approve
                                        </a>
                                        <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>" class="btn btn-danger btn-sm" title="Reject Application">
                                            <i class="bi bi-x-circle"></i> Reject
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