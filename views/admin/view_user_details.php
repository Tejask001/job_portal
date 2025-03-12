<?php
$page_title = "User/Company Details";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php'));
    exit();
}

// Function to format text (capitalize first letter of each word)
function formatText($text)
{
    if ($text === null || $text === 'N/A') {
        return 'N/A';
    }
    return ucwords(strtolower(html_escape($text)));
}

// Get the user ID from the query string
$user_id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$user_id) {
    $_SESSION['error_message'] = "User ID is required.";
    redirect(generate_url('views/admin/manage_users.php'));
    exit();
}

require_once __DIR__ . '/../../controllers/AdminController.php';
require_once __DIR__ . '/../../controllers/CompanyController.php'; // Include Company Controller
require_once __DIR__ . '/../../controllers/UserController.php';
require_once __DIR__ . '/../../controllers/JobController.php';

$adminController = new AdminController($pdo);
$companyController = new CompanyController($pdo); // Instantiate Company Controller
$userController = new UserController($pdo); // Instantiate the UserController
$jobController = new JobController($pdo);
$user = $adminController->getUserDetails($user_id);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    redirect(generate_url('views/admin/manage_users.php'));
    exit();
}

// Attempt to retrieve company details if the user is a company
$company = null;
if ($user['user_type'] === 'company') {
    $company = $adminController->getCompanyDetailsById($user_id); // New method call
}

// Retrieve applications if the user is a seeker
$seekerApplications = null;
if ($user['user_type'] === 'seeker') {
    $seekerApplications = $userController->getApplicationsByUserId($user_id);
}

// Retrieve applications if the user is a company
$companyApplications = null;
if ($user['user_type'] === 'company' && $company) {
    // Retrieve applications for the company using the CompanyController
    $companyApplications = $jobController->getJobsByCompanyId($company['id']);
}
?>

