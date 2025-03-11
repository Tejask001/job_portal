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

<h1><?php echo html_escape($company['company_name']); ?></h1>

<?php if ($company['company_logo']): ?>
    <img src="<?php echo generate_url($company['company_logo']); ?>" alt="<?php echo html_escape($company['company_name']); ?> Logo" style="max-width: 200px;">
<?php endif; ?>

<dl>
    <dt>Industry:</dt>
    <dd><?php echo html_escape($company['industry'] ?? 'N/A'); ?></dd>

    <dt>Employee Count:</dt>
    <dd><?php echo html_escape($company['employee_count'] ?? 'N/A'); ?></dd>

    <dt>Location:</dt>
    <dd><?php echo html_escape($company['location'] ?? 'N/A'); ?></dd>

    <dt>Website:</dt>
    <dd><a href="<?php echo html_escape($company['website_link'] ?? '#'); ?>" target="_blank"><?php echo html_escape($company['website_link'] ?? 'N/A'); ?></a></dd>

    <dt>Description:</dt>
    <dd><?php echo html_escape($company['company_description'] ?? 'N/A'); ?></dd>
</dl>

<a href="<?php echo generate_url("views/jobs/company_jobs.php?id=" . html_escape($company_id)); ?>" class="btn">View Jobs at <?php echo html_escape($company['company_name']); ?></a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>