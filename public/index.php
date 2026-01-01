<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/../app/config/config.php';



$isAjaxRequest = (
    (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
    (isset($_GET['ajax']) && $_GET['ajax'] == '1') ||
    (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
);

if ($isAjaxRequest && isset($_GET['page'])) {
    // direct route for class AJAX operations
    $page = $_GET['page'] ?? '';
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    
    error_log('Routing AJAX: page=' . $page . ', method=' . $method . ', POST data: ' . json_encode($_POST));
    
    if ($page === 'class_store' && $method === 'POST') {
        error_log('Routing to ClassController::store()');
        require_once __DIR__ . '/../app/controller/ClassController.php';
        $controller = new ClassController();
        $controller->store();
        exit;
    }

    if ($page === 'join_class' && $method === 'POST') {
        error_log('Routing to ClassController::join()');
        require_once __DIR__ . '/../app/controller/ClassController.php';
        $controller = new ClassController();
        $controller->join();
        exit;
    }

    if ($page === 'class_delete' && $method === 'POST') {
        error_log('Routing to ClassController::delete()');
        require_once __DIR__ . '/../app/controller/ClassController.php';
        $controller = new ClassController();
        $controller->delete();
        exit;
    }
    
    error_log('Routing AJAX: No matching route found for page=' . $page . ', method=' . $method);
}


$isAjaxRequest = (
    (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
    (isset($_GET['ajax']) && $_GET['ajax'] == '1') ||
    (isset($_SERVER['HTTP_ACCEPT']) && 
     strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
);

// Remove duplicate routing block - already handled above

if (!isset($_GET['page'])) {
    header('Location: index.php?page=home');
    exit;
}


if (
    empty($_SESSION['user_id']) &&
    isset($_COOKIE['user_id'], $_COOKIE['username'])
) {
    try {
        $uid = intval($_COOKIE['user_id']);
        $uname = $_COOKIE['username'];
        if ($uid > 0 && isset($config) && $config instanceof mysqli) {
            $stmt = mysqli_prepare($config, 'SELECT * FROM users WHERE id = ? LIMIT 1');
            mysqli_stmt_bind_param($stmt, 'i', $uid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);
            if ($row && ($row['username'] ?? '') === $uname) {
                $_SESSION['user'] = $row;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['level'] = $row['level'];
            }
        }
    } catch (Throwable $e) {
    }
}


spl_autoload_register(function ($class) {
    $root = dirname(__DIR__);
    $appDir = $root . '/app/';

    // Handle App\ namespace classes (e.g., App\Core\Router)
    if (strpos($class, 'App\\') === 0) {
        $relative = substr($class, 4);
        $file = $appDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }

    // Handle Controller classes (e.g., AuthController, TaskController)
    if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*Controller$/', $class)) {
        $file = $appDir . 'controller/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }

    // Handle Model classes (e.g., UsersModel, TaskModel)
    if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*Model$/', $class)) {
        $file = $appDir . 'model/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

use App\Core\Router;


$router = new Router();

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
$router->post('/news_delete', 'NewsController@delete');
$router->get('/news_print', 'NewsController@print');
$router->get('/docs_print', 'DocsController@print');
$router->get('/print', 'PrintController@index');
$router->get('/print_table', 'PrintController@printTable');
$router->get('/chat', 'ChatController@chat');
$router->get('/company', 'CompanyController@company');
$router->get('/profile', 'ProfileController@profile');
$router->get('/settings', 'SettingsController@index');
$router->post('/settings', 'SettingsController@update');
$router->get('/notifications', 'NotificationController@index');
$router->post('/notifications/mark-read', 'NotificationController@markRead');
$router->post('/notifications/mark-unread', 'NotificationController@markUnread');
$router->post('/notifications/delete', 'NotificationController@delete');
$router->post('/notifications/mark-all-read', 'NotificationController@markAllRead');
$router->post('/notifications/clear', 'NotificationController@clear');
$router->get('/notifications/unread-count', 'NotificationController@unreadCount');
$router->get('/dashboard', 'DashboardController@dashboard');
$router->get('/dashboard-guru', 'DashboardController@guru');
$router->get('/account', 'AccountController@account');
$router->get('/students', 'AccountController@students');
$router->get('/teachers', 'AccountController@teachers');
$router->get('/create_user', 'AccountController@create');
$router->post('/store_user', 'AccountController@store');
$router->get('/edit_user', 'AccountController@edit');
$router->post('/update_user', 'AccountController@update');
$router->post('/delete_user', 'AccountController@delete');
$router->get('/get_user_profile', 'AccountController@getUserProfile');
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
$router->post('/class_update_role', 'ClassController@updateMemberRole');
$router->get('/class/detail/{id}', 'ClassController@detail');
$router->get('/join_class', 'ClassController@joinForm');
$router->get('/join_form', 'ClassController@joinForm');
$router->post('/join_class', 'ClassController@join');
$router->post('/join-class', 'ClassController@join');
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
$router->post('/tasks/review', 'TaskController@reviewSubmission');
$router->get('/grades/grading', 'GradeController@grading');
$router->post('/grades/grade-submission', 'GradeController@gradeSubmission');
$router->post('/tasks/send-reminders', 'TaskController@sendReminders');
$router->get('/tasks/submissions', 'TaskController@submissions');
$router->get('/announcement', 'AnnouncementController@index');
$router->get('/announcement_create', 'AnnouncementController@create');
$router->get('/announcement_edit', 'AnnouncementController@edit');
$router->post('/announcement_store', 'AnnouncementController@store');
$router->post('/announcement_update', 'AnnouncementController@update');
$router->post('/announcement_delete', 'AnnouncementController@delete');
$router->get('/announcement_show', 'AnnouncementController@show');
$router->get('/announcement_print', 'AnnouncementController@print');
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
$router->get('/grades_print', 'GradeController@print');
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

$router->get('/schedule', 'ScheduleController@index');
$router->get('/schedule/create', 'ScheduleController@create');
$router->post('/schedule/store', 'ScheduleController@store');
$router->get('/schedule/edit/{id}', 'ScheduleController@edit');
$router->post('/schedule/update/{id}', 'ScheduleController@update');
$router->post('/schedule/delete/{id}', 'ScheduleController@delete');

$router->get('/attendance', 'AttendanceController@index');
$router->get('/attendance_manage', 'AttendanceController@manage');
$router->post('/attendance_checkin', 'AttendanceController@checkIn');
$router->post('/attendance_checkout', 'AttendanceController@checkOut');
$router->post('/attendance_edit', 'AttendanceController@edit');
$router->post('/attendance_delete', 'AttendanceController@delete');

$router->get('/student/tasks', 'TaskController@studentTasks');
$router->get('/student/task/{id}', 'TaskController@studentTaskDetail');
$router->get('/student/submit', 'TaskController@studentSubmit');
$router->post('/student/submit_action', 'TaskController@storeSubmission');
$router->get('/student/attendance', 'AttendanceController@my');
$router->get('/student/profile', 'ProfileController@studentProfile');
$router->post('/student/profile_update', 'ProfileController@updateStudentProfile');
$router->get('/student/notifications', 'NotificationController@index');



$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Determine request URI from GET parameter or REQUEST_URI
$requestUri = '/';
if (!empty($_GET['page'])) {
    $page = trim((string)$_GET['page'], '/');
    // Normalize page parameter to route format
    if ($page === 'home' || $page === '') {
        $requestUri = '/';
    } else {
        $requestUri = '/' . $page;
    }
} else {
    // Fallback to REQUEST_URI if no page parameter
    $rawUri = $_SERVER['REQUEST_URI'] ?? '/';
    $parsedUri = parse_url($rawUri, PHP_URL_PATH);
    if ($parsedUri) {
        $requestUri = $parsedUri;
    }
    if ($requestUri === '' || $requestUri === 'index.php' || $requestUri === '/index.php') {
        $requestUri = '/';
    }
}

// Ensure requestUri starts with /
if ($requestUri !== '/' && strpos($requestUri, '/') !== 0) {
    $requestUri = '/' . $requestUri;
}

try {
    $matched = $router->dispatch($method, $requestUri);
    
    if (!$matched) {
        http_response_code(404);
        include __DIR__ . '/../app/views/pages/errors/notFound.php';
    }
} catch (Throwable $e) {
    error_log('Router dispatch error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    echo '<!DOCTYPE html><html><head><title>Error</title></head><body>';
    echo '<h1>Internal Server Error</h1>';
    echo '<p>An error occurred while processing your request.</p>';
    if (defined('DEBUG') && constant('DEBUG')) {
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    }
    echo '</body></html>';
    exit;
}