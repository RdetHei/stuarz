<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../app/config/config.php';

// Simple PSR-4-like autoloader for App\* and legacy controllers without namespace
spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $appDir = $root . '/app/';

    // App\ namespace -> app/
    if (strpos($class, 'App\\') === 0) {
        $relative = substr($class, 4);
        $file = $appDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }

    // Legacy global controllers like HomeController (no namespace)
    if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*Controller$/', $class)) {
        $file = $appDir . 'controller/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

use App\Core\Router;

$router = new Router();

//Routes
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'RegisterController@register');
$router->get('/docs', 'DocsController@docs');
$router->get('/news', 'NewsController@news');
$router->get('/news_show', 'NewsController@show');
$router->get('/chat', 'ChatController@chat');
$router->get('/company', 'CompanyController@company');
$router->get('/profile', 'ProfileController@profile');
$router->get('/dashboard', 'DashboardController@dashboard');
$router->get('/account', 'AccountController@account');
$router->get('/create_user', 'AccountController@create');
$router->post('/store_user', 'AccountController@store');
$router->get('/edit_user', 'AccountController@edit');
$router->post('/update_user', 'AccountController@update');
$router->post('/delete_user', 'AccountController@delete');
$router->get('/certificates', 'CertificatesController@certificate');
$router->get('/my_certificates', 'CertificatesController@myCertificates');
$router->post('/upload_certificate', 'CertificatesController@upload');
$router->post('/delete_certificate', 'CertificatesController@delete');
$router->get('/class', 'ClassController@class');
$router->get('/dashboard-admin', 'DashboardAdminController@dashboardAdmin');
$router->get('/dashboard-admin-docs', 'DashboardAdminController@docsIndex');
$router->get('/dashboard-admin-docs-create', 'DashboardAdminController@docsCreate');
$router->post('/dashboard-admin-docs-store', 'DashboardAdminController@docsStore');
$router->get('/dashboard-admin-docs-edit', 'DashboardAdminController@docsEdit');
$router->post('/dashboard-admin-docs-update', 'DashboardAdminController@docsUpdate');
$router->post('/dashboard-admin-docs-delete', 'DashboardAdminController@docsDelete');
$router->get('/dashboard-admin-news', 'DashboardAdminController@news');
$router->get('/dashboard-admin-news-create', 'DashboardAdminController@newsCreate');
$router->post('/dashboard-admin-news-store', 'DashboardAdminController@newsStore');
$router->get('/dashboard-admin-news-edit', 'DashboardAdminController@newsEdit');
$router->post('/dashboard-admin-news-update', 'DashboardAdminController@newsUpdate');
$router->post('/dashboard-admin-news-delete', 'DashboardAdminController@newsDelete');
$router->get('/grades', 'GradeController@index');
$router->get('/admin/dashboard', 'DashboardAdminController@index');
$router->get('/admin/news', 'NewsController@adminList');
$router->get('/admin/announcement', 'AnnouncementController@adminList');
$router->get('/admin/docs', 'DocsController@adminList');
$router->get('/admin/account', 'AccountController@adminList');
$router->get('/admin/create-user', 'AccountController@createUser');
$router->get('/admin/edit-user', 'AccountController@editUser');
$router->get('/admin/edit-announcement', 'AnnouncementController@editAnnouncement');
$router->get('/admin/news-form', 'NewsController@newsForm');
$router->get('/admin/docs-form', 'DocsController@docsForm');
$router->get('/admin/upload-announcement', 'AnnouncementController@uploadAnnouncement');


// Backward-compat: map ?page=... into a path for dispatching
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (!empty($_GET['page'])) {
    $page = '/' . trim((string)$_GET['page'], '/');
    if ($page === '/home') {
        $requestUri = '/';
    } else {
        $requestUri = $page;
    }
}

$matched = $router->dispatch($method, $requestUri);

if (!$matched) {
        http_response_code(404);
        include __DIR__ . '/../app/views/pages/notFound.php';
}
