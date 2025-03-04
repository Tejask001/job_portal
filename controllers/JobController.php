<?php
//session_start();
require_once __DIR__ . '/../config/database.php'; // Include database connection
require_once __DIR__ . '/../models/Job.php';
require_once __DIR__ . '/../models/Application.php';

function redirect($url)
{
    header("Location: " . $url);
    exit();
}

class JobController
{
    private $jobModel;
    private $applicationModel;

    public function __construct($pdo)
    {
        $this->jobModel = new Job($pdo);
        $this->applicationModel = new Application($pdo);
    }

    public function postJob($company_id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks)
    {
        if (empty($title) || empty($description) || empty($posting_type) || empty($employment_type) || empty($work_type) || empty($skills) || empty($no_of_openings) || empty($start_date) || empty($stipend_salary)) {
            $_SESSION['error_message'] = "All fields are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/post_job.php');
            return;
        }

        $job_id = $this->jobModel->createJob($company_id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks);

        if ($job_id) {
            $_SESSION['success_message'] = "Job posted successfully! Waiting for admin approval.";
            redirect('/job_portal/views/company/dashboard.php');
        } else {
            $_SESSION['error_message'] = "Job posting failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/post_job.php');
        }
    }

    public function updateJob($id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks)
    {
        if (empty($title) || empty($description) || empty($posting_type) || empty($employment_type) || empty($work_type) || empty($skills) || empty($no_of_openings) || empty($start_date) || empty($stipend_salary)) {
            $_SESSION['error_message'] = "All fields are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/edit_job.php?id=' . $id);
            return;
        }

        $updated = $this->jobModel->updateJob($id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks);

        if ($updated) {
            $_SESSION['success_message'] = "Job updated successfully!";
            redirect('/job_portal/views/company/dashboard.php');
        } else {
            $_SESSION['error_message'] = "Job update failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/edit_job.php?id=' . $id);
        }
    }

    public function deleteJob($id)
    {
        $deleted = $this->jobModel->deleteJob($id);

        if ($deleted) {
            $_SESSION['success_message'] = "Job deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Job deletion failed. Please try again.";
        }
        redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/company/dashboard.php');
    }

    public function applyForJob($job_id, $user_id, $name, $email, $phone, $resume_path, $why_are_you_fit)
    {
        if (empty($name) || empty($email) || empty($phone) || empty($resume_path) || empty($why_are_you_fit)) {
            $_SESSION['error_message'] = "All fields are required.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/jobs/apply.php?id=' . $job_id);
            return;
        }

        $application_id = $this->applicationModel->createApplication($job_id, $user_id, $name, $email, $phone, $resume_path, $why_are_you_fit);

        if ($application_id) {
            $_SESSION['success_message'] = "Application submitted successfully!";
            redirect('/job_portal/views/seeker/dashboard.php');
        } else {
            $_SESSION['error_message'] = "Application submission failed. Please try again.";
            redirect($_SERVER['HTTP_REFERER'] ?? '/job_portal/views/jobs/apply.php?id=' . $job_id);
        }
    }

    // Add this function
    public function getJobsByCompanyId($company_id)
    {
        return $this->jobModel->getJobsByCompanyId($company_id);
    }
}
