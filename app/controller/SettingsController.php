<?php
require_once dirname(__DIR__) . '/config/config.php';

class SettingsController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: index.php?page=login");
            exit;
        }

        global $config;
        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            $_SESSION['user'] = $user;
        }

        // Load settings view with separate layout (not dLayout)
        include dirname(__DIR__) . '/views/pages/settings/index.php';
    }

    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: index.php?page=login");
            exit;
        }

        global $config;

        // Handle different setting updates
        if (isset($_POST['update_profile'])) {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');

            $stmt = mysqli_prepare($config, "UPDATE users SET name = ?, email = ?, username = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $username, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash'] = 'Profil berhasil diperbarui';
        } elseif (isset($_POST['update_password'])) {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Verify current password
            $stmt = mysqli_prepare($config, "SELECT password FROM users WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);

            if (!password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Password saat ini tidak benar';
                header("Location: index.php?page=settings");
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Password baru tidak cocok';
                header("Location: index.php?page=settings");
                exit;
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($config, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $_SESSION['flash'] = 'Password berhasil diperbarui';
        }

        header("Location: index.php?page=settings");
        exit;
    }
}



