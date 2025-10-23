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
        // Get list of classes
        $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
        $students = [];
        $attendance = [];
        
        if (!empty($_GET['class_id']) && !empty($_GET['date'])) {
            $cid = intval($_GET['class_id']);
            $date = $this->db->real_escape_string($_GET['date']);
            
            // Get students with their attendance status
            $sql = "SELECT 
                    u.id, 
                    u.username,
                    u.email,
                    COALESCE(a.status, 'belum') as status,
                    a.notes
                FROM class_members cm 
                JOIN users u ON cm.user_id = u.id 
                LEFT JOIN attendance a ON a.user_id = u.id 
                    AND a.class_id = cm.class_id 
                    AND a.date = ?
                WHERE cm.class_id = ? 
                AND cm.role = 'member'
                ORDER BY u.username";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('si', $date, $cid);
            $stmt->execute();
            $result = $stmt->get_result();
            $students = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
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
            $notes = $this->db->real_escape_string($_POST['notes'][$i] ?? '');
            $this->model->store([
                'user_id' => $uid,
                'date' => $date,
                'status' => $status,
                'class_id' => $class_id,
                'notes' => $notes
            ]);
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