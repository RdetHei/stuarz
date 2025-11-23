<?php
require_once dirname(__DIR__) . '/model/ClassModel.php';
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/helpers/class_helper.php';

class ClassController {
    private $model;
    private $db;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new ClassModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $user = $_SESSION['user'] ?? null;
        $userId = $user['id'] ?? null;
        $userLevel = $user['level'] ?? 'user';
        
        // Filter classes based on user level
        if ($userLevel === 'user' && $userId) {
            // User biasa: hanya kelas yang diikuti
            $classes = $this->model->getAll($userId);
        } elseif (($userLevel === 'admin' || $userLevel === 'guru') && $userId) {
            // Admin/guru: kelas yang mereka kelola
            $classes = $this->model->getManagedClasses($userId);
        } else {
            // Fallback: semua kelas (untuk non-logged in atau edge cases)
            $classes = $this->model->getAll();
        }
        
        // Check if user has any classes
        $hasClasses = !empty($classes);
        
        // Get statistics
        $totalClasses = count($classes);
        // safe student count (class_members may be missing or empty)
        $totalStudents = 0;
        $res = $this->db->query("SELECT COUNT(DISTINCT user_id) as count FROM class_members");
        if ($res) {
            $row = $res->fetch_assoc();
            $totalStudents = intval($row['count'] ?? 0);
        }
        // Some schemas don't have an `is_active` column — fallback to total classes
        $totalActiveClasses = $totalClasses;
        $averageStudentsPerClass = $totalClasses > 0 ? round($totalStudents / $totalClasses) : 0;
        
        $stats = [
            'classes' => $totalClasses,
            'students' => $totalStudents,
            'activeClasses' => $totalActiveClasses,
            'averageStudents' => $averageStudentsPerClass
        ];
        
