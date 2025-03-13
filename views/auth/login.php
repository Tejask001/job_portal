<?php
$page_title = "Login";
include __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0"><i class="bi bi-box-arrow-in-right me-2"></i> Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo generate_url('controllers/AuthController.php?action=login'); ?>" method="post">
                            <div class="mb-3">
                                <label class="form-label" for="email"><i class="bi bi-envelope me-1"></i> Email:</label>
                                <input class="form-control" id="email" type="email" name="email" placeholder="Enter your email" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password"><i class="bi bi-lock me-1"></i> Password:</label>
                                <input class="form-control" id="password" type="password" name="password" placeholder="Enter your password" required />
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                <!--  <a class="small" href="#">Forgot Password?</a> -->
                                <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-right-circle me-1"></i> Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small"><a href="<?php echo generate_url('views/auth/register.php'); ?>">Need an account? Sign up!</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>