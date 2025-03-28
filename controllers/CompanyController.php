<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Start session only if it's not started
}

require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../models/Application.php';
require_once __DIR__ . '/../models/Notification.php'; //Include noification model
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/CompanyContact.php'; //Include new model
require_once __DIR__ . '/../config/functions.php'; // Include the functions file

class CompanyController
{
    private $companyModel;
    private $applicationModel;
    private $notificationModel;
    private $jobModel;
    private $companyContactModel; // New property
    private $pdo;

    public function __construct($pdo)
    {
        $this->companyModel = new Company($pdo);
        $this->applicationModel = new Application($pdo);
        $this->notificationModel = new Notification($pdo);
        $this->jobModel = new Job($pdo);
        $this->companyContactModel = new CompanyContact($pdo); // Initialize new model
        $this->pdo = $pdo;
    }

    public function saveCompanyProfile(
        $user_id,
        $company_name,
        $company_logo,
        $company_description,
        $industry,
        $employee_count,
        $website_link,
        $location,
        $contact_name,
        $contact_age,
        $contact_gender,
        $contact_email,
        $contact_phone,
        $contact_title,
        $contact_department,
        $id = null,  // Optional company ID for updates, nullable
        $contact_id = null  // Optional contact ID for updates, nullable
    ) {

        // Validate company input
        if (empty($company_name)) {
            $_SESSION['error_message'] = "Company name is required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
            return;
        }

        // Validate contact input (you can add more validation as needed)
        if (empty($contact_name) || empty($contact_email)) {
            $_SESSION['error_message'] = "Contact name and email are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
            return;
        }

        // Check if updating or creating
        if ($id === null) { // Creating new company and contact

            $company_id = $this->companyModel->createCompany(
                $user_id,
                $company_name,
                $company_logo,
                $company_description,
                $industry,
                $employee_count,
                $website_link,
                $location
            );

            if ($company_id) {
                // Create Contact
                $this->companyContactModel->createContact(
                    $user_id,
                    $contact_name,
                    $contact_age,
                    $contact_gender,
                    $contact_email,
                    $contact_phone,
                    $contact_title,
                    $contact_department
                );
                $_SESSION['success_message'] = "Company profile created successfully!";
                redirect('/job_portal/views/company/dashboard.php');
            } else {
                $_SESSION['error_message'] = "Company profile creation failed. Please try again.";
                redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/company_profile.php');
            }
        } else { // Updating existing company and/or contact
            // Update Company
            $updatedCompany = $this->companyModel->updateCompanyProfile(
                $id,
                $company_name,
                $company_logo,
                $company_description,
                $industry,
                $employee_count,
                $website_link,
                $location
            );

            //Update Contact, we do not know if the contact exists or not, so either way we use a try catch to see if we can update it
            if ($contact_id) {
                $this->companyContactModel->updateContact(
                    $contact_id,
                    $contact_name,
                    $contact_age,
                    $contact_gender,
                    $contact_email,
                    $contact_phone,
                    $contact_title,
                    $contact_department
                );
            } else {
                $this->companyContactModel->createContact(
                    $user_id,
                    $contact_name,
                    $contact_age,
                    $contact_gender,
                    $contact_email,
                    $contact_phone,
                    $contact_title,
                    $contact_department
                );
            }

            $_SESSION['success_message'] = "Company profile updated successfully!";
            redirect('/job_portal/views/company/dashboard.php');
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

    public function getJobApplications($company_id)
    {
        return $this->applicationModel->getApplicationsForCompany($company_id);
    }

    public function updateApplicationStatus($application_id, $status)
    {
        $updated = $this->applicationModel->updateApplicationStatus($application_id, $status);

        if ($updated) {
            // Get the application details to retrieve user_id
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

            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/manage_applications.php');
        } else {
            $_SESSION['error_message'] = "Failed to update application status. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/manage_applications.php');
        }
    }

    public function handleRequest()
    {
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            switch ($action) {
                case 'save_company_profile':
                    $company_logo = $this->handleLogoUpload();

                    $this->saveCompanyProfile(
                        $_POST['user_id'] ?? '',
                        $_POST['company_name'] ?? '',
                        $company_logo,
                        $_POST['company_description'] ?? '',
                        $_POST['industry'] ?? '',
                        $_POST['employee_count'] ?? '',
                        $_POST['website_link'] ?? '',
                        $_POST['location'] ?? '',
                        $_POST['contact_name'] ?? '',
                        $_POST['contact_age'] ?? '',
                        $_POST['contact_gender'] ?? '',
                        $_POST['contact_email'] ?? '',
                        $_POST['contact_phone'] ?? '',
                        $_POST['contact_title'] ?? '',
                        $_POST['contact_department'] ?? '',
                        $_POST['id'] ?? null,  // company ID for update
                        $_POST['contact_id'] ?? null //Contact ID for update
                    );
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
$companyController = new CompanyController($pdo);
$companyController->handleRequest();
