<?php
require_once __DIR__ . '/../model/AttendanceModel.php';
require_once __DIR__ . '/../model/ClassModel.php';

class AttendanceController {
    private $db;
    private $model;
    private $classModel;

    public function __construct($db = null) {
        if ($db === null) {
            global $config;
            $db = $config;
        }
        $this->db = $db;
        $this->model = new AttendanceModel($db);
        $this->classModel = new ClassModel($db);
        // Ensure timezone follows session if available (login sets it)
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['timezone'])) {
            date_default_timezone_set($_SESSION['timezone']);
        }
    }

    private function isAdmin() {
        return isset($_SESSION['level']) && $_SESSION['level'] === 'admin';
    }

    public function index() {
        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate = $_GET['end'] ?? date('Y-m-d');
        $filterClass = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;

        $records = $this->model->getFilteredAttendance($startDate, $endDate, $filterClass);
        $classes = $this->model->getClasses();
        $activeClass = null;
        $activeClassId = intval($_SESSION['active_class_id'] ?? 0);
        if ($activeClassId) {
            $activeClass = $this->classModel->getById($activeClassId, $_SESSION['user']['id'] ?? null);
        }

        $content = dirname(__DIR__) . '/views/pages/attendance/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function manage() {
        if (!$this->isAdmin()) {
            header('Location: index.php?page=attendance');
            exit;
        }

        $startDate = $_GET['start'] ?? date('Y-m-d');
        $endDate = $_GET['end'] ?? date('Y-m-d');
        $filterClass = isset($_GET['class_id']) && $_GET['class_id'] !== '' ? intval($_GET['class_id']) : null;

        $records = $this->model->getFilteredAttendance($startDate, $endDate, $filterClass);
        $classes = $this->model->getClasses();

        $content = dirname(__DIR__) . '/views/pages/attendance/manage.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function checkIn() {
        try {
            $userId = $_SESSION['user_id'] ?? 0;
            $classId = intval($_POST['class_id'] ?? 0);
            if (!$classId) {
                $classId = intval($_SESSION['active_class_id'] ?? 0);
            }
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
            if (!$classId) {
                $classId = intval($_SESSION['active_class_id'] ?? 0);
            }
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

    public function edit() {
        try {
            if (!$this->isAdmin()) {
                throw new Exception('Unauthorized access');
            }

            $id = intval($_POST['id'] ?? 0);
            if (!$id) {
                throw new Exception('Invalid attendance record');
            }

            $attendance = $this->model->getAttendanceById($id);
            if (!$attendance) {
                throw new Exception('Attendance record not found');
            }

            $data = [
                'class_id' => isset($_POST['class_id']) ? intval($_POST['class_id']) : null,
                'date' => $_POST['date'] ?? null,
                'check_in' => $_POST['check_in'] ?? null,
                'check_out' => $_POST['check_out'] ?? null,
                'status' => $_POST['status'] ?? null
            ];

            // Validate check-in/check-out times
            if ($data['check_in'] && $data['check_out']) {
                $checkIn = strtotime($data['check_in']);
                $checkOut = strtotime($data['check_out']);
                if ($checkOut <= $checkIn) {
                    throw new Exception('Check-out time must be after check-in time');
                }
            }

            if ($this->model->updateAttendance($id, array_filter($data))) {
                echo json_encode(['success' => true, 'message' => 'Attendance record updated successfully']);
            } else {
                throw new Exception('Failed to update attendance record');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete() {
        try {
            if (!$this->isAdmin()) {
                throw new Exception('Unauthorized access');
            }

            $id = intval($_POST['id'] ?? 0);
            if (!$id) {
                throw new Exception('Invalid attendance record');
            }

            if ($this->model->deleteAttendance($id)) {
                echo json_encode(['success' => true, 'message' => 'Attendance record deleted successfully']);
            } else {
                throw new Exception('Failed to delete attendance record');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}