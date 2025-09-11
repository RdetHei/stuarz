<?php
require_once dirname(__DIR__) . '/config/config.php';

class ProfileController
{
    public function profile()
    {
        global $config;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: index.php?page=dashboard");
            exit;
        }

        // Ambil data user berdasarkan user_id
        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$user) {
            echo "Data user tidak ditemukan.";
            return;
        }

        $title = "Profile - Stuarz";
        $content = dirname(__DIR__) . '/../view/landing/page/profile.php';

        include dirname(__DIR__) . '/../view/dLayout.php';
    }
}
