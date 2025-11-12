<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/users.php';

class AccountController
{
    private $model;
    public function __construct()
    {
        global $config;
        $this->model = new users($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['user']['level']) && $_SESSION['user']['level'] === 'admin';
    }

    // allow admin or the owner of the id
    private function allowAdminOrOwner(int $id): bool
    {
        if ($this->isAdmin()) return true;
        return isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === $id;
    }

    // helper to set flash message with optional level; keeps backward compatibility
    private function setFlash(string $message, string $level = 'info'): void
    {
        // many places in the code expect $_SESSION['flash'] to be a string,
        // so set that for compatibility and also store a level.
        $_SESSION['flash'] = $message;
        $_SESSION['flash_level'] = $level;
    }

    public function account()
    {
        $users = $this->model->getAll();
        $content = dirname(__DIR__) . '/views/pages/users/account.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    // show users with level = 'user' (students page)
    public function students()
    {
        // Use DB-level filter for efficiency
        $users = $this->model->getByLevel('user');
        $content = dirname(__DIR__) . '/views/pages/users/students.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    // show users with level = 'teacher' (teachers page)
    public function teachers()
    {
        // Use DB-level filter for efficiency
        $users = $this->model->getByLevel('guru');
        $content = dirname(__DIR__) . '/views/pages/users/teachers.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header('Location: index.php?page=account');
            exit;
        }
        $content = dirname(__DIR__) . '/views/pages/users/create_user.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store()
    {
        if (!$this->isAdmin()) {
            $this->setFlash('Akses ditolak.', 'danger');
            header('Location: index.php?page=account');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $level    = $_POST['level'] ?? 'user';
        $role     = trim($_POST['role'] ?? '');
        $password = $_POST['password'] ?? '';

        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $bio      = trim($_POST['bio'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $this->setFlash('Isi semua kolom required.', 'warning');
            header('Location: index.php?page=create_user');
            exit;
        }

        $avatarUrl = 'assets/default-avatar.png';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $upload = $_FILES['avatar'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($upload['type'], $allowed, true) || $upload['size'] > 2 * 1024 * 1024) {
                $this->setFlash('Avatar harus berupa jpg/png/webp dan <= 2MB.', 'warning');
                header('Location: index.php?page=create_user');
                exit;
            }
            $ext = pathinfo($upload['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $fname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $dest = $uploadDir . $fname;
            if (move_uploaded_file($upload['tmp_name'], $dest)) {
                $avatarUrl = 'uploads/avatars/' . $fname;
            }
        }

        // NEW: banner upload (same validation, different folder & column)
        $bannerUrl = 'assets/default-banner.png';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $uploadB = $_FILES['banner'];
            $allowed = ['image/jpeg','image/png','image/webp'];
            if (!in_array($uploadB['type'], $allowed, true) || $uploadB['size'] > 5 * 1024 * 1024) {
                // allow larger size for banners (5MB)
                $this->setFlash('Banner harus berupa jpg/png/webp dan <= 5MB.', 'warning');
                header('Location: index.php?page=create_user');
                exit;
            }
            $extB = pathinfo($uploadB['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $fnameB = time() . '_banner_' . bin2hex(random_bytes(6)) . '.' . $extB;
            $uploadDirB = dirname(__DIR__, 2) . '/public/uploads/banners/';
            if (!is_dir($uploadDirB)) mkdir($uploadDirB, 0755, true);
            $destB = $uploadDirB . $fnameB;
            if (move_uploaded_file($uploadB['tmp_name'], $destB)) {
                $bannerUrl = 'uploads/banners/' . $fnameB;
            }
        }

        $data = [
            'username'   => $username,
            'name'       => $name,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'level'      => $level,
            'role'       => $role,
            'avatar'     => $avatarUrl,
            'banner'     => $bannerUrl,
            'join_date'  => date('Y-m-d H:i:s'),
            'phone'      => $phone,
            'address'    => $address,
            'bio'        => $bio,
        ];

        $ok = $this->model->createUser($data);
        $this->setFlash($ok ? 'Akun berhasil dibuat.' : 'Gagal membuat akun.', $ok ? 'success' : 'danger');
        header('Location: index.php?page=account');
        exit;
    }

    public function edit()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID tidak valid.';
            header("Location: index.php?page=account");
            exit;
        }

        // debug: tulis id yang diminta
        @file_put_contents(__DIR__ . '/../../logs/account_edit_debug.log', date('c') . " REQUEST id={$id} by_session=" . (int)($_SESSION['user']['id'] ?? 0) . PHP_EOL, FILE_APPEND);

        if (!$this->allowAdminOrOwner($id)) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header("Location: index.php?page=account");
            exit;
        }

        // Ambil user berdasarkan id yang diminta
        $user = $this->model->getUserById($id);

        // debug: tulis hasil query (id atau null)
        @file_put_contents(__DIR__ . '/../../logs/account_edit_debug.log', date('c') . " DB returned id=" . ($user['id'] ?? 'null') . " username=" . ($user['username'] ?? 'null') . PHP_EOL, FILE_APPEND);

        if (!$user) {
            $_SESSION['flash'] = 'Pengguna tidak ditemukan.';
            header("Location: index.php?page=account");
            exit;
        }

        // pastikan $user yang di-include view berasal dari sini (tidak ditimpa di view)
        $content = dirname(__DIR__) . '/views/pages/users/edit_user.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update()
    {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->setFlash('ID tidak valid.', 'danger');
            header('Location: index.php?page=account');
            exit;
        }

        if (!$this->allowAdminOrOwner($id)) {
            $this->setFlash('Akses ditolak.', 'danger');
            header('Location: index.php?page=account');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $level    = $_POST['level'] ?? 'user';
    $password = $_POST['password'] ?? '';
    $role     = trim($_POST['role'] ?? '');

        $phone    = trim($_POST['phone'] ?? '');
        $address  = trim($_POST['address'] ?? '');
        $bio      = trim($_POST['bio'] ?? '');

        if ($username === '' || $email === '') {
            $this->setFlash('Data tidak lengkap.', 'warning');
            header("Location: index.php?page=edit_user&id={$id}");
            exit;
        }

        $existing = $this->model->getUserById($id);
        if (!$existing) {
            $this->setFlash('Pengguna tidak ditemukan.', 'danger');
            header('Location: index.php?page=account');
            exit;
        }

        $avatarUrl = $existing['avatar'] ?? 'assets/default-avatar.png';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $upload = $_FILES['avatar'];
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($upload['type'], $allowed, true) || $upload['size'] > 2 * 1024 * 1024) {
                $this->setFlash('Avatar harus berupa jpg/png/webp dan <= 2MB.', 'warning');
                header("Location: index.php?page=edit_user&id={$id}");
                exit;
            }
            $ext = pathinfo($upload['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $fname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $uploadDir = dirname(__DIR__, 2) . '/public/uploads/avatars/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $dest = $uploadDir . $fname;
            if (move_uploaded_file($upload['tmp_name'], $dest)) {
                $newUrl = 'uploads/avatars/' . $fname;
                // remove old file when in uploads
                if (!empty($avatarUrl) && (strpos($avatarUrl, 'uploads/avatars/') === 0 || strpos($avatarUrl, '/uploads/avatars/') === 0)) {
                    $oldRel = ltrim($avatarUrl, '/');
                    $oldFile = dirname(__DIR__, 2) . '/public/' . $oldRel;
                    if (is_file($oldFile)) @unlink($oldFile);
                }
                $avatarUrl = $newUrl;
            } else {
                $this->setFlash('Upload avatar gagal.', 'danger');
                header("Location: index.php?page=edit_user&id={$id}");
                exit;
            }
        }

        // NEW: banner update handling
        $bannerUrl = $existing['banner'] ?? 'assets/default-banner.png';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $uploadB = $_FILES['banner'];
            $allowed = ['image/jpeg','image/png','image/webp'];
            if (!in_array($uploadB['type'], $allowed, true) || $uploadB['size'] > 5 * 1024 * 1024) {
                $this->setFlash('Banner harus berupa jpg/png/webp dan <= 5MB.', 'warning');
                header("Location: index.php?page=edit_user&id={$id}");
                exit;
            }
            $extB = pathinfo($uploadB['name'], PATHINFO_EXTENSION) ?: 'jpg';
            $fnameB = time() . '_banner_' . bin2hex(random_bytes(6)) . '.' . $extB;
            $uploadDirB = dirname(__DIR__, 2) . '/public/uploads/banners/';
            if (!is_dir($uploadDirB)) mkdir($uploadDirB, 0755, true);
            $destB = $uploadDirB . $fnameB;
            if (move_uploaded_file($uploadB['tmp_name'], $destB)) {
                $newBannerRel = 'uploads/banners/' . $fnameB;
                // remove old banner file when stored in uploads
                if (!empty($bannerUrl) && (strpos($bannerUrl, 'uploads/banners/') === 0 || strpos($bannerUrl, '/uploads/banners/') === 0)) {
                    $oldRelB = ltrim($bannerUrl, '/');
                    $oldFileB = dirname(__DIR__, 2) . '/public/' . $oldRelB;
                    if (is_file($oldFileB)) @unlink($oldFileB);
                }
                $bannerUrl = $newBannerRel;
            } else {
                $this->setFlash('Upload banner gagal.', 'danger');
                header("Location: index.php?page=edit_user&id={$id}");
                exit;
            }
        }

        $data = [
            'username' => $username,
            'name'     => $name,
            'email'    => $email,
            'password' => $password ? password_hash($password, PASSWORD_DEFAULT) : '',
            'level'    => $level,
            'role'     => $role,
            'avatar'   => $avatarUrl,
            'banner'   => $bannerUrl,
            'phone'    => $phone,
            'address'  => $address,
            'bio'      => $bio,
        ];

        $ok = $this->model->updateUser($id, $data);
        if (!$ok) {
            $this->setFlash('Gagal memperbarui akun.', 'danger');
            header("Location: index.php?page=edit_user&id={$id}");
            exit;
        }

        // if editing own account, refresh session user
        if (isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === $id) {
            $_SESSION['user'] = $this->model->getUserById($id);
        }

        $this->setFlash('Akun diperbarui.', 'success');
        header('Location: index.php?page=account');
        exit;
    }

    public function delete()
    {
        // only admin can delete arbitrary users
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header('Location: index.php?page=account');
            exit;
        }

        // accept id from GET for confirmation step, POST for actual delete
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID tidak valid.';
            header('Location: index.php?page=account');
            exit;
        }

        // If request is GET or confirmation not present, show confirmation card
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'GET' || !isset($_POST['confirm'])) {
            // load user to show info
            $userToDelete = $this->model->getUserById($id);
            if (!$userToDelete) {
                $_SESSION['flash'] = 'Pengguna tidak ditemukan.';
                header('Location: index.php?page=account');
                exit;
            }
            // Render confirmation card view
            $content = dirname(__DIR__) . '/views/pages/users/confirm_delete.php';
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
            exit;
        }

        // Otherwise: POST with confirmation -> perform delete
        // ensure confirm value is affirmative
        if (isset($_POST['confirm']) && (string)$_POST['confirm'] === '1') {
            // fetch user for message
            $userToDelete = $this->model->getUserById($id);
            $ok = $this->model->deleteUser($id);

            // notify and set flash
            global $config;
            require_once dirname(__DIR__) . '/helpers/notifier.php';
            $uid = $_SESSION['user']['id'] ?? 0;
            if ($ok) {
                $username = $userToDelete['username'] ?? '';
                notify_event($config, 'delete', 'user', $id, $uid, "Akun dihapus: {$username}", null);
            }

            $_SESSION['flash'] = $ok ? 'Akun dihapus.' : 'Gagal menghapus akun.';
            header('Location: index.php?page=account');
            exit;
        }

        // fallback
        $_SESSION['flash'] = 'Aksi dibatalkan.';
        header('Location: index.php?page=account');
        exit;
    }
}
