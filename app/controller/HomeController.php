<?php
class HomeController {
    public function index() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../app/views/pages/home.php';
        
        include '../app/views/layouts/layout.php';
    }
}