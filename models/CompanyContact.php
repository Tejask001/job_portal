<?php

class CompanyContact
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createContact(
        $user_id,
        $name,
        $age,
        $gender,
        $email,
        $phone,
        $title,
        $department
    ) {
        $stmt = $this->pdo->prepare("INSERT INTO company_contacts (user_id, name, age, gender, email, phone, title, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $age, $gender, $email, $phone, $title, $department]);
        return $this->pdo->lastInsertId();
    }

    public function getContactByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM company_contacts WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateContact(
        $id,
        $name,
        $age,
        $gender,
        $email,
        $phone,
        $title,
        $department
    ) {
        $stmt = $this->pdo->prepare("UPDATE company_contacts SET name = ?, age = ?, gender = ?, email = ?, phone = ?, title = ?, department = ? WHERE id = ?");
        $stmt->execute([$name, $age, $gender, $email, $phone, $title, $department, $id]);
        return $stmt->rowCount();
    }
}
