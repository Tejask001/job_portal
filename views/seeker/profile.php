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
?>

<h1>Update Your Profile</h1>

<form action="<?php echo generate_url('controllers/UserController.php?action=update_profile'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo html_escape($user_id); ?>">
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo html_escape($user['name']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo html_escape($user['email']); ?>" required>
    </div>
    <button type="submit" class="btn">Update Profile</button>
</form>

<h2>Upload Resume</h2>
<form action="<?php echo generate_url('controllers/UserController.php?action=upload_resume'); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">
    <div class="form-group">
        <label for="resume">Choose a Resume File:</label>
        <input type="file" id="resume" name="resume" required>
    </div>
    <button type="submit" class="btn">Upload Resume</button>
</form>

<h2>My Resumes</h2>
<?php if (empty($resumes)): ?>
    <p>No resumes uploaded yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($resumes as $resume): ?>
            <li>
                <a href="<?php echo generate_url($resume['resume_path']); ?>" target="_blank"><?php echo html_escape($resume['resume_name']); ?></a>
                <a href="<?php echo generate_url('controllers/UserController.php?action=delete_resume&id=' . html_escape($resume['id'])); ?>" onclick="return confirm('Are you sure you want to delete this resume?');">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>