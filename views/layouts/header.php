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
    <!-- Other meta tags and title -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?php echo generate_url('public/css/style.css'); ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo generate_url('index.php'); ?>">Job Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('index.php'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_url('views/jobs/job_listing.php'); ?>">Jobs</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Logged in -->
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
                            <a class="nav-link" href="<?php echo generate_url('controllers/AuthController.php?action=logout'); ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Not logged in -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo generate_url('views/auth/login.php'); ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo generate_url('views/auth/register.php'); ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php include_once __DIR__ . '/../includes/flash_messages.php'; ?>