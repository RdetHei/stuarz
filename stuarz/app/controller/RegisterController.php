<?php
require_once dirname(__DIR__) . '/config/config.php';

class RegisterController{
    public function register()
    {
        global $config;
        $error = '';
        $success = '';

        // Proses register jika form disubmit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = mysqli_real_escape_string($config, $_POST['username'] ?? '');
            $email    = mysqli_real_escape_string($config, $_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            // Validasi
            if (empty($username) || empty($email) || empty($password) || empty($confirm)) {
                $error = 'Semua field wajib diisi.';
            } elseif ($password !== $confirm) {
                $error = 'Password dan konfirmasi tidak cocok.';
            } else {
                // Cek apakah username atau email sudah terdaftar
                $checkUser = "SELECT * FROM users WHERE username='$username' OR email='$email'";
                $result = mysqli_query($config, $checkUser);

                if ($result && mysqli_num_rows($result) > 0) {
                    $error = 'Username atau email sudah digunakan.';
                } else {
                    // Hash password untuk keamanan
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Simpan user baru
                    $sql = "INSERT INTO users (username, email, password, level) 
                            VALUES ('$username', '$email', '$hashedPassword', 'user')";

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

        // Tampilkan view register
        $view = dirname(__DIR__) . '/../view/landing/page/register.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
