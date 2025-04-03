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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h1 class="fw-bold"><i class="bi bi-person-fill me-2"></i> Welcome, <?php echo html_escape($_SESSION['user_name']); ?>!</h1>
            <p class="text-muted">Manage your profile, saved jobs, and applications with ease.</p>
        </div>

        <div class="row">
            <!-- My Applications Section -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white"><i class="bi bi-file-earmark-text me-2"></i> My Applications</div>
                    <div class="card-body">
                        <?php if (empty($applications)): ?>
                            <p class="text-muted"><i class="bi bi-info-circle me-1"></i> You have not applied for any jobs yet.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($applications as $application):
                                    $job = $jobModel->getJobById($application['job_id']);
                                ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <?php if (is_array($job) && isset($job['title'])): ?>
                                                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($application['job_id'])); ?>"
                                                    class="fw-semibold text-decoration-none">
                                                    <i class="bi bi-briefcase me-1"></i> <?php echo html_escape($job['title']); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Job Unavailable</span>
                                            <?php endif; ?>
                                            <small class="d-block text-muted"><i class="bi bi-calendar-check me-1"></i> Applied on: <?php echo html_escape($application['applied_at']); ?></small>
                                        </div>
                                        <div class="application-actions d-flex align-items-center">
                                            <?php
                                            $status = html_escape($application['application_status']);
                                            $status_class = '';

                                            switch ($status) {
                                                case 'pending':
                                                    $status_class = 'badge bg-warning text-dark';
                                                    break;
                                                case 'approved':
                                                    $status_class = 'badge bg-success';
                                                    break;
                                                case 'rejected':
                                                    $status_class = 'badge bg-danger';
                                                    break;
                                                default:
                                                    $status_class = 'badge bg-secondary';
                                                    break;
                                            }
                                            ?>
                                            <span class="<?php echo $status_class; ?> me-2"><?php echo $status; ?></span>
                                            <a href="<?php echo generate_url('controllers/UserController.php?action=withdraw_application&id=' . html_escape($application['id'])); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to withdraw this application?');"><i class="bi bi-x-circle"></i> Withdraw</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-info text-white"><i class="bi bi-bell me-2"></i> Notifications</div>
                    <div class="card-body">
                        <?php if (empty($notifications)): ?>
                            <p class="text-muted"><i class="bi bi-info-circle me-1"></i> No new notifications.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($notifications as $notification): ?>
                                    <li class="list-group-item">
                                        <i class="bi bi-exclamation-circle me-1"></i> <span class="fw-semibold"><?php echo html_escape($notification['message']); ?></span>
                                        <br>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> <?php echo html_escape($notification['created_at']); ?></small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-success text-white"><i class="bi bi-lightning-fill me-2"></i> Quick Actions</div>
                    <div class="card-body d-flex flex-column">
                        <a href="<?php echo generate_url('views/seeker/profile.php'); ?>" class="btn btn-primary mb-2"><i class="bi bi-person-circle me-1"></i> Update Profile</a>
                        <a href="<?php echo generate_url('views/seeker/saved_jobs.php'); ?>" class="btn btn-info mb-2"><i class="bi bi-bookmark-heart me-1"></i> View Saved Jobs</a>
                        <a href="<?php echo generate_url('views/jobs/job_listing.php'); ?>" class="btn btn-success"><i class="bi bi-briefcase me-1"></i> Browse Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>