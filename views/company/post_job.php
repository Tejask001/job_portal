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

<h1>Post a New Job</h1>

<form action="<?php echo generate_url('controllers/JobController.php?action=post_job'); ?>" method="post">
    <input type="hidden" name="company_id" value="<?php echo html_escape($company['id']); ?>">

    <div class="form-group">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title" required>
    </div>

    <div class="form-group">
        <label for="description">Job Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
    </div>

    <div class="form-group">
        <label for="posting_type">Posting Type:</label>
        <select id="posting_type" name="posting_type" required>
            <option value="fulltime">Full-time</option>
            <option value="internship">Internship</option>
        </select>
    </div>

    <div class="form-group">
        <label for="employment_type">Employment Type:</label>
        <select id="employment_type" name="employment_type" required>
            <option value="fulltime">Full-time</option>
            <option value="parttime">Part-time</option>
        </select>
    </div>

    <div class="form-group">
        <label for="work_type">Work Type:</label>
        <select id="work_type" name="work_type" required>
            <option value="onsite">On-site</option>
            <option value="remote">Remote</option>
            <option value="hybrid">Hybrid</option>
        </select>
    </div>

    <div class="form-group">
        <label for="skills">Skills Required (Comma-separated):</label>
        <input type="text" id="skills" name="skills" placeholder="e.g., HTML, CSS, JavaScript">
    </div>

    <div class="form-group">
        <label for="no_of_openings">Number of Openings:</label>
        <input type="number" id="no_of_openings" name="no_of_openings" required>
    </div>

    <div class="form-group">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
    </div>

    <div class="form-group">
        <label for="duration">Duration (for internships):</label>
        <input type="text" id="duration" name="duration" placeholder="e.g., 3 months">
    </div>

    <div class="form-group">
        <label for="who_can_apply">Who Can Apply:</label>
        <input type="text" id="who_can_apply" name="who_can_apply" placeholder="e.g., 3rd year engineering students">
    </div>

    <div class="form-group">
        <label for="stipend_salary">Stipend/Salary:</label>
        <input type="text" id="stipend_salary" name="stipend_salary" required>
    </div>

    <div class="form-group">
        <label for="perks">Perks (Comma-separated):</label>
        <input type="text" id="perks" name="perks" placeholder="e.g., Certificate, Letter of Recommendation">
    </div>

    <button type="submit" class="btn">Post Job</button>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>