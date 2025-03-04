<?php
session_start(); // Add session_start() back
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class CompanyController
{
    private $companyModel;
    private $pdo;  // Add PDO property

    public function __construct($pdo)
    {
        $this->companyModel = new Company($pdo);
        $this->pdo = $pdo; // Initialize PDO
    }

    public function createCompany($user_id, $company_name, $company_logo, $company_description)
    {
        // Validate input
        if (empty($company_name)) {
            $_SESSION['error_message'] = "Company name is required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
            return;
        }

        $company_id = $this->companyModel->createCompany($user_id, $company_name, $company_logo, $company_description);

        if ($company_id) {
            $_SESSION['success_message'] = "Company profile created successfully!";
            redirect('/job_portal/views/company/dashboard.php');
        } else {
            $_SESSION['error_message'] = "Company profile creation failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
        }
    }

    public function updateCompanyProfile($id, $company_name, $company_logo, $company_description)
    {
        // Validate input
        if (empty($company_name)) {
            $_SESSION['error_message'] = "Company name is required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
            return;
        }

        $updated = $this->companyModel->updateCompanyProfile($id, $company_name, $company_logo, $company_description);

        if ($updated) {
            $_SESSION['success_message'] = "Company profile updated successfully!";
            redirect('/job_portal/views/company/dashboard.php');
        } else {
            $_SESSION['error_message'] = "Company profile update failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
        }
    }

    private function handleLogoUpload()
    {
        // Check if a file was uploaded
        if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../public/images/';  // Absolute path
            $file_extension = pathinfo($_FILES['company_logo']['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid() . '.' . $file_extension;
            $company_logo = 'public/images/' . $new_file_name; // Relative path for the database

            if (move_uploaded_file($_FILES['company_logo']['tmp_name'], $upload_dir . $new_file_name)) {
                // File uploaded successfully
                return $company_logo;
            } else {
                // File upload failed
                $_SESSION['error_message'] = "Failed to upload company logo.";
                redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
                exit();
            }
        }

        return ''; // Return an empty string if no logo was uploaded or if upload failed
    }

    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'create_company':
                    $company_logo = $this->handleLogoUpload();

                    $this->createCompany(
                        $_POST['user_id'] ?? '',
                        $_POST['company_name'] ?? '',
                        $company_logo,
                        $_POST['company_description'] ?? ''
                    );
                    break;
                case 'update_company_profile':
                    $company_logo = $this->handleLogoUpload();
                    $this->updateCompanyProfile(
                        $_POST['id'] ?? '',
                        $_POST['company_name'] ?? '',
                        $company_logo, // Pass the processed company_logo
                        $_POST['company_description'] ?? ''
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
$companyController = new CompanyController($pdo);
$companyController->handleRequest();
