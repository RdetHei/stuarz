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
        $notifications = $nm->getRecent(100);

        $content = dirname(__DIR__) . '/views/pages/notifications/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
}
