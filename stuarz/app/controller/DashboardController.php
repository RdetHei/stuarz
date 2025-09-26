<?php
require_once dirname(__DIR__) . '/config/config.php';

class DashboardController
{
    public function dashboard()
    {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/../view/landing/page/dashboard.php';

        include dirname(__DIR__) . '/../view/dLayout.php';
    }

        public function dashboardAdmin() {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../view/landing/page/dashboard.php';
        
        include '../view/dLayout.php';
   
    }
}

