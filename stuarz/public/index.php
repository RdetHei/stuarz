<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../app/controller/HomeController.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/RegisterController.php';
require_once __DIR__ . '/../app/controller/DocsController.php';
require_once __DIR__ . '/../app/controller/CompanyController.php';
require_once __DIR__ . '/../app/controller/AccountController.php';
require_once __DIR__ . '/../app/controller/DashboardController.php';
require_once __DIR__ . '/../app/controller/ProfileController.php';

$page = $_GET['page'] ?? 'home';


switch ($page) {
    case 'home':
        (new HomeController())->index();
        break;
    case 'login':
        (new AuthController())->login();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'register':
        (new RegisterController())->register();
        break;
    case 'docs':
        (new DocsController())->docs();
        break;
    case 'company':
        (new CompanyController())->company();
        break;
    case 'dashboard-admin':
        (new DashboardController())->dashboardAdmin();
        break;
    case 'profile':
        (new ProfileController())->profile();
        break;
    case 'dashboard':
        (new DashboardController())->dashboard();
        break;
    case 'account':
        (new AccountController())->account();
        break;
    case 'create_user':
        (new AccountController())->create();
        break;
    case 'store_user':
        (new AccountController())->store();
        break;
    case 'edit_user':
        (new AccountController())->edit();
        break;
    case 'update_user':
        (new AccountController())->update();
        break;
    case 'delete_user':
        (new AccountController())->delete();
        break;
    default:
        http_response_code(404);
        header('Location: ../view/landing/page/notFound.php');
}
