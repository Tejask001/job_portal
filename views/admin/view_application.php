<?php
$page_title = "View Job Application";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

// Check if application_id is provided
if (!isset($_GET['application_id']) || !is_numeric($_GET['application_id'])) {
    $_SESSION['error_message'] = "Invalid application ID.";
    redirect(generate_url('views/admin/manage_applications.php'));
    exit();
}

$application_id = intval($_GET['application_id']);

require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../models/Application.php';
require_once __DIR__ . '/../../models/User.php';

$adminController = new AdminController($pdo);
$applicationModel = new Application($pdo);

$application = $applicationModel->getApplicationById($application_id);

if (!$application) {
    $_SESSION['error_message'] = "Application not found.";
    redirect(generate_url('views/admin/manage_applications.php'));
    exit();
}

$userModel = new User($pdo);
$user = $userModel->getUserById($application['user_id']);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    $user = ['name' => 'User not found', 'email' => 'N/A', 'age' => 'N/A', 'gender' => 'N/A', 'experience' => 'N/A'];
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i> View Job Application</h1>

        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0"><i class="bi bi-person me-1"></i> Applicant Information</h5>
            </div>
            <div class="card-body">
                <p><i class="bi bi-person-fill me-1"></i> <strong>Name:</strong> <?php echo html_escape($application['name']); ?></p>
                <p><i class="bi bi-envelope-fill me-1"></i> <strong>Email:</strong> <?php echo html_escape($application['email']); ?></p>
                <p><i class="bi bi-telephone-fill me-1"></i> <strong>Phone:</strong> <?php echo html_escape($application['phone']); ?></p>
                <p><i class="bi bi-question-circle-fill me-1"></i> <strong>Why are you a fit:</strong> <?php echo html_escape($application['why_are_you_fit']); ?></p>

                <?php if ($application['resume_path']): ?>
                    <p><i class="bi bi-file-earmark-pdf-fill me-1"></i> <strong>Resume:</strong> <a href="<?php echo generate_url($application['resume_path']); ?>" target="_blank" class="text-primary">View Resume <i class="bi bi-box-arrow-up-right"></i></a></p>
                <?php else: ?>
                    <p><i class="bi bi-file-earmark-x-fill me-1"></i> <strong>Resume:</strong> No resume provided.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-info text-white py-3">
                <h5 class="card-title mb-0"><i class="bi bi-person-circle me-1"></i> User Details</h5>
            </div>
            <div class="card-body">
                <p><i class="bi bi-person-fill me-1"></i> <strong>User Name:</strong> <?php echo html_escape($user['name']); ?></p>
                <p><i class="bi bi-envelope-fill me-1"></i> <strong>User Email:</strong> <?php echo html_escape($user['email']); ?></p>
                <p><i class="bi bi-calendar-date-fill me-1"></i> <strong>Age:</strong> <?php echo html_escape($user['age']); ?></p>
                <p><i class="bi bi-gender-ambiguous me-1"></i> <strong>Gender:</strong> <?php echo html_escape($user['gender']); ?></p>
                <p><i class="bi bi-stars me-1"></i> <strong>Experience:</strong> <?php echo html_escape($user['experience']); ?></p>
            </div>
        </div>

        <!-- Update Application Status From This Page -->
        <div class="mb-3">
            <?php if ($application['application_status'] === 'pending'): ?>
                <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>" class="btn btn-success me-2"><i class="bi bi-check-circle me-1"></i> Approve</a>
                <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i> Reject</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>