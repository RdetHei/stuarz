<?php
require_once dirname(__DIR__) . '/model/ClassModel.php';
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/helpers/class_helper.php';
require_once dirname(__DIR__) . '/helpers/level_helper.php';
require_once dirname(__DIR__) . '/services/ClassService.php';

class ClassController {
    private $model;
    private $db;
    private $classService;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new ClassModel($config);
        // Instantiate ClassService using PDO (separate from existing mysqli connection)
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=stuarz;charset=utf8mb4', 'root', '');
            $this->classService = new ClassService($pdo);
        } catch (Exception $e) {
            // fallback: null service (controllers will still try model methods)
            $this->classService = null;
        }
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        // Use service (PDO) when available to fetch all classes with user's join status
        $userId = intval($_SESSION['user']['id'] ?? 0);
        if ($this->classService) {
            try {
                $classes = $this->classService->getAllClassesWithUserStatus($userId);
            } catch (Exception $e) {
                error_log('ClassService error in index: ' . $e->getMessage());
                $classes = $this->model->getAllClassesWithUserStatus($userId);
            }
        } else {
            $classes = $this->model->getAllClassesWithUserStatus($userId);
        }

        // Filter: by default show only classes the user has joined. Use ?show=all to view all.
        $showAll = (isset($_GET['show']) && $_GET['show'] === 'all');
        if (!$showAll) {
            $classes = array_values(array_filter($classes, function($c){ return intval($c['is_joined'] ?? 0) === 1; }));
        }

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
        try {
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
            // Auto-add creator as teacher member to class_members (CRITICAL for unique constraint)
            $newClassId = $this->db->insert_id;
            $creatorId = intval($data['created_by']);
            
            // Add creator to class_members with role determined from user's level
            try {
                // Fetch creator level from users table and map to role
                $stmt = $this->db->prepare('SELECT level FROM users WHERE id = ? LIMIT 1');
                $roleToAdd = 'user';
                if ($stmt) {
                    $stmt->bind_param('i', $creatorId);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $r = $res ? $res->fetch_assoc() : null;
                    $levelVal = $r['level'] ?? '';
                    $roleToAdd = (is_teacher_level($levelVal) || strtolower($levelVal) === 'admin') ? 'teacher' : 'user';
                    $stmt->close();
                }
                $this->model->addMember($newClassId, $creatorId, $roleToAdd);
            } catch (\Exception $e) {
                error_log('Warning: Could not add creator as member: ' . $e->getMessage());
                // Non-fatal; continue to schedule creation
            }
            
            // Auto-generate default schedules for this class (Mon-Sat) so it appears in schedule table
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

            // Respond appropriately for AJAX clients
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
        } catch (\Throwable $e) {
            // Log the full exception for debugging
            error_log('ClassController::store error: ' . $e->getMessage() . " -- " . $e->getTraceAsString());
            // If AJAX, return JSON error so client-side can show appropriate message
            if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                header('Content-Type: application/json', true, 500);
                echo json_encode(['ok' => false, 'message' => 'Server error: ' . $e->getMessage()]);
                exit;
            }
            $_SESSION['flash'] = 'Gagal menambah kelas: ' . $e->getMessage();
            header('Location: index.php?page=class_create');
            exit;
        }
    }

    // Show join class form
    public function joinForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user'])) {
            $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
            header('Location: index.php?page=login');
            exit;
        }
        // FIXED: Use ClassService to get ALL classes with user's join status
        // This ensures is_joined is correctly set based on class_members, not fallback logic
        $userId = intval($_SESSION['user']['id'] ?? 0);
        if ($this->classService) {
            try {
                $classes = $this->classService->getAllClassesWithUserStatus($userId);
            } catch (Exception $e) {
                // Fallback to model if service fails
                error_log('ClassService error: ' . $e->getMessage());
                $classes = $this->model->getAllClassesWithUserStatus($userId);
            }
        } else {
            // Fallback to model
            $classes = $this->model->getAllClassesWithUserStatus($userId);
        }
        $content = dirname(__DIR__) . '/views/pages/classes/index.php';
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

            // Determine role from user's level: 'teacher' for teacher-level, 'user' for students
            $role = (is_teacher_level($level) || $level === 'admin') ? 'teacher' : 'user';

            // Attempt to add member via ClassService if available, fallback to model
            if ($this->classService) {
                $result = $this->classService->joinClass(intval($user['id']), intval($class['id']), $role);
            } else {
                $result = $this->model->addMember(intval($class['id']), intval($user['id']), $role);
            }
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
            $_SESSION['error'] = $e->getMessage();
        }
        // Redirect back to referrer or class list
        $back = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=class';
        header('Location: ' . $back);
        exit;
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
        $class = $this->model->getById($id);
        if (!$class) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }
        // members and schedules
        $members = $this->model->getMembers($id);

        // Determine whether current user can manage roles/members (creator, class guru, or admin)
        $currentUserId = intval($_SESSION['user']['id'] ?? 0);
        $canManage = false;
        if (!empty($currentUserId)) {
            // class creator
            if (intval($class['created_by'] ?? 0) === $currentUserId) $canManage = true;
            // site admin level
            $currLevel = $_SESSION['user']['level'] ?? '';
            if (!$canManage && ($currLevel === 'admin' || $currLevel === 'Guru' || $currLevel === 'guru')) $canManage = true;
            // check membership role for current user
            if (!$canManage) {
                $stmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ? LIMIT 1');
                if ($stmt) {
                    $stmt->bind_param('ii', $id, $currentUserId);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $r = $res ? $res->fetch_assoc() : null;
                    if ($r && in_array(strtolower($r['role'] ?? ''), ['guru','admin','teacher'])) $canManage = true;
                    $stmt->close();
                }
            }
        }
        // use ScheduleModel to fetch schedules with relations if available
        $schedules = [];
        if (is_file(dirname(__DIR__) . '/model/ScheduleModel.php')) {
            require_once dirname(__DIR__) . '/model/ScheduleModel.php';
            $schedModel = new ScheduleModel($this->db);
            $schedules = $schedModel->getAllWithRelations(['class_id' => $id]);
        }

        // fetch tasks for this class only
        $tasks = [];
        if (is_file(dirname(__DIR__) . '/model/TasksCompletedModel.php')) {
            require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
            $tkModel = new TasksCompletedModel($this->db);
            $tasks = $tkModel->getAll(['class_id' => $id]);
        }

        $content = dirname(__DIR__) . '/views/pages/classes/detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    // Update a member's role (only for class managers: creator, guru, or admin)
    public function updateMemberRole() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['user'])) throw new \Exception('Silakan login terlebih dahulu.');

            $currentUserId = intval($_SESSION['user']['id'] ?? 0);
            $classId = intval($_POST['class_id'] ?? 0);
            $userId = intval($_POST['user_id'] ?? 0);
            // determine role from the target user's level (ignore arbitrary POST role)
            if (!$classId || !$userId) throw new \Exception('Parameter tidak lengkap.');

            // Fetch target user's level
            $stmt = $this->db->prepare('SELECT level FROM users WHERE id = ? LIMIT 1');
            $newRole = 'user';
            if ($stmt) {
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $res = $stmt->get_result();
                $r = $res ? $res->fetch_assoc() : null;
                $levelVal = $r['level'] ?? '';
                $newRole = (is_teacher_level($levelVal) || strtolower($levelVal) === 'admin') ? 'teacher' : 'user';
                $stmt->close();
            }

            // Permission check: must be class creator, class guru/admin, or site admin
            $allowed = false;
            // site admin
            $currLevel = $_SESSION['user']['level'] ?? '';
            if ($currLevel === 'admin' || $currLevel === 'Guru' || $currLevel === 'guru') $allowed = true;

            // class creator
            $class = $this->model->getById($classId);
            if (!$allowed && $class && intval($class['created_by'] ?? 0) === $currentUserId) $allowed = true;

            // membership role
            if (!$allowed) {
                $stmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ? LIMIT 1');
                if ($stmt) {
                    $stmt->bind_param('ii', $classId, $currentUserId);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $r = $res ? $res->fetch_assoc() : null;
                    if ($r && in_array(strtolower($r['role'] ?? ''), ['guru','admin','teacher'])) $allowed = true;
                    $stmt->close();
                }
            }

            if (!$allowed) throw new \Exception('Tidak memiliki izin untuk mengubah role.');

            // Perform update via service if available, else fallback to model/db
            $ok = false;
            if ($this->classService) {
                try {
                    $ok = $this->classService->updateMemberRole($userId, $classId, $newRole);
                } catch (\Exception $e) {
                    $ok = false;
                }
            } else {
                $stmt2 = $this->db->prepare('UPDATE class_members SET role = ? WHERE class_id = ? AND user_id = ?');
                if ($stmt2) {
                    $stmt2->bind_param('sii', $newRole, $classId, $userId);
                    $stmt2->execute();
                    $ok = $stmt2->affected_rows > 0;
                    $stmt2->close();
                }
            }

            $_SESSION['flash'] = $ok ? 'Role anggota diperbarui.' : 'Gagal memperbarui role anggota.';
        } catch (\Exception $e) {
            $_SESSION['flash'] = 'Error: ' . $e->getMessage();
        }
        $back = $_SERVER['HTTP_REFERER'] ?? ('index.php?page=class_members&id=' . intval($classId));
        header('Location: ' . $back);
        exit;
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
        // Determine whether current user can manage roles (creator, guru, or admin)
        $currentUserId = intval($_SESSION['user']['id'] ?? 0);
        $canManage = false;
        if (!empty($currentUserId)) {
            if (intval($class['created_by'] ?? 0) === $currentUserId) $canManage = true;
            $currLevel = $_SESSION['user']['level'] ?? '';
            if (!$canManage && ($currLevel === 'admin' || $currLevel === 'Guru' || $currLevel === 'guru')) $canManage = true;
            if (!$canManage) {
                $stmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ? LIMIT 1');
                if ($stmt) {
                    $stmt->bind_param('ii', $class_id, $currentUserId);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $r = $res ? $res->fetch_assoc() : null;
                    if ($r && in_array(strtolower($r['role'] ?? ''), ['guru','admin','teacher'])) $canManage = true;
                    $stmt->close();
                }
            }
        }
        $content = dirname(__DIR__) . '/views/pages/classes/class_members.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function addMember() {
        try {
            $classId = intval($_POST['class_id'] ?? 0);
            $userId = intval($_POST['user_id'] ?? 0);
            // Determine role from the user's level to avoid mismatches
            $role = 'user';
            $stmt = $this->db->prepare('SELECT level FROM users WHERE id = ? LIMIT 1');
            if ($stmt) {
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $res = $stmt->get_result();
                $r = $res ? $res->fetch_assoc() : null;
                $levelVal = $r['level'] ?? '';
                $role = (is_teacher_level($levelVal) || strtolower($levelVal) === 'admin') ? 'teacher' : 'user';
                $stmt->close();
            }

            if (!$classId || !$userId) {
                throw new \Exception("Invalid class or user ID");
            }
            if ($this->classService) {
                $result = $this->classService->joinClass($userId, $classId, $role);
            } else {
                $result = $this->model->addMember($classId, $userId, $role);
            }
            
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
        if ($this->classService) {
            $ok = $this->classService->leaveClass($user_id, $class_id);
        } else {
            $ok = $this->model->removeMember($class_id, $user_id);
        }
        $_SESSION['flash'] = $ok ? 'Anggota dihapus.' : 'Gagal menghapus anggota.';
        header('Location: index.php?page=class_members&id=' . $class_id);
        exit;
    }
}
