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

<div class="container mt-4">
    <h1 class="mb-4">User Details</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">User Information</h5>
            <dl class="row">
                <dt class="col-sm-3">ID:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['id']); ?></dd>

                <dt class="col-sm-3">User Type:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['user_type']); ?></dd>

                <dt class="col-sm-3">Name:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['name']); ?></dd>

                <dt class="col-sm-3">Email:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['email']); ?></dd>

                <?php if ($user['user_type'] === 'seeker'): ?>
                    <dt class="col-sm-3">Age:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['age'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Gender:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['gender'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Experience:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['experience'] ?? 'N/A'); ?></dd>
                <?php endif; ?>

                <dt class="col-sm-3">Created At:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['created_at']); ?></dd>

                <dt class="col-sm-3">Updated At:</dt>
                <dd class="col-sm-9"><?php echo html_escape($user['updated_at']); ?></dd>
            </dl>
        </div>
    </div>

    <?php if ($company): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Company Details</h5>
                <dl class="row">
                    <dt class="col-sm-3">Company Name:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['company_name']); ?></dd>

                    <dt class="col-sm-3">Company Logo:</dt>
                    <dd class="col-sm-9">
                        <img src="<?php echo generate_url($company['company_logo']); ?>" alt="Company Logo" class="img-fluid" style="max-width: 100px;">
                    </dd>

                    <dt class="col-sm-3">Company Description:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['company_description']); ?></dd>

                    <dt class="col-sm-3">Industry:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['industry'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Employee Count:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['employee_count'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Website Link:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['website_link'] ?? 'N/A'); ?></dd>

                    <dt class="col-sm-3">Location:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($company['location'] ?? 'N/A'); ?></dd>
                </dl>
            </div>
        </div>
    <?php endif; ?>

    <a href="<?php echo generate_url('views/admin/manage_users.php'); ?>" class="btn btn-secondary mt-3">Back to Manage Users</a>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>