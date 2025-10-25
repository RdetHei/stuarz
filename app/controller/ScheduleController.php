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
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $filters = [];
        // Apply filters based on user level
        $userLevel = $_SESSION['level'] ?? 'user';
        $userId = $_SESSION['user_id'] ?? 0;

        // Guru can only see their own schedules
        if ($userLevel === 'guru') {
            $filters['teacher_id'] = $userId;
        }
        // Admin can see all or filter by class/teacher
        elseif ($userLevel === 'admin') {
            if (!empty($_GET['class_id'])) $filters['class_id'] = intval($_GET['class_id']);
            if (!empty($_GET['teacher_id'])) $filters['teacher_id'] = intval($_GET['teacher_id']);
        }
        // Regular users can only see schedules for their class
        else {
            // Get user's class
            $stmt = $this->db->prepare("SELECT class FROM users WHERE id = ?");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user && $user['class']) {
                // Get class ID from class name
                $stmt = $this->db->prepare("SELECT id FROM classes WHERE name = ?");
                $stmt->bind_param('s', $user['class']);
                $stmt->execute();
                $result = $stmt->get_result();
                $class = $result->fetch_assoc();
                if ($class) {
                    $filters['class_id'] = $class['id'];
                }
            }
        }

        $schedules = $this->model->getAll($filters);
        $content = dirname(__DIR__) . '/views/pages/schedule/schedule.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        // Only admin and guru can create schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if (!in_array($userLevel, ['admin', 'guru'])) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $mode = 'create';
        $item = null;
        $days = $this->allowedDays;
        // load helper lists
        $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
        $subjects = $this->db->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC) ?? [];
        
        // For admin, show all teachers (guru or admin). For guru, they can only assign themselves
        if ($userLevel === 'admin') {
            $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin'")->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            $userId = $_SESSION['user_id'];
            $stmt = $this->db->prepare("SELECT id, name FROM users WHERE id = ? AND (level='guru' OR level='admin')");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $teachers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?? [];
        }

        $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        // Only admin and guru can create schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if (!in_array($userLevel, ['admin', 'guru'])) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $data = [
            'class' => $_POST['class'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'teacher_id' => intval($_POST['teacher_id'] ?? 0),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'day' => $_POST['day'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
        ];

        // Validate selected teacher exists and has role guru or admin
        $teacherId = $data['teacher_id'];
        $teacherCheck = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level='guru' OR level='admin')")->fetch_assoc();
        if (!$teacherCheck) {
            $_SESSION['error'] = 'Guru yang dipilih tidak valid.';
            header('Location: index.php?page=schedule');
            exit;
        }

        // For guru, ensure they can only create schedules for themselves
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
            // Capture DB error for debugging
            $_SESSION['error'] = 'Gagal menyimpan jadwal: ' . ($this->db->error ?? 'Unknown DB error');
            header('Location: index.php?page=schedule');
            exit;
        }
        header('Location: index.php?page=schedule');
        exit;
    }

    public function edit($id) {
        // Only admin and guru can edit schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if (!in_array($userLevel, ['admin', 'guru'])) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengedit jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        // Verify access rights for guru
        $userId = $_SESSION['user_id'];
        if ($userLevel === 'guru') {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM schedule WHERE id = ? AND teacher_id = ?");
            $stmt->bind_param('ii', $id, $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] === 0) {
                $_SESSION['error'] = 'Anda hanya dapat mengedit jadwal Anda sendiri.';
                header('Location: index.php?page=schedule');
                exit;
            }
        }

        $mode = 'edit';
        $item = $this->model->getById($id);
        if (!$item) { 
            header('Location: index.php?page=schedule'); 
            exit; 
        }
        
        $days = $this->allowedDays;
        $classes = $this->db->query("SELECT id, name FROM classes")->fetch_all(MYSQLI_ASSOC) ?? [];
        $subjects = $this->db->query("SELECT id, name FROM subjects")->fetch_all(MYSQLI_ASSOC) ?? [];
        
        // For admin, show all teachers (guru or admin). For guru, they can only assign themselves
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
        // Only admin and guru can update schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if (!in_array($userLevel, ['admin', 'guru'])) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengubah jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        // For guru, verify they own the schedule being updated
        if ($userLevel === 'guru') {
            $userId = $_SESSION['user_id'];
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM schedule WHERE id = ? AND teacher_id = ?");
            $stmt->bind_param('ii', $id, $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] === 0) {
                $_SESSION['error'] = 'Anda hanya dapat mengubah jadwal Anda sendiri.';
                header('Location: index.php?page=schedule');
                exit;
            }
        }

        $data = [
            'class' => $_POST['class'] ?? '',
            'subject' => $_POST['subject'] ?? '',
            'teacher_id' => intval($_POST['teacher_id'] ?? 0),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'day' => $_POST['day'] ?? '',
            'start_time' => $_POST['start_time'] ?? '',
            'end_time' => $_POST['end_time'] ?? '',
        ];

        // Validate selected teacher exists and has role guru or admin
        $teacherId = $data['teacher_id'];
        $teacherCheck = $this->db->query("SELECT id FROM users WHERE id = $teacherId AND (level='guru' OR level='admin')")->fetch_assoc();
        if (!$teacherCheck) {
            $_SESSION['error'] = 'Guru yang dipilih tidak valid.';
            header('Location: index.php?page=schedule');
            exit;
        }

        // For guru, ensure they can only update to themselves as teacher
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
        // Only admin and guru can delete schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if (!in_array($userLevel, ['admin', 'guru'])) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk menghapus jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        // For guru, verify they own the schedule being deleted
        if ($userLevel === 'guru') {
            $userId = $_SESSION['user_id'];
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM schedule WHERE id = ? AND teacher_id = ?");
            $stmt->bind_param('ii', $id, $userId);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            
            if ($result['count'] === 0) {
                $_SESSION['error'] = 'Anda hanya dapat menghapus jadwal Anda sendiri.';
                header('Location: index.php?page=schedule');
                exit;
            }
        }

        $this->model->delete($id);
        header('Location: index.php?page=schedule');
        exit;
    }
}