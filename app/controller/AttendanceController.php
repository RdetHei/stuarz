<?php
require_once __DIR__ . '/../model/AttendanceModel.php';
class AttendanceController {
    private $db;
    private $model;
    public function __construct($db = null) {
        // fallback ketika Router memanggil tanpa argumen
        if ($db === null) {
            global $config;
            $db = $config ?? null;
        }
        $this->db = $db;
        $this->model = new AttendanceModel($this->db);
    }

    public function index() {
        $filters = [];
        if (!empty($_GET['class_id'])) $filters['class_id'] = intval($_GET['class_id']);
        if (!empty($_GET['date'])) $filters['date'] = $this->db->real_escape_string($_GET['date']);
    $records = $this->model->getAll($filters);
    $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
    $content = dirname(__DIR__) . '/views/pages/attendance/index.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function mark() {
        // teacher marks; show list of students in a class & date
        $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
        $students = [];
        if (!empty($_GET['class_id'])) {
            $cid = intval($_GET['class_id']);
            $students = $this->db->query("SELECT id, name FROM users WHERE class_id=$cid AND role='student'")->fetch_all(MYSQLI_ASSOC) ?? [];
        }
    $date = $_GET['date'] ?? date('Y-m-d');
    $content = dirname(__DIR__) . '/views/pages/attendance/form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        // expects user_id[], status[], class_id, date
        $date = $_POST['date'] ?? date('Y-m-d');
        $class_id = intval($_POST['class_id'] ?? 0);
        $user_ids = $_POST['user_id'] ?? [];
        $statuses = $_POST['status'] ?? [];
        foreach ($user_ids as $i => $uid) {
            $uid = intval($uid);
            $status = $this->db->real_escape_string($statuses[$i] ?? 'Absen');
            $this->model->store(['user_id'=>$uid,'date'=>$date,'status'=>$status,'class_id'=>$class_id]);
        }
        header('Location: index.php?page=attendance'); exit;
    }

    public function report() {
        $user_id = intval($_GET['user_id'] ?? 0);
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
    $report = $user_id ? $this->model->reportByUser($user_id, $from, $to) : null;
    $students = $this->db->query("SELECT id, name FROM users WHERE role='student'")->fetch_all(MYSQLI_ASSOC) ?? [];
    $content = dirname(__DIR__) . '/views/pages/attendance/report.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
}