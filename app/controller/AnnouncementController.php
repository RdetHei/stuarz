<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/AnnouncementModel.php';
require_once dirname(__DIR__) . '/helpers/notifier.php';

class AnnouncementController {
    private $model;
    private string $uploadDir;
    private string $publicDir;

    public function __construct() {
        global $config;
        $this->model = new AnnouncementModel($config);
        $this->publicDir = dirname(__DIR__, 2) . '/public/';
        $this->uploadDir = $this->publicDir . 'uploads/announcements/';
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $announcements = $this->model->getAll();

        // Normalize fields so views can rely on `username` and `avatar` keys
        // Also prefix relative upload paths with the base URL so JSON/photo srcs resolve correctly
        $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        if ($baseUrl === '/') $baseUrl = '';
        $prefix = ($baseUrl ? $baseUrl . '/' : '');

        if (!empty($announcements) && is_array($announcements)) {
            foreach ($announcements as &$a) {
                $a['username'] = $a['creator'] ?? $a['username'] ?? 'Anonim';
                $avatar = $a['creator_avatar'] ?? $a['avatar'] ?? '';
                $a['avatar'] = $avatar ? $prefix . ltrim($avatar, '/') : '';
                $a['photo'] = !empty($a['photo']) ? $prefix . ltrim($a['photo'], '/') : '';
            }
            unset($a);
        }

        $content = dirname(__DIR__) . '/views/pages/announcements/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/announcements/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $photo = '';
        $uploadedNewPhoto = false;
        try {
            global $config;
            $created_by = $this->getCurrentUserId();
            $title = trim($_POST['title'] ?? '');
            $contentText = trim($_POST['content'] ?? '');
            $photo = $this->handlePhotoUpload(null, $uploadedNewPhoto);


            if ($title === '') {
                throw new \Exception("Title is required");
            }
            if ($contentText === '') {
                throw new \Exception("Content is required");
            }
            if ($created_by <= 0) {
                throw new \Exception("User is not authenticated");
            }

            $data = [
                'created_by' => $created_by,
                'title' => $title,
                'content' => $contentText,
                'photo' => $photo
            ];

            $result = $this->model->create($data);

            if ($result) {
                $_SESSION['flash'] = "Announcement created successfully";
                $_SESSION['flash_level'] = 'success';
                // record notification
                $insertId = mysqli_insert_id($config);
                $uid = $_SESSION['user']['id'] ?? 0;
                notify_event($config, 'create', 'announcement', $insertId, $uid, "Pengumuman dibuat: {$title}", 'index.php?page=announcement_show&id=' . $insertId);
            } else {
                if ($uploadedNewPhoto && $photo) {
                    $this->deletePhotoFile($photo);
                }
                $_SESSION['flash'] = "Failed to create announcement";
                $_SESSION['flash_level'] = 'danger';
            }
        } catch (\Exception $e) {
            if ($uploadedNewPhoto && !empty($photo)) {
                $this->deletePhotoFile($photo);
            }
            $_SESSION['flash'] = $e->getMessage();
            $_SESSION['flash_level'] = 'danger';
        }

        header('Location: index.php?page=announcement');
        exit;
    }

