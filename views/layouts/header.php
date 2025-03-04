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
    <link rel="stylesheet" href="<?php echo generate_url('public/css/style.css'); ?>">
</head>

<body>
    <nav>
        <ul>
            <li><a href="<?php echo generate_url('index.php'); ?>">Home</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Logged in -->
                <?php if ($_SESSION['user_type'] == 'admin'): ?>
                    <li><a href="<?php echo generate_url('views/admin/dashboard.php'); ?>">Admin Dashboard</a></li>
                <?php elseif ($_SESSION['user_type'] == 'company'): ?>
                    <li><a href="<?php echo generate_url('views/company/dashboard.php'); ?>">Company Dashboard</a></li>
                <?php elseif ($_SESSION['user_type'] == 'seeker'): ?>
                    <li><a href="<?php echo generate_url('views/seeker/dashboard.php'); ?>">My Dashboard</a></li>
                <?php endif; ?>
                <li><a href="<?php echo generate_url('controllers/AuthController.php?action=logout'); ?>">Logout</a></li>
            <?php else: ?>
                <!-- Not logged in -->
                <li><a href="<?php echo generate_url('views/auth/login.php'); ?>">Login</a></li>
                <li><a href="<?php echo generate_url('views/auth/register.php'); ?>">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="container">
        <?php include_once __DIR__ . '/../includes/flash_messages.php'; ?>