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

<h1>Edit Job</h1>

<form action="<?php echo generate_url('controllers/JobController.php?action=update_job&id=' . html_escape($job_id)); ?>" method="post">
    <div class="form-group">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" value="<?php echo html_escape($job['title']); ?>" required>
    </div>

    <div class="form-group">
        <label for="description">Job Description:</label>
        <textarea id="description" name="description" rows="4" required><?php echo html_escape($job['description']); ?></textarea>
    </div>

    <div class="form-group">
        <label for="posting_type">Posting Type:</label>
        <select id="posting_type" name="posting_type" required>
            <option value="fulltime" <?php echo ($job['posting_type'] == 'fulltime') ? 'selected' : ''; ?>>Full-time</option>
            <option value="internship" <?php echo ($job['posting_type'] == 'internship') ? 'selected' : ''; ?>>Internship</option>
        </select>
    </div>

    <div class="form-group">
        <label for="employment_type">Employment Type:</label>
        <select id="employment_type" name="employment_type" required>
            <option value="fulltime" <?php echo ($job['employment_type'] == 'fulltime') ? 'selected' : ''; ?>>Full-time</option>
            <option value="parttime" <?php echo ($job['employment_type'] == 'parttime') ? 'selected' : ''; ?>>Part-time</option>
        </select>
    </div>

    <div class="form-group">
        <label for="work_type">Work Type:</label>
        <select id="work_type" name="work_type" required>
            <option value="onsite" <?php echo ($job['work_type'] == 'onsite') ? 'selected' : ''; ?>>On-site</option>
            <option value="remote" <?php echo ($job['work_type'] == 'remote') ? 'selected' : ''; ?>>Remote</option>
            <option value="hybrid" <?php echo ($job['work_type'] == 'hybrid') ? 'selected' : ''; ?>>Hybrid</option>
        </select>
    </div>

    <div class="form-group">
        <label for="skills">Skills Required (Comma-separated):</label>
        <input type="text" id="skills" name="skills" value="<?php echo html_escape($job['skills']); ?>" placeholder="e.g., HTML, CSS, JavaScript">
    </div>

    <div class="form-group">
        <label for="no_of_openings">Number of Openings:</label>
        <input type="number" id="no_of_openings" name="no_of_openings" value="<?php echo html_escape($job['no_of_openings']); ?>" required>
    </div>

    <div class="form-group">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" value="<?php echo html_escape($job['start_date']); ?>" required>
    </div>

    <div class="form-group">
        <label for="duration">Duration (for internships):</label>
        <input type="text" id="duration" name="duration" value="<?php echo html_escape($job['duration']); ?>" placeholder="e.g., 3 months">
    </div>

    <div class="form-group">
        <label for="who_can_apply">Who Can Apply:</label>
        <input type="text" id="who_can_apply" name="who_can_apply" value="<?php echo html_escape($job['who_can_apply']); ?>" placeholder="e.g., 3rd year engineering students">
    </div>

    <div class="form-group">
        <label for="stipend_salary">Stipend/Salary:</label>
        <input type="text" id="stipend_salary" name="stipend_salary" value="<?php echo html_escape($job['stipend_salary']); ?>" required>
    </div>

    <div class="form-group">
        <label for="perks">Perks (Comma-separated):</label>
        <input type="text" id="perks" name="perks" value="<?php echo html_escape($job['perks']); ?>" placeholder="e.g., Certificate, Letter of Recommendation">
    </div>

    <button type="submit" class="btn">Update Job</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>