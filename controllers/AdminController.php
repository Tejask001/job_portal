<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not started
}
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Application.php'; // Include the Application model
require_once __DIR__ . '/../models/Notification.php'; // Include the Application model
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class AdminController
{
    private $userModel;
    private $jobModel;
    private $companyModel;
    private $applicationModel; // Add this line!
    private $notificationModel;
    private $pdo;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->jobModel = new Job($pdo);
        $this->companyModel = new Company($pdo);
        $this->applicationModel = new Application($pdo); // Initialize the Application
        $this->notificationModel = new Notification($pdo);
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

    // Retrieve all Job Applications
    public function getAllJobApplications()
    {
        return $this->applicationModel->getAllJobApplications();
    }

    // Update status of Job application
    public function updateApplicationStatus($application_id, $status)
    {
        $updated = $this->applicationModel->updateApplicationStatus($application_id, $status);

        if ($updated) {
            $application = $this->applicationModel->getApplicationById($application_id);

            if ($application) {
                $user_id = $application['user_id'];
                $job_id = $application['job_id'];
                $job = $this->jobModel->getJobById($job_id);
                $job_title = $job['title'];

                // Get The Job Model
                $jobModel = new Job($this->pdo);

                //Increment the positions filled IFF its been approved, this may be prone to edge cases where the number of applications to the jobs is more. But let that not concern us right now
                if ($status === 'approved') {
                    //Update the Model in database
                    $jobModel->incrementPositionsFilled($job_id);
                }

                // Create the notification message
                $message = "Your application for the job titled " . $job_title . " has been " . $status . ".";

                // Create notification for user in DB
                $this->notificationModel->createNotification($user_id, $message);

                $_SESSION['success_message'] = "Application status updated successfully!";
            } else {
                $_SESSION['error_message'] = "Application status updated successfully, however notification could not be created due to application ID";
            }

            $_SESSION['success_message'] = "Application status updated successfully!";
        } else {
            $_SESSION['error_message'] = "Application status update failed. Please try again.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/admin/manage_applications.php');
    }

    public function getCompanyDetailsById($company_id)
    {
        return $this->companyModel->getCompanyByUserId($company_id);
    }

    public function getUserDetails($user_id)
    {
        return $this->userModel->getUserById($user_id);
    }

    // Add new cases to the switch statement inside the handleRequest function
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
                case 'update_application_status':
                    $this->updateApplicationStatus(
                        $_GET['application_id'] ?? '',
                        $_GET['status'] ?? ''
                    );
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
