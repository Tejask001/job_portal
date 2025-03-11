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

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Post a New Job</h1>

            <form action="<?php echo generate_url('controllers/JobController.php?action=post_job'); ?>" method="post">
                <input type="hidden" name="company_id" value="<?php echo html_escape($company['id']); ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Job Title:</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Job Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <!-- Opportunity Type -->
                <div class="mb-3">
                    <label for="opportunity_type" class="form-label">Opportunity Type:</label>
                    <select class="form-select" id="opportunity_type" name="posting_type" required>
                        <option value="regular_job">Regular Job</option>
                        <option value="internship">Internship</option>
                    </select>
                </div>

                <!-- Employment Status -->
                <div class="mb-3">
                    <label for="employment_status" class="form-label">Employment Status:</label>
                    <select class="form-select" id="employment_status" name="employment_type" required>
                        <option value="fulltime">Full-time</option>
                        <option value="parttime">Part-time</option>
                        <option value="contract">Contract</option>
                    </select>
                </div>

                <!-- Work Arrangement -->
                <div class="mb-3">
                    <label for="work_arrangement" class="form-label">Work Arrangement:</label>
                    <select class="form-select" id="work_arrangement" name="work_type" required>
                        <option value="onsite">On-site</option>
                        <option value="remote">Remote</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label">Skills Required (Comma-separated):</label>
                    <input type="text" class="form-control" id="skills" name="skills" placeholder="e.g., HTML, CSS, JavaScript">
                </div>

                <div class="mb-3">
                    <label for="job_location" class="form-label">Job Location:</label>
                    <input type="text" class="form-control" id="job_location" name="job_location" required placeholder="e.g., City, State">
                </div>

                <div class="mb-3">
                    <label for="no_of_openings" class="form-label">Number of Openings:</label>
                    <input type="number" class="form-control" id="no_of_openings" name="no_of_openings" required>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (for internships):</label>
                    <input type="text" class="form-control" id="duration" name="duration" placeholder="e.g., 3 months">
                </div>

                <div class="mb-3">
                    <label for="who_can_apply" class="form-label">Who Can Apply:</label>
                    <input type="text" class="form-control" id="who_can_apply" name="who_can_apply" placeholder="e.g., 3rd year engineering students">
                </div>

                <div class="mb-3">
                    <label for="stipend_salary" class="form-label">Stipend/Salary:</label>
                    <input type="text" class="form-control" id="stipend_salary" name="stipend_salary" required>
                </div>

                <div class="mb-3">
                    <label for="perks" class="form-label">Perks (Comma-separated):</label>
                    <input type="text" class="form-control" id="perks" name="perks" placeholder="e.g., Certificate, Letter of Recommendation">
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="text" class="form-control" id="age" name="age" placeholder="e.g., 20-25, Any">
                </div>

                <div class="mb-3">
                    <label for="gender_preferred" class="form-label">Gender Preferred:</label>
                    <select class="form-select" id="gender_preferred" name="gender_preferred">
                        <option value="open to all">Open to All</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="experience" class="form-label">Experience:</label>
                    <input type="text" class="form-control" id="experience" name="experience" placeholder="e.g., 2+ years, Entry Level">
                </div>

                <div class="mb-3">
                    <label for="key_responsibilities" class="form-label">Key Responsibilities:</label>
                    <textarea class="form-control" id="key_responsibilities" name="key_responsibilities" rows="4" placeholder="List the key responsibilities of the job"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Post Job</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>