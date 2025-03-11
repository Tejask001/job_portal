<?php

class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createUser($user_type, $name, $email, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (user_type, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$user_type, $name, $email, $hashed_password]);
        return $this->pdo->lastInsertId(); // Return the new user ID
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

    public function updateUserProfile($id, $name, $email, $age = null, $gender = null, $experience = null)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ?, age = ?, gender = ?, experience = ? WHERE id = ?");
            $stmt->execute([$name, $email, $age, $gender, $experience, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error updating user: " . $e->getMessage()); // Log the error
            return false;
        }
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
    // Add deleteUserAccount function here
    public function deleteUserAccount($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error deleting user account: " . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($id, $new_password)
    {
        try {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            return false;
        }
    }
}
