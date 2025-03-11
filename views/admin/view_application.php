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
                <p><strong>Resume:</strong> <a href="<?php echo generate_url($application['resume_path']); ?>" target="_blank">View Resume</a></p>
            <?php else: ?>
                <p><strong>Resume:</strong> No resume provided.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">User Details</h5>
            <p><strong>User Name:</strong> <?php echo html_escape($user['name']); ?></p>
            <p><strong>User Email:</strong> <?php echo html_escape($user['email']); ?></p>
            <p><strong>Age:</strong> <?php echo html_escape($user['age']); ?></p>
            <p><strong>Gender:</strong> <?php echo html_escape($user['gender']); ?></p>
            <p><strong>Experience:</strong> <?php echo html_escape($user['experience']); ?></p>
        </div>
    </div>

    <!-- Update Application Status From This Page -->
    <div class="mt-3">
        <?php if ($application['application_status'] === 'pending'): ?>
            <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=approved'); ?>" class="btn btn-success">Approve</a>
            <a href="<?php echo generate_url('controllers/AdminController.php?action=update_application_status&application_id=' . html_escape($application['id']) . '&status=rejected'); ?>" class="btn btn-danger">Reject</a>
        <?php endif; ?>
    </div>

    <a href="<?php echo generate_url('views/admin/manage_applications.php'); ?>" class="btn btn-secondary mt-3">Back to Applications</a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>