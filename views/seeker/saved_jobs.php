<?php
$page_title = "Saved Jobs";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seeker') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../controllers/UserController.php';
$userController = new UserController($pdo);
$savedJobs = $userController->getSavedJobsByUserId($_SESSION['user_id']);
?>

<h1>My Saved Jobs</h1>

<?php if (empty($savedJobs)): ?>
    <p>You have no saved jobs.</p>
<?php else: ?>
    <?php foreach ($savedJobs as $job): ?>
        <div class="job-listing">
            <h3><?php echo html_escape($job['title']); ?></h3>
            <p class="company-name"><?php echo html_escape($job['company_name']); ?></p>
            <p><?php echo substr(html_escape($job['description']), 0, 100); ?>...</p>
            <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>">View Details</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>