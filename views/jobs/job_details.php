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

<h1><?php echo html_escape($job['title']); ?></h1>
<p>Posted by: <a href="<?php echo generate_url('views/company/company_details.php?id=' . html_escape($job['company_id'])); ?>"><?php echo html_escape($job['company_name']); ?></a></p>


<p><strong>Description:</strong></p>
<p><?php echo html_escape($job['description']); ?></p>

<p><strong>Opportunity Type:</strong> <?php echo format_text($job['posting_type']); ?></p>
<p><strong>Employment Status:</strong> <?php echo format_text($job['employment_type']); ?></p>
<p><strong>Work Arrangement:</strong> <?php echo format_text($job['work_type']); ?></p>
<p><strong>Job Location:</strong> <?php echo html_escape($job['job_location']); ?></p>
<p><strong>Skills Required:</strong> <?php echo html_escape($job['skills']); ?></p>
<p><strong>Number of Openings:</strong> <?php echo html_escape($job['no_of_openings']); ?></p>
<p><strong>Start Date:</strong> <?php echo html_escape($job['start_date']); ?></p>
<p><strong>Stipend/Salary:</strong> <?php echo html_escape($job['stipend_salary']); ?></p>
<p><strong>Perks:</strong> <?php echo html_escape($job['perks']); ?></p>

<!-- Display new fields -->
<p><strong>Age:</strong> <?php echo html_escape($job['age']); ?></p>
<p><strong>Gender Preferred:</strong> <?php echo format_text($job['gender_preferred']); ?></p>
<p><strong>Experience:</strong> <?php echo html_escape($job['experience']); ?></p>
<p><strong>Key Responsibilities:</strong></p>
<p><?php echo nl2br(html_escape($job['key_responsibilities'])); ?></p> <!-- nl2br for line breaks in textareas -->

<?php if ($is_seeker): ?>
    <?php if ($is_saved): ?>
        <a href="<?php echo generate_url('controllers/UserController.php?action=unsave_job&job_id=' . $job_id); ?>" class="btn">Unsave Job</a>
    <?php else: ?>
        <a href="<?php echo generate_url('controllers/UserController.php?action=save_job&job_id=' . $job_id); ?>" class="btn">Save Job</a>
    <?php endif; ?>

    <?php if ($job['positions_filled'] >= $job['no_of_openings']): ?>
        <p>This job has been filled.</p>
    <?php elseif (!$has_applied): ?>
        <a href="<?php echo generate_url('views/jobs/apply.php?id=' . $job['id']); ?>" class="btn">Apply Now</a>
    <?php else: ?>
        <p>You have already applied for this job.</p>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../layouts/footer.php'; ?>