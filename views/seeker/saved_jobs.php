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

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <h1 class="mb-4"><i class="bi bi-bookmark-heart-fill me-2"></i> My Saved Jobs</h1>

        <?php if (empty($savedJobs)): ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i> You have no saved jobs.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($savedJobs as $job): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-briefcase me-1"></i> <?php echo html_escape($job['title']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><i class="bi bi-building me-1"></i> <?php echo html_escape($job['company_name']); ?></h6>
                                <p class="card-text">
                                    <i class="bi bi-card-text me-1"></i> <?php echo substr(html_escape($job['description']), 0, 100); ?>...
                                </p>
                                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>" class="btn btn-primary"><i class="bi bi-eye me-1"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>