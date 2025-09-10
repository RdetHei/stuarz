<?php
class DashboardController {
    public function dashboard() {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../view/landing/page/dashboard.php';
        
        include '../view/dLayout.php';
    }
}