<?php
$page_title = "Apply for Job";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'seeker') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}

// Get the job ID from the query string
$job_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$job_id) {
    $_SESSION['error_message'] = "Job ID is required.";
    redirect(generate_url('index.php'));
    exit();
}

require_once __DIR__ . '/../../models/Job.php';
$jobModel = new Job($pdo);
$job = $jobModel->getJobById($job_id);

if (!$job) {
    $_SESSION['error_message'] = "Job not found.";
    redirect(generate_url('index.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/UserController.php';
$userController = new UserController($pdo);
$resumes = $userController->getResumesByUserId($_SESSION['user_id']);
?>

<h1>Apply for: <?php echo html_escape($job['title']); ?></h1>

<form action="<?php echo generate_url('controllers/JobController.php?action=apply_for_job'); ?>" method="post">
    <input type="hidden" name="job_id" value="<?php echo html_escape($job_id); ?>">
    <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">

    <div class="form-group">
        <label for="name">Your Name:</label>
        <input type="text" id="name" name="name" required>
    </div>

    <div class="form-group">
        <label for="email">Your Email:</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div class="form-group">
        <label for="phone">Your Phone:</label>
        <input type="text" id="phone" name="phone">
    </div>

    <div class="form-group">
        <label for="resume_path">Select a Resume:</label>
        <select id="resume_path" name="resume_path" required>
            <?php if (empty($resumes)): ?>
                <option value="">No resumes uploaded. Please upload a resume in your profile.</option>
            <?php else: ?>
                <?php foreach ($resumes as $resume): ?>
                    <option value="<?php echo html_escape($resume['resume_path']); ?>"><?php echo html_escape($resume['resume_name']); ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="why_are_you_fit">Why are you a good fit for this job?</label>
        <textarea id="why_are_you_fit" name="why_are_you_fit" rows="4" required></textarea>
    </div>

    <button type="submit" class="btn" <?php echo (empty($resumes) ? 'disabled' : ''); ?>>Apply Now</button>
    <?php if (empty($resumes)): ?>
        <p>Please <a href="<?php echo generate_url('views/seeker/profile.php'); ?>">upload a resume</a> before applying.</p>
    <?php endif; ?>
</form>

<?php include __DIR__ . '/../layouts/footer.php'; ?>