<?php
// SELALU pakai dirname(__DIR__) biar aman
require_once dirname(__DIR__) . '/config/config.php'; // D:\...\app\config\config.php

class LoginController {

    public function login() {
        global $config;

        // Jika POST (submit form)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = mysqli_real_escape_string($config, $_POST['username'] ?? '');
            $password = mysqli_real_escape_string($config, $_POST['password'] ?? '');

            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = mysqli_query($config, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['username'] = $username;
                header('Location: /stuarz/public/index.php?page=dashboard'); // arahkan ke dashboard
                exit;
            } else {
                $error = 'Username atau password salah.';
            }
        }

        // **TAMPILKAN VIEW LOGIN** (cek file-nya ada dulu)
        $view = dirname(__DIR__) . '/../view/landing/page/login.php'; // D:\...\app\view\landing\page\login.php
        if (!is_file($view)) {
            // Bantuan debug biar jelas kalau path-nya beda
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
