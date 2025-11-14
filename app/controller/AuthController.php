<?php
require_once dirname(__DIR__) . '/config/config.php';

class AuthController
{
    public function login()
    {
        global $config;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Ambil semua field user
            $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) session_start();


                    // Simpan semua data user di session
                    $_SESSION['user'] = $user;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['level'] = $user['level'];
                    // Simpan timezone aplikasi di session dan set timezone untuk proses saat ini
                    $_SESSION['timezone'] = $GLOBALS['app_timezone'] ?? 'Asia/Jakarta';
                    if (!ini_get('date.timezone')) {
                        date_default_timezone_set($_SESSION['timezone']);
                    }

                    // Set cookie login untuk 1 jam
                    setcookie('user_id', $user['id'], time() + 3600, '/');
                    setcookie('username', $user['username'], time() + 3600, '/');
                    setcookie('level', $user['level'], time() + 3600, '/');

                    // Check if profile is complete
                    if (!$this->isProfileComplete($user)) {
                        header('Location: index.php?page=setup-profile');
                        exit;
                    }

                    // Redirect sesuai level
                    switch ($user['level']) {
                        case 'admin':
                            header('Location: index.php?page=dashboard-admin');
                            break;
                        case 'guru':
                            header('Location: index.php?page=dashboard-guru');
                            break;
                        default:
                            header('Location: index.php?page=dashboard-user');
                            break;
                    }
                    exit;
                } else {
                    $error = 'Password salah.';
                }
            } else {
                $error = 'Username tidak ditemukan.';
            }
        }

        // Panggil view login
        $view = dirname(__DIR__) . '/views/pages/auth/login.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

    // Hapus semua session
    session_unset();
    session_destroy();

    // Hapus cookie login
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('username', '', time() - 3600, '/');
    setcookie('level', '', time() - 3600, '/');

    // Redirect ke halaman login
    header("Location: index.php?page=login");
    exit;
    }
    
    private function isProfileComplete($user) {
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
