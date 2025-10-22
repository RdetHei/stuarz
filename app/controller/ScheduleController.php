<?php
require_once __DIR__ . '/../model/ScheduleModel.php';
class ScheduleController {
    private $db;
    private $model;
    private $allowedDays = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    public function __construct($db = null) {
        // fallback to global $config when router instantiates without args
        if ($db === null) {
            global $config;
            $db = $config ?? null;
        }
        $this->db = $db;
        $this->model = new ScheduleModel($this->db);
    }

    public function index() {
        $filters = [];
        if (!empty($_GET['class_id'])) $filters['class_id'] = intval($_GET['class_id']);
        if (!empty($_GET['teacher_id'])) $filters['teacher_id'] = intval($_GET['teacher_id']);
    $schedules = $this->model->getAll($filters);
    $content = dirname(__DIR__) . '/views/pages/schedule/schedule.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $mode = 'create';
        $item = null;
        $days = $this->allowedDays;
    // load helper lists
    $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
    $subjects = $this->db->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC) ?? [];
    $teachers = $this->db->query("SELECT id, name FROM users WHERE level='teacher'")->fetch_all(MYSQLI_ASSOC) ?? [];
    $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $data = [
            'class' => $_POST['class'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'teacher_id' => $_POST['teacher_id'] ?? 0,
            'class_id' => $_POST['class_id'] ?? 0,
            'day' => $_POST['day'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
        ];
        if (!in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid';
            header('Location: /schedule/create'); exit;
        }
        $res = $this->model->create($data);
        if (is_array($res) && isset($res['error'])) {
            $_SESSION['error'] = $res['error'];
            header('Location: /schedule/create'); exit;
        }
        header('Location: index.php?page=schedule'); exit;
    }

    public function edit($id) {
        $mode = 'edit';
        $item = $this->model->getById($id);
        if (!$item) { header('Location: /schedule'); exit; }
        $days = $this->allowedDays;
    $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
    $subjects = $this->db->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC) ?? [];
    $teachers = $this->db->query("SELECT id, name FROM users WHERE level='teacher'")->fetch_all(MYSQLI_ASSOC) ?? [];
    $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update($id) {
        $data = [
            'class' => $_POST['class'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'teacher_id' => $_POST['teacher_id'] ?? 0,
            'class_id' => $_POST['class_id'] ?? 0,
            'day' => $_POST['day'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
        ];
        if (!in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid';
            header("Location: /schedule/edit/$id"); exit;
        }
        $res = $this->model->update($id, $data);
        if (is_array($res) && isset($res['error'])) {
            $_SESSION['error'] = $res['error'];
            header("Location: /schedule/edit/$id"); exit;
        }
        header('Location: index.php?page=schedule'); exit;
    }

    public function delete($id) {
        $this->model->delete($id);
        header('Location: index.php?page=schedule'); exit;
    }
}