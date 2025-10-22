<?php
class HomeController {
    public function index() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = dirname(__DIR__) . '/views/pages/landing/home.php';
        
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }
}