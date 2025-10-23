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
        $content = dirname(__DIR__) . '/views/pages/announcements/list.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/announcements/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        try {
            $data = [
                'created_by' => $_SESSION['user_id'] ?? 0, // or however you get the user ID
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'class_id' => $_POST['class_id'] ?? null,
                'photo' => $_POST['photo'] ?? '' // handle file upload separately if needed
            ];

            if (empty($data['title'])) {
                throw new \Exception("Title is required");
            }

            $result = $this->model->create($data);
            
            if ($result) {
                $_SESSION['success'] = "Announcement created successfully";
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
                    try {
                        $data = [
                            'created_by' => $_SESSION['user_id'] ?? 0, // or however you get the user ID
                            'title' => $_POST['title'] ?? '',
                            'content' => $_POST['content'] ?? '',
                            'class_id' => $_POST['class_id'] ?? null,
                            'photo' => $_POST['photo'] ?? '' // handle file upload separately if needed
                        ];

                        if (empty($data['title'])) {
                            throw new \Exception("Title is required");
                        }

                        $result = $this->model->create($data);
                        
                        if ($result) {
                            $_SESSION['success'] = "Announcement created successfully";
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
