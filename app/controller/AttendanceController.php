<?php
require_once __DIR__ . '/../model/AttendanceModel.php';

class AttendanceController {
    private $db;
    private $model;

    public function __construct($db = null) {
        if ($db === null) {
            global $config;
            $db = $config;
        }
        $this->db = $db;
        $this->model = new AttendanceModel($db);
    }

    public function index() {
        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate = $_GET['end'] ?? date('Y-m-d');
        $filterClass = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;

        $records = $this->model->getFilteredAttendance($startDate, $endDate, $filterClass);
        $classes = $this->model->getClasses();

        $content = dirname(__DIR__) . '/views/pages/attendance/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function checkIn() {
        try {
            $userId = $_SESSION['user_id'] ?? 0;
            $classId = intval($_POST['class_id'] ?? 0);
            if (!$classId) throw new Exception('Pilih kelas terlebih dahulu.');
            $date = date('Y-m-d');
            $time = date('H:i:s');

            $existing = $this->model->getTodayAttendance($userId, $date, $classId);
            if ($existing) throw new Exception('Anda sudah check-in hari ini untuk kelas ini.');

            $status = strtotime($time) > strtotime('08:00:00') ? 'late' : 'present';
            $this->model->insertAttendance($userId, $classId, $date, $time, $status);

            echo json_encode(['success' => true, 'message' => "Check-in berhasil: {$time}"]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function checkOut() {
        try {
            $userId = $_SESSION['user_id'] ?? 0;
            $classId = intval($_POST['class_id'] ?? 0);
            if (!$classId) throw new Exception('Pilih kelas terlebih dahulu.');
            $date = date('Y-m-d');
            $time = date('H:i:s');

            $existing = $this->model->getTodayAttendance($userId, $date, $classId);
            if (!$existing) throw new Exception('Belum check-in untuk kelas ini.');
            if (!empty($existing['check_out'])) throw new Exception('Anda sudah check-out.');

            $this->model->updateCheckOut($userId, $date, $classId, $time);
            echo json_encode(['success' => true, 'message' => "Check-out berhasil: {$time}"]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}