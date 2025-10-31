<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/users.php';

class SetupProfileController {
    private $model;
    
    public function __construct() {
        global $config;
        $this->model = new users($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    
    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Check if profile is already complete
        if ($this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=dashboard');
            exit;
        }
        
        $view = dirname(__DIR__) . '/views/pages/auth/setup_profile.php';
        include $view;
    }
    
    public function store() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        
        // Validate required fields
        $errors = [];
        
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $class = trim($_POST['class'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        
        if (empty($name)) {
            $errors[] = 'Nama lengkap wajib diisi';
        }
        
        if (empty($phone)) {
            $errors[] = 'Nomor telepon wajib diisi';
        } elseif (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
            $errors[] = 'Format nomor telepon tidak valid';
        }
        
        if (empty($address)) {
            $errors[] = 'Alamat wajib diisi';
        }
        
        if (empty($class)) {
            $errors[] = 'Kelas wajib diisi';
        }
        
        // Handle file uploads
        $avatarPath = null;
        $bannerPath = null;
        
        // Upload avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarPath = $this->uploadFile($_FILES['avatar'], 'avatars');
            if (!$avatarPath) {
                $errors[] = 'Gagal mengupload avatar';
            }
        }
        
        // Upload banner
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $bannerPath = $this->uploadFile($_FILES['banner'], 'banners');
            if (!$bannerPath) {
                $errors[] = 'Gagal mengupload banner';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['setup_errors'] = $errors;
            header('Location: index.php?page=setup-profile');
            exit;
        }
        
        // Update user profile
        $updateData = [
            'name' => $name,
            'phone' => $phone,
            'address' => $address,
            'class' => $class,
            'bio' => $bio
        ];
        
        if ($avatarPath) {
            $updateData['avatar'] = $avatarPath;
        }
        
        if ($bannerPath) {
            $updateData['banner'] = $bannerPath;
        }
        
        $success = $this->model->updateProfile($userId, $updateData);
        
        if ($success) {
            // Update session with new data
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['class'] = $class;
            $_SESSION['user']['bio'] = $bio;
            if ($avatarPath) $_SESSION['user']['avatar'] = $avatarPath;
            if ($bannerPath) $_SESSION['user']['banner'] = $bannerPath;
            
            $_SESSION['success'] = 'Profile berhasil diselesaikan!';
            header('Location: index.php?page=dashboard-admin');
            exit;
        } else {
            $_SESSION['setup_errors'] = ['Gagal menyimpan profile. Silakan coba lagi.'];
            header('Location: index.php?page=setup-profile');
            exit;
        }
    }
    
    private function isProfileComplete($userId) {
        $user = $this->model->getUserById($userId);
        
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
    
    private function uploadFile($file, $folder) {
        // Filesystem path to uploads directory
        $uploadDirFs = dirname(__DIR__, 2) . "/public/uploads/{$folder}/";
        // Public path returned to be saved in DB and used in <img src>
        $publicBase = "uploads/{$folder}/";

        if (!is_dir($uploadDirFs)) {
            mkdir($uploadDirFs, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes, true)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION) ?: 'jpg';
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $destFs = $uploadDirFs . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destFs)) {
            return $publicBase . $fileName;
        }

        return false;
    }
}

