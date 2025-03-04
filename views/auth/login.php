<?php
$page_title = "Login";
include __DIR__ . '/../layouts/header.php';
?>

<h1>Login</h1>

<form action="<?php echo generate_url('controllers/AuthController.php?action=login'); ?>" method="post">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
    <p>Don't have an account? <a href="<?php echo generate_url('views/auth/register.php'); ?>">Register</a></p>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>