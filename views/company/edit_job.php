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

    <!-- Opportunity Type -->
    <div class="form-group">
        <label for="opportunity_type">Opportunity Type:</label>
        <select id="opportunity_type" name="posting_type" required>
            <option value="regular_job">Regular Job</option>
            <option value="internship">Internship</option>
        </select>
    </div>

    <!-- Employment Status -->
    <div class="form-group">
        <label for="employment_status">Employment Status:</label>
        <select id="employment_status" name="employment_type" required>
            <option value="fulltime">Full-time</option>
            <option value="parttime">Part-time</option>
            <option value="contract">Contract</option>
        </select>
    </div>

    <!-- Work Arrangement -->
    <div class="form-group">
        <label for="work_arrangement">Work Arrangement:</label>
        <select id="work_arrangement" name="work_type" required>
            <option value="onsite">On-site</option>
            <option value="remote">Remote</option>
            <option value="hybrid">Hybrid</option>
        </select>
    </div>

    <div class="form-group">
        <label for="skills">Skills Required (Comma-separated):</label>
        <input type="text" id="skills" name="skills" value="<?php echo html_escape($job['skills']); ?>" placeholder="e.g., HTML, CSS, JavaScript">
    </div>

    <div class="form-group">
        <label for="job_location">Job Location:</label>
        <input type="text" id="job_location" name="job_location" value="<?php echo html_escape($job['job_location']); ?>" required placeholder="e.g., City, State">
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

    <div class="form-group">
        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?php echo html_escape($job['age']); ?>" placeholder="e.g., 20-25, Any">
    </div>

    <div class="form-group">
        <label for="gender_preferred">Gender Preferred:</label>
        <select id="gender_preferred" name="gender_preferred">
            <option value="open to all" <?php echo ($job['gender_preferred'] == 'open to all') ? 'selected' : ''; ?>>Open to All</option>
            <option value="male" <?php echo ($job['gender_preferred'] == 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo ($job['gender_preferred'] == 'female') ? 'selected' : ''; ?>>Female</option>
        </select>
    </div>

    <div class="form-group