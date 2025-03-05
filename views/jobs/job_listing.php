<?php
$page_title = "Job Listings";
include __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);
$jobs = $jobModel->getAllJobs(true); // Get only approved jobs
?>

<h1>Job Listings</h1>

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

<?php include __DIR__ . '/../layouts/footer.php'; ?>