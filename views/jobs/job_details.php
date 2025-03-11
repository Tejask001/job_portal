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

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title"><?php echo html_escape($job['title']); ?></h1>
            <h6 class="card-subtitle mb-2 text-muted">
                Posted by:
                <a href="<?php echo generate_url('views/company/company_details.php?id=' . html_escape($job['company_id'])); ?>">
                    <?php echo html_escape($job['company_name']); ?>
                </a>
            </h6>

            <hr>

            <h5 class="mt-3">Description</h5>
            <p class="card-text"><?php echo html_escape($job['description']); ?></p>

            <ul class="list-unstyled">
                <li><strong>Opportunity Type:</strong> <?php echo format_text($job['posting_type']); ?></li>
                <li><strong>Employment Status:</strong> <?php echo format_text($job['employment_type']); ?></li>
                <li><strong>Work Arrangement:</strong> <?php echo format_text($job['work_type']); ?></li>
                <li><strong>Job Location:</strong> <?php echo html_escape($job['job_location']); ?></li>
                <li><strong>Skills Required:</strong> <?php echo html_escape($job['skills']); ?></li>
                <li><strong>Number of Openings:</strong> <?php echo html_escape($job['no_of_openings']); ?></li>
                <li><strong>Start Date:</strong> <?php echo html_escape($job['start_date']); ?></li>
                <li><strong>Stipend/Salary:</strong> <?php echo html_escape($job['stipend_salary']); ?></li>
                <li><strong>Perks:</strong> <?php echo html_escape($job['perks']); ?></li>
                <li><strong>Age:</strong> <?php echo html_escape($job['age']); ?></li>
                <li><strong>Gender Preferred:</strong> <?php echo format_text($job['gender_preferred']); ?></li>
                <li><strong>Experience:</strong> <?php echo html_escape($job['experience']); ?></li>
            </ul>

            <h5 class="mt-3">Key Responsibilities</h5>
            <p class="card-text"><?php echo nl2br(html_escape($job['key_responsibilities'])); ?></p>

            <div class="mt-4">
                <?php if ($is_seeker): ?>
                    <?php if ($is_saved): ?>
                        <a href="<?php echo generate_url('controllers/UserController.php?action=unsave_job&job_id=' . $job_id); ?>" class="btn btn-secondary">Unsave Job</a>
                    <?php else: ?>
                        <a href="<?php echo generate_url('controllers/UserController.php?action=save_job&job_id=' . $job_id); ?>" class="btn btn-primary">Save Job</a>
                    <?php endif; ?>

                    <?php if ($job['positions_filled'] >= $job['no_of_openings']): ?>
                        <p class="alert alert-warning">This job has been filled.</p>
                    <?php elseif (!$has_applied): ?>
                        <a href="<?php echo generate_url('views/jobs/apply.php?id=' . $job['id']); ?>" class="btn btn-success">Apply Now</a>
                    <?php else: ?>
                        <p class="alert alert-info">You have already applied for this job.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>