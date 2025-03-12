<?php
// session_start();
$page_title = "Welcome to Job Portal";
include __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Job.php';

$jobModel = new Job($pdo);
$jobs = $jobModel->getAllJobs(true, 10); // Get only approved jobs and limit to 10

?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-house-fill me-2"></i> Latest Job Openings</h1>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i> No jobs available at the moment. Please check back later.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($jobs as $job): ?>
                    <div class="col-md-6 mb-4">
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

<?php include __DIR__ . '/views/layouts/footer.php'; ?>