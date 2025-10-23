<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
class TaskController {
    private $model;
    public function __construct() {
        global $config;
        $this->model = new TasksCompletedModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public function index() {
        $tasks = $this->model->getAll();
    $content = dirname(__DIR__) . '/views/pages/tasks/index.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function create() {
    $content = dirname(__DIR__) . '/views/pages/tasks/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function store() {
        try {
            $data = [
                'user_id' => $_SESSION['user_id'] ?? 0,
                'class_id' => $_POST['class_id'] ?? 0,
                'task_name' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'file_path' => ''
            ];

            // Handle file upload if exists
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/tasks/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['file']['name']);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
                    $data['file_path'] = $filePath;
                }
            }

            $result = $this->model->create($data);
            
            if ($result) {
                $_SESSION['success'] = "Task submitted successfully";
            } else {
                $_SESSION['error'] = "Failed to submit task";
            }
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: index.php?page=tasks');
        exit;
    }
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $task = $this->model->getById($id);
        $content = dirname(__DIR__) . '/../views/pages/tasks/form.php';
        include dirname(__DIR__) . '/../views/layouts/dLayout.php';
    }
    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => trim($_POST['status'] ?? ''),
            'deadline' => trim($_POST['deadline'] ?? ''),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'subject_id' => intval($_POST['subject_id'] ?? 0)
        ];
        if ($data['title'] === '' || $data['description'] === '' || $data['status'] === '' || $data['deadline'] === '' || !$data['class_id'] || !$data['subject_id']) {
            $_SESSION['flash'] = 'Semua field wajib diisi!';
            header('Location: index.php?page=tasks/edit&id='.$id); exit;
        }
        $ok = $this->model->update($id, $data);
        $_SESSION['flash'] = $ok ? 'Tugas berhasil diupdate.' : 'Gagal update tugas.';
        header('Location: index.php?page=tasks'); exit;
    }
    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Tugas dihapus.' : 'Gagal hapus tugas.';
        header('Location: index.php?page=tasks'); exit;
    }
}
