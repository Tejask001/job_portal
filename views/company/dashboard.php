<?php
$page_title = "Company Dashboard";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../models/Company.php';
require_once __DIR__ . '/../../controllers/JobController.php';
$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);

if (!$company) {
    $_SESSION['info_message'] = "Please create your company profile.";
    redirect(generate_url('views/company/company_profile.php'));
    exit();
}

$jobController = new JobController($pdo);
$jobs = $jobController->getJobsByCompanyId($company['id']);

function formatFieldName($fieldName)
{
    // Replace underscores with spaces
    $formattedName = str_replace('_', ' ', $fieldName);
    // Convert the first letter of each word to uppercase
    $formattedName = ucwords($formattedName);
    return html_escape($formattedName);
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4 text-center"><i class="bi bi-building me-2"></i> Company Dashboard</h1>
        <p class="text-muted text-center">Manage your job postings and company profile here.</p>

        <div class="d-flex justify-content-center mb-5 gap-3">
            <a href="<?php echo generate_url('views/company/post_job.php'); ?>" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle me-1"></i> Post a Job</a>
            <a href="<?php echo generate_url('views/company/company_profile.php'); ?>" class="btn btn-secondary btn-lg"><i class="bi bi-pencil-square me-1"></i> Edit Profile</a>
            <a href="<?php echo generate_url('views/company/manage_applications.php'); ?>" class="btn btn-info text-white btn-lg"><i class="bi bi-clipboard-check me-1"></i> Manage Applications</a>
        </div>

        <h2 class="text-center mt-5"><i class="bi bi-list-ul me-2"></i> My Job Postings</h2>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle me-1"></i> You have not posted any jobs yet.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th><i class="bi bi-textarea-t me-1"></i> <?php echo formatFieldName('title') ?></th>
                            <th><i class="bi bi-tag me-1"></i> <?php echo formatFieldName('posting_type') ?></th>
                            <th><i class="bi bi-clock me-1"></i> <?php echo formatFieldName('created_at') ?></th>
                            <th><i class="bi bi-shield-check me-1"></i> <?php echo formatFieldName('admin_approval') ?></th>
                            <th><i class="bi bi-gear me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                            <tr>
                                <td><?php echo html_escape($job['title']); ?></td>
                                <td><?php echo formatFieldName($job['posting_type']); ?></td>
                                <td><?php echo date("M d, Y", strtotime($job['created_at'])); ?></td>
                                <td>
                                    <?php
                                    if ($job['admin_approval'] == 1) {
                                        echo '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Approved</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo generate_url('views/company/edit_job.php?id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-outline-primary" title="Edit Job"><i class="bi bi-pencil"></i></a>
                                    <a href="<?php echo generate_url('controllers/JobController.php?action=delete_job&id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-outline-danger" title="Delete Job" onclick="return confirm('Are you sure you want to delete this job?');"><i class="bi bi-trash"></i></a>
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