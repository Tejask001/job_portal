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

function format_text($text)
{
    $text = str_replace('_', ' ', $text);  // Replace underscores with spaces
    $text = ucwords($text);               // Capitalize the first letter of each word
    return html_escape($text);             // Escape HTML entities
}
?>

<h1>Job Listings</h1>

<form action="" method="GET">
    <input type="text" name="search" placeholder="Search jobs..." value="<?php echo html_escape($searchTerm); ?>">
    <button type="submit">Search</button>

    <fieldset>
        <legend>Opportunity Type</legend>
        <label><input type="checkbox" name="posting_type[]" value="regular_job" <?php if (in_array('regular_job', $postingTypes)) echo 'checked'; ?>>Regular Job</label>
        <label><input type="checkbox" name="posting_type[]" value="internship" <?php if (in_array('internship', $postingTypes)) echo 'checked'; ?>> Internship</label>
    </fieldset>

    <fieldset>
        <legend>Employment Status</legend>
        <label><input type="checkbox" name="employment_type[]" value="fulltime" <?php if (in_array('fulltime', $employmentTypes)) echo 'checked'; ?>> Full-time</label>
        <label><input type="checkbox" name="employment_type[]" value="parttime" <?php if (in_array('parttime', $employmentTypes)) echo 'checked'; ?>> Part-time</label>
        <label><input type="checkbox" name="employment_type[]" value="contract" <?php if (in_array('contract', $employmentTypes)) echo 'checked'; ?>>Contract</label>
        <!-- Add new employment types if you have them -->
    </fieldset>

    <fieldset>
        <legend>Work Arrangement</legend>
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
            <!-- Display additional Job Fields -->
            <p><strong>Opportunity Type:</strong> <?php echo format_text($job['posting_type']); ?></p>
            <p><strong>Employment Status:</strong> <?php echo format_text($job['employment_type']); ?></p>
            <p><strong>Work Arrangement:</strong> <?php echo format_text($job['work_type']); ?></p>
            <p><strong>Age:</strong> <?php echo html_escape($job['age']); ?></p>
            <p><strong>Experience:</strong> <?php echo html_escape($job['experience']); ?></p>
            <p><?php echo substr(html_escape($job['description']), 0, 100); ?>...</p>
            <p><strong>Location:</strong> <?php echo html_escape($job['job_location']); ?></p>
            <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . $job['id']); ?>">View Details</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>