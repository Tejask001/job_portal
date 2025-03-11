<?php
$page_title = "Company Details";
include __DIR__ . '/../layouts/header.php';

// Get the company ID from the query string
$company_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$company_id) {
    $_SESSION['error_message'] = "Company ID is required.";
    redirect(generate_url('index.php')); // Redirect to the homepage or company listing page
    exit();
}

require_once __DIR__ . '/../../models/Company.php';
$companyModel = new Company($pdo);
$company = $companyModel->getCompanyById($company_id);

if (!$company) {
    $_SESSION['error_message'] = "Company not found.";
    redirect(generate_url('index.php')); // Redirect to the homepage or company listing page
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title"><?php echo html_escape($company['company_name']); ?></h1>

            <?php if ($company['company_logo']): ?>
                <img src="<?php echo generate_url($company['company_logo']); ?>" alt="<?php echo html_escape($company['company_name']); ?> Logo" class="img-fluid mb-3" style="max-width: 200px;">
            <?php endif; ?>

            <dl class="row">
                <dt class="col-sm-3">Industry:</dt>
                <dd class="col-sm-9"><?php echo html_escape($company['industry'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-3">Employee Count:</dt>
                <dd class="col-sm-9"><?php echo html_escape($company['employee_count'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-3">Location:</dt>
                <dd class="col-sm-9"><?php echo html_escape($company['location'] ?? 'N/A'); ?></dd>

                <dt class="col-sm-3">Website:</dt>
                <dd class="col-sm-9">
                    <?php if (!empty($company['website_link'])): ?>
                        <a href="<?php echo html_escape($company['website_link']); ?>" target="_blank"><?php echo html_escape($company['website_link']); ?></a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </dd>

                <dt class="col-sm-3">Description:</dt>
                <dd class="col-sm-9"><?php echo html_escape($company['company_description'] ?? 'N/A'); ?></dd>
            </dl>

            <a href="<?php echo generate_url("views/jobs/company_jobs.php?id=" . html_escape($company_id)); ?>" class="btn btn-primary">View Jobs at <?php echo html_escape($company['company_name']); ?></a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>