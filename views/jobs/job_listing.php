<?php
$page_title = "Job Listings";
include __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);

// Handle search query
$searchTerm = $_GET['search'] ?? '';  // Get search term from query string

if ($searchTerm) {
    $jobs = $jobModel->searchJobs($searchTerm);
} else {
    $jobs = $jobModel->getAllJobs(true); // Get only approved jobs
}
?>

<h1>Job Listings</h1>

<form action="" method="GET">
    <input type="text" name="search" placeholder="Search jobs..." value="<?php echo html_escape($searchTerm); ?>">
    <button type="submit">Search</button>
</form>

<?php if (empty($jobs)): ?>
    <p>No jobs available at the moment. Please check back later.</p>
<?php else: ?>
    <?php foreach ($jobs as $job): ?>
        <div class="job-listing">
            <h3><?php echo html_escape($job['title']); ?></h3>
            <p class="company-name"><?php echo html_escape($job['company_name']); ?></p>
            <p><?php echo substr(html_escape($job['description']), 0, 100); ?>...</p>
            <p><strong>Location:</strong> <?php echo html_escape($job['job_location']); ?></p>
            <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>">View Details</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>