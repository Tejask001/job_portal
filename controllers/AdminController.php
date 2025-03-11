<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not started
}
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Company.php'; // Add this line!
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class AdminController
{
    private $userModel;
    private $jobModel;
    private $companyModel;
    private $pdo;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->jobModel = new Job($pdo);
        $this->companyModel = new Company($pdo);
        $this->pdo = $pdo;
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

    public function getCompanyDetailsById($company_id)
    {
        return $this->companyModel->getCompanyByUserId($company_id);
    }

    public function getUserDetails($user_id)
    {
        return $this->userModel->getUserById($user_id);
    }


    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'delete_user':
                    $this->deleteUser($_GET['id'] ?? '');
                    break;
                case 'approve_job':
                    $this->approveJob($_GET['id'] ?? '');
                    break;
                case 'unapprove_job':
                    $this->unapproveJob($_GET['id'] ?? '');
                    break;
                default:
                    echo "Invalid action.";
                    break;
            }
        }
    }
}

// Create an instance and handle the request
$adminController = new AdminController($pdo);
$adminController->handleRequest();
