<?php
require_once dirname(__DIR__) . '/config/config.php';

class RegisterController {
    public function register()
    {
        global $config;
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = mysqli_real_escape_string($config, $_POST['username'] ?? '');
            $email    = mysqli_real_escape_string($config, $_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
                $error = 'Semua field wajib diisi.';
            } elseif ($password !== $confirm) {
                $error = 'Password dan konfirmasi tidak cocok.';
            } else {
                // Cek apakah username atau email sudah ada
                $checkUser = "SELECT * FROM users WHERE username='$username' OR email='$email'";
                $result = mysqli_query($config, $checkUser);

                if ($result && mysqli_num_rows($result) > 0) {
                    $error = 'Username atau email sudah digunakan.';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Default avatar = huruf depan username (pakai UI Avatars)
                    $avatar = "https://ui-avatars.com/api/?name=" . urlencode($username) . "&background=0D8ABC&color=fff";

                    // Insert user dengan nilai default
                    $sql = "INSERT INTO users 
                            (username, email, password, level, phone, address, join_date, class, profile_picture, tasks_completed, attendance, certificates, average_grade) 
                            VALUES 
                            ('$username', '$email', '$hashedPassword', 'user', '', '', CURDATE(), '', '$avatar', 0, 0, 0, 'N/A')";

                    if (mysqli_query($config, $sql)) {
                        $success = 'Registrasi berhasil! Silakan login.';
                        header("Location: /stuarz/public/index.php?page=login");
                        exit;
                    } else {
                        $error = 'Terjadi kesalahan: ' . mysqli_error($config);
                    }
                }
            }
        }

        $view = dirname(__DIR__) . '/../view/landing/page/register.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
