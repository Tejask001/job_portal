<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not started
}

require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Resume.php';
require_once __DIR__ . '/../models/Job.php'; // Add Job Model for saved jobs
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class UserController
{
    private $userModel;
    private $resumeModel;
    private $jobModel; // Add Job Model
    private $applicationModel;
    private $notificationModel;
    private $pdo;

    public function __construct($pdo)
    {
        $this->userModel = new User($pdo);
        $this->resumeModel = new Resume($pdo);
        $this->jobModel = new Job($pdo); // Initialize Job Model
        $this->applicationModel = new Application($pdo);
        $this->notificationModel = new Notification($pdo);
        $this->pdo = $pdo;
    }

    public function updateUserProfile($id, $name, $email, $age = null, $gender = null, $experience = null)
    {
        // Validate input
        if (empty($name) || empty($email)) {
            $_SESSION['error_message'] = "Name and email are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            return;
        }

        try {
            $updated = $this->userModel->updateUserProfile($id, $name, $email, $age, $gender, $experience);

            if ($updated) {
                $_SESSION['success_message'] = "Profile updated successfully!";
                $_SESSION['user_name'] = $name; // Update the session name as well
                redirect('/job_portal/views/seeker/profile.php');
            } else {
                $_SESSION['error_message'] = "Profile update failed. Please try again.";
                redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Profile update failed due to database error: " . $e->getMessage();
            error_log("Error updating profile: " . $e->getMessage());
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
        }
    }

    public function uploadResume($user_id, $resume)
    { // Modified arguments
        // Validate file upload
        if ($resume['resume']['error'] !== UPLOAD_ERR_OK) { // Access the file array correctly
            $_SESSION['error_message'] = "Resume upload failed: " . $this->uploadErrorMessage($resume['resume']['error']);
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            return;
        }

        $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']; // Example
        if (!in_array($resume['resume']['type'], $allowed_types)) { // Access the file array correctly
            $_SESSION['error_message'] = "Invalid resume file type. Only PDF and Word documents are allowed.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            return;
        }

        $upload_dir = __DIR__ . '/../public/uploads/resumes/'; // Absolute path
        $file_extension = pathinfo($resume['resume']['name'], PATHINFO_EXTENSION); // Access the file array correctly
        $new_file_name = uniqid() . '.' . $file_extension; // Unique filename
        $resume_path = 'public/uploads/resumes/' . $new_file_name;  // Relative path for the database

        if (move_uploaded_file($resume['resume']['tmp_name'], $upload_dir . $new_file_name)) { // Access the file array correctly
            $resume_id = $this->resumeModel->createResume($user_id, $resume_path, $resume['resume']['name']); // Access the file array correctly

            if ($resume_id) {
                $_SESSION['success_message'] = "Resume uploaded successfully!";
                redirect('/job_portal/views/seeker/profile.php');
            } else {
                $_SESSION['error_message'] = "Failed to save resume information to the database.";
                redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            }
        } else {
            $_SESSION['error_message'] = "Failed to move uploaded file.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
        }
    }

    private function uploadErrorMessage($error_code)
    {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded.';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded.';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder.';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk.';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension.';
            default:
                return 'Unknown upload error.';
        }
    }

    public function getResumesByUserId($user_id)
    {
        return $this->resumeModel->getResumesByUserId($user_id);
    }

    public function deleteResume($id)
    {
        $resume = $this->resumeModel->getResumeById($id);
        if (!$resume) {
            $_SESSION['error_message'] = "Resume not found.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            return;
        }

        $file_path = __DIR__ . '/../public/' . $resume['resume_path'];

        $deleted = $this->resumeModel->deleteResume($id);

        if ($deleted) {
            if (file_exists($file_path)) {
                unlink($file_path); // Delete the file from the server
            }
            $_SESSION['success_message'] = "Resume deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete resume.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
    }

    // Saved Jobs Functions
    public function saveJob($job_id, $user_id)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO saved_jobs (job_id, user_id) VALUES (?, ?)");
            $stmt->execute([$job_id, $user_id]);
            $_SESSION['success_message'] = "Job saved successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Failed to save job: " . $e->getMessage();
            error_log("Error saving job: " . $e->getMessage()); // Log the error
        }

        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/jobs/job_details.php?id=' . $job_id); // Redirect back to job details page
    }

    public function unsaveJob($job_id, $user_id)
    {

        try {
            $stmt = $this->pdo->prepare("DELETE FROM saved_jobs WHERE job_id = ? AND user_id = ?");
            $stmt->execute([$job_id, $user_id]);
            $_SESSION['success_message'] = "Job unsaved successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Failed to unsave job: " . $e->getMessage();
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/saved_jobs.php'); //Redirect back to saved jobs page or wherever appropriate
    }

    public function getSavedJobsByUserId($user_id)
    {

        try {
            $stmt = $this->pdo->prepare("SELECT jobs.*, companies.company_name
                                    FROM saved_jobs
                                    JOIN jobs ON saved_jobs.job_id = jobs.id
                                    JOIN companies ON jobs.company_id = companies.id
                                    WHERE saved_jobs.user_id = ? AND jobs.admin_approval = 1
                                    ORDER BY saved_jobs.saved_at DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Failed to retrieve saved jobs: " . $e->getMessage();
            return []; // Return an empty array in case of error
        }
    }

    public function isJobSaved($job_id, $user_id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM saved_jobs WHERE job_id = ? AND user_id = ?");
            $stmt->execute([$job_id, $user_id]);
            $count = $stmt->fetchColumn();
            return $count > 0;
        } catch (PDOException $e) {
            error_log("Error checking if job is saved: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    public function getApplicationsByUserId($user_id)
    {
        return $this->applicationModel->getApplicationsByUserId($user_id);
    }

    public function withdrawApplication($application_id)
    {
        // Verify that the application exists and belongs to the user
        $application = $this->applicationModel->getApplicationById($application_id);

        if (!$application) {
            $_SESSION['error_message'] = "Application not found.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/dashboard.php');
            return;
        }

        if ($application['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = "You are not authorized to delete this application.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/dashboard.php');
            return;
        }

        $deleted = $this->applicationModel->deleteApplication($application_id);

        if ($deleted) {

            $this->jobModel->decrementPositionsFilled($application['job_id']);
            $_SESSION['success_message'] = "Application deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete application.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/dashboard.php');
    }

    public function getNotificationsByUserId($user_id)
    {
        return $this->notificationModel->getNotificationsByUserId($user_id);
    }

    // Delete Account Function
    public function deleteAccount($id)
    {
        // Additional security: You might want to require the user to re-enter their password before deleting the account.

        $deleted = $this->userModel->deleteUserAccount($id);

        if ($deleted) {
            //Clear session and redirect to logout or home page
            session_unset();
            session_destroy();
            $_SESSION['success_message'] = "Account deleted successfully!";
            redirect('/job_portal/index.php'); // Redirect to homepage or a "goodbye" page
        } else {
            $_SESSION['error_message'] = "Failed to delete account.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
        }
    }
    //Update Password function
    public function updatePassword($id, $old_password, $new_password, $confirm_password)
    {
        //Verify new passwords match
        if ($new_password !== $confirm_password) {
            $_SESSION['error_message'] = "New passwords do not match.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/update_password.php');
            return;
        }

        //Get the user from the database
        $user = $this->userModel->getUserById($id);

        //verify that the user exist
        if (!$user) {
            $_SESSION['error_message'] = "User not found.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/update_password.php');
            return;
        }

        //verify current password
        if (!password_verify($old_password, $user['password'])) {
            $_SESSION['error_message'] = "Incorrect old password.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/update_password.php');
            return;
        }

        //verify the length of the new password
        if (strlen($new_password) < 8) {
            $_SESSION['error_message'] = "New password must be at least 8 characters long.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/update_password.php');
            return;
        }
        $updated = $this->userModel->updatePassword($id, $new_password);

        if ($updated) {
            $_SESSION['success_message'] = "Password updated successfully!";
            redirect('/job_portal/views/seeker/profile.php');
        } else {
            $_SESSION['error_message'] = "Failed to update password.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/update_password.php');
        }
    }


    // Handle requests based on the 'action' parameter
    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'update_profile':
                    //Call the updateUserProfile function with all parameters
                    $this->updateUserProfile(
                        $_POST['id'] ?? '',
                        $_POST['name'] ?? '',
                        $_POST['email'] ?? '',
                        $_POST['age'] ?? null,
                        $_POST['gender'] ?? null,
                        $_POST['experience'] ?? null
                    );
                    break;
                case 'upload_resume':
                    //Call the uploadResume function
                    $this->uploadResume(
                        $_POST['user_id'] ?? '',
                        $_FILES // Pass the entire $_FILES array
                    );
                    break;
                case 'delete_resume':
                    $this->deleteResume($_GET['id'] ?? '');
                    break;
                case 'save_job':
                    $this->saveJob($_GET['job_id'] ?? '', $_SESSION['user_id'] ?? '');
                    break;
                case 'unsave_job':
                    $this->unsaveJob($_GET['job_id'] ?? '', $_SESSION['user_id'] ?? '');
                    break;
                case 'withdraw_application':
                    $this->withdrawApplication($_GET['id'] ?? '');
                    break;
                case 'delete_account':
                    $this->deleteAccount($_SESSION['user_id'] ?? '');
                    break;
                case 'update_password':
                    $this->updatePassword(
                        $_POST['id'] ?? '',
                        $_POST['old_password'] ?? '',
                        $_POST['new_password'] ?? '',
                        $_POST['confirm_password'] ?? ''
                    );
                    break;
                default:
                    // Invalid action
                    echo "Invalid action."; //Or redirect to homepage
                    break;
            }
        }
    }
}

// Create an instance of the UserController and handle the request
$userController = new UserController($pdo);
$userController->handleRequest();
