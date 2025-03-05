<?php
class Job
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createJob($company_id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks)
    {
        $stmt = $this->pdo->prepare("INSERT INTO jobs (company_id, title, description, posting_type, employment_type, work_type, skills, no_of_openings, start_date, duration, who_can_apply, stipend_salary, perks) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$company_id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks]);
        return $this->pdo->lastInsertId();
    }

    public function getJobById($id)
    {
        $stmt = $this->pdo->prepare("SELECT jobs.*, companies.company_name, companies.company_logo FROM jobs JOIN companies ON jobs.company_id = companies.id WHERE jobs.id = ? AND jobs.admin_approval = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllJobs($approved_only = false)
    {
        $sql = "SELECT jobs.*, companies.company_name, companies.company_logo FROM jobs JOIN companies ON jobs.company_id = companies.id";
        if ($approved_only) {
            $sql .= " WHERE admin_approval = 1";
        }
        $sql .= " ORDER BY jobs.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateJob($id, $title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks)
    {
        $stmt = $this->pdo->prepare("UPDATE jobs SET title = ?, description = ?, posting_type = ?, employment_type = ?, work_type = ?, skills = ?, no_of_openings = ?, start_date = ?, duration = ?, who_can_apply = ?, stipend_salary = ?, perks = ? WHERE id = ?");
        $stmt->execute([$title, $description, $posting_type, $employment_type, $work_type, $skills, $no_of_openings, $start_date, $duration, $who_can_apply, $stipend_salary, $perks, $id]);
        return $stmt->rowCount();
    }

    public function deleteJob($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM jobs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function getJobsByCompanyId($company_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM jobs WHERE company_id = ? ORDER BY created_at DESC");
        $stmt->execute([$company_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveJob($job_id)
    {
        $stmt = $this->pdo->prepare("UPDATE jobs SET admin_approval = 1 WHERE id = ?");
        $stmt->execute([$job_id]);
        return $stmt->rowCount();
    }

    public function unapproveJob($job_id)
    {
        $stmt = $this->pdo->prepare("UPDATE jobs SET admin_approval = 0 WHERE id = ?");
        $stmt->execute([$job_id]);
        return $stmt->rowCount();
    }
}
