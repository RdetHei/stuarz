<?php
class DashboardController {
    public function dashboard() {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../view/dashboard/page/dashboard.php';
        
        include '../view/layout.php';
    }
}