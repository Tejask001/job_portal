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

<div class="container mt-4">
    <h1 class="mb-4">My Saved Jobs</h1>

    <?php if (empty($savedJobs)): ?>
        <p class="alert alert-info">You have no saved jobs.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($savedJobs as $job): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo html_escape($job['title']); ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo html_escape($job['company_name']); ?></h6>
                            <p class="card-text"><?php echo substr(html_escape($job['description']), 0, 100); ?>...</p>
                            <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>