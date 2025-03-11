<?php
// session_start();
$page_title = "Welcome to Job Portal";
include __DIR__ . '/views/layouts/header.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Job.php';

$jobModel = new Job($pdo);
$jobs = $jobModel->getAllJobs(true, 10); // Get only approved jobs and limit to 10

?>

<h1>Latest Job Openings</h1>
<?php if (empty($jobs)): ?>
    <p>No jobs available at the moment. Please check back later.</p>
<?php else: ?>
    <?php foreach ($jobs as $job): ?>
        <div class="job-listing">
            <h3><?php echo html_escape($job['title']); ?></h3>
            <p class="company-name"><?php echo html_escape($job['company_name']); ?></p>
            <p><?php echo substr(html_escape($job['description']), 0, 100); ?>...</p>
            <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>">View Details</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/views/layouts/footer.php'; ?>