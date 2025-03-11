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

<div class="container mt-5 shadow-sm">
    <div class="row justify-content-center">
        <div class="card" style="border: none;">
            <h1 class="mt-2 text-center">Edit Job</h1>
            <div class="card-body p-4">
                <form action="<?php echo generate_url('controllers/JobController.php?action=update_job&id=' . html_escape($job_id)); ?>" method="post">

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">Job Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo html_escape($job['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">Job Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo html_escape($job['description']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="opportunity_type" class="form-label fw-bold">Opportunity Type:</label>
                            <select class="form-select" id="opportunity_type" name="posting_type" required>
                                <option value="regular_job" <?php if ($job['posting_type'] == 'regular_job') echo 'selected'; ?>>Regular Job</option>
                                <option value="internship" <?php if ($job['posting_type'] == 'internship') echo 'selected'; ?>>Internship</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="employment_status" class="form-label fw-bold">Employment Status:</label>
                            <select class="form-select" id="employment_status" name="employment_type" required>
                                <option value="fulltime" <?php if ($job['employment_type'] == 'fulltime') echo 'selected'; ?>>Full-time</option>
                                <option value="parttime" <?php if ($job['employment_type'] == 'parttime') echo 'selected'; ?>>Part-time</option>
                                <option value="contract" <?php if ($job['employment_type'] == 'contract') echo 'selected'; ?>>Contract</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="work_arrangement" class="form-label fw-bold">Work Arrangement:</label>
                            <select class="form-select" id="work_arrangement" name="work_type" required>
                                <option value="onsite" <?php if ($job['work_type'] == 'onsite') echo 'selected'; ?>>On-site</option>
                                <option value="remote" <?php if ($job['work_type'] == 'remote') echo 'selected'; ?>>Remote</option>
                                <option value="hybrid" <?php if ($job['work_type'] == 'hybrid') echo 'selected'; ?>>Hybrid</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="skills" class="form-label fw-bold">Skills Required:</label>
                            <input type="text" class="form-control" id="skills" name="skills" value="<?php echo html_escape($job['skills']); ?>" placeholder="e.g., HTML, CSS, JavaScript">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="job_location" class="form-label fw-bold">Job Location:</label>
                            <input type="text" class="form-control" id="job_location" name="job_location" value="<?php echo html_escape($job['job_location']); ?>" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="no_of_openings" class="form-label fw-bold">Number of Openings:</label>
                            <input type="number" class="form-control" id="no_of_openings" name="no_of_openings" value="<?php echo html_escape($job['no_of_openings']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="start_date" class="form-label fw-bold">Start Date:</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo html_escape($job['start_date']); ?>" required>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="duration" class="form-label fw-bold">Duration (for internships):</label>
                            <input type="text" class="form-control" id="duration" name="duration" value="<?php echo html_escape($job['duration']); ?>" placeholder="e.g., 3 months">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="who_can_apply" class="form-label fw-bold">Who Can Apply:</label>
                            <input type="text" class="form-control" id="who_can_apply" name="who_can_apply" value="<?php echo html_escape($job['who_can_apply']); ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="stipend_salary" class="form-label fw-bold">Stipend/Salary:</label>
                            <input type="text" class="form-control" id="stipend_salary" name="stipend_salary" value="<?php echo html_escape($job['stipend_salary']); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="perks" class="form-label fw-bold">Perks:</label>
                            <input type="text" class="form-control" id="perks" name="perks" value="<?php echo html_escape($job['perks']); ?>" placeholder="e.g., Certificate, Letter of Recommendation">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="age" class="form-label fw-bold">Age:</label>
                            <input type="text" class="form-control" id="age" name="age" value="<?php echo html_escape($job['age']); ?>" placeholder="e.g., 20-25, Any">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="gender_preferred" class="form-label fw-bold">Gender Preferred:</label>
                            <select class="form-select" id="gender_preferred" name="gender_preferred">
                                <option value="open to all" <?php echo ($job['gender_preferred'] == 'open to all') ? 'selected' : ''; ?>>Open to All</option>
                                <option value="male" <?php echo ($job['gender_preferred'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($job['gender_preferred'] == 'female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="experience" class="form-label fw-bold">Experience:</label>
                            <input type="text" class="form-control" id="experience" name="experience" value="<?php echo html_escape($job['experience']); ?>" placeholder="e.g., 2+ years, Entry Level">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="key_responsibilities" class="form-label fw-bold">Key Responsibilities:</label>
                        <textarea class="form-control" id="key_responsibilities" name="key_responsibilities" rows="4"><?php echo html_escape($job['key_responsibilities']); ?></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary w-50">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>