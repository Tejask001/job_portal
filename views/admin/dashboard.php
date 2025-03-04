<?php
$page_title = "Admin Dashboard";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}
?>

<h1>Admin Dashboard</h1>
<p>Manage users and job postings.</p>

<ul>
    <li><a href="<?php echo generate_url('views/admin/manage_users.php'); ?>">Manage Users</a></li>
    <li><a href="<?php echo generate_url('views/admin/manage_jobs.php'); ?>">Manage Jobs</a></li>
</ul>

<?php include __DIR__ . '/../layouts/footer.php'; ?>