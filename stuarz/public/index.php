<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../app/controller/HomeController.php';
require_once __DIR__ . '/../app/controller/LoginController.php';
require_once __DIR__ . '/../app/controller/DashboardController.php';
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'login':
        (new LoginController())->login();
        break;
    case 'dashboard':
        (new DashboardController())->dashboard();
        break;
    default:
        http_response_code(404);
        echo '404 Page Not Found';
}