        $content = dirname(__DIR__) . '/views/pages/classes/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/classes/class_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'code' => trim($_POST['code'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'created_by' => $_SESSION['user']['id'] ?? 0
        ];
        // Auto-generate code if empty (6 uppercase alphanumeric) and ensure uniqueness
        if (empty($data['code'])) {
            $tries = 0;
            do {
                $data['code'] = generateClassCode(6);
                $exists = $this->model->findByCode($data['code']);
                $tries++;
            } while ($exists && $tries < 6);
        }
        $errors = $this->model->validate($data);
        if ($errors) {
            $_SESSION['flash'] = implode(' ', $errors);
            header('Location: index.php?page=class_create');
            exit;
        }
        $ok = $this->model->create($data);
        if ($ok) {
            // Auto-generate default schedules for this class (Mon-Sat) so it appears in schedule table
            $newClassId = $this->db->insert_id;
            $creatorId = intval($data['created_by']);
            
            // Auto-add creator as teacher member
            try {
                $userLevel = $_SESSION['user']['level'] ?? 'user';
                $role = ($userLevel === 'admin') ? 'admin' : 'teacher';
                $this->model->addMember($newClassId, $creatorId, $role);
            } catch (\Exception $e) {
                // Ignore if already member (shouldn't happen, but safe)
            }
            
            // Fetch class name for logging if needed (optional)
            // Prepare insert statement
            $stmt = $this->db->prepare("INSERT INTO schedule (`class`,`subject`,`teacher_id`,`class_id`,`day`,`start_time`,`end_time`) VALUES (?,?,?,?,?,?,?)");
            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            foreach ($days as $d) {
                // Defaults: empty room, subject TBD, teacher = creator/admin, times placeholder
                $room = '';
                $subject = 'TBD';
                $teacherId = $creatorId; // ensure FK satisfied
                $start = '07:00:00';
                $end = '08:00:00';
                $stmt->bind_param('ssiiiss', $room, $subject, $teacherId, $newClassId, $d, $start, $end);
                $stmt->execute();
            }
            $stmt->close();
        }
        if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-Type: application/json');
            if ($ok) {
                echo json_encode(['ok' => true, 'message' => 'Kelas berhasil ditambah.', 'class_id' => intval($this->db->insert_id ?? 0)]);
            } else {
                echo json_encode(['ok' => false, 'message' => 'Gagal menambah kelas.']);
            }
            exit;
        }
        $_SESSION['flash'] = $ok ? 'Kelas berhasil ditambah.' : 'Gagal menambah kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    // Show join class form
    public function joinForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user'])) {
            $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
            header('Location: index.php?page=login');
            exit;
        }
        $user = $_SESSION['user'];
        $userLevel = $user['level'] ?? 'user';
        $userId = $user['id'] ?? null;
        
        // Check if user already has classes
        if ($userLevel === 'user' && $userId) {
            $userClasses = $this->model->getAll($userId);
            $hasClasses = !empty($userClasses);
        } elseif (($userLevel === 'admin' || $userLevel === 'guru') && $userId) {
            $userClasses = $this->model->getManagedClasses($userId);
            $hasClasses = !empty($userClasses);
        } else {
            $hasClasses = false;
        }
        
        // For admin/guru, show all classes for selection; for user, show their classes
        if ($userLevel === 'admin' || $userLevel === 'guru') {
            $classes = $this->model->getAll(); // All classes for admin/guru to select
        } else {
            $classes = $this->model->getAll($userId); // User's classes
        }
        
        $content = dirname(__DIR__) . '/views/pages/classes/join_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    // Handle join class POST
    public function join() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['user'])) {
                throw new \Exception('Silakan login terlebih dahulu.');
            }
            $user = $_SESSION['user'];
            $level = $user['level'] ?? 'user';

            // Admin/guru may pass class_id to join directly
            $classId = intval($_POST['class_id'] ?? 0);
            $code = trim($_POST['class_code'] ?? trim($_POST['code'] ?? ''));

            if (($level !== 'admin' && $level !== 'guru') && empty($code)) {
                throw new \Exception('Kode kelas wajib diisi.');
            }

            if ($classId) {
                $class = $this->model->getById($classId);
            } else {
                $class = $this->model->findByCode($code);
            }

            if (!$class) {
                throw new \Exception('Kode kelas tidak valid atau kelas tidak ditemukan.');
            }

            // Determine role
            $role = ($level === 'admin' || $level === 'guru') ? 'teacher' : 'student';

            // Attempt to add member; model will prevent duplicates
            $result = $this->model->addMember(intval($class['id']), intval($user['id']), $role);
            if ($result) {
                // success
                if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => true, 'message' => 'Berhasil bergabung ke kelas.', 'class_id' => intval($class['id'])]);
                    exit;
                }
                $_SESSION['success'] = 'Berhasil bergabung ke kelas.';
            } else {
                if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => false, 'message' => 'Gagal bergabung ke kelas.']);
                    exit;
                }
                $_SESSION['error'] = 'Gagal bergabung ke kelas.';
            }
        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'message' => $errorMsg]);
                exit;
            }
            $_SESSION['error'] = $errorMsg;
        }
        // Redirect back to referrer or class list (only if not AJAX)
        if (empty($_GET['ajax']) && (!isset($_SERVER['HTTP_ACCEPT']) || strpos($_SERVER['HTTP_ACCEPT'], 'application/json') === false)) {
            $back = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=class';
            header('Location: ' . $back);
            exit;
        }
    }

    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $class = $this->model->getById($id);
        if (!$class) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }
        $content = dirname(__DIR__) . '/views/pages/classes/class_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    // Show class detail
    public function detail($id = null) {
        if ($id === null) {
            // try to parse from PATH_INFO or GET
            $id = intval($_GET['id'] ?? 0);
            if (!$id && isset($_GET['page'])) {
                // page may be like 'class/detail/12'
                $parts = explode('/', $_GET['page']);
                if (isset($parts[2])) $id = intval($parts[2]);
            }
        }
        $id = intval($id);
        if (!$id) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }
        $userId = $_SESSION['user']['id'] ?? null;
        $class = $this->model->getById($id, $userId);
        if (!$class) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }
        // members and schedules
        $members = $this->model->getMembers($id);
        // use ScheduleModel to fetch schedules with relations if available
        $schedules = [];
        if (is_file(dirname(__DIR__) . '/model/ScheduleModel.php')) {
            require_once dirname(__DIR__) . '/model/ScheduleModel.php';
            $schedModel = new ScheduleModel($this->db);
            $schedules = $schedModel->getAllWithRelations(['class_id' => $id]);
        }

        $content = dirname(__DIR__) . '/views/pages/classes/detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'code' => trim($_POST['code'] ?? ''),
            'description' => trim($_POST['description'] ?? '')
        ];
        $errors = $this->model->validate($data, true, $id);
        if ($errors) {
            $_SESSION['flash'] = implode(' ', $errors);
            header('Location: index.php?page=class_edit&id=' . $id);
            exit;
        }
        $ok = $this->model->update($id, $data);
        $_SESSION['flash'] = $ok ? 'Kelas diperbarui.' : 'Gagal memperbarui kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Kelas dihapus.' : 'Gagal menghapus kelas.';
        header('Location: index.php?page=class');
        exit;
    }

    // Anggota kelas
    public function members() {
        $class_id = intval($_GET['id'] ?? 0);
        $class = $this->model->getById($class_id);
        $members = $this->model->getMembers($class_id);
        $content = dirname(__DIR__) . '/views/pages/classes/class_members.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function addMember() {
        try {
            $classId = intval($_POST['class_id'] ?? 0);
            $userId = intval($_POST['user_id'] ?? 0);
            $role = $_POST['role'] ?? 'member';

            if (!$classId || !$userId) {
                throw new \Exception("Invalid class or user ID");
            }

            $result = $this->model->addMember($classId, $userId, $role);
            
            if ($result) {
                $_SESSION['success'] = "Member added successfully";
            } else {
                $_SESSION['error'] = "Failed to add member";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function removeMember() {
        $class_id = intval($_POST['class_id'] ?? 0);
        $user_id = intval($_POST['user_id'] ?? 0);
        $ok = $this->model->removeMember($class_id, $user_id);
        $_SESSION['flash'] = $ok ? 'Anggota dihapus.' : 'Gagal menghapus anggota.';
        header('Location: index.php?page=class_members&id=' . $class_id);
        exit;
    }
}