<?php
$page_title = "Job Listings";
include __DIR__ . '/../layouts/header.php';

require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);

// Handle search query
$searchTerm = $_GET['search'] ?? '';

// Handle filter values
$postingTypes = $_GET['posting_type'] ?? [];
$employmentTypes = $_GET['employment_type'] ?? [];
$workTypes = $_GET['work_type'] ?? [];

// Determine which jobs to retrieve
if ($searchTerm) {
    $jobs = $jobModel->searchJobs($searchTerm);
    if (!empty($postingTypes) || !empty($employmentTypes) || !empty($workTypes)) {
        $jobs = array_filter($jobs, function ($job) use ($postingTypes, $employmentTypes, $workTypes) {
            $postingTypeCondition = empty($postingTypes) || in_array($job['posting_type'], $postingTypes);
            $employmentTypeCondition = empty($employmentTypes) || in_array($job['employment_type'], $employmentTypes);
            $workTypeCondition = empty($workTypes) || in_array($job['work_type'], $workTypes);
            return $postingTypeCondition && $employmentTypeCondition && $workTypeCondition;
        });
    }
} elseif (!empty($postingTypes) || !empty($employmentTypes) || !empty($workTypes)) {
    $jobs = $jobModel->filterJobs($postingTypes, $employmentTypes, $workTypes);
} else {
    $jobs = $jobModel->getAllJobs(true); // Get only approved jobs
}
?>

<h1>Job Listings</h1>

<form action="" method="GET">
    <input type="text" name="search" placeholder="Search jobs..." value="<?php echo html_escape($searchTerm); ?>">
    <button type="submit">Search</button>

    <fieldset>
        <legend>Posting Type</legend>
        <label><input type="checkbox" name="posting_type[]" value="fulltime" <?php if (in_array('fulltime', $postingTypes)) echo 'checked'; ?>> Full-time</label>
        <label><input type="checkbox" name="posting_type[]" value="internship" <?php if (in_array('internship', $postingTypes)) echo 'checked'; ?>> Internship</label>
    </fieldset>

    <fieldset>
        <legend>Employment Type</legend>
        <label><input type="checkbox" name="employment_type[]" value="fulltime" <?php if (in_array('fulltime', $employmentTypes)) echo 'checked'; ?>> Full-time</label>
        <label><input type="checkbox" name="employment_type[]" value="parttime" <?php if (in_array('parttime', $employmentTypes)) echo 'checked'; ?>> Part-time</label>
    </fieldset>

    <fieldset>
        <legend>Work Type</legend>
        <label><input type="checkbox" name="work_type[]" value="onsite" <?php if (in_array('onsite', $workTypes)) echo 'checked'; ?>> On-site</label>
        <label><input type="checkbox" name="work_type[]" value="remote" <?php if (in_array('remote', $workTypes)) echo 'checked'; ?>> Remote</label>
        <label><input type="checkbox" name="work_type[]" value="hybrid" <?php if (in_array('hybrid', $workTypes)) echo 'checked'; ?>> Hybrid</label>
    </fieldset>

    <button type="submit">Apply Filters</button>
</form>

<?php if (empty($jobs)): ?>
    <p>No jobs available matching your criteria. Please check back later.</p>
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