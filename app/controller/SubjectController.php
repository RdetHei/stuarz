<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/SubjectsModel.php';
class SubjectController {
    private $model;
    private $db;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new SubjectsModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public function index() {
        $subjects = $this->model->getAll();
    $content = dirname(__DIR__) . '/views/pages/subjects/index.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function create() {
        // Get list of teachers
        $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin'")->fetch_all(MYSQLI_ASSOC);
        $content = dirname(__DIR__) . '/views/pages/subjects/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function store() {
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $teacherId = intval($_POST['teacher_id'] ?? 0);
        
        if ($name === '' || $desc === '' || $teacherId === 0) {
            $_SESSION['flash'] = 'Nama, deskripsi, dan guru wajib diisi!';
            header('Location: index.php?page=subjects/create'); exit;
        }

        // Check if teacher is available
        $teacher = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level = 'guru' OR level = 'admin')")->fetch_assoc();
        if (!$teacher) {
            $_SESSION['flash'] = 'Guru tidak ditemukan!';
            header('Location: index.php?page=subjects/create'); exit;
        }

        // Check if teacher already has a subject
        $existing = $this->db->query("SELECT id FROM subjects WHERE teacher_id = $teacherId")->fetch_assoc();
        if ($existing) {
            $_SESSION['flash'] = 'Guru ini sudah memiliki mata pelajaran lain!';
            header('Location: index.php?page=subjects/create'); exit;
        }

        $ok = $this->model->create([
            'name' => $name,
            'description' => $desc,
            'teacher_id' => $teacherId
        ]);
        $_SESSION['flash'] = $ok ? 'Subject berhasil ditambah.' : 'Gagal menambah subject.';
        header('Location: index.php?page=subjects'); exit;
    }
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $subject = $this->model->getById($id);
        // Get list of teachers
        $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin'")->fetch_all(MYSQLI_ASSOC);
        $content = dirname(__DIR__) . '/views/pages/subjects/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $teacherId = intval($_POST['teacher_id'] ?? 0);
        
        if ($name === '' || $desc === '' || $teacherId === 0) {
            $_SESSION['flash'] = 'Nama, deskripsi, dan guru wajib diisi!';
            header('Location: index.php?page=subjects/edit&id='.$id); exit;
        }

        // Check if teacher is available
        $teacher = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level = 'guru' OR level = 'admin')")->fetch_assoc();
        if (!$teacher) {
            $_SESSION['flash'] = 'Guru tidak ditemukan!';
            header('Location: index.php?page=subjects/edit&id='.$id); exit;
        }

        // Check if teacher already has a subject (excluding current subject)
        $existing = $this->db->query("SELECT id FROM subjects WHERE teacher_id = $teacherId AND id != $id")->fetch_assoc();
        if ($existing) {
            $_SESSION['flash'] = 'Guru ini sudah memiliki mata pelajaran lain!';
            header('Location: index.php?page=subjects/edit&id='.$id); exit;
        }

        $ok = $this->model->update($id, [
            'name' => $name,
            'description' => $desc,
            'teacher_id' => $teacherId
        ]);
        $_SESSION['flash'] = $ok ? 'Subject berhasil diupdate.' : 'Gagal update subject.';
        header('Location: index.php?page=subjects'); exit;
    }
    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Subject dihapus.' : 'Gagal hapus subject.';
        header('Location: index.php?page=subjects'); exit;
    }
}
