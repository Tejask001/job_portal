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
    // Company profile not yet created.  Redirect to create profile page.
    $_SESSION['info_message'] = "Please create your company profile.";
    redirect(generate_url('views/company/company_profile.php'));
    exit();
}

$jobController = new JobController($pdo);
$jobs = $jobController->getJobsByCompanyId($company['id']);
?>

<div class="container mt-4">
    <h1 class="mb-4">Company Dashboard</h1>
    <p>Manage your job postings here.</p>

    <div class="mb-3">
        <a href="<?php echo generate_url('views/company/post_job.php'); ?>" class="btn btn-primary">Post a New Job</a>
        <a href="<?php echo generate_url('views/company/company_profile.php'); ?>" class="btn btn-secondary">Edit Company Profile</a>
        <a href="<?php echo generate_url('views/company/manage_applications.php'); ?>" class="btn btn-info text-white">Manage Applications</a>
    </div>

    <h2>My Job Postings</h2>

    <?php if (empty($jobs)): ?>
        <p class="alert alert-info">You have not posted any jobs yet.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Posting Type</th>
                        <th>Created At</th>
                        <th>Admin Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?php echo html_escape($job['title']); ?></td>
                            <td><?php echo html_escape($job['posting_type']); ?></td>
                            <td><?php echo html_escape($job['created_at']); ?></td>
                            <td>
                                <?php
                                if ($job['admin_approval'] == 1) {
                                    echo '<span class="badge bg-success">Approved</span>';
                                } else {
                                    echo '<span class="badge bg-warning text-dark">Unapproved</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="<?php echo generate_url('views/company/edit_job.php?id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="<?php echo generate_url('controllers/JobController.php?action=delete_job&id=' . html_escape($job['id'])); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>