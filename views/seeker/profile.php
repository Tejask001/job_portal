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

<div class="container mt-4">
    <h1 class="mb-4">Update Your Profile</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Basic Information</h5>
            <form action="<?php echo generate_url('controllers/UserController.php?action=update_profile'); ?>" method="post">
                <input type="hidden" name="id" value="<?php echo html_escape($user_id); ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo html_escape($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo html_escape($user['email']); ?>" required>
                </div>

                <!-- Seeker-Specific Fields -->
                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo html_escape($user['age'] ?? ''); ?>" placeholder="Enter your age">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Gender:</label>
                    <select class="form-select" id="gender" name="gender">
                        <option value="" <?php if (empty($user['gender'])) echo 'selected'; ?>>Select Gender</option>
                        <option value="male" <?php if ($user['gender'] === 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($user['gender'] === 'female') echo 'selected'; ?>>Female</option>
                        <option value="other" <?php if ($user['gender'] === 'other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="experience" class="form-label">Experience:</label>
                    <input type="text" class="form-control" id="experience" name="experience" value="<?php echo html_escape($user['experience'] ?? ''); ?>" placeholder="e.g., 2+ years, Entry Level">
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Upload Resume</h5>
            <form action="<?php echo generate_url('controllers/UserController.php?action=upload_resume'); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">
                <div class="mb-3">
                    <label for="resume" class="form-label">Choose a Resume File:</label>
                    <input type="file" class="form-control" id="resume" name="resume" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Resume</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">My Resumes</h5>
            <?php if (empty($resumes)): ?>
                <p class="card-text">No resumes uploaded yet.</p>
            <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($resumes as $resume): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?php echo generate_url($resume['resume_path']); ?>" target="_blank"><?php echo html_escape($resume['resume_name']); ?></a>
                            <a href="<?php echo generate_url('controllers/UserController.php?action=delete_resume&id=' . html_escape($resume['id'])); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resume?');">Delete</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Account Management</h5>
            <a href="<?php echo generate_url('views/seeker/update_password.php'); ?>" class="btn btn-secondary">Update Password</a>
            <a href="<?php echo generate_url('controllers/UserController.php?action=delete_account'); ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">Delete Account</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>