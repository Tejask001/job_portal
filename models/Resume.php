<?php

class Resume
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createResume($user_id, $resume_path, $resume_name)
    {
        $stmt = $this->pdo->prepare("INSERT INTO resumes (user_id, resume_path, resume_name) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $resume_path, $resume_name]);
        return $this->pdo->lastInsertId();
    }

    public function getResumesByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResumeById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM resumes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteResume($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM resumes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
