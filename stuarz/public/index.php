<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../app/controller/HomeController.php';
require_once __DIR__ . '/../app/controller/AuthController.php';
require_once __DIR__ . '/../app/controller/RegisterController.php';
require_once __DIR__ . '/../app/controller/DocsController.php';
require_once __DIR__ . '/../app/controller/CompanyController.php';
require_once __DIR__ . '/../app/controller/AccountController.php';
require_once __DIR__ . '/../app/controller/DashboardController.php';
require_once __DIR__ . '/../app/controller/DashboardAdminController.php';
require_once __DIR__ . '/../app/controller/ClassController.php';
require_once __DIR__ . '/../app/controller/NewsController.php';
require_once __DIR__ . '/../app/controller/ProfileController.php';
require_once __DIR__ . '/../app/controller/CertificatesController.php';

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
    // Docs CRUD moved to admin area
    case 'company':
        (new CompanyController())->company();
        break;
    case 'news':
        (new NewsController())->news();
        break;
    case 'news_show':
        (new NewsController())->show();
        break;
    case 'dashboard-admin':
        (new DashboardAdminController())->dashboardAdmin();
        break;
    case 'dashboard-admin-docs':
        (new DashboardAdminController())->docsIndex();
        break;
    case 'dashboard-admin-docs-create':
        (new DashboardAdminController())->docsCreate();
        break;
    case 'dashboard-admin-docs-store':
        (new DashboardAdminController())->docsStore();
        break;
    case 'dashboard-admin-docs-edit':
        (new DashboardAdminController())->docsEdit();
        break;
    case 'dashboard-admin-docs-update':
        (new DashboardAdminController())->docsUpdate();
        break;
    case 'dashboard-admin-docs-delete':
        (new DashboardAdminController())->docsDelete();
        break;
    // News admin
    case 'dashboard-admin-news':
        (new DashboardAdminController())->newsIndex();
        break;
    case 'dashboard-admin-news-create':
        (new DashboardAdminController())->newsCreate();
        break;
    case 'dashboard-admin-news-store':
        (new DashboardAdminController())->newsStore();
        break;
    case 'dashboard-admin-news-edit':
        (new DashboardAdminController())->newsEdit();
        break;
    case 'dashboard-admin-news-update':
        (new DashboardAdminController())->newsUpdate();
        break;
    case 'dashboard-admin-news-delete':
        (new DashboardAdminController())->newsDelete();
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
    case 'class':
        (new ClassController())->index();
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
    case 'certificates':
        (new CertificatesController())->certificate();
        break;
    case 'my_certificates':
        (new CertificatesController())->myCertificates();
        break;
    case 'upload_certificate':
        (new CertificatesController())->upload();
        break;
    case 'delete_certificate':
        (new CertificatesController())->delete();
        break;
    default:
        http_response_code(404);
        header('Location: ../view/landing/page/notFound.php');
}
