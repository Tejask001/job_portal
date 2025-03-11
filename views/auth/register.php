<?php
$page_title = "Register";
include __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Register</h1>

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
                <button type="submit" class="btn btn-primary">Register</button>
                <p class="mt-2">Already have an account? <a href="<?php echo generate_url('views/auth/login.php'); ?>">Login</a></p>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>