<div class="container-fluid bg-light py-5">
    <div class="container">
        <h1 class="mb-4"><i class="bi bi-person-lines-fill me-2"></i> User/Company Details</h1>

        <div class="card shadow-lg border-0 rounded-lg mb-4">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="card-title mb-0"><i class="bi bi-person-fill me-1"></i> User Information</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3"><i class="bi bi-hash me-1"></i> ID:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['id']); ?></dd>

                    <dt class="col-sm-3"><i class="bi bi-person-badge-fill me-1"></i> User Type:</dt>
                    <dd class="col-sm-9"><?php echo formatText($user['user_type']); ?></dd>

                    <dt class="col-sm-3"><i class="bi bi-person-circle me-1"></i> Name:</dt>
                    <dd class="col-sm-9"><?php echo formatText($user['name']); ?></dd>

                    <dt class="col-sm-3"><i class="bi bi-envelope-fill me-1"></i> Email:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['email']); ?></dd>

                    <?php if ($user['user_type'] === 'seeker'): ?>
                        <dt class="col-sm-3"><i class="bi bi-calendar-date me-1"></i> Age:</dt>
                        <dd class="col-sm-9"><?php echo formatText($user['age'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-gender-ambiguous me-1"></i> Gender:</dt>
                        <dd class="col-sm-9"><?php echo formatText($user['gender'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-briefcase-fill me-1"></i> Experience:</dt>
                        <dd class="col-sm-9"><?php echo formatText($user['experience'] ?? 'N/A'); ?></dd>
                    <?php endif; ?>

                    <dt class="col-sm-3"><i class="bi bi-clock-fill me-1"></i> Created At:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['created_at']); ?></dd>

                    <dt class="col-sm-3"><i class="bi bi-arrow-clockwise me-1"></i> Updated At:</dt>
                    <dd class="col-sm-9"><?php echo html_escape($user['updated_at']); ?></dd>
                </dl>
            </div>
        </div>

        <?php if ($company): ?>
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="card-title mb-0"><i class="bi bi-building me-1"></i> Company Details</h5>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3"><i class="bi bi-building-fill me-1"></i> Company Name:</dt>
                        <dd class="col-sm-9"><?php echo formatText($company['company_name']); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-image-fill me-1"></i> Company Logo:</dt>
                        <dd class="col-sm-9">
                            <img src="<?php echo generate_url($company['company_logo']); ?>" alt="Company Logo" class="img-fluid rounded" style="max-width: 150px;">
                        </dd>

                        <dt class="col-sm-3"><i class="bi bi-card-text me-1"></i> Company Description:</dt>
                        <dd class="col-sm-9"><?php echo formatText($company['company_description']); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-list-stars me-1"></i> Industry:</dt>
                        <dd class="col-sm-9"><?php echo formatText($company['industry'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-people-fill me-1"></i> Employee Count:</dt>
                        <dd class="col-sm-9"><?php echo formatText($company['employee_count'] ?? 'N/A'); ?></dd>

                        <dt class="col-sm-3"><i class="bi bi-link-45deg me-1"></i> Website Link:</dt>
                        <dd class="col-sm-9"><a href="<?php echo html_escape($company['website_link'] ?? '#'); ?>" target="_blank" rel="noopener noreferrer"><?php echo html_escape($company['website_link'] ?? 'N/A'); ?></a></dd>

                        <dt class="col-sm-3"><i class="bi bi-geo-alt-fill me-1"></i> Location:</dt>
                        <dd class="col-sm-9"><?php echo formatText($company['location'] ?? 'N/A'); ?></dd>
                    </dl>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($user['user_type'] === 'seeker' && $seekerApplications): ?>
            <h2 class="mt-5"><i class="bi bi-file-earmark-text me-2"></i> Applications</h2>
            <div class="row">
                <?php foreach ($seekerApplications as $application): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-briefcase-fill me-1"></i> <?php echo html_escape($application['title']); ?></h5>
                                <p class="card-text"><i class="bi bi-calendar-check me-1"></i> Applied at: <?php echo date("M d, Y", strtotime($application['applied_at'])); ?></p>

                                <!-- Add Job Details Link -->
                                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($application['job_id'])); ?>" class="btn btn-outline-info btn-sm me-2"><i class="bi bi-info-circle me-1"></i> Job Details</a>
                                <a href="<?php echo generate_url('views/admin/view_application.php?application_id=' . html_escape($application['id'])); ?>" class="btn btn-primary btn-sm"><i class="bi bi-eye-fill me-1"></i> View Application</a>
                            </div>
                            <div class="card-footer">
                                <?php
                                $statusClass = '';
                                switch ($application['application_status']) {
                                    case 'pending':
                                        $statusClass = 'bg-warning text-dark';
                                        break;
                                    case 'approved':
                                        $statusClass = 'bg-success';
                                        break;
                                    case 'rejected':
                                        $statusClass = 'bg-danger';
                                        break;
                                    default:
                                        $statusClass = 'bg-secondary';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $statusClass; ?>"><i class="bi bi-info-circle me-1"></i> <?php echo formatText($application['application_status']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($user['user_type'] === 'company' && $companyApplications): ?>
            <h2 class="mt-5"><i class="bi bi-file-earmark-text me-2"></i> Jobs Posted by Company</h2>
            <div class="row">
                <?php foreach ($companyApplications as $job): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-briefcase-fill me-1"></i> <?php echo html_escape($job['title']); ?></h5>
                                <p class="card-text">
                                    <i class="bi bi-tag"></i> <strong>Opportunity Type:</strong> <?php echo formatText($job['posting_type']); ?><br>
                                    <i class="bi bi-clock"></i> <strong>Employment Status:</strong> <?php echo formatText($job['employment_type']); ?><br>
                                    <i class="bi bi-stars"></i> <strong>Experience:</strong> <?php echo html_escape($job['experience']); ?><br>
                                    <i class="bi bi-card-text"></i> <?php echo substr(html_escape($job['description']), 0, 100); ?>...<br>
                                    <i class="bi bi-geo-alt"></i> <strong>Location:</strong> <?php echo html_escape($job['job_location']); ?>
                                </p>
                                <a href="<?php echo generate_url('views/jobs/job_details.php?id=' . html_escape($job['id'])); ?>" class="btn btn-primary"><i class="bi bi-eye"></i> View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>