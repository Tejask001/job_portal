<?php
session_start(); // Ensure session is started at the top of each page
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/functions.php';

// Helper function to generate URLs correctly.
function generate_url($path)
{
    return "/job_portal/" . ltrim($path, "/");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>

    <!-- Bootstrap & Custom Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="<?php echo generate_url('public/css/style.css'); ?>">

    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #343a40;
            --light-color: #f8f9fa;
            --hover-color: #0056b3;
            --danger-color: #dc3545;
            --danger-hover: #c82333;
        }

        body {
            background-color: var(--light-color);
            color: #212529;
        }

        .navbar {
            background-color: var(--secondary-color) !important;
        }

        .navbar-brand,
        .nav-link {
            color: var(--light-color) !important;
        }

        .nav-link:hover {
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border: none;
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo generate_url('index.php'); ?>">Job Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('index.php'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('views/jobs/job_listing.php'); ?>">Jobs</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_type'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/admin/dashboard.php'); ?>">Admin Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['user_type'] == 'company'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/company/dashboard.php'); ?>">Company Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['user_type'] == 'seeker'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/seeker/dashboard.php'); ?>">My Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3" href="<?php echo generate_url('controllers/AuthController.php?action=logout'); ?>">Logout</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3" href="<?php echo generate_url('views/auth/login.php'); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3" href="<?php echo generate_url('views/auth/register.php'); ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>


</body>

</html>