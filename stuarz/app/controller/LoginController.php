<?php
require_once dirname(__DIR__) . '/config/config.php';

class LoginController
{
    public function login()
    {
        global $config;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim(mysqli_real_escape_string($config, $_POST['username'] ?? ''));
            $password = trim($_POST['password'] ?? '');

            $sql = "SELECT * FROM users WHERE username='$username'";
            $result = mysqli_query($config, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['level'] = $user['level'];

                    if ($user['level'] === 'admin') {
                        header('Location: /stuarz/public/index.php?page=dashboard-admin');
                    } else {
                        header('Location: /stuarz/public/index.php?page=dashboard');
                    }
                    exit;
                } else {
                    $error = 'Password salah.';
                }
            } else {
                $error = 'Username tidak ditemukan.';
            }
        }

        $view = dirname(__DIR__) . '/../view/landing/page/login.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
