<?php
require_once __DIR__ . '/../model/ScheduleModel.php';
class ScheduleController {
    private $db;
    private $model;
    private $allowedDays = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    public function __construct($db = null) {
        if ($db === null) {
            global $config;
            $db = $config ?? null;
        }
        $this->db = $db;
        $this->model = new ScheduleModel($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $filters = [];
        $userLevel = $_SESSION['level'] ?? 'user';
        $userId = $_SESSION['user_id'] ?? 0;

        if ($userLevel === 'admin') {
            if (!empty($_GET['class_id'])) $filters['class_id'] = intval($_GET['class_id']);
            if (!empty($_GET['teacher_id'])) $filters['teacher_id'] = intval($_GET['teacher_id']);
            if (!empty($_GET['day'])) $filters['day'] = $_GET['day'];
        }
        else {
            $stmt = $this->db->prepare("SELECT class_id FROM class_members WHERE user_id = ? LIMIT 1");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $member = $result->fetch_assoc();
            $stmt->close();
            
            if ($member && $member['class_id']) {
                $filters['class_id'] = $member['class_id'];
            } else {
                $stmt = $this->db->prepare("SELECT class FROM users WHERE id = ?");
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
                if ($user && $user['class']) {
                    $stmt = $this->db->prepare("SELECT id FROM classes WHERE name = ?");
                    $stmt->bind_param('s', $user['class']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $class = $result->fetch_assoc();
                    $stmt->close();
                    if ($class) {
                        $filters['class_id'] = $class['id'];
                    }
                }
            }
        }

        if (!empty($filters['class_id'])) {
            $allClasses = $this->db->query("SELECT id, name, code AS class_code, description FROM classes WHERE id = " . intval($filters['class_id']) . " ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            $allClasses = $this->db->query("SELECT id, name, code AS class_code, description FROM classes ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        }
        
        $classesWithSchedules = [];
        foreach ($allClasses as $class) {
            $classId = $class['id'];
            
            $classSchedules = $this->model->getAllWithRelations(['class_id' => $classId]);
            
            if (!is_array($classSchedules)) {
                $classSchedules = [];
            }
            
            $classesWithSchedules[$classId] = array_merge($class, [
                'schedules' => $classSchedules
            ]);
        }
        
        if (!empty($filters['teacher_id']) || !empty($filters['day'])) {
            $filteredClasses = [];
            foreach ($classesWithSchedules as $classId => $classData) {
                $hasMatchingSchedule = false;
                foreach ($classData['schedules'] as $schedule) {
                    $match = true;
                    if (!empty($filters['teacher_id']) && $schedule['teacher_id'] != $filters['teacher_id']) {
                        $match = false;
                    }
                    if (!empty($filters['day']) && $schedule['day'] != $filters['day']) {
                        $match = false;
                    }
                    if ($match) {
                        $hasMatchingSchedule = true;
                        break;
                    }
                }
                if ($hasMatchingSchedule) {
                    $filteredClasses[$classId] = $classData;
                }
            }
            $classesWithSchedules = $filteredClasses;
        }

        $filterClasses = $this->db->query("SELECT id, name FROM classes ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        $filterTeachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin' ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        
        $content = dirname(__DIR__) . '/views/pages/schedule/schedule.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $mode = 'create';
        $item = null;
        $days = $this->allowedDays;
        $classes = $this->db->query("SELECT id, name, code AS class_code FROM classes ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];
        $subjects = $this->db->query("SELECT id, name FROM subjects ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];
        
        $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin' ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];

        $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $data = [
            'class' => trim($_POST['class'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'teacher_id' => intval($_POST['teacher_id'] ?? 0),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'day' => trim($_POST['day'] ?? ''),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
        ];
        
        if (empty($data['day']) || !in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid atau tidak dipilih.';
            header('Location: index.php?page=schedule/create');
            exit;
        }

        $teacherId = $data['teacher_id'];
        $stmtT = $this->db->prepare("SELECT id FROM users WHERE id = ? AND (level='guru' OR level='admin')");
        $stmtT->bind_param('i', $teacherId);
        $stmtT->execute();
        $teacherCheck = $stmtT->get_result()->fetch_assoc();
        $stmtT->close();
        if (!$teacherCheck) {
            $_SESSION['error'] = 'Guru yang dipilih tidak valid.';
            header('Location: index.php?page=schedule');
            exit;
        }

        if ($userLevel === 'guru') {
            $userId = $_SESSION['user_id'];
            if ($data['teacher_id'] != $userId) {
                $_SESSION['error'] = 'Anda hanya dapat membuat jadwal untuk diri sendiri.';
                header('Location: index.php?page=schedule');
                exit;
            }
        }

        if (!in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid';
            header('Location: index.php?page=schedule');
            exit;
        }

        $res = $this->model->create($data);
        if (is_array($res) && isset($res['error'])) {
            $_SESSION['error'] = $res['error'];
            header('Location: index.php?page=schedule');
            exit;
        }
        if ($res === false) {
            $_SESSION['error'] = 'Gagal menyimpan jadwal: ' . ($this->db->error ?? 'Unknown DB error');
            header('Location: index.php?page=schedule');
            exit;
        }
        header('Location: index.php?page=schedule');
        exit;
    }

    public function edit($id) {
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengedit jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $mode = 'edit';
        $item = $this->model->getById($id);
        if (!$item) { 
            header('Location: index.php?page=schedule'); 
            exit; 
        }
        
        $days = $this->allowedDays;
        $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
        $subjects = $this->db->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC) ?? [];
        
        if ($userLevel === 'admin') {
            $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin'")->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            $stmt = $this->db->prepare("SELECT id, name FROM users WHERE id = ? AND (level='guru' OR level='admin')");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
        }

        $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update($id) {
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengubah jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $data = [
            'class' => trim($_POST['class'] ?? ''),
            'subject' => trim($_POST['subject'] ?? ''),
            'teacher_id' => intval($_POST['teacher_id'] ?? 0),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'day' => trim($_POST['day'] ?? ''),
            'start_time' => trim($_POST['start_time'] ?? ''),
            'end_time' => trim($_POST['end_time'] ?? ''),
        ];
        
        if (empty($data['day']) || !in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid atau tidak dipilih.';
            header('Location: index.php?page=schedule/edit/' . $id);
            exit;
        }

        $teacherId = $data['teacher_id'];
        $stmtT = $this->db->prepare("SELECT id FROM users WHERE id = ? AND (level='guru' OR level='admin')");
        $stmtT->bind_param('i', $teacherId);
        $stmtT->execute();
        $teacherCheck = $stmtT->get_result()->fetch_assoc();
        $stmtT->close();
        if (!$teacherCheck) {
            $_SESSION['error'] = 'Guru yang dipilih tidak valid.';
            header('Location: index.php?page=schedule');
            exit;
        }

        if ($userLevel === 'guru') {
            $userId = $_SESSION['user_id'];
            if ($data['teacher_id'] != $userId) {
                $_SESSION['error'] = 'Anda hanya dapat mengatur jadwal untuk diri sendiri.';
                header('Location: index.php?page=schedule');
                exit;
            }
        }

        if (!in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid';
            header('Location: index.php?page=schedule');
            exit;
        }

        $res = $this->model->update($id, $data);
        if (is_array($res) && isset($res['error'])) {
            $_SESSION['error'] = $res['error'];
            header('Location: index.php?page=schedule');
            exit;
        }
        if ($res === false) {
            $_SESSION['error'] = 'Gagal mengupdate jadwal: ' . ($this->db->error ?? 'Unknown DB error');
            header('Location: index.php?page=schedule');
            exit;
        }
        header('Location: index.php?page=schedule');
        exit;
    }

    public function delete($id) {
        header('Content-Type: application/json');
        
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            echo json_encode([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menghapus jadwal.'
            ]);
            exit;
        }

        $id = intval($id ?? $_POST['id'] ?? 0);
        
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
                'message' => 'Jadwal berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus jadwal.'
            ]);
        }
        exit;
    }
}