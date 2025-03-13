<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($user_type, $name, $email, $password, $age = null, $gender = null, $experience = null)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (user_type, name, email, password, age, gender, experience) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_type, $name, $email, $hashed_password, $age, $gender, $experience]);
        return $this->pdo->lastInsertId();
    }

    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $name, $email, $age, $gender, $experience)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, age = ?, gender = ?, experience = ? WHERE id = ?");
        $stmt->execute([$name, $email, $age, $gender, $experience, $id]);
        return $stmt->rowCount();
    }

    public function updatePassword($id, $new_password)
    {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $id]);
        return $stmt->rowCount();
    }

    public function deleteUser($id)
    {

        try {
            $this->pdo->beginTransaction();
            $contactCheck = $this->pdo->prepare("SELECT * FROM company_contacts WHERE user_id = ?");
            $contactCheck->execute([$id]);
            $contacts = $contactCheck->fetchAll();

            // Delete from related table first if contacts are present
            if ($contacts) {
                $deleteCompanyContacts = $this->pdo->prepare("DELETE FROM company_contacts WHERE user_id = ?");
                $deleteCompanyContacts->execute([$id]);
            }

            //Now we delete the user, if all is good
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $this->pdo->commit();

            return $stmt->rowCount();
        } catch (Exception $e) {
            $this->pdo->rollback();

            throw $e;
        }
    }
}
