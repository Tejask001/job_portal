<?php
$page_title = "User/Company Details";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

// Get the user ID from the query string
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$user_id) {
    $_SESSION['error_message'] = "User ID is required.";
    redirect(generate_url('views/admin/manage_users.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/AdminController.php';
$adminController = new AdminController($pdo);
$user = $adminController->getUserDetails($user_id);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    redirect(generate_url('views/admin/manage_users.php'));
    exit();
}

// Attempt to retrieve company details if the user is a company
$company = null;
if ($user['user_type'] === 'company') {
    $company = $adminController->getCompanyDetailsById($user_id); // New method call
}
?>

<h1>User Details</h1>

<dl>
    <dt>ID:</dt>
    <dd><?php echo html_escape($user['id']); ?></dd>

    <dt>User Type:</dt>
    <dd><?php echo html_escape($user['user_type']); ?></dd>

    <dt>Name:</dt>
    <dd><?php echo html_escape($user['name']); ?></dd>

    <dt>Email:</dt>
    <dd><?php echo html_escape($user['email']); ?></dd>

    <?php if ($user['user_type'] === 'seeker'): ?>
        <dt>Age:</dt>
        <dd><?php echo html_escape($user['age'] ?? 'N/A'); ?></dd>

        <dt>Gender:</dt>
        <dd><?php echo html_escape($user['gender'] ?? 'N/A'); ?></dd>

        <dt>Experience:</dt>
        <dd><?php echo html_escape($user['experience'] ?? 'N/A'); ?></dd>
    <?php endif; ?>

    <dt>Created At:</dt>
    <dd><?php echo html_escape($user['created_at']); ?></dd>

    <dt>Updated At:</dt>
    <dd><?php echo html_escape($user['updated_at']); ?></dd>
</dl>

<?php if ($company): ?>
    <h2>Company Details</h2>
    <dl>
        <dt>Company Name:</dt>
        <dd><?php echo html_escape($company['company_name']); ?></dd>

        <dt>Company Logo:</dt>
        <dd><img src="<?php echo generate_url($company['company_logo']); ?>" alt="<?php echo html_escape($company['company_name']); ?>" style="max-width: 100px;"></dd>

        <dt>Company Description:</dt>
        <dd><?php echo html_escape($company['company_description']); ?></dd>
    </dl>
<?php endif; ?>

<a href="<?php echo generate_url('views/admin/manage_users.php'); ?>" class="btn">Back to Manage Users</a>

<?php include __DIR__ . '/../layouts/footer.php'; ?>