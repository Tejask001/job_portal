<?php
$page_title = "Admin Dashboard";
include __DIR__ . '/../layouts/header.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    $_SESSION['error_message'] = "Unauthorized access.";
    redirect(generate_url('views/auth/login.php')); // Redirect to login page
    exit();
}
?>

<div class="container-fluid bg-light py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0"><i class="bi bi-speedometer2 me-2"></i> Admin Dashboard</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center mb-4">Manage users, job postings, and applications efficiently.</p>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-people fs-1 text-primary mb-3"></i>
                                    <h5 class="card-title fw-bold">Manage Users</h5>
                                    <p class="card-text text-muted">View and manage user accounts.</p>
                                    <a href="<?php echo generate_url('views/admin/manage_users.php'); ?>" class="btn btn-primary w-100">
                                        <i class="bi bi-arrow-right me-1"></i> Go to Manage Users
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-briefcase fs-1 text-success mb-3"></i>
                                    <h5 class="card-title fw-bold">Manage Jobs</h5>
                                    <p class="card-text text-muted">Control job listings</p>
                                    <a href="<?php echo generate_url('views/admin/manage_jobs.php'); ?>" class="btn btn-success w-100">
                                        <i class="bi bi-arrow-right me-1"></i> Go to Manage Jobs
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-file-earmark-text fs-1 text-warning mb-3"></i>
                                    <h5 class="card-title fw-bold">Manage Applications</h5>
                                    <p class="card-text text-muted">Review and manage job applications.</p>
                                    <a href="<?php echo generate_url('views/admin/manage_applications.php'); ?>" class="btn btn-warning w-100">
                                        <i class="bi bi-arrow-right me-1"></i> Go to Manage Applications
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                    <i class="bi bi-plus-circle fs-1 text-danger mb-3"></i>
                                    <h5 class="card-title fw-bold">Post Job For Company</h5>
                                    <p class="card-text text-muted">Create new job postings on behalf of companies.</p>
                                    <a href="<?php echo generate_url('views/admin/post_job_as_company.php'); ?>" class="btn btn-danger w-100">
                                        <i class="bi bi-arrow-right me-1"></i> Post a Job
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>