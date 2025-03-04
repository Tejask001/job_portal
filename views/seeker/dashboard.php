<?php
$page_title = "Job Seeker Dashboard";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seeker') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../controllers/UserController.php';
$userController = new UserController($pdo);
$applications = $userController->getApplicationsByUserId($_SESSION['user_id']);
?>

<h1>Welcome, <?php echo html_escape($_SESSION['user_name']); ?>!</h1>
<p>Manage your profile, saved jobs, and applications here.</p>

<div class="row">
    <div class="column">
        <h2>My Applications</h2>
        <?php if (empty($applications)): ?>
            <p>You have not applied for any jobs yet.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($applications as $application): ?>
                    <li>
                        <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($application['job_id'])); ?>">
                            <?php echo html_escape($application['title']); ?>
                        </a> - Applied on: <?php echo html_escape($application['applied_at']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="column">
        <a href="<?php echo generate_url('views/seeker/profile.php'); ?>" class="btn">Update Profile</a>
        <a href="<?php echo generate_url('views/seeker/saved_jobs.php'); ?>" class="btn">View Saved Jobs</a>
        <a href="<?php echo generate_url('views/jobs/job_listing.php'); ?>" class="btn">Browse Jobs</a>
    </div>
</div>


<?php include __DIR__ . '/../layouts/footer.php'; ?>