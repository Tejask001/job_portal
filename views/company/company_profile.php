<?php
$page_title = "Company Profile";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

require_once __DIR__ . '/../../models/Company.php';
$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);
?>

<h1>Company Profile</h1>

<form action="<?php
                if ($company) {
                    echo generate_url('controllers/CompanyController.php?action=update_company_profile&id=' . html_escape($company['id']));
                } else {
                    echo generate_url('controllers/CompanyController.php?action=create_company');
                }
                ?>" method="post" enctype="multipart/form-data">
    <?php if ($company): ?>
        <input type="hidden" name="id" value="<?php echo html_escape($company['id']); ?>">
    <?php endif; ?>
    <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">

    <div class="form-group">
        <label for="company_name">Company Name:</label>
        <input type="text" id="company_name" name="company_name" value="<?php echo ($company) ? html_escape($company['company_name']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="company_logo">Company Logo:</label>
        <input type="file" id="company_logo" name="company_logo">
        <?php if ($company && $company['company_logo']): ?>
            <img src="<?php echo generate_url($company['company_logo']); ?>" alt="Company Logo" style="max-width: 100px;">
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="company_description">Company Description:</label>
        <textarea id="company_description" name="company_description" rows="4"><?php echo ($company) ? html_escape($company['company_description']) : ''; ?></textarea>
    </div>

    <button type="submit" class="btn"><?php echo ($company) ? 'Update Profile' : 'Create Profile'; ?></button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>