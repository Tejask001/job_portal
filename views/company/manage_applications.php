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

<h1>Manage Job Applications</h1>

<?php if (empty($applications)): ?>
    <p>No applications received yet.</p>
<?php else: ?>
    <table class="admin-dashboard">
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
                    <td><?php echo html_escape($application['seeker_name']); ?></td>
                    <td><?php echo html_escape($application['title']); ?></td>
                    <td><?php echo html_escape($application['applied_at']); ?></td>
                    <td><?php echo html_escape($application['application_status']); ?></td>
                    <td>
                        <?php if ($application['application_status'] === 'pending'): ?>
                            <a href="<?php echo generate_url('controllers/CompanyController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>">Approve</a>
                            <a href="<?php echo generate_url('controllers/CompanyController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>">Reject</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>