    public function show() {
        $id = intval($_GET['id'] ?? 0);
        $announcement = $this->model->getById($id);

        if (!$announcement) {
            $_SESSION['flash'] = "Announcement not found";
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $comments = $this->model->getCommentsByAnnouncementId($id);

        $content = dirname(__DIR__) . '/views/pages/announcements/detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function addComment() {
        global $config;
        $announcement_id = intval($_POST['announcement_id'] ?? 0);
        $user_id = $_SESSION['user']['id'] ?? 0;
        $content = trim($_POST['content'] ?? '');
        $ok = false;

        // Basic validation before inserting
        if ($announcement_id > 0 && $user_id > 0 && $content !== '') {
            // Use prepared statement to safely insert the comment.
            $stmt = mysqli_prepare($config, "INSERT INTO announcement_comments (announcement_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "iis", $announcement_id, $user_id, $content);
                if (mysqli_stmt_execute($stmt)) {
                    $ok = true;
                }
                mysqli_stmt_close($stmt);
            }
        }
        // If comment added, notify announcement owner (if not commenter)
        if ($ok) {
            // attempt to find announcement owner and notify
            $announcement = $this->model->getById($announcement_id);
            $ownerId = $announcement['created_by'] ?? null;
            $title = $announcement['title'] ?? '';
            if ($ownerId && (int)$ownerId !== (int)$user_id) {
                require_once dirname(__DIR__) . '/helpers/notifier.php';
                $msg = 'Komentar baru pada: ' . $title;
                notify_event($config, 'create', 'announcement_comment', $announcement_id, $ownerId, $msg, 'index.php?page=announcement_show&id=' . $announcement_id);
            }
        }

        $_SESSION['flash'] = $ok ? 'Komentar ditambah.' : 'Gagal menambah komentar.';
        $_SESSION['flash_level'] = $ok ? 'success' : 'danger';
        header('Location: index.php?page=announcement_show&id=' . $announcement_id);
        exit;
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID pengumuman tidak valid.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $announcement = $this->model->getById($id);
        if (!$announcement) {
            $_SESSION['flash'] = 'Pengumuman tidak ditemukan.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $content = dirname(__DIR__) . '/views/pages/announcements/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update() {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID pengumuman tidak valid.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $existingPhoto = $_POST['photo_existing'] ?? '';
        $uploadedNewPhoto = false;
        $photo = $existingPhoto;
        try {
            global $config;
            $title = trim($_POST['title'] ?? '');
            $contentText = trim($_POST['content'] ?? '');
           

            if ($title === '' || $contentText === '') {
                throw new \Exception('Judul dan isi wajib diisi.');
            }

            $photo = $this->handlePhotoUpload($existingPhoto, $uploadedNewPhoto);

            $updated = $this->model->update($id, [
                'title' => $title,
                'content' => $contentText,
                'photo' => $photo,
            ]);

            if ($updated) {
                if ($uploadedNewPhoto && $existingPhoto && $photo !== $existingPhoto) {
                    $this->deletePhotoFile($existingPhoto);
                }
                $uid = $_SESSION['user']['id'] ?? 0;
                notify_event($config, 'update', 'announcement', $id, $uid, "Pengumuman diperbarui: {$title}", 'index.php?page=announcement_show&id=' . $id);
                $_SESSION['flash'] = 'Pengumuman diperbarui.';
                $_SESSION['flash_level'] = 'success';
            } else {
                if ($uploadedNewPhoto && $photo && $photo !== $existingPhoto) {
                    $this->deletePhotoFile($photo);
                }
                $_SESSION['flash'] = 'Gagal memperbarui pengumuman.';
                $_SESSION['flash_level'] = 'danger';
            }
        } catch (\Exception $e) {
            if ($uploadedNewPhoto && $photo && $photo !== $existingPhoto) {
                $this->deletePhotoFile($photo);
            }
            $_SESSION['flash'] = $e->getMessage();
            $_SESSION['flash_level'] = 'danger';
        }

        header('Location: index.php?page=announcement');
        exit;
    }

    public function delete() {
        $id = intval($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID pengumuman tidak valid.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $announcement = $this->model->getById($id);
        if (!$announcement) {
            $_SESSION['flash'] = 'Pengumuman tidak ditemukan.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        global $config;
        $deleted = $this->model->delete($id);
        if ($deleted) {
            if (!empty($announcement['photo'])) {
                $this->deletePhotoFile($announcement['photo']);
            }
            $uid = $_SESSION['user']['id'] ?? 0;
            notify_event($config, 'delete', 'announcement', $id, $uid, "Pengumuman dihapus: {$announcement['title']}", null);
            $_SESSION['flash'] = 'Pengumuman dihapus.';
            $_SESSION['flash_level'] = 'success';
        } else {
            $_SESSION['flash'] = 'Gagal menghapus pengumuman.';
            $_SESSION['flash_level'] = 'danger';
        }

        header('Location: index.php?page=announcement');
        exit;
    }

    public function adminList() {
        $announcements = $this->model->getAll();

        $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        if ($baseUrl === '/') $baseUrl = '';
        $prefix = ($baseUrl ? $baseUrl . '/' : '');

        if (!empty($announcements) && is_array($announcements)) {
            foreach ($announcements as &$a) {
                $a['username'] = $a['creator'] ?? $a['username'] ?? 'Anonim';
                $avatar = $a['creator_avatar'] ?? $a['avatar'] ?? '';
                $a['avatar'] = $avatar ? $prefix . ltrim($avatar, '/') : '';
                $a['photo'] = !empty($a['photo']) ? $prefix . ltrim($a['photo'], '/') : '';
            }
            unset($a);
        }

        $content = dirname(__DIR__) . '/views/pages/announcements/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function print()
    {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID pengumuman tidak valid.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $announcement = $this->model->getById($id);
        if (!$announcement) {
            $_SESSION['flash'] = 'Pengumuman tidak ditemukan.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=announcement');
            exit;
        }

        $content = dirname(__DIR__) . '/views/pages/announcements/announcement_print.php';
        include dirname(__DIR__) . '/views/layouts/print.php';
    }

    public function uploadAnnouncement() {
        $announcement = null;
        $content = dirname(__DIR__) . '/views/pages/announcements/upload_announcement.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function editAnnouncement() {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash'] = 'ID pengumuman tidak valid.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=admin/announcement');
            exit;
        }

        $announcement = $this->model->getById($id);
        if (!$announcement) {
            $_SESSION['flash'] = 'Pengumuman tidak ditemukan.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=admin/announcement');
            exit;
        }

        $content = dirname(__DIR__) . '/views/pages/announcements/upload_announcement.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    private function getCurrentUserId(): int {
        return (int)($_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0);
    }

    private function handlePhotoUpload(?string $currentPhoto = null, bool &$uploadedNew = false): string {
        $uploadedNew = false;

        if (empty($_FILES['photo']) || $_FILES['photo']['error'] === UPLOAD_ERR_NO_FILE) {
            return $currentPhoto ?? '';
        }

        $file = $_FILES['photo'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Gagal mengunggah foto.');
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            throw new \Exception('Ukuran foto maksimal 5MB.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mime])) {
            throw new \Exception('Format foto tidak didukung.');
        }

        if (!is_dir($this->uploadDir)) {
            if (!mkdir($concurrentDirectory = $this->uploadDir, 0775, true) && !is_dir($concurrentDirectory)) {
                throw new \Exception('Tidak dapat membuat direktori penyimpanan foto.');
            }
        }

        $filename = sprintf('%s_%s.%s', time(), bin2hex(random_bytes(4)), $allowed[$mime]);
        $targetPath = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception('Gagal menyimpan foto.');
        }

        $uploadedNew = true;

        return 'uploads/announcements/' . $filename;
    }

    private function deletePhotoFile(string $relativePath): void {
        $relativePath = ltrim($relativePath, '/');
        $fullPath = $this->publicDir . $relativePath;
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}