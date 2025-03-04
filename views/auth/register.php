<?php
$page_title = "Register";
include __DIR__ . '/../layouts/header.php';
?>

<h1>Register</h1>

<form action="<?php echo generate_url('controllers/AuthController.php?action=register'); ?>" method="post">
    <div class="form-group">
        <label for="user_type">User Type:</label>
        <select id="user_type" name="user_type" required>
            <option value="seeker">Job Seeker</option>
            <option value="company">Company</option>
        </select>
    </div>
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Register</button>
    <p>Already have an account? <a href="<?php echo generate_url('views/auth/login.php'); ?>">Login</a></p>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>