<?php
class HomeController {
    public function index() {
        $title = "Stuarz — Find and Join us in Stuarz";
        $description = "Blablabla blebleble blublublu";

        // Tentukan file view utama
        $content = '../view/landing/page/home.php';
        
        include '../view/layout.php';
    }
}