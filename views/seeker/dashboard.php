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
$notifications = $userController->getNotificationsByUserId($_SESSION['user_id']);
require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);
?>

<div class="container mt-4">
    <h1 class="mb-4">Welcome, <?php echo html_escape($_SESSION['user_name']); ?>!</h1>
    <p>Manage your profile, saved jobs, and applications here.</p>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">My Applications</h5>
                    <?php if (empty($applications)): ?>
                        <p class="card-text">You have not applied for any jobs yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($applications as $application):
                                //Get the Job to render the details of the application
                                $job = $jobModel->getJobById($application['job_id']);
                            ?>
                                <li class="list-group-item">
                                    <?php if (is_array($job) && isset($job['title'])): ?>
                                        <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($application['job_id'])); ?>">
                                            <?php echo html_escape($job['title']); ?>
                                        </a>
                                    <?php else: ?>
                                        Job Titled <?php echo  html_escape($application['title']); ?> is unavailabe
                                    <?php endif; ?>
                                    <small class="text-muted"> - Applied on: <?php echo html_escape($application['applied_at']); ?></small>
                                    <?php if ($application['application_status'] == 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($application['application_status'] == 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php endif; ?>
                                    <a href="<?php echo generate_url('controllers/UserController.php?action=withdraw_application&id=' . html_escape($application['id'])); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this application?');">Withdraw</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Notifications</h5>
                    <?php if (empty($notifications)): ?>
                        <p class="card-text">No new notifications.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($notifications as $notification): ?>
                                <li class="list-group-item">
                                    <?php echo html_escape($notification['message']); ?>
                                    <br>
                                    <small class="text-muted"> - <?php echo html_escape($notification['created_at']); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="mt-3"> <!-- Added mt-3 for spacing -->
                        <a href="<?php echo generate_url('views/seeker/profile.php'); ?>" class="btn btn-primary mb-2 w-100">Update Profile</a>
                        <a href="<?php echo generate_url('views/seeker/saved_jobs.php'); ?>" class="btn btn-outline-primary mb-2 w-100">View Saved Jobs</a>
                        <a href="<?php echo generate_url('views/jobs/job_listing.php'); ?>" class="btn btn-success w-100">Browse Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>