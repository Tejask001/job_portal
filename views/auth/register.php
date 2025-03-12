<?php
$page_title = "Register";
include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0"><i class="bi bi-person-plus me-2"></i> Register</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo generate_url('controllers/AuthController.php?action=register'); ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label" for="user_type"><i class="bi bi-person-check me-1"></i> User Type:</label>
                                <select class="form-select" id="user_type" name="user_type" required>
                                    <option value="seeker">Job Seeker</option>
                                    <option value="company">Company</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name"><i class="bi bi-person me-1"></i> Name:</label>
                                <input class="form-control" id="name" type="text" name="name" placeholder="Enter your name" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i> Email:</label>
                                <input class="form-control" id="email" type="email" name="email" placeholder="Enter your email" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password"><i class="bi bi-lock me-1"></i> Password:</label>
                                <input class="form-control" id="password" type="password" name="password" placeholder="Enter your password" required />
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-1"></i> Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="<?php echo generate_url('views/auth/login.php'); ?>">Already have an account? Go to login</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>