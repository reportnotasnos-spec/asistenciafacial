<?php

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Create a new notification
    public function add($userId, $title, $message, $type = 'info', $link = null)
    {
        $this->db->query('INSERT INTO notifications (user_id, title, message, type, link) VALUES (:user_id, :title, :message, :type, :link)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':title', $title);
        $this->db->bind(':message', $message);
        $this->db->bind(':type', $type);
        $this->db->bind(':link', $link);
        return $this->db->execute();
    }

    // Get unread notifications for a user
    public function getUnread($userId)
    {
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get all notifications (with limit)
    public function getAll($userId, $limit = 20)
    {
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Mark a specific notification as read
    public function markAsRead($id, $userId)
    {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Mark all as read
    public function markAllAsRead($userId)
    {
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}
