<?php
$page_title = "Edit Job";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'company') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

// Get the job ID from the query string
$job_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$job_id) {
    $_SESSION['error_message'] = "Job ID is required.";
    redirect(generate_url('views/company/dashboard.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/JobController.php';
$jobController = new JobController($pdo);
$job = (new Job($pdo))->getJobById($job_id); // Directly using the model

if (!$job) {
    $_SESSION['error_message'] = "Job not found.";
    redirect(generate_url('views/company/dashboard.php'));
    exit();
}

require_once __DIR__ . '/../../models/Company.php';
$companyModel = new Company($pdo);
$company = $companyModel->getCompanyByUserId($_SESSION['user_id']);

if ($job['company_id'] !== $company['id']) {
    $_SESSION['error_message'] = "You are not authorized to edit this job.";
    redirect(generate_url('views/company/dashboard.php'));
    exit();
}
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Edit Job</h1>

            <form action="<?php echo generate_url('controllers/JobController.php?action=update_job&id=' . html_escape($job_id)); ?>" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Job Title:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo html_escape($job['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Job Description:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo html_escape($job['description']); ?></textarea>
                </div>

                <!-- Opportunity Type -->
                <div class="mb-3">
                    <label for="opportunity_type" class="form-label">Opportunity Type:</label>
                    <select class="form-select" id="opportunity_type" name="posting_type" required>
                        <option value="regular_job" <?php if ($job['posting_type'] == 'regular_job') echo 'selected'; ?>>Regular Job</option>
                        <option value="internship" <?php if ($job['posting_type'] == 'internship') echo 'selected'; ?>>Internship</option>
                    </select>
                </div>

                <!-- Employment Status -->
                <div class="mb-3">
                    <label for="employment_status" class="form-label">Employment Status:</label>
                    <select class="form-select" id="employment_status" name="employment_type" required>
                        <option value="fulltime" <?php if ($job['employment_type'] == 'fulltime') echo 'selected'; ?>>Full-time</option>
                        <option value="parttime" <?php if ($job['employment_type'] == 'parttime') echo 'selected'; ?>>Part-time</option>
                        <option value="contract" <?php if ($job['employment_type'] == 'contract') echo 'selected'; ?>>Contract</option>
                    </select>
                </div>

                <!-- Work Arrangement -->
                <div class="mb-3">
                    <label for="work_arrangement" class="form-label">Work Arrangement:</label>
                    <select class="form-select" id="work_arrangement" name="work_type" required>
                        <option value="onsite" <?php if ($job['work_type'] == 'onsite') echo 'selected'; ?>>On-site</option>
                        <option value="remote" <?php if ($job['work_type'] == 'remote') echo 'selected'; ?>>Remote</option>
                        <option value="hybrid" <?php if ($job['work_type'] == 'hybrid') echo 'selected'; ?>>Hybrid</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="skills" class="form-label">Skills Required (Comma-separated):</label>
                    <input type="text" class="form-control" id="skills" name="skills" value="<?php echo html_escape($job['skills']); ?>" placeholder="e.g., HTML, CSS, JavaScript">
                </div>

                <div class="mb-3">
                    <label for="job_location" class="form-label">Job Location:</label>
                    <input type="text" class="form-control" id="job_location" name="job_location" value="<?php echo html_escape($job['job_location']); ?>" required placeholder="e.g., City, State">
                </div>

                <div class="mb-3">
                    <label for="no_of_openings" class="form-label">Number of Openings:</label>
                    <input type="number" class="form-control" id="no_of_openings" name="no_of_openings" value="<?php echo html_escape($job['no_of_openings']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo html_escape($job['start_date']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (for internships):</label>
                    <input type="text" class="form-control" id="duration" name="duration" value="<?php echo html_escape($job['duration']); ?>" placeholder="e.g., 3 months">
                </div>

                <div class="mb-3">
                    <label for="who_can_apply" class="form-label">Who Can Apply:</label>
                    <input type="text" class="form-control" id="who_can_apply" name="who_can_apply" value="<?php echo html_escape($job['who_can_apply']); ?>" placeholder="e.g., 3rd year engineering students">
                </div>

                <div class="mb-3">
                    <label for="stipend_salary" class="form-label">Stipend/Salary:</label>
                    <input type="text" class="form-control" id="stipend_salary" name="stipend_salary" value="<?php echo html_escape($job['stipend_salary']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="perks" class="form-label">Perks (Comma-separated):</label>
                    <input type="text" class="form-control" id="perks" name="perks" value="<?php echo html_escape($job['perks']); ?>" placeholder="e.g., Certificate, Letter of Recommendation">
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age:</label>
                    <input type="text" class="form-control" id="age" name="age" value="<?php echo html_escape($job['age']); ?>" placeholder="e.g., 20-25, Any">
                </div>

                <div class="mb-3">
                    <label for="gender_preferred" class="form-label">Gender Preferred:</label>
                    <select class="form-select" id="gender_preferred" name="gender_preferred">
                        <option value="open to all" <?php echo ($job['gender_preferred'] == 'open to all') ? 'selected' : ''; ?>>Open to All</option>
                        <option value="male" <?php echo ($job['gender_preferred'] == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($job['gender_preferred'] == 'female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="experience" class="form-label">Experience:</label>
                    <input type="text" class="form-control" id="experience" name="experience" value="<?php echo html_escape($job['experience']); ?>" placeholder="e.g., 2+ years, Entry Level">
                </div>

                <div class="mb-3">
                    <label for="key_responsibilities" class="form-label">Key Responsibilities:</label>
                    <textarea class="form-control" id="key_responsibilities" name="key_responsibilities" rows="4" placeholder="List the key responsibilities of the job"><?php echo html_escape($job['key_responsibilities']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Job</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>