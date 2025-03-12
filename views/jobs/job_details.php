<?php
$page_title = "Job Details";
include __DIR__ . '/../layouts/header.php';

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

// Check if user is logged in and is a job seeker
$is_seeker = isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'seeker';

// Check if the job is saved by the user
require_once __DIR__ . '/../../controllers/UserController.php';
$userController = new UserController($pdo);
$is_saved = $is_seeker ? $userController->isJobSaved($job_id, $_SESSION['user_id']) : false;

// Check if the user has already applied
$has_applied = false;
if ($is_seeker) {
    require_once __DIR__ . '/../../models/Application.php';
    $applicationModel = new Application($pdo);
    $applications = $applicationModel->getApplicationsByUserId($_SESSION['user_id']);
    foreach ($applications as $application) {
        if ($application['job_id'] == $job_id) {
            $has_applied = true;
            break;
        }
    }
}

function format_text($text)
{
    $text = str_replace('_', ' ', $text);  // Replace underscores with spaces
    $text = ucwords($text);               // Capitalize the first letter of each word
    return html_escape($text);             // Escape HTML entities
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container bg-light">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body">
                <h1 class="card-title"><i class="bi bi-briefcase me-2"></i> <?php echo html_escape($job['title']); ?></h1>
                <h6 class="card-subtitle mb-2 text-muted">
                    <i class="bi bi-building me-1"></i> Posted by:
                    <a href="<?php echo generate_url('views/company/company_details.php?id=' . html_escape($job['company_id'])); ?>" class="text-decoration-none">
                        <?php echo html_escape($job['company_name']); ?>
                    </a>
                </h6>

                <hr>

                <h5 class="mt-3"><i class="bi bi-card-text me-1"></i> Description</h5>
                <p class="card-text"><?php echo html_escape($job['description']); ?></p>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-tag me-1"></i> <strong>Opportunity Type:</strong> <?php echo format_text($job['posting_type']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-clock me-1"></i> <strong>Employment Status:</strong> <?php echo format_text($job['employment_type']); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-diagram-3 me-1"></i> <strong>Work Arrangement:</strong> <?php echo format_text($job['work_type']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-geo-alt me-1"></i> <strong>Job Location:</strong> <?php echo html_escape($job['job_location']); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-tools me-1"></i> <strong>Skills Required:</strong> <?php echo html_escape($job['skills']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-hash me-1"></i> <strong>Number of Openings:</strong> <?php echo html_escape($job['no_of_openings']); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-calendar-date me-1"></i> <strong>Start Date:</strong> <?php echo html_escape($job['start_date']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-cash-coin me-1"></i> <strong>Stipend/Salary:</strong> <?php echo html_escape($job['stipend_salary']); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-gift me-1"></i> <strong>Perks:</strong> <?php echo html_escape($job['perks']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-person-bounding-box me-1"></i> <strong>Age:</strong> <?php echo html_escape($job['age']); ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <i class="bi bi-gender-ambiguous me-1"></i> <strong>Gender Preferred:</strong> <?php echo format_text($job['gender_preferred']); ?>
                    </div>
                    <div class="col-md-6">
                        <i class="bi bi-stars me-1"></i> <strong>Experience:</strong> <?php echo html_escape($job['experience']); ?>
                    </div>
                </div>

                <h5 class="mt-3"><i class="bi bi-list-task me-1"></i> Key Responsibilities</h5>
                <p class="card-text"><?php echo nl2br(html_escape($job['key_responsibilities'])); ?></p>

                <div class="mt-4">
                    <?php if ($is_seeker): ?>
                        <?php if ($is_saved): ?>
                            <a href="<?php echo generate_url('controllers/UserController.php?action=unsave_job&job_id=' . $job_id); ?>" class="btn btn-warning me-2"><i class="bi bi-bookmark-dash me-1"></i> Unsave Job</a>
                        <?php else: ?>
                            <a href="<?php echo generate_url('controllers/UserController.php?action=save_job&job_id=' . $job_id); ?>" class="btn btn-primary me-2"><i class="bi bi-bookmark-plus me-1"></i> Save Job</a>
                        <?php endif; ?>

                        <?php if ($job['positions_filled'] >= $job['no_of_openings']): ?>
                            <div class="alert alert-warning mt-3" role="alert"><i class="bi bi-exclamation-triangle me-2"></i> This job has been filled.</div>
                        <?php elseif (!$has_applied): ?>
                            <a href="<?php echo generate_url('views/jobs/apply.php?id=' . $job['id']); ?>" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Apply Now</a>
                        <?php else: ?>
                            <div class="alert alert-info mt-3" role="alert"><i class="bi bi-info-circle me-2"></i> You have already applied for this job.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>