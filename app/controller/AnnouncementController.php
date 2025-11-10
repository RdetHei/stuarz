<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/AnnouncementModel.php';
require_once dirname(__DIR__) . '/helpers/notifier.php';

class AnnouncementController {
    private $model;
    public function __construct() {
        global $config;
        $this->model = new AnnouncementModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $announcements = $this->model->getAll();
        $content = dirname(__DIR__) . '/views/pages/announcements/list.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/announcements/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        try {
            global $config;
            $created_by = $_SESSION['user_id'] ?? 0;
            $title = trim($_POST['title'] ?? '');
            $contentText = trim($_POST['content'] ?? '');
            $photo = $_POST['photo'] ?? '';

            // Allow class_id to be optional / nullable
            $class_id = null;
            if (isset($_POST['class_id']) && $_POST['class_id'] !== '') {
                $class_id = intval($_POST['class_id']);
            }

            if ($title === '') {
                throw new \Exception("Title is required");
            }

            $data = [
                'created_by' => $created_by,
                'title' => $title,
                'content' => $contentText,
                'class_id' => $class_id, // may be null
                'photo' => $photo
            ];

            $result = $this->model->create($data);

            if ($result) {
                $_SESSION['success'] = "Announcement created successfully";
                // record notification
                $insertId = mysqli_insert_id($config);
                $uid = $_SESSION['user']['id'] ?? 0;
                notify_event($config, 'create', 'announcement', $insertId, $uid, "Pengumuman dibuat: {$title}", 'index.php?page=announcement_show&id=' . $insertId);
            } else {
                $_SESSION['error'] = "Failed to create announcement";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?page=announcement');
        exit;
    }

    public function show() {
        $id = intval($_GET['id'] ?? 0);
        $announcement = $this->model->getById($id);

        if (!$announcement) {
            $_SESSION['error'] = "Announcement not found";
            header('Location: index.php?page=announcement');
            exit;
        }

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

        $_SESSION['flash'] = $ok ? 'Komentar ditambah.' : 'Gagal menambah komentar.';
        header('Location: index.php?page=announcement_show&id=' . $announcement_id);
        exit;
    }
}