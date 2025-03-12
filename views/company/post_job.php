<?php
$page_title = "Post a Job";
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

if (!$company) {
    $_SESSION['error_message'] = "Please create your company profile first.";
    redirect(generate_url('views/company/company_profile.php'));
    exit();
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container d-flex bg-light justify-content-center">
        <!-- Centering Container -->
        <div class="card shadow-lg border-0 rounded-lg w-75">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Post a New Job</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo generate_url('controllers/JobController.php?action=post_job'); ?>" method="post">
                    <input type="hidden" name="company_id" value="<?php echo html_escape($company['id']); ?>">

                    <div class="mb-3">
                        <label for="title" class="form-label"><i class="bi bi-textarea-t me-1"></i> Job Title:</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter job title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label"><i class="bi bi-card-text me-1"></i> Job Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Enter job description" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="opportunity_type" class="form-label"><i class="bi bi-tag me-1"></i> Opportunity Type:</label>
                            <select class="form-select" id="opportunity_type" name="posting_type" required>
                                <option value="regular_job">Regular Job</option>
                                <option value="internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="employment_status" class="form-label"><i class="bi bi-briefcase me-1"></i> Employment Status:</label>
                            <select class="form-select" id="employment_status" name="employment_type" required>
                                <option value="fulltime">Full-time</option>
                                <option value="parttime">Part-time</option>
                                <option value="contract">Contract</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="work_arrangement" class="form-label"><i class="bi bi-laptop me-1"></i> Work Arrangement:</label>
                            <select class="form-select" id="work_arrangement" name="work_type" required>
                                <option value="onsite">On-site</option>
                                <option value="remote">Remote</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="skills" class="form-label"><i class="bi bi-tools me-1"></i> Skills Required:</label>
                        <input type="text" class="form-control" id="skills" name="skills" placeholder="e.g., HTML, CSS, JavaScript">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="job_location" class="form-label"><i class="bi bi-geo-alt me-1"></i> Job Location:</label>
                            <input type="text" class="form-control" id="job_location" name="job_location" placeholder="City, State" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="no_of_openings" class="form-label"><i class="bi bi-hash me-1"></i> Number of Openings:</label>
                            <input type="number" class="form-control" id="no_of_openings" name="no_of_openings" placeholder="Enter Number" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="start_date" class="form-label"><i class="bi bi-calendar me-1"></i> Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label for="stipend_salary" class="form-label"><i class="bi bi-cash-coin me-1"></i> Stipend/Salary:</label>
                            <input type="text" class="form-control" id="stipend_salary" name="stipend_salary" placeholder="Enter Amount" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="duration" class="form-label"><i class="bi bi-clock me-1"></i> Duration (for internships):</label>
                            <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 3 months">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="who_can_apply" class="form-label"><i class="bi bi-person me-1"></i> Who Can Apply:</label>
                            <input type="text" class="form-control" id="who_can_apply" name="who_can_apply" placeholder="e.g., Senior Students">
                        </div>



                    </div>

                    <div class="mb-3">
                        <label for="perks" class="form-label"><i class="bi bi-gift me-1"></i> Perks:</label>
                        <input type="text" class="form-control" id="perks" name="perks" placeholder="e.g., Certificate, Letter of Recommendation">
                    </div>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <label for="experience" class="form-label"><i class="bi bi-stars me-1"></i> Experience:</label>
                            <input type="text" class="form-control" id="experience" name="experience" placeholder="e.g., 2+ years, Entry Level">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="age" class="form-label"><i class="bi bi-person-bounding-box me-1"></i> Age:</label>
                            <input type="text" class="form-control" id="age" name="age" placeholder="e.g., 20-25, Any">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="gender_preferred" class="form-label"><i class="bi bi-gender-ambiguous me-1"></i> Gender Preferred:</label>
                            <select class="form-select" id="gender_preferred" name="gender_preferred">
                                <option value="open to all">Open to All</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>

                    </div>


                    <div class="mb-3">
                        <label for="key_responsibilities" class="form-label"><i class="bi bi-list-task me-1"></i> Key Responsibilities:</label>
                        <textarea class="form-control" id="key_responsibilities" name="key_responsibilities" rows="4" placeholder="Enter responsibilities"></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-50"><i class="bi bi-cloud-arrow-up me-1"></i> Post Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>