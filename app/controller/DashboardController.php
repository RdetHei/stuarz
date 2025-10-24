<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/users.php';

class DashboardController
{
    private $userModel;
    
    public function __construct() {
        global $config;
        $this->userModel = new users($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    
    public function dashboard()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Check if profile is complete
        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=setup-profile');
            exit;
        }
        
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/views/pages/dashboard/dashboard.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function dashboardAdmin()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Check if profile is complete
        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=setup-profile');
            exit;
        }
        
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = dirname(__DIR__) . '/views/pages/dashboard/dashboard.php';
        
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    
    private function isProfileComplete($userId) {
        $user = $this->userModel->getUserById($userId);
        
        if (!$user) return false;
        
        // Check if essential fields are filled
        $requiredFields = ['name', 'phone', 'address', 'class'];
        
        foreach ($requiredFields as $field) {
            if (empty($user[$field])) {
                return false;
            }
        }
        
        return true;
    }
}

