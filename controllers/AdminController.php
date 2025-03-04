<?php
//session_start();
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Job.php';

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

class AdminController
{
    private $userModel;
    private $jobModel;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->jobModel = new Job($pdo);
    }

    public function getAllUsers()
    {
        return $this->userModel->getAllUsers();
    }

    public function deleteUser($id)
    {
        $deleted = $this->userModel->deleteUser($id);

        if ($deleted) {
            $_SESSION['success_message'] = "User deleted successfully!";
        } else {
            $_SESSION['error_message'] = "User deletion failed. Please try again.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/admin/manage_users.php');
    }

    public function approveJob($job_id)
    {
        $approved = $this->jobModel->approveJob($job_id);

        if ($approved) {
            $_SESSION['success_message'] = "Job approved successfully!";
        } else {
            $_SESSION['error_message'] = "Job approval failed. Please try again.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/admin/manage_jobs.php');
    }

    public function unapproveJob($job_id)
    {
        $unapproved = $this->jobModel->unapproveJob($job_id);

        if ($unapproved) {
            $_SESSION['success_message'] = "Job unapproved successfully!";
        } else {
            $_SESSION['error_message'] = "Job unapproval failed. Please try again.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/admin/manage_jobs.php');
    }

    public function getAllJobs()
    {
        return $this->jobModel->getAllJobs();
    }
}
