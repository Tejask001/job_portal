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

<div class="container-fluid bg-light py-5">
    <div class="container">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-primary text-white text-center py-3">
                <h3 class="mb-0"><i class="bi bi-file-earmark-text me-2"></i> Apply for: <?php echo html_escape($job['title']); ?></h3>
            </div>
            <div class="card-body">
                <form action="<?php echo generate_url('controllers/JobController.php?action=apply_for_job'); ?>" method="post">
                    <input type="hidden" name="job_id" value="<?php echo html_escape($job_id); ?>">
                    <input type="hidden" name="user_id" value="<?php echo html_escape($_SESSION['user_id']); ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label"><i class="bi bi-person me-1"></i> Your Name:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i> Your Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label"><i class="bi bi-telephone me-1"></i> Your Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter your phone number (optional)">
                    </div>

                    <div class="mb-3">
                        <label for="resume_path" class="form-label"><i class="bi bi-file-earmark-pdf me-1"></i> Select a Resume:</label>
                        <select class="form-select" id="resume_path" name="resume_path" required <?php echo (empty($resumes) ? 'disabled' : ''); ?>>
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
                        <label for="why_are_you_fit" class="form-label"><i class="bi bi-question-circle me-1"></i> Why are you a good fit for this job?</label>
                        <textarea class="form-control" id="why_are_you_fit" name="why_are_you_fit" rows="4" placeholder="Explain why you are a good fit for this job" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success" <?php echo (empty($resumes) ? 'disabled' : ''); ?>><i class="bi bi-check-circle me-1"></i> Apply Now</button>
                    <?php if (empty($resumes)): ?>
                        <p class="mt-2">Please <a href="<?php echo generate_url('views/seeker/profile.php'); ?>" class="text-decoration-none"><i class="bi bi-arrow-right-circle me-1"></i> upload a resume</a> before applying.</p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>