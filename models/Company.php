<?php

class Company
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCompany(
        $user_id,
        $company_name,
        $company_logo,
        $company_description,
        $industry,
        $employee_count,
        $website_link,
        $location
    ) {
        $stmt = $this->pdo->prepare("INSERT INTO companies (user_id, company_name, company_logo, company_description, industry, employee_count, website_link, location) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $company_name, $company_logo, $company_description, $industry, $employee_count, $website_link, $location]);
        return $this->pdo->lastInsertId(); // Return the new company ID
    }

    public function getCompanyByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCompanyById($id)
    {
        $stmt = $this->pdo->prepare("SELECT id, user_id, company_name, company_logo, company_description, industry, employee_count, website_link, location FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCompanies()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM companies");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCompanyProfile(
        $id,
        $company_name,
        $company_logo,
        $company_description,
        $industry,
        $employee_count,
        $website_link,
        $location
    ) {
        $stmt = $this->pdo->prepare("UPDATE companies SET company_name = ?, company_logo = ?, company_description = ?, industry = ?, employee_count = ?, website_link = ?, location = ? WHERE id = ?");
        $stmt->execute([$company_name, $company_logo, $company_description, $industry, $employee_count, $website_link, $location, $id]);
        return $stmt->rowCount();
    }

    public function deleteCompany($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM companies WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
