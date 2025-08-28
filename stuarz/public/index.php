<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once '../app/controller/HomeController.php';


$page = $_GET['page'] ?? 'home';


switch ($page) {
    case 'home':
        $controller = new HomeController();
        $controller->index();
        break;

   

    default:
        include '404.php';
}