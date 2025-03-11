<?php
$page_title = "Register";
include __DIR__ . '/../layouts/header.php';
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Register</h2>

            <form action="<?php echo generate_url('controllers/AuthController.php?action=register'); ?>" method="post">
                <div class="mb-3">
                    <label for="user_type" class="form-label">User Type:</label>
                    <select class="form-select" id="user_type" name="user_type" required>
                        <option value="seeker">Job Seeker</option>
                        <option value="company">Company</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">Already have an account?
                    <a href="<?php echo generate_url('views/auth/login.php'); ?>" class="text-primary">Login</a>
                </p>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>