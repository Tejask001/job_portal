<?php
$page_title = "Login";
include __DIR__ . '/../layouts/header.php';
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Login</h1>

            <form action="<?php echo generate_url('controllers/AuthController.php?action=login'); ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p class="mt-2">Don't have an account? <a href="<?php echo generate_url('views/auth/register.php'); ?>">Register</a></p>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>