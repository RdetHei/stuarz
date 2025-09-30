<?php
require_once dirname(__DIR__) . '/config/config.php';

class DashboardController
{
    public function dashboard()
    {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/views/pages/dashboard.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

        public function dashboardAdmin() {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../app/views/pages/dashboard.php';
        
        include '../app/views/layouts/dLayout.php';
   
    }
}

