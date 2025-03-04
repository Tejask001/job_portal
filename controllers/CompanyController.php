<?php
//session_start();
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/Company.php';

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

class CompanyController
{
    private $companyModel;

    public function __construct($pdo)
    {
        $this->companyModel = new Company($pdo);
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
}
