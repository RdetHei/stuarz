<?php
require_once dirname(__DIR__) . '/config/config.php';
class ProfileController
{
    public function profile()
    {
        global $config;
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header("Location: index.php?page=login");
            exit;
        }

        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            $_SESSION['user'] = $user;
        }

        $title = "Profile - Stuarz";
        $description = "Your profile details";
        $content = dirname(__DIR__) . '/views/pages/users/profile.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    /**
     * Student-facing profile (simplified view)
     */
    public function studentProfile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        global $config;
        $stmt = mysqli_prepare($config, "SELECT * FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        $content = dirname(__DIR__) . '/views/pages/student/profile.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function updateStudentProfile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        global $config;

        $bio = trim($_POST['bio'] ?? '');
        $allowedExt = ['jpg','jpeg','png'];
        $maxSize = 2 * 1024 * 1024; // 2MB for avatar

        $avatarPath = null;
        if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $orig = $_FILES['avatar']['name'];
            $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExt, true)) {
                $_SESSION['error'] = 'Avatar harus berupa JPG/PNG.';
                header('Location: index.php?page=student/profile'); exit;
            }
            if ($_FILES['avatar']['size'] > $maxSize) {
                $_SESSION['error'] = 'Ukuran avatar maksimal 2MB.';
                header('Location: index.php?page=student/profile'); exit;
            }
            $dir = 'public/uploads/avatars/';
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $safe = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($orig, PATHINFO_FILENAME));
            $fileName = $userId . '_' . time() . '_' . $safe . '.' . $ext;
            $dest = $dir . $fileName;
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                $_SESSION['error'] = 'Gagal mengunggah avatar.';
                header('Location: index.php?page=student/profile'); exit;
            }
            $avatarPath = $dest;
        }

        // Update DB
        $fields = [];
        $params = '';
        $values = [];
        if ($bio !== '') { $fields[] = 'bio = ?'; $params .= 's'; $values[] = $bio; }
        if ($avatarPath !== null) { $fields[] = 'avatar = ?'; $params .= 's'; $values[] = $avatarPath; }

        if (!empty($fields)) {
            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?';
            $stmt = mysqli_prepare($config, $sql);
            $params .= 'i'; $values[] = $userId;
            mysqli_stmt_bind_param($stmt, $params, ...$values);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        $_SESSION['success'] = 'Profil diperbarui.';
        header('Location: index.php?page=student/profile'); exit;
    }
}
