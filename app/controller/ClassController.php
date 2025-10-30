<?php
require_once dirname(__DIR__) . '/model/ClassModel.php';
require_once dirname(__DIR__) . '/config/config.php';

class ClassController {
    private $model;
    private $db;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new ClassModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $classes = $this->model->getAll();
        
        // Get statistics
        $totalClasses = count($classes);
        // safe student count (class_members may be missing or empty)
        $totalStudents = 0;
        $res = $this->db->query("SELECT COUNT(DISTINCT user_id) as count FROM class_members");
        if ($res) {
            $row = $res->fetch_assoc();
            $totalStudents = intval($row['count'] ?? 0);
        }
        // Some schemas don't have an `is_active` column — fallback to total classes
        $totalActiveClasses = $totalClasses;
        $averageStudentsPerClass = $totalClasses > 0 ? round($totalStudents / $totalClasses) : 0;
        
        $stats = [
            'classes' => $totalClasses,
            'students' => $totalStudents,
            'activeClasses' => $totalActiveClasses,
            'averageStudents' => $averageStudentsPerClass
        ];
        
        $content = dirname(__DIR__) . '/views/pages/classes/class_list.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/classes/class_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'code' => trim($_POST['code'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'created_by' => $_SESSION['user']['id'] ?? 0
        ];
        $errors = $this->model->validate($data);
        if ($errors) {
            $_SESSION['flash'] = implode(' ', $errors);
            header('Location: index.php?page=class_create');
            exit;
        }
        $ok = $this->model->create($data);
        $_SESSION['flash'] = $ok ? 'Kelas berhasil ditambah.' : 'Gagal menambah kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $class = $this->model->getById($id);
        if (!$class) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }
        $content = dirname(__DIR__) . '/views/pages/classes/class_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'code' => trim($_POST['code'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        $errors = $this->model->validate($data, true, $id);
        if ($errors) {
            $_SESSION['flash'] = implode(' ', $errors);
            header('Location: index.php?page=class_edit&id=' . $id);
            exit;
        }
        $ok = $this->model->update($id, $data);
        $_SESSION['flash'] = $ok ? 'Kelas diperbarui.' : 'Gagal memperbarui kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Kelas dihapus.' : 'Gagal menghapus kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    // Anggota kelas
    public function members() {
        $class_id = intval($_GET['id'] ?? 0);
        $class = $this->model->getById($class_id);
        $members = $this->model->getMembers($class_id);
        $content = dirname(__DIR__) . '/views/pages/classes/class_members.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function addMember() {
        try {
            $classId = intval($_POST['class_id'] ?? 0);
            $userId = intval($_POST['user_id'] ?? 0);
            $role = $_POST['role'] ?? 'member';

            if (!$classId || !$userId) {
                throw new \Exception("Invalid class or user ID");
            }

            $result = $this->model->addMember($classId, $userId, $role);
            
            if ($result) {
                $_SESSION['success'] = "Member added successfully";
            } else {
                $_SESSION['error'] = "Failed to add member";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function removeMember() {
        $class_id = intval($_POST['class_id'] ?? 0);
        $user_id = intval($_POST['user_id'] ?? 0);
        $ok = $this->model->removeMember($class_id, $user_id);
        $_SESSION['flash'] = $ok ? 'Anggota dihapus.' : 'Gagal menghapus anggota.';
        header('Location: index.php?page=class_members&id=' . $class_id);
        exit;
    }
}