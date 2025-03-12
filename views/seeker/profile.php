<?php
$page_title = "Update Profile";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seeker') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../controllers/UserController.php';
$userController = new UserController($pdo);
$user_id = $_SESSION['user_id'];

$resumes = $userController->getResumesByUserId($user_id);
$user = (new User($pdo))->getUserById($user_id);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    redirect(generate_url('views/seeker/dashboard.php'));
    exit();
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-5 text-center"><i class="bi bi-person-circle me-2"></i> Update Your Profile</h1>

        <!-- Basic Information Card -->
        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-primary text-white py-3"><i class="bi bi-info-circle me-1"></i> Basic Information</div>
            <div class="card-body">
                <form action="<?php echo generate_url('controllers/UserController.php?action=update_profile'); ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo html_escape($user_id); ?>">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="name" class="form-label"><i class="bi bi-person me-1"></i> Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo html_escape($user['name']); ?>" placeholder="Enter your name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i> Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo html_escape($user['email']); ?>" placeholder="Enter your email" required>
                        </div>
                        <div class="col-md-2">
                            <label for="age" class="form-label"><i class="bi bi-calendar-date me-1"></i> Age:</label>
                            <input type="number" class="form-control" id="age" name="age" value="<?php echo html_escape($user['age'] ?? ''); ?>" placeholder="Enter age">
                        </div>
                        <div class="col-md-2">
                            <label for="gender" class="form-label"><i class="bi bi-gender-ambiguous me-1"></i> Gender:</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="" <?php if (empty($user['gender'])) echo 'selected'; ?>>Select</option>
                                <option value="male" <?php if ($user['gender'] === 'male') echo 'selected'; ?>>Male</option>
                                <option value="female" <?php if ($user['gender'] === 'female') echo 'selected'; ?>>Female</option>
                                <option value="other" <?php if ($user['gender'] === 'other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <label for="experience" class="form-label"><i class="bi bi-briefcase me-1"></i> Experience:</label>
                            <input type="text" class="form-control" id="experience" name="experience" value="<?php echo html_escape($user['experience'] ?? ''); ?>" placeholder="e.g., 2+ years">
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4" style="margin-top: auto;">
                            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-arrow-clockwise me-1"></i> Update Profile</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Upload Resume Card -->
        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-success text-white py-3"><i class="bi bi-upload me-1"></i> Upload Resume</div>
            <div class="card-body">
                <form action="<?php echo generate_url('controllers/UserController.php?action=upload_resume'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="resume" class="form-label"><i class="bi bi-file-earmark-pdf me-1"></i> Choose a Resume File:</label>
                            <input type="file" class="form-control" id="resume" name="resume" required>
                        </div>
                        <div class="col-md-4" style="margin-top: auto;">
                            <button type="submit" class="btn btn-success w-100"><i class="bi bi-cloud-upload me-1"></i> Upload Resume</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- My Resumes Card -->
        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-info text-white py-3"><i class="bi bi-files me-1"></i> My Resumes</div>
            <div class="card-body">
                <?php if (empty($resumes)): ?>
                    <p class="card-text"><i class="bi bi-info-circle me-1"></i> No resumes uploaded yet.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($resumes as $resume): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?php echo generate_url($resume['resume_path']); ?>" target="_blank" class="text-decoration-none"><i class="bi bi-file-earmark-pdf me-1"></i> <?php echo html_escape($resume['resume_name']); ?></a>
                                <a href="<?php echo generate_url('controllers/UserController.php?action=delete_resume&id=' . html_escape($resume['id'])); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resume?');"><i class="bi bi-trash me-1"></i> Delete</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

        <!-- Account Management Card -->
        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-secondary text-white py-3"><i class="bi bi-gear me-1"></i> Account Management</div>
            <div class="card-body text-center">
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?php echo generate_url('views/seeker/update_password.php'); ?>" class="btn btn-secondary"><i class="bi bi-key me-1"></i> Update Password</a>
                    <a href="<?php echo generate_url('controllers/UserController.php?action=delete_account'); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');"><i class="bi bi-x-circle me-1"></i> Delete Account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>