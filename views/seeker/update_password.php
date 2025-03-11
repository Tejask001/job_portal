<?php
$page_title = "Update Password";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seeker') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<h1>Update Your Password</h1>

<form action="<?php echo generate_url('controllers/UserController.php?action=update_password'); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo html_escape($user_id); ?>">
    <div class="form-group">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required>
    </div>
    <div class="form-group">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
    </div>
    <div class="form-group">
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn">Update Password</button>
    <a href="<?php echo generate_url('views/seeker/profile.php'); ?>" class="btn">Cancel</a>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>