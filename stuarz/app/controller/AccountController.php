<?php
require_once dirname(__DIR__) . '/config/config.php';

class AccountController
{
    public function account()
    {
        global $config;

        // Pastikan session aktif
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Jika user belum login, redirect ke login
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Ambil semua akun dari database
        $query = "SELECT id, username, email, level, join_date FROM users";
        $result = mysqli_query($config, $query);

        $accounts = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Jika ada data null, kasih default value
                $row['username'] = $row['username'] ?? 'Unknown User';
                $row['email'] = $row['email'] ?? 'No Email';
                $row['level'] = $row['level'] ?? 'User';
                $row['join_date'] = $row['join_date'] ?? '-';

                $accounts[] = $row;
            }
        }

        // Atur judul dan konten view
        $title = "Account List - Stuarz";
        $content = dirname(__DIR__) . '/../view/landing/page/account.php';

        include dirname(__DIR__) . '/../view/dLayout.php';
    }
}
