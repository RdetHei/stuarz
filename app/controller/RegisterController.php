<?php
require_once dirname(__DIR__) . '/config/config.php';

class RegisterController
{
    public function register()
    {
        global $config;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            // Validasi
            if ($username === '' || $email === '' || $password === '' || $confirm === '') {
                $error = 'Semua field wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid.';
            } elseif ($password !== $confirm) {
                $error = 'Password dan konfirmasi tidak cocok.';
            } else {
                // Cek duplikasi username/email
                $stmt = $config->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
                $stmt->bind_param('ss', $username, $email);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $error = 'Username atau email sudah digunakan.';
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $level = 'user';
                    $sql = 'INSERT INTO users (username, email, password, level, join_date) VALUES (?, ?, ?, ?, CURDATE())';
                    $stmt2 = $config->prepare($sql);
                    $stmt2->bind_param('ssss', $username, $email, $hash, $level);
                    if ($stmt2->execute()) {
                        header('Location: index.php?page=login');
                        exit;
                    } else {
                        $error = 'Gagal mendaftar. Silakan coba lagi.';
                    }
                    $stmt2->close();
                }
                $stmt->close();
            }
        }
        $view = dirname(__DIR__) . '/views/pages/auth/register.php';
        include $view;
    }
}
