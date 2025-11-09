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

        // Admin can see all or filter by class/teacher/day
        if ($userLevel === 'admin') {
            if (!empty($_GET['class_id'])) $filters['class_id'] = intval($_GET['class_id']);
            if (!empty($_GET['teacher_id'])) $filters['teacher_id'] = intval($_GET['teacher_id']);
            if (!empty($_GET['day'])) $filters['day'] = $_GET['day'];
        }
        // Regular users can only see schedules for their class
        else {
            // Get user's class from class_members
            $stmt = $this->db->prepare("SELECT class_id FROM class_members WHERE user_id = ? LIMIT 1");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $member = $result->fetch_assoc();
            $stmt->close();
            
            if ($member && $member['class_id']) {
                $filters['class_id'] = $member['class_id'];
            } else {
                // Fallback: try to get from users.class field
                $stmt = $this->db->prepare("SELECT class FROM users WHERE id = ?");
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                $stmt->close();
                if ($user && $user['class']) {
                    // Get class ID from class name
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

        // Get all classes (or filtered class if class_id filter is set)
        if (!empty($filters['class_id'])) {
            $allClasses = $this->db->query("SELECT id, name, code, description FROM classes WHERE id = " . intval($filters['class_id']) . " ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            $allClasses = $this->db->query("SELECT id, name, code, description FROM classes ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        }
        
        // For each class, get ALL its schedules regardless of teacher/day filters
        // Each card should show ALL schedules for that class
        $classesWithSchedules = [];
        foreach ($allClasses as $class) {
            $classId = $class['id'];
            
            // Get ALL schedules for this class (no teacher/day filtering)
            // This ensures each card shows all schedules related to the class
            $classSchedules = $this->model->getAllWithRelations(['class_id' => $classId]);
            
            // Ensure we have an array
            if (!is_array($classSchedules)) {
                $classSchedules = [];
            }
            
            $classesWithSchedules[$classId] = array_merge($class, [
                'schedules' => $classSchedules
            ]);
        }
        
        // If teacher or day filter is set, only show classes that have matching schedules
        // But still display ALL schedules for those classes
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
                // Only include class if it has at least one matching schedule
                // But still show ALL schedules for that class
                if ($hasMatchingSchedule) {
                    $filteredClasses[$classId] = $classData;
                }
            }
            $classesWithSchedules = $filteredClasses;
        }

        // Provide lists for filters to the view (always show all classes/teachers for filter dropdown)
        $filterClasses = $this->db->query("SELECT id, name FROM classes ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        $filterTeachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin' ORDER BY name")?->fetch_all(MYSQLI_ASSOC) ?? [];
        
        $content = dirname(__DIR__) . '/views/pages/schedule/schedule.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        // Only admin can create schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $mode = 'create';
        $item = null;
        $days = $this->allowedDays;
        // load helper lists
        $classes = $this->db->query("SELECT id, name, code FROM classes ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];
        $subjects = $this->db->query("SELECT id, name FROM subjects ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];
        
        // Admin: daftar guru/admin (menampilkan pengguna dengan level 'guru' atau 'admin')
        $teachers = $this->db->query("SELECT id, name FROM users WHERE level='guru' OR level='admin' ORDER BY name")->fetch_all(MYSQLI_ASSOC) ?? [];

        $content = dirname(__DIR__) . '/views/pages/schedule/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        // Only admin can create schedules
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
        
        // Validate day is selected and valid
        if (empty($data['day']) || !in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid atau tidak dipilih.';
            header('Location: index.php?page=schedule/create');
            exit;
        }

        // Validate selected teacher exists and has level 'guru' atau 'admin'
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
        // Only admin can edit schedules
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
        // Only admin can update schedules
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
        
        // Validate day is selected and valid
        if (empty($data['day']) || !in_array($data['day'], $this->allowedDays)) {
            $_SESSION['error'] = 'Hari tidak valid atau tidak dipilih.';
            header('Location: index.php?page=schedule/edit/' . $id);
            exit;
        }

        // Validate selected teacher exists and has level 'guru' atau 'admin'
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
        // Only admin can delete schedules
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel !== 'admin') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk menghapus jadwal.';
            header('Location: index.php?page=schedule');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $this->model->delete($id);
        header('Location: index.php?page=schedule');
        exit;
    }
}