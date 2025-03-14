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
    <title><?php echo isset($page_title) ? html_escape($page_title) : 'Job Portal'; ?></title>

    <!-- Bootstrap & Custom Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        .navbar-brand {
            font-weight: bold;
            /* Make the brand name bolder */
        }

        .nav-link {
            color: var(--light-color) !important;
            transition: color 0.3s ease;
            /* Smooth transition for color change */
        }

        .nav-link:hover {
            color: #bbb !important;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border: none;
            transition: background-color 0.3s ease;
            /* Smooth transition for background color */
        }

        .btn-danger:hover {
            background-color: var(--danger-hover);
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm bg-dark">
        <div class="container bg-dark" style="margin: 0px auto;">
            <a class="navbar-brand" href="<?php echo generate_url('index.php'); ?>"><i class="bi bi-briefcase me-1"></i>People's Consulting</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('index.php'); ?>"><i class="bi bi-house-door me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('views/jobs/job_listing.php'); ?>"><i class="bi bi-search me-1"></i> Jobs</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['user_type'] == 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/admin/dashboard.php'); ?>"><i class="bi bi-speedometer2 me-1"></i> Admin Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['user_type'] == 'company'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/company/dashboard.php'); ?>"><i class="bi bi-building me-1"></i> Company Dashboard</a>
                            </li>
                        <?php elseif ($_SESSION['user_type'] == 'seeker'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo generate_url('views/seeker/dashboard.php'); ?>"><i class="bi bi-person me-1"></i> My Dashboard</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3" href="<?php echo generate_url('controllers/AuthController.php?action=logout'); ?>"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary text-white px-3 me-2" href="<?php echo generate_url('views/auth/login.php'); ?>"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3" href="<?php echo generate_url('views/auth/register.php'); ?>"><i class="bi bi-person-plus me-1"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php
    // Display messages and errors if they exist
    if (isset($_SESSION['success_message'])) {
        echo '<div class="container mt-3"><div class="alert alert-success" role="alert">' . html_escape($_SESSION['success_message']) . '</div></div>';
        unset($_SESSION['success_message']); // Remove the message after displaying it
    }

    if (isset($_SESSION['error_message'])) {
        echo '<div class="container mt-3"><div class="alert alert-danger" role="alert">' . html_escape($_SESSION['error_message']) . '</div></div>';
        unset($_SESSION['error_message']); // Remove the message after displaying it
    }
    ?>