<?php
$page_title = "Jobs at [Company Name]"; //Placeholder, will be dynamically updated
include __DIR__ . '/../layouts/header.php';

// Get the company ID from the query string
$company_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$company_id) {
    $_SESSION['error_message'] = "Company ID is required.";
    redirect(generate_url('index.php')); // Redirect to the homepage or job listing page
    exit();
}

require_once __DIR__ . '/../../models/Company.php';
require_once __DIR__ . '/../../models/Job.php';

$companyModel = new Company($pdo);
$jobModel = new Job($pdo);

$company = $companyModel->getCompanyById($company_id);

if (!$company) {
    $_SESSION['error_message'] = "Company not found.";
    redirect(generate_url('index.php')); // Redirect to the homepage or job listing page
    exit();
}

$jobs = $jobModel->getJobsByCompanyId($company_id);

$page_title = "Jobs at " . $company['company_name']; //Dynamic title update
?>

<h1>Jobs at <?php echo html_escape($company['company_name']); ?></h1>

<?php if (empty($jobs)): ?>
    <p>No jobs found for this company.</p>
<?php else: ?>
    <ul>
        <?php foreach ($jobs as $job): ?>
            <li>
                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($job['id'])); ?>"><?php echo html_escape($job['title']); ?></a> - <?php echo html_escape($job['job_location']); ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<a href="<?php echo generate_url('views/company/company_details.php?id=' . html_escape($company_id)); ?>" class="btn">Back to Company Details</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>