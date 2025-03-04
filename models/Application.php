<?php

class Application
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createApplication($job_id, $user_id, $name, $email, $phone, $resume_path, $why_are_you_fit)
    {
        $stmt = $this->pdo->prepare("INSERT INTO job_applications (job_id, user_id, name, email, phone, resume_path, why_are_you_fit) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$job_id, $user_id, $name, $email, $phone, $resume_path, $why_are_you_fit]);
        return $this->pdo->lastInsertId();
    }

    public function getApplicationsByJobId($job_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM job_applications WHERE job_id = ?");
        $stmt->execute([$job_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApplicationsByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT job_applications.*, jobs.title FROM job_applications JOIN jobs ON job_applications.job_id = jobs.id WHERE user_id = ? ORDER BY applied_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApplicationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM job_applications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteApplication($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM job_applications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function updateApplicationStatus($application_id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE job_applications SET application_status = ? WHERE id = ?");
        $stmt->execute([$status, $application_id]);
        return $stmt->rowCount();
    }

    public function getApplicationsForCompany($company_id)
    {
        $stmt = $this->pdo->prepare("SELECT job_applications.*, jobs.title, users.name as seeker_name, users.email as seeker_email
                                    FROM job_applications
                                    JOIN jobs ON job_applications.job_id = jobs.id
                                    JOIN users ON job_applications.user_id = users.id
                                    WHERE jobs.company_id = ?
                                    ORDER BY job_applications.applied_at DESC");
        $stmt->execute([$company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
