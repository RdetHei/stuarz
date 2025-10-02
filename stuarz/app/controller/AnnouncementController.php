<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/AnnouncementModel.php';

class AnnouncementController {
    private $model;
    public function __construct() {
        global $config;
        $this->model = new AnnouncementModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $announcements = $this->model->getAll();
        $content = dirname(__DIR__) . '/views/pages/announcement_list.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/announcement_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'photo' => trim($_POST['photo'] ?? ''),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'created_by' => $_SESSION['user']['id'] ?? 0
        ];
        $ok = $this->model->create($data);
        $_SESSION['flash'] = $ok ? 'Pengumuman berhasil ditambah.' : 'Gagal menambah pengumuman.';
        header('Location: index.php?page=announcement');
        exit;
    }

    public function show() {
        $id = intval($_GET['id'] ?? 0);
        $announcement = $this->model->getById($id);
        $comments = $this->model->getComments($id);
        $content = dirname(__DIR__) . '/views/pages/announcement_detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function addComment() {
        $announcement_id = intval($_POST['announcement_id'] ?? 0);
        $user_id = $_SESSION['user']['id'] ?? 0;
        $content = trim($_POST['content'] ?? '');
        $ok = $this->model->addComment($announcement_id, $user_id, $content);
        $_SESSION['flash'] = $ok ? 'Komentar ditambah.' : 'Gagal menambah komentar.';
        header('Location: index.php?page=announcement_show&id=' . $announcement_id);
        exit;
    }
}
            require_once dirname(__DIR__) . '/config/config.php';
            require_once dirname(__DIR__) . '/model/AnnouncementModel.php';

            class AnnouncementControllers {
                private $model;
                public function __construct() {
                    global $config;
                    $this->model = new AnnouncementModel($config);
                    if (session_status() === PHP_SESSION_NONE) session_start();
                }

                public function index() {
                    $announcements = $this->model->getAll();
                    $content = dirname(__DIR__) . '/views/pages/announcement_list.php';
                    include dirname(__DIR__) . '/views/layouts/dLayout.php';
                }

                public function create() {
                    $content = dirname(__DIR__) . '/views/pages/announcement_form.php';
                    include dirname(__DIR__) . '/views/layouts/dLayout.php';
                }

                public function store() {
                    $data = [
                        'title' => trim($_POST['title'] ?? ''),
                        'content' => trim($_POST['content'] ?? ''),
                        'photo' => trim($_POST['photo'] ?? ''),
                        'class_id' => intval($_POST['class_id'] ?? 0),
                        'created_by' => $_SESSION['user']['id'] ?? 0
                    ];
                    $ok = $this->model->create($data);
                    $_SESSION['flash'] = $ok ? 'Pengumuman berhasil ditambah.' : 'Gagal menambah pengumuman.';
                    header('Location: index.php?page=announcement');
                    exit;
                }

                public function show() {
                    $id = intval($_GET['id'] ?? 0);
                    $announcement = $this->model->getById($id);
                    $comments = $this->model->getComments($id);
                    $content = dirname(__DIR__) . '/views/pages/announcement_detail.php';
                    include dirname(__DIR__) . '/views/layouts/dLayout.php';
                }

                public function addComment() {
                    $announcement_id = intval($_POST['announcement_id'] ?? 0);
                    $user_id = $_SESSION['user']['id'] ?? 0;
                    $content = trim($_POST['content'] ?? '');
                    $ok = $this->model->addComment($announcement_id, $user_id, $content);
                    $_SESSION['flash'] = $ok ? 'Komentar ditambah.' : 'Gagal menambah komentar.';
                    header('Location: index.php?page=announcement_show&id=' . $announcement_id);
                    exit;
                }
            }
