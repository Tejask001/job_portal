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
require_once __DIR__ . '/../../models/CompanyContact.php'; // Include the new model
$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);

$companyContactModel = new CompanyContact($pdo);
$companyContact = $companyContactModel->getContactByUserId($_SESSION['user_id']); // Load contact details
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
                        <form action="<?php echo generate_url('controllers/CompanyController.php?action=save_company_profile'); ?>" method="post" enctype="multipart/form-data">
                            <?php if ($company): ?>
                                <input type="hidden" name="id" value="<?php echo html_escape($company['id']); ?>">
                            <?php endif; ?>
                            <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">

                            <!-- Company Profile Section -->
                            <h4 class="mt-3 mb-2">Company Information</h4>
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

                            <!-- Point of Contact Section -->
                            <h4 class="mt-4 mb-2">Point of Contact Details</h4>
                            <?php if ($companyContact): ?>
                                <input type="hidden" name="contact_id" value="<?php echo html_escape($companyContact['id']); ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="contact_name" class="form-label"><i class="bi bi-person me-1"></i> Name:</label>
                                <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?php echo ($companyContact) ? html_escape($companyContact['name']) : ''; ?>" placeholder="Enter name" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_age" class="form-label"><i class="bi bi-calendar me-1"></i> Age:</label>
                                    <input type="number" class="form-control" id="contact_age" name="contact_age" value="<?php echo ($companyContact) ? html_escape($companyContact['age']) : ''; ?>" placeholder="Enter age">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contact_gender" class="form-label"><i class="bi bi-gender-ambiguous me-1"></i> Gender:</label>
                                    <select class="form-select" id="contact_gender" name="contact_gender">
                                        <option value="male" <?php if ($companyContact && $companyContact['gender'] == 'male') echo 'selected'; ?>>Male</option>
                                        <option value="female" <?php if ($companyContact && $companyContact['gender'] == 'female') echo 'selected'; ?>>Female</option>
                                        <option value="other" <?php if ($companyContact && $companyContact['gender'] == 'other') echo 'selected'; ?>>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contact_email" class="form-label"><i class="bi bi-envelope me-1"></i> Email:</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo ($companyContact) ? html_escape($companyContact['email']) : ''; ?>" placeholder="Enter email" required>
                            </div>

                            <div class="mb-3">
                                <label for="contact_phone" class="form-label"><i class="bi bi-telephone me-1"></i> Phone:</label>
                                <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo ($companyContact) ? html_escape($companyContact['phone']) : ''; ?>" placeholder="Enter phone number">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_title" class="form-label"><i class="bi bi-person-badge me-1"></i> Title:</label>
                                    <input type="text" class="form-control" id="contact_title" name="contact_title" value="<?php echo ($companyContact) ? html_escape($companyContact['title']) : ''; ?>" placeholder="Enter job title">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="contact_department" class="form-label"><i class="bi bi-building me-1"></i> Department:</label>
                                    <input type="text" class="form-control" id="contact_department" name="contact_department" value="<?php echo ($companyContact) ? html_escape($companyContact['department']) : ''; ?>" placeholder="Enter department">
                                </div>
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

    <?php include __DIR__ . '/../layouts/footer.php'; ?>