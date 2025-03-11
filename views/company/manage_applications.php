<?php
$page_title = "Manage Job Applications";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/CompanyController.php';
require_once __DIR__ . '/../../models/Company.php';

$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);

if (!$company) {
    $_SESSION['error_message'] = "Please create your company profile first.";
    redirect(generate_url('views/company/company_profile.php'));
    exit();
}

$companyController = new CompanyController($pdo);
$applications = $companyController->getJobApplications($company['id']);
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold"><i class="bi bi-clipboard-check"></i> Manage Job Applications</h1>
    </div>

    <?php if (empty($applications)): ?>
        <div class="alert alert-info text-center">
            <i class="bi bi-exclamation-circle"></i> No applications received yet.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th><i class="bi bi-person"></i> Applicant Name</th>
                        <th><i class="bi bi-briefcase"></i> Job Title</th>
                        <th><i class="bi bi-calendar"></i> Applied At</th>
                        <th><i class="bi bi-info-circle"></i> Status</th>
                        <th><i class="bi bi-gear"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?php echo html_escape($application['seeker_name']); ?></td>
                            <td><?php echo html_escape($application['title']); ?></td>
                            <td><?php echo html_escape($application['applied_at']); ?></td>
                            <td>
                                <?php
                                $status = strtolower($application['application_status']);
                                $badgeClass = ($status === 'approved') ? 'bg-success' : (($status === 'rejected') ? 'bg-danger' : 'bg-warning text-dark');
                                ?>
                                <span class="badge <?php echo $badgeClass; ?> p-2">
                                    <?php echo ucfirst($application['application_status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo generate_url('views/company/view_application.php?application_id=' . html_escape($application['id'])); ?>"
                                    class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> View Application
                                </a>
                                <?php if ($status === 'pending'): ?>
                                    <a href="<?php echo generate_url('controllers/CompanyController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>"
                                        class="btn btn-success btn-sm">
                                        <i class="bi bi-check-circle"></i> Approve
                                    </a>
                                    <a href="<?php echo generate_url('controllers/CompanyController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>"
                                        class="btn btn-danger btn-sm">
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


<?php include __DIR__ . '/../layouts/footer.php'; ?>