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

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Apply for: <?php echo html_escape($job['title']); ?></h1>

            <form action="<?php echo generate_url('controllers/JobController.php?action=apply_for_job'); ?>" method="post">
                <input type="hidden" name="job_id" value="<?php echo html_escape($job_id); ?>">
                <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Your Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Your Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Your Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>

                <div class="mb-3">
                    <label for="resume_path" class="form-label">Select a Resume:</label>
                    <select class="form-select" id="resume_path" name="resume_path" required>
                        <?php if (empty($resumes)): ?>
                            <option value="">No resumes uploaded. Please upload a resume in your profile.</option>
                        <?php else: ?>
                            <?php foreach ($resumes as $resume): ?>
                                <option value="<?php echo html_escape($resume['resume_path']); ?>"><?php echo html_escape($resume['resume_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="why_are_you_fit" class="form-label">Why are you a good fit for this job?</label>
                    <textarea class="form-control" id="why_are_you_fit" name="why_are_you_fit" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" <?php echo (empty($resumes) ? 'disabled' : ''); ?>>Apply Now</button>
                <?php if (empty($resumes)): ?>
                    <p class="mt-2">Please <a href="<?php echo generate_url('views/seeker/profile.php'); ?>">upload a resume</a> before applying.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>