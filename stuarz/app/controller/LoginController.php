<?php
require_once dirname(__DIR__) . '/config/config.php';

class LoginController
{
    public function login()
    {
        global $config;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil input
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Gunakan prepared statement biar aman
            $stmt = mysqli_prepare($config, "SELECT id, username, password, level FROM users WHERE username = ?");
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                // Verifikasi password
                if (password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['level'] = $user['level'];

                    // Redirect sesuai level
                    switch ($user['level']) {
                        case 'admin':
                            header('Location: /stuarz/public/index.php?page=dashboard-admin');
                            break;
                        case 'guru':
                            header('Location: /stuarz/public/index.php?page=dashboard-guru');
                            break;
                        case 'murid':
                            header('Location: /stuarz/public/index.php?page=dashboard-murid');
                            break;
                        default:
                            header('Location: /stuarz/public/index.php?page=dashboard');
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
        $view = dirname(__DIR__) . '/../view/landing/page/login.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
