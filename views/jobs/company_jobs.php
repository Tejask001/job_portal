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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-building me-2"></i> Jobs at <?php echo html_escape($company['company_name']); ?></h1>

        <?php if (empty($jobs)): ?>
            <div class="alert alert-info" role="alert"><i class="bi bi-info-circle me-2"></i> No jobs found for this company.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($jobs as $job): ?>
                    <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($job['id'])); ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1"><i class="bi bi-file-earmark-text me-1"></i> <?php echo html_escape($job['title']); ?></h6>
                        </div>
                        <span class="badge bg-primary rounded-pill"><i class="bi bi-geo-alt me-1"></i> <?php echo html_escape($job['job_location']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>