<?php

class Notification
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createNotification($user_id, $message)
    {
        $stmt = $this->pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        $stmt->execute([$user_id, $message]);
        return $this->pdo->lastInsertId();
    }

    public function getNotificationsByUserId($user_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsRead($notification_id)
    {
        $stmt = $this->pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        $stmt->execute([$notification_id]);
        return $stmt->rowCount();
    }
}
