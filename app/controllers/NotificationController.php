<?php

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        if (!Session::isLoggedIn()) {
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $this->notificationModel = $this->model('Notification');
    }

    // API Endpoint: Get latest notifications for the logged in user
    public function fetch()
    {
        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getAll($userId, 10);
        $unreadCount = count($this->notificationModel->getUnread($userId));

        header('Content-Type: application/json');
        echo json_encode([
            'count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    // API Endpoint: Mark as read
    public function markRead($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            if ($this->notificationModel->markAsRead($id, $userId)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
        }
    }

    // API Endpoint: Mark all as read
    public function markAllRead()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $this->notificationModel->markAllAsRead($userId);
            echo json_encode(['status' => 'success']);
        }
    }
}
