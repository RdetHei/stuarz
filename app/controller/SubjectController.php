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
        $search = trim((string)($_GET['q'] ?? ''));
        
        $subjects = $this->model->getAll();
        
        if ($search !== '') {
            $subjects = array_filter($subjects, function($s) use ($search) {
                $name = strtolower($s['name'] ?? '');
                $desc = strtolower($s['description'] ?? '');
                $teacherName = strtolower($s['teacher_name'] ?? '');
                $searchLower = strtolower($search);
                return strpos($name, $searchLower) !== false || 
                       strpos($desc, $searchLower) !== false || 
                       strpos($teacherName, $searchLower) !== false;
            });
            $subjects = array_values($subjects);
        }
        
        $totalSubjects = count($subjects);
        
        $totalStudents = $this->db->query("SELECT COUNT(*) as count FROM users WHERE level='siswa'")->fetch_assoc()['count'];
        $totalTeachers = $this->db->query("SELECT COUNT(*) as count FROM users WHERE level='guru'")->fetch_assoc()['count'];
        $totalClasses = $this->db->query("SELECT COUNT(*) as count FROM classes")->fetch_assoc()['count'];
        
        $stats = [
            'subjects' => $totalSubjects,
            'students' => $totalStudents,
            'teachers' => $totalTeachers,
            'classes' => $totalClasses
        ];
        
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }
        
        $content = dirname(__DIR__) . '/views/pages/subjects/index.php';
        if ($ajax) {
            include $content;
        } else {
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
        }
    }
    public function create() {
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

        $teacher = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level = 'guru' OR level = 'admin')")->fetch_assoc();
        if (!$teacher) {
            $_SESSION['flash'] = 'Guru tidak ditemukan!';
            header('Location: index.php?page=subjects/create'); exit;
        }

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

        $teacher = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level = 'guru' OR level = 'admin')")->fetch_assoc();
        if (!$teacher) {
            $_SESSION['flash'] = 'Guru tidak ditemukan!';
            header('Location: index.php?page=subjects/edit&id='.$id); exit;
        }

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
        header('Content-Type: application/json');
        
        $id = intval($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid.'
            ]);
            exit;
        }
        
        $ok = $this->model->delete($id);
        
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus mata pelajaran.'
            ]);
        }
        exit;
    }
}