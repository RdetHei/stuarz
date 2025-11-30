<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/NotificationsModel.php';

class NotificationController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: index.php?page=login');
            exit;
        }

        global $config;
        $nm = new NotificationsModel($config);
        // Only retrieve notifications belonging to the current user
        $notifications = $nm->getForUser($userId, 200);

        $content = dirname(__DIR__) . '/views/pages/notifications/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    private function jsonResponse(array $data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function markAllRead()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        global $config;
        $nm = new NotificationsModel($config);
        $ok = $nm->markAllRead($userId);
        $this->jsonResponse(['success' => (bool)$ok]);
    }

    public function markRead()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        if ($id <= 0) $this->jsonResponse(['success' => false, 'error' => 'invalid_id']);
        global $config;
        $nm = new NotificationsModel($config);
        $ok = $nm->setReadStatus($id, true);
        $this->jsonResponse(['success' => (bool)$ok]);
    }

    public function markUnread()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        if ($id <= 0) $this->jsonResponse(['success' => false, 'error' => 'invalid_id']);
        global $config;
        $nm = new NotificationsModel($config);
        $ok = $nm->setReadStatus($id, false);
        $this->jsonResponse(['success' => (bool)$ok]);
    }

    public function delete()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $id = isset($input['id']) ? (int)$input['id'] : 0;
        if ($id <= 0) $this->jsonResponse(['success' => false, 'error' => 'invalid_id']);
        global $config;
        $nm = new NotificationsModel($config);
        $ok = $nm->deleteById($id);
        $this->jsonResponse(['success' => (bool)$ok]);
    }

public function clear()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $userId = $_SESSION['user_id'] ?? null;
    
    if (!$userId) {
        $this->jsonResponse(['success' => false, 'error' => 'not_authenticated']);
    }

    global $config;
    $nm = new NotificationsModel($config);

    // Hapus hanya notifikasi milik user yang sedang login
    $ok = $nm->clearByUser($userId);

    $this->jsonResponse(['success' => (bool)$ok]);
}


    public function unreadCount()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        global $config;
        $nm = new NotificationsModel($config);
        $count = $nm->countUnread($userId);
        $this->jsonResponse(['count' => $count]);
    }
}
