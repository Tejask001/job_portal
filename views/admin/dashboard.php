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

<div class="container mt-4">
    <h1 class="mb-4">Admin Dashboard</h1>
    <p>Manage users and job postings.</p>

    <div class="list-group">
        <a href="<?php echo generate_url('views/admin/manage_users.php'); ?>" class="list-group-item list-group-item-action">Manage Users</a>
        <a href="<?php echo generate_url('views/admin/manage_jobs.php'); ?>" class="list-group-item list-group-item-action">Manage Jobs</a>
        <a href="<?php echo generate_url('views/admin/manage_applications.php'); ?>" class="list-group-item list-group-item-action">Manage Applications</a>
        <a href="<?php echo generate_url('views/admin/post_job_as_company.php'); ?>" class="list-group-item list-group-item-action">Post Job For Company</a>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>