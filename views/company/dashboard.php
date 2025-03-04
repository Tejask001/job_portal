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

<h1>Company Dashboard</h1>
<p>Manage your job postings here.</p>

<a href="<?php echo generate_url('views/company/post_job.php'); ?>" class="btn">Post a New Job</a>
<a href="<?php echo generate_url('views/company/company_profile.php'); ?>" class="btn">Edit Company Profile</a>

<h2>My Job Postings</h2>

<?php if (empty($jobs)): ?>
    <p>You have not posted any jobs yet.</p>
<?php else: ?>
    <table class="admin-dashboard">
        <thead>
            <tr>
                <th>Title</th>
                <th>Posting Type</th>
                <th>Created At</th>
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
                        <a href="<?php echo generate_url('views/company/edit_job.php?id=' . html_escape($job['id'])); ?>">Edit</a>
                        <a href="<?php echo generate_url('controllers/JobController.php?action=delete_job&id=' . html_escape($job['id'])); ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>