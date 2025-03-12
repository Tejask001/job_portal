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

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white py-3">
                        <h3 class="text-center font-weight-light my-4"><i class="bi bi-key me-2"></i> Update Your Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo generate_url('controllers/UserController.php?action=update_password'); ?>" method="post">
                            <input type="hidden" name="id" value="<?php echo html_escape($user_id); ?>">
                            <div class="mb-3">
                                <label class="form-label" for="old_password"><i class="bi bi-lock me-1"></i> Old Password:</label>
                                <input class="form-control" id="old_password" type="password" name="old_password" placeholder="Enter old password" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="new_password"><i class="bi bi-lock-fill me-1"></i> New Password:</label>
                                <input class="form-control" id="new_password" type="password" name="new_password" placeholder="Enter new password" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="confirm_password"><i class="bi bi-shield-lock me-1"></i> Confirm New Password:</label>
                                <input class="form-control" id="confirm_password" type="password" name="confirm_password" placeholder="Confirm new password" required />
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise me-1"></i> Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>