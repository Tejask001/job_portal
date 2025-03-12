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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold"><i class="bi bi-person-circle me-2"></i> View Job Application</h1>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0"><i class="bi bi-info-circle me-1"></i> Applicant Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-person me-1"></i> Name:</strong> <?php echo html_escape($application['name']); ?></p>
                        <p><strong><i class="bi bi-envelope me-1"></i> Email:</strong>
                            <a href="mailto:<?php echo html_escape($application['email']); ?>" class="text-decoration-none">
                                <?php echo html_escape($application['email']); ?>
                            </a>
                        </p>
                        <p><strong><i class="bi bi-telephone me-1"></i> Phone:</strong> <?php echo html_escape($application['phone']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-chat-text me-1"></i> Why are you a fit?</strong></p>
                        <div class="bg-light p-3 rounded border">
                            <?php echo nl2br(html_escape($application['why_are_you_fit'])); ?>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center">
                    <p>
                        <strong><i class="bi bi-file-earmark-text me-1"></i> Resume:</strong>
                        <?php if ($application['resume_path']): ?>
                            <a href="<?php echo generate_url($application['resume_path']); ?>" target="_blank" class="btn btn-success btn-sm">
                                <i class="bi bi-eye me-1"></i> View Resume
                            </a>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> No resume provided</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Add Application Status Update Section Here -->
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>