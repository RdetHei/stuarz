<?php
require_once dirname(__DIR__) . '/config/config.php';

class LoginController
{
    public function login()
    {
        global $config;
        $error = '';

        // Proses login jika form disubmit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = mysqli_real_escape_string($config, $_POST['username'] ?? '');
            $password = mysqli_real_escape_string($config, $_POST['password'] ?? '');

            // Query user berdasarkan username dan password
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($config, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);

                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['username'] = $user['username'];
                $_SESSION['level'] = $user['level']; // Pastikan kolom 'level' ada di tabel users

                // Redirect sesuai level
                if ($user['level'] === 'admin') {
                    header('Location: /stuarz/public/index.php?page=dashboard');
                } else {
                    header('Location: /stuarz/public/index.php?page=dashboard');
                }
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        }

        // Tampilkan view login
        $view = dirname(__DIR__) . '/../view/landing/page/login.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
