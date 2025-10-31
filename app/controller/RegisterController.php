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
                try {
                    $stmt = $config->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
                    $stmt->bind_param('ss', $username, $email);
                    $stmt->execute();
                    $stmt->store_result();
                    if ($stmt->num_rows > 0) {
                        $error = 'Username atau email sudah digunakan.';
                    } else {
                        $hash = password_hash($password, PASSWORD_BCRYPT);
                        $level = 'user';
                        $role = 'student';
                        $sql = 'INSERT INTO users (username, email, password, level, role, join_date) VALUES (?, ?, ?, ?, ?, CURDATE())';
                        $stmt2 = $config->prepare($sql);
                        $stmt2->bind_param('sssss', $username, $email, $hash, $level, $role);
                        if ($stmt2->execute()) {
                        // Get the new user ID
                        $newUserId = $config->insert_id;
                        
                        // Start session and set user data
                        if (session_status() === PHP_SESSION_NONE) session_start();
                        
                        // Get user data for session
                        $userStmt = $config->prepare('SELECT * FROM users WHERE id = ?');
                        $userStmt->bind_param('i', $newUserId);
                        $userStmt->execute();
                        $userResult = $userStmt->get_result();
                        $user = $userResult->fetch_assoc();
                        
                        // Set session data
                        $_SESSION['user'] = $user;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        $_SESSION['level'] = $user['level'];
                        
                        // Set cookies
                        setcookie('user_id', $user['id'], time() + 3600, '/');
                        setcookie('username', $user['username'], time() + 3600, '/');
                        setcookie('level', $user['level'], time() + 3600, '/');
                        
                        // Redirect to setup profile
                        header('Location: index.php?page=setup-profile');
                        exit;
                        } else {
                            $error = 'Gagal mendaftar. Silakan coba lagi.';
                        }
                        $stmt2->close();
                    }
                    $stmt->close();
                } catch (mysqli_sql_exception $e) {
                    // Log detailed error for debugging and present a friendly message
                    $logDir = dirname(__DIR__) . '/logs';
                    if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
                    $logFile = $logDir . '/register_errors.log';
                    $log = '[' . date('Y-m-d H:i:s') . "] Register error: " . $e->getMessage() . "\n";
                    @file_put_contents($logFile, $log, FILE_APPEND);
                    $error = 'Terjadi kesalahan pada server saat mendaftar. Silakan hubungi administrator.';
                }
            }
        }
        $view = dirname(__DIR__) . '/views/pages/auth/register.php';
        include $view;
    }
}
