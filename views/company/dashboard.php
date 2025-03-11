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
    return $formattedName;
}
?>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h1 class="mb-4 text-center">📊 Company Dashboard</h1>
        <p class="text-muted text-center">Manage your job postings here.</p>

        <div class="d-flex justify-content-center mb-4 gap-3">
            <a href="<?php echo generate_url('views/company/post_job.php'); ?>" class="btn btn-primary btn-lg"><i class="bi bi-plus-circle"></i> Post a Job</a>
            <a href="<?php echo generate_url('views/company/company_profile.php'); ?>" class="btn btn-secondary btn-lg"><i class="bi bi-pencil-square"></i> Edit Profile</a>
            <a href="<?php echo generate_url('views/company/manage_applications.php'); ?>" class="btn btn-info text-white btn-lg"><i class="bi bi-clipboard-check"></i> Manage Applications</a>
        </div>

        <h2 class="text-center mt-4">📌 My Job Postings</h2>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info text-center">You have not posted any jobs yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th><?php echo formatFieldName('title') ?></th>
                            <th><?php echo formatFieldName('posting_type') ?></th>
                            <th><?php echo formatFieldName('created_at') ?></th>
                            <th><?php echo formatFieldName('admin_approval') ?></th>
                            <th>Actions</th>
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
                                        echo '<span class="badge bg-success">✔ Approved</span>';
                                    } else {
                                        echo '<span class="badge bg-warning text-dark">⏳ Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="<?php echo generate_url('views/company/edit_job.php?id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="<?php echo generate_url('controllers/JobController.php?action=delete_job&id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this job?');"><i class="bi bi-trash"></i> Delete</a>
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