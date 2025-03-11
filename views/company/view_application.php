<?php
$page_title = "View Job Application";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

// Check if application_id is provided
if (!isset($_GET['application_id']) || !is_numeric($_GET['application_id'])) {
    $_SESSION['error_message'] = "Invalid application ID.";
    redirect(generate_url('views/company/manage_applications.php'));
    exit();
}

$application_id = intval($_GET['application_id']);

require_once __DIR__ . '/../../controllers/CompanyController.php';
require_once __DIR__ . '/../../models/Company.php';
require_once __DIR__ . '/../../models/Application.php';

$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);

if (!$company) {
    $_SESSION['error_message'] = "Please create your company profile first.";
    redirect(generate_url('views/company/company_profile.php'));
    exit();
}

$applicationModel = new Application($pdo);
$application = $applicationModel->getApplicationById($application_id);

if (!$application) {
    $_SESSION['error_message'] = "Application not found.";
    redirect(generate_url('views/company/manage_applications.php'));
    exit();
}

// You might want to add another check to ensure the application belongs to a job posted by this company.

?>

<div class="container mt-4">
    <h1 class="mb-4">View Job Application</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Applicant Information</h5>
            <p><strong>Name:</strong> <?php echo html_escape($application['name']); ?></p>
            <p><strong>Email:</strong> <?php echo html_escape($application['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo html_escape($application['phone']); ?></p>
            <p><strong>Why are you a fit:</strong> <?php echo html_escape($application['why_are_you_fit']); ?></p>

            <?php if ($application['resume_path']): ?>
                <p><strong>Resume:</strong> <a href="<?php echo html_escape($application['resume_path']); ?>" target="_blank">View Resume</a></p>
            <?php else: ?>
                <p><strong>Resume:</strong> No resume provided.</p>
            <?php endif; ?>
        </div>
    </div>

    <a href="<?php echo generate_url('views/company/manage_applications.php'); ?>" class="btn btn-secondary mt-3">Back to Applications</a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>