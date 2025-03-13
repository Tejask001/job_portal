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

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0"><i class="bi bi-building me-2"></i> <?php echo ($company) ? 'Update Company Profile' : 'Create Company Profile'; ?></h3>
                    </div>
                    <div class="card-body">
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

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label"><i class="bi bi-building-fill me-1"></i> Company Name:</label>
                                    <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo ($company) ? html_escape($company['company_name']) : ''; ?>" placeholder="Enter company name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="industry" class="form-label"><i class="bi bi-briefcase me-1"></i> Industry:</label>
                                    <input type="text" class="form-control" id="industry" name="industry" value="<?php echo ($company) ? html_escape($company['industry']) : ''; ?>" placeholder="Enter industry">
                                </div>


                            </div>

                            <div class="row">

                                <div class="col-md-4 mb-3">
                                    <label for="employee_count" class="form-label"><i class="bi bi-people me-1"></i> Employee Count:</label>
                                    <input type="number" class="form-control" id="employee_count" name="employee_count" value="<?php echo ($company) ? html_escape($company['employee_count']) : ''; ?>" placeholder="Enter employee count">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="location" class="form-label"><i class="bi bi-geo-alt me-1"></i> Location:</label>
                                    <input type="text" class="form-control" id="location" name="location" value="<?php echo ($company) ? html_escape($company['location']) : ''; ?>" placeholder="Enter location">
                                </div>


                                <div class="col-md-4 mb-3">
                                    <label for="website_link" class="form-label"><i class="bi bi-link-45deg me-1"></i> Website Link:</label>
                                    <input type="text" class="form-control" id="website_link" name="website_link" value="<?php echo ($company) ? html_escape($company['website_link']) : ''; ?>" placeholder="Enter website link">
                                </div>

                            </div>

                            <div class="mb-3">
                                <label for="company_logo" class="form-label"><i class="bi bi-image me-1"></i> Company Logo:</label>
                                <input type="file" class="form-control" id="company_logo" name="company_logo">
                                <?php if ($company && $company['company_logo']): ?>
                                    <img src="<?php echo generate_url($company['company_logo']); ?>" alt="Company Logo" class="img-fluid mt-2 rounded" style="max-width: 120px;">
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label for="company_description" class="form-label"><i class="bi bi-card-text me-1"></i> Company Description:</label>
                                <textarea class="form-control" id="company_description" name="company_description" rows="4" placeholder="Enter company description"><?php echo ($company) ? html_escape($company['company_description']) : ''; ?></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> <?php echo ($company) ? 'Update Profile' : 'Create Profile'; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>