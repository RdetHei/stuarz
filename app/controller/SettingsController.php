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

            // Basic validation
            if ($name === '' || $email === '' || $username === '') {
                $_SESSION['error'] = 'Nama, email, dan username wajib diisi.';
                header("Location: index.php?page=settings");
                exit;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Format email tidak valid.';
                header("Location: index.php?page=settings");
                exit;
            }

            // Ensure email/username are unique (exclude current user)
            $checkStmt = mysqli_prepare($config, "SELECT id FROM users WHERE (email = ? OR username = ?) AND id != ? LIMIT 1");
            mysqli_stmt_bind_param($checkStmt, "ssi", $email, $username, $userId);
            mysqli_stmt_execute($checkStmt);
            $res = mysqli_stmt_get_result($checkStmt);
            $conflict = mysqli_fetch_assoc($res);
            mysqli_stmt_close($checkStmt);

            if ($conflict) {
                $_SESSION['error'] = 'Email atau username sudah digunakan oleh akun lain.';
                header("Location: index.php?page=settings");
                exit;
            }

            $stmt = mysqli_prepare($config, "UPDATE users SET name = ?, email = ?, username = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $username, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            // Refresh session user data
            $stmt = mysqli_prepare($config, "SELECT id, username, name, email, `level`, COALESCE(role,'') AS role, avatar, banner, join_date, phone, address, `class`, bio FROM users WHERE id = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $updatedUser = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            if ($updatedUser) $_SESSION['user'] = $updatedUser;

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

            if (!isset($user['password']) || !password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Password saat ini tidak benar';
                header("Location: index.php?page=settings");
                exit;
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Password baru tidak cocok';
                header("Location: index.php?page=settings");
                exit;
            }

            // No minimum length requirement enforced here (handled by policy if needed)

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



