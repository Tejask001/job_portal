<?php
// session_start();
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

    public function updateUserProfile($id, $name, $email)
    {
        // Validate input
        if (empty($name) || empty($email)) {
            $_SESSION['error_message'] = "Name and email are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/profile.php');
            return;
        }

        $updated = $this->userModel->updateUserProfile($id, $name, $email);

        if ($updated) {
            $_SESSION['success_message'] = "Profile updated successfully!";
            $_SESSION['user_name'] = $name; // Update the session name as well
            redirect('/job_portal/views/seeker/profile.php');
        } else {
            $_SESSION['error_message'] = "Profile update failed. Please try again.";
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

        $file_path = __DIR__ . '/../' . $resume['resume_path'];

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
        //global $pdo; //Remove the global variable. It is available in the class

        echo "saveJob called with job_id: " . html_escape($job_id) . " and user_id: " . html_escape($user_id) . "<br>"; // Debugging
        var_dump($this->pdo);  // Check PDO connection

        try {
            echo "Preparing SQL statement...<br>"; // Debugging
            $stmt = $this->pdo->prepare("INSERT INTO saved_jobs (job_id, user_id) VALUES (?, ?)");
            echo "SQL statement prepared successfully.<br>"; // Debugging
            $stmt->execute([$job_id, $user_id]);
            echo "SQL statement executed successfully.<br>"; // Debugging

            $_SESSION['success_message'] = "Job saved successfully!";
            echo "Success message set.<br>"; // Debugging
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Failed to save job: " . $e->getMessage();
            error_log("Error saving job: " . $e->getMessage()); // Log the error

            // Display the error message for debugging
            echo "Database Error: " . $e->getMessage() . "<br>";
        }

        echo "Before redirect... HTTP_REFERER is: " . ($_SERVER['HTTP_REFERER'] ?? 'Not Set') . "<br>";
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/jobs/job_details.php?id=' . $job_id); // Redirect back to job details page
        echo "After redirect (this should not be displayed).<br>"; //Debug

    }

    public function unsaveJob($job_id, $user_id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("DELETE FROM saved_jobs WHERE job_id = ? AND user_id = ?");
            $stmt->execute([$job_id, $user_id]);
            $_SESSION['success_message'] = "Job unsaved successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Failed to unsave job: " . $e->getMessage();
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/seeker/saved_jobs.php'); //Redirect back to saved jobs page or wherever appropriate
    }

    public function getSavedJobsByUserId($user_id)
    {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT jobs.*, companies.company_name
                                    FROM saved_jobs
                                    JOIN jobs ON saved_jobs.job_id = jobs.id
                                    JOIN companies ON jobs.company_id = companies.id
                                    WHERE saved_jobs.user_id = ?
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
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM saved_jobs WHERE job_id = ? AND user_id = ?");
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

    public function getNotificationsByUserId($user_id)
    {
        return $this->notificationModel->getNotificationsByUserId($user_id);
    }

    // Handle requests based on the 'action' parameter
    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'update_profile':
                    //Call the updateUserProfile function
                    $this->updateUserProfile(
                        $_POST['id'] ?? '',
                        $_POST['name'] ?? '',
                        $_POST['email'] ?? ''
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
