<?php
// session_start();
$page_title = "Welcome to Job Portal";
include __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Job.php';

$jobModel = new Job($pdo);
$jobs = $jobModel->getAllJobs(true, 10); // Get only approved jobs and limit to 10

?>

<div class="container">
    <h1 class="mt-4 mb-4">Latest Job Openings</h1>

    <?php if (empty($jobs)): ?>
        <p class="alert alert-info">No jobs available at the moment. Please check back later.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($jobs as $job): ?>
                <div class="col-md-6 mb-4"> <!-- Adjust col-md- to col-12 for single column on small screens -->
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

<?php include __DIR__ . '/views/layouts/footer.php'; ?>