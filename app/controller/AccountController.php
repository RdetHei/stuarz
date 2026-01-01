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

    private function allowAdminOrOwner(int $id): bool
    {
        if ($this->isAdmin()) return true;
        return isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === $id;
    }

    private function setFlash(string $message, string $level = 'info'): void
    {
        $_SESSION['flash'] = $message;
        $_SESSION['flash_level'] = $level;
    }

    public function account()
    {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.';
            header('Location: index.php?page=dashboard');
            exit;
        }

        $search = trim((string)($_GET['q'] ?? ''));
        $filterLevel = trim((string)($_GET['filter'] ?? ''));
        
        $users = $this->model->getAll();
        
        if ($filterLevel !== '') {
            $users = array_filter($users, function($u) use ($filterLevel) {
                return ($u['level'] ?? '') === $filterLevel;
            });
            $users = array_values($users);
        }
        
        if ($search !== '') {
            $users = array_filter($users, function($u) use ($search) {
                $username = strtolower($u['username'] ?? '');
                $email = strtolower($u['email'] ?? '');
                $name = strtolower($u['name'] ?? '');
                $searchLower = strtolower($search);
                return strpos($username, $searchLower) !== false || 
                       strpos($email, $searchLower) !== false || 
                       strpos($name, $searchLower) !== false;
            });
            $users = array_values($users);
        }
        
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }
        
        $title = "Daftar Akun - Stuarz";
        $description = "Kelola akun pengguna";
        $content = dirname(__DIR__) . '/views/pages/users/account.php';
        
        if ($ajax) {
            include $content;
        } else {
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
        }
    }

    public function students()
    {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header('Location: index.php?page=dashboard');
            exit;
        }

        $search = trim((string)($_GET['q'] ?? ''));
        
        $users = $this->model->getByLevel('user');
        if (!is_array($users)) {
            $users = [];
        }
        
        if ($search !== '') {
            $users = array_filter($users, function($u) use ($search) {
                $username = strtolower($u['username'] ?? '');
                $email = strtolower($u['email'] ?? '');
                $name = strtolower($u['name'] ?? '');
                $searchLower = strtolower($search);
                return strpos($username, $searchLower) !== false || 
                       strpos($email, $searchLower) !== false || 
                       strpos($name, $searchLower) !== false;
            });
            $users = array_values($users);
        }
        
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }
        
        $title = "Daftar Siswa - Stuarz";
        $description = "Kelola data siswa";
        $content = dirname(__DIR__) . '/views/pages/users/students.php';
        if ($ajax) {
            include $content;
        } else {
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
        }
    }

    public function teachers()
    {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header('Location: index.php?page=dashboard');
            exit;
        }

        $search = trim((string)($_GET['q'] ?? ''));
        
        $users = $this->model->getByLevel('guru');
        if (!is_array($users)) {
            $users = [];
        }
        
        if ($search !== '') {
            $users = array_filter($users, function($u) use ($search) {
                $username = strtolower($u['username'] ?? '');
                $email = strtolower($u['email'] ?? '');
                $name = strtolower($u['name'] ?? '');
                $searchLower = strtolower($search);
                return strpos($username, $searchLower) !== false || 
                       strpos($email, $searchLower) !== false || 
                       strpos($name, $searchLower) !== false;
            });
            $users = array_values($users);
        }
        
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }
        
        $title = "Daftar Guru - Stuarz";
        $description = "Kelola data guru";
        $content = dirname(__DIR__) . '/views/pages/users/teachers.php';
        if ($ajax) {
            include $content;
        } else {
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
        }
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header('Location: index.php?page=account');
            exit;
        }
        $title = "Buat Akun Baru - Stuarz";
        $description = "Buat akun pengguna baru";
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

        $bannerUrl = 'assets/default-banner.png';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $uploadB = $_FILES['banner'];
            $allowed = ['image/jpeg','image/png','image/webp'];
            if (!in_array($uploadB['type'], $allowed, true) || $uploadB['size'] > 5 * 1024 * 1024) {
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
        if ($ok) {
            global $config;
            require_once dirname(__DIR__) . '/helpers/notifier.php';
            $newId = mysqli_insert_id($config);
            $uid = $_SESSION['user']['id'] ?? 0;
            notify_event($config, 'create', 'user', $newId, $uid, "Akun dibuat: {$username}", 'index.php?page=edit_user&id=' . $newId);
        }
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

        @file_put_contents(__DIR__ . '/../../logs/account_edit_debug.log', date('c') . " REQUEST id={$id} by_session=" . (int)($_SESSION['user']['id'] ?? 0) . PHP_EOL, FILE_APPEND);

        if (!$this->allowAdminOrOwner($id)) {
            $_SESSION['flash'] = 'Akses ditolak.';
            header("Location: index.php?page=account");
            exit;
        }

        $user = $this->model->getUserById($id);

        @file_put_contents(__DIR__ . '/../../logs/account_edit_debug.log', date('c') . " DB returned id=" . ($user['id'] ?? 'null') . " username=" . ($user['username'] ?? 'null') . PHP_EOL, FILE_APPEND);

        if (!$user) {
            $_SESSION['flash'] = 'Pengguna tidak ditemukan.';
            header("Location: index.php?page=account");
            exit;
        }

        $returnTo = 'account';
        if (!empty($_GET['from'])) {
            $candidate = trim($_GET['from']);
            $allowed = ['profile', 'account', 'students', 'teachers'];
            if (in_array($candidate, $allowed, true)) $returnTo = $candidate;
        } elseif (!empty($_SERVER['HTTP_REFERER'])) {
            $ref = $_SERVER['HTTP_REFERER'];
            $parts = parse_url($ref);
            if (!empty($parts['query'])) {
                parse_str($parts['query'], $qs);
                if (!empty($qs['page']) && in_array($qs['page'], ['profile','account','students','teachers'], true)) {
                    $returnTo = $qs['page'];
                }
            }
        } else {
            if (isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === (int)$id) {
                $returnTo = 'profile';
            }
        }

        $title = "Edit Akun - Stuarz";
        $description = "Edit data pengguna";
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

        $existing = $this->model->getUserById($id);
        if (!$existing) {
            $this->setFlash('Pengguna tidak ditemukan.', 'danger');
            header('Location: index.php?page=account');
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');

        $level = $existing['level'] ?? 'user';
        if ($this->isAdmin() && isset($_POST['level'])) {
            $level = $_POST['level'] ?? 'user';
        }
        
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

        if (isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === $id) {
            $_SESSION['user'] = $this->model->getUserById($id);
        }

        global $config;
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $uid = $_SESSION['user']['id'] ?? 0;
        notify_event($config, 'update', 'user', $id, $uid, "Akun diperbarui: {$username}", 'index.php?page=edit_user&id=' . $id);

        $this->setFlash('Akun diperbarui.', 'success');

        $allowed = ['profile', 'account', 'students', 'teachers'];
        $returnTo = $_POST['return_to'] ?? '';
        if (in_array($returnTo, $allowed, true)) {
            header('Location: index.php?page=' . $returnTo);
            exit;
        }

        if (isset($_SESSION['user']['id']) && (int)$_SESSION['user']['id'] === $id) {
            header('Location: index.php?page=profile');
            exit;
        }

        header('Location: index.php?page=account');
        exit;
    }

    public function delete()
    {
        header('Content-Type: application/json');
        
        if (!$this->isAdmin()) {
            echo json_encode([
                'success' => false,
                'message' => 'Akses ditolak.'
            ]);
            exit;
        }

        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid.'
            ]);
            exit;
        }

        $userToDelete = $this->model->getUserById($id);
        if (!$userToDelete) {
            echo json_encode([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.'
            ]);
            exit;
        }

        $ok = $this->model->deleteUser($id);

        global $config;
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $uid = $_SESSION['user']['id'] ?? 0;
        if ($ok) {
            $username = $userToDelete['username'] ?? '';
            notify_event($config, 'delete', 'user', $id, $uid, "Akun dihapus: {$username}", null);
            echo json_encode([
                'success' => true,
                'message' => 'Akun berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus akun.'
            ]);
        }
        exit;
    }

    public function getUserProfile()
    {
        header('Content-Type: application/json');
        
        $userId = intval($_GET['id'] ?? $_POST['id'] ?? 0);
        
        if ($userId <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid.'
            ]);
            exit;
        }

        $user = $this->model->getUserById($userId);
        
        if (!$user) {
            echo json_encode([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ]);
            exit;
        }

        $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        if ($baseUrl === '/') $baseUrl = '';
        $avatar = $user['avatar'] ?? '';
        $avatarTrim = ltrim((string)$avatar, '/');
        $isRemote = filter_var($avatarTrim, FILTER_VALIDATE_URL) !== false || preg_match('#^https?://#i', $avatarTrim);
        $projectRoot = dirname(__DIR__, 2);
        $fsCandidate = $projectRoot . '/public/' . $avatarTrim;
        $imgValid = $isRemote || ($avatarTrim !== '' && is_file($fsCandidate));
        
        $avatarUrl = '';
        if ($isRemote) {
            $avatarUrl = $avatarTrim;
        } elseif ($avatarTrim !== '' && $imgValid) {
            $avatarUrl = ($baseUrl !== '' ? $baseUrl . '/' : '/') . $avatarTrim;
        } else {
            $avatarUrl = ($baseUrl !== '' ? $baseUrl . '/' : '/') . 'assets/default-avatar.png';
        }

        $banner = $user['banner'] ?? '';
        $bannerTrim = ltrim((string)$banner, '/');
        $bannerIsRemote = filter_var($bannerTrim, FILTER_VALIDATE_URL) !== false || preg_match('#^https?://#i', $bannerTrim);
        $bannerFsCandidate = $projectRoot . '/public/' . $bannerTrim;
        $bannerValid = $bannerIsRemote || ($bannerTrim !== '' && is_file($bannerFsCandidate));
        
        $bannerUrl = '';
        if ($bannerIsRemote) {
            $bannerUrl = $bannerTrim;
        } elseif ($bannerTrim !== '' && $bannerValid) {
            $bannerUrl = ($baseUrl !== '' ? $baseUrl . '/' : '/') . $bannerTrim;
        }

        $name = $user['name'] ?? $user['username'] ?? 'User';
        $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name), 0, 2));
        if (strlen($initials) < 2) {
            $initials = strtoupper(substr($user['username'] ?? 'U', 0, 2));
        }

        $joinDate = $user['join_date'] ?? '';
        $formattedJoinDate = '';
        if ($joinDate) {
            try {
                $date = new DateTime($joinDate);
                $formattedJoinDate = $date->format('F Y');
            } catch (Exception $e) {
                $formattedJoinDate = $joinDate;
            }
        }

        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'] ?? '',
                'username' => $user['username'] ?? '',
                'email' => $user['email'] ?? '',
                'level' => $user['level'] ?? 'user',
                'role' => $user['role'] ?? '',
                'bio' => $user['bio'] ?? '',
                'phone' => $user['phone'] ?? '',
                'address' => $user['address'] ?? '',
                'class' => $user['class'] ?? '',
                'avatar' => $avatarUrl,
                'hasAvatar' => $imgValid,
                'banner' => $bannerUrl,
                'hasBanner' => $bannerValid,
                'initials' => $initials,
                'joinDate' => $formattedJoinDate,
                'joinDateRaw' => $joinDate
            ]
        ]);
        exit;
    }
}