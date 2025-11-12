<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../app/config/config.php';

if (!isset($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}


if (
    empty($_SESSION['user_id']) &&
    isset($_COOKIE['user_id'], $_COOKIE['username'], $_COOKIE['level'])
) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    $_SESSION['level'] = $_COOKIE['level'];
    // Tambahkan validasi ke database jika perlu
}


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

    // Legacy global controllers seperti HomeController (no namespace)
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
$router->post('/register', 'RegisterController@register');
$router->get('/setup-profile', 'SetupProfileController@index');
$router->post('/setup-profile/store', 'SetupProfileController@store');
$router->get('/docs', 'DocsController@docs');
$router->get('/news', 'NewsController@news');
$router->get('/news_show', 'NewsController@show');
$router->get('/chat', 'ChatController@chat');
$router->get('/company', 'CompanyController@company');
$router->get('/profile', 'ProfileController@profile');
$router->get('/settings', 'SettingsController@index');
$router->post('/settings', 'SettingsController@update');
$router->get('/notifications', 'NotificationController@index');
$router->get('/dashboard', 'DashboardController@dashboard');
$router->get('/account', 'AccountController@account');
$router->get('/students', 'AccountController@students');
$router->get('/teachers', 'AccountController@teachers');
$router->get('/create_user', 'AccountController@create');
$router->post('/store_user', 'AccountController@store');
$router->get('/edit_user', 'AccountController@edit');
$router->post('/update_user', 'AccountController@update');
$router->post('/delete_user', 'AccountController@delete');
$router->get('/certificates', 'CertificatesController@certificate');
$router->get('/my_certificates', 'CertificatesController@myCertificates');
$router->post('/upload_certificate', 'CertificatesController@upload');
$router->post('/delete_certificate', 'CertificatesController@delete');
$router->get('/class', 'ClassController@index');
$router->get('/class_create', 'ClassController@create');
$router->post('/class_store', 'ClassController@store');
$router->get('/class_edit', 'ClassController@edit');
$router->post('/class_update', 'ClassController@update');
$router->post('/class_delete', 'ClassController@delete');
$router->get('/class_members', 'ClassController@members');
$router->post('/class_add_member', 'ClassController@addMember');
$router->get('/grades', 'GradeController@index');
$router->get('/grades/create', 'GradeController@create');
$router->post('/grades/store', 'GradeController@store');
$router->get('/grades/edit', 'GradeController@edit');
$router->post('/grades/update', 'GradeController@update');
$router->post('/grades/delete', 'GradeController@delete');
$router->get('/subjects', 'SubjectController@index');
$router->get('/subjects/create', 'SubjectController@create');
$router->post('/subjects/store', 'SubjectController@store');
$router->get('/subjects/edit', 'SubjectController@edit');
$router->post('/subjects/update', 'SubjectController@update');
$router->post('/subjects/delete', 'SubjectController@delete');
$router->get('/tasks', 'TaskController@index');
$router->get('/tasks/create', 'TaskController@create');
$router->post('/tasks/store', 'TaskController@store');
$router->get('/tasks/edit', 'TaskController@edit');
$router->post('/tasks/update', 'TaskController@update');
$router->post('/tasks/delete', 'TaskController@delete');
$router->post('/tasks/submit', 'TaskController@storeSubmission');
$router->get('/tasks/submissions', 'TaskController@submissions');
$router->get('/announcement', 'AnnouncementController@index');
$router->get('/announcement_create', 'AnnouncementController@create');
$router->get('/announcement_edit', 'AnnouncementController@edit');
$router->post('/announcement_store', 'AnnouncementController@store');
$router->post('/announcement_update', 'AnnouncementController@update');
$router->post('/announcement_delete', 'AnnouncementController@delete');
$router->get('/announcement_show', 'AnnouncementController@show');
$router->post('/announcement_add_comment', 'AnnouncementController@addComment');
$router->post('/class_remove_member', 'ClassController@removeMember');
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

// Schedule
$router->get('/schedule', 'ScheduleController@index');
$router->get('/schedule/create', 'ScheduleController@create');
$router->post('/schedule/store', 'ScheduleController@store');
$router->get('/schedule/edit/{id}', 'ScheduleController@edit');
$router->post('/schedule/update/{id}', 'ScheduleController@update');
$router->post('/schedule/delete/{id}', 'ScheduleController@delete');

// Attendance routes
$router->get('/attendance', 'AttendanceController@index');
$router->get('/attendance_manage', 'AttendanceController@manage');
$router->post('/attendance_checkin', 'AttendanceController@checkIn');
$router->post('/attendance_checkout', 'AttendanceController@checkOut');
$router->post('/attendance_edit', 'AttendanceController@edit');
$router->post('/attendance_delete', 'AttendanceController@delete');


// Backward-compat: map ?page=... into a path for dispatching
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if (!empty($_GET['page'])) {
    $page = '/' . trim((string)$_GET['page'], '/');
    if ($page === 'index.php?page=home') {
        $requestUri = '/';
    } else {
        $requestUri = $page;
    }
}

$matched = $router->dispatch($method, $requestUri);

if (!$matched) {
        http_response_code(404);
        include __DIR__ . '/../app/views/pages/errors/notFound.php';
}