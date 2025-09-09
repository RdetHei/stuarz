<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../app/controller/HomeController.php';
require_once __DIR__ . '/../app/controller/LoginController.php';
require_once __DIR__ . '/../app/controller/RegisterController.php';
require_once __DIR__ . '/../app/controller/DocsController.php';
require_once __DIR__ . '/../app/controller/DashboardController.php';
require_once __DIR__ . '/../app/controller/DashboardAdminController.php';
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'login':
        (new LoginController())->login();
        break;
    case 'register':
        (new RegisterController())->register();
        break;
    case 'docs':
        (new DocsController())->docs();
        break;
    case 'dashboard':
        (new DashboardController())->dashboard();
        break;
    case 'dashboard-admin':
        (new DashboardAdminController())->dashboardAdmin();
        break;
    default:
        http_response_code(404);
        header('Location: ../view/landing/page/notFound.php');
}
