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
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=stuarz;charset=utf8mb4', 'root', '');
            $this->classService = new ClassService($pdo);
        } catch (Exception $e) {
            $this->classService = null;
        }
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function index() {
        $search = trim((string)($_GET['q'] ?? ''));
        $filterJoined = isset($_GET['filter']) ? $_GET['filter'] : '';
        
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

        $showAll = (isset($_GET['show']) && $_GET['show'] === 'all');
        if (!$showAll) {
            $classes = array_values(array_filter($classes, function($c){ return intval($c['is_joined'] ?? 0) === 1; }));
        }
        
        if ($search !== '') {
            $classes = array_filter($classes, function($c) use ($search) {
                $name = strtolower($c['name'] ?? '');
                $code = strtolower($c['code'] ?? '');
                $desc = strtolower($c['description'] ?? '');
                $searchLower = strtolower($search);
                return strpos($name, $searchLower) !== false || 
                       strpos($code, $searchLower) !== false || 
                       strpos($desc, $searchLower) !== false;
            });
            $classes = array_values($classes);
        }
        
        if ($filterJoined === 'joined') {
            $classes = array_values(array_filter($classes, function($c){ return intval($c['is_joined'] ?? 0) === 1; }));
        } elseif ($filterJoined === 'not_joined') {
            $classes = array_values(array_filter($classes, function($c){ return intval($c['is_joined'] ?? 0) === 0; }));
        }

        $totalClasses = count($classes);
        $totalStudents = 0;
        $res = $this->db->query("SELECT COUNT(DISTINCT user_id) as count FROM class_members");
        if ($res) {
            $row = $res->fetch_assoc();
            $totalStudents = intval($row['count'] ?? 0);
        }
        $totalActiveClasses = $totalClasses;
        $averageStudentsPerClass = $totalClasses > 0 ? round($totalStudents / $totalClasses) : 0;
        
        $stats = [
            'classes' => $totalClasses,
            'students' => $totalStudents,
            'activeClasses' => $totalActiveClasses,
            'averageStudents' => $averageStudentsPerClass
        ];
        
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }
        
        $content = dirname(__DIR__) . '/views/pages/classes/index.php';
        if ($ajax) {
            include $content;
        } else {
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
        }
    }

    public function create() {
        $content = dirname(__DIR__) . '/views/pages/classes/class_form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
       
        if (ob_get_level()) ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['user']) && empty($_SESSION['user_id'])) {
                http_response_code(401);
                echo json_encode(['ok' => false, 'message' => 'Silakan login terlebih dahulu.']);
                exit;
            }
            
            $userId = intval($_SESSION['user']['id'] ?? $_SESSION['user_id'] ?? 0);
            if ($userId <= 0) {
                http_response_code(400);
                echo json_encode(['ok' => false, 'message' => 'User ID tidak valid.']);
                exit;
            }
            
            
            $codeValue = trim($_POST['code'] ?? $_POST['class_code'] ?? '');
            
          
            error_log('ClassController::store - Received POST data: ' . json_encode($_POST));
            
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'code' => $codeValue,
                'description' => trim($_POST['description'] ?? ''),
                'created_by' => $userId
            ];
        
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
                $errorMessage = implode(' ', $errors);
                error_log('ClassController::store - Validation failed: ' . $errorMessage);
                error_log('ClassController::store - Data being validated: ' . json_encode($data));
                http_response_code(400);
                echo json_encode(['ok' => false, 'message' => $errorMessage]);
                exit;
            }
            
         
            try {
                $ok = $this->model->create($data);
            } catch (\Exception $e) {
                error_log('ClassModel::create error: ' . $e->getMessage());
                error_log('ClassModel::create error trace: ' . $e->getTraceAsString());
                http_response_code(500);
                echo json_encode(['ok' => false, 'message' => 'Gagal membuat kelas: ' . $e->getMessage()]);
                exit;
            }
            
            $newClassId = 0;
            if ($ok) {
                $newClassId = intval($this->db->insert_id ?? 0);
            if ($newClassId <= 0) {
                $class = $this->model->findByCode($data['code']);
                if ($class) {
                    $newClassId = intval($class['id'] ?? 0);
                }
            }
            $creatorId = intval($data['created_by']);
            
            try {
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
            }
            
            if ($newClassId > 0) {
                try {
                    $classCode = $data['code'] ?? '';
                    $stmt = $this->db->prepare("INSERT INTO schedule (`class`,`subject`,`teacher_id`,`class_id`,`day`,`start_time`,`end_time`) VALUES (?,?,?,?,?,?,?)");
                    if ($stmt) {
                        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                        foreach ($days as $d) {
                            $room = $classCode;
                            $subject = 'TBD';
                            $teacherId = $creatorId;
                            $start = '07:00:00';
                            $end = '08:00:00';
                            $stmt->bind_param('ssiiiss', $room, $subject, $teacherId, $newClassId, $d, $start, $end);
                            if (!$stmt->execute()) {
                                error_log('Warning: Failed to insert schedule for day ' . $d . ': ' . $stmt->error);
                            }
                        }
                        $stmt->close();
                    }
                } catch (\Exception $e) {
                    error_log('Warning: Could not create default schedule: ' . $e->getMessage());
                }
            }
            }

            if ($ok && $newClassId > 0) {
                echo json_encode(['ok' => true, 'message' => 'Kelas berhasil ditambah.', 'class_id' => $newClassId]);
                exit;
            } else {
                error_log('ClassController::store - Insert failed: ok=' . ($ok ? 'true' : 'false') . ', newClassId=' . $newClassId);
                http_response_code(500);
                echo json_encode(['ok' => false, 'message' => 'Gagal menambah kelas. Pastikan semua field diisi dengan benar.']);
                exit;
            }
        } catch (\Throwable $e) {
            error_log('ClassController::store error: ' . $e->getMessage() . " -- " . $e->getTraceAsString());
            http_response_code(500);
            echo json_encode(['ok' => false, 'message' => 'Server error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function joinForm() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user'])) {
            $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
            header('Location: index.php?page=login');
            exit;
        }
        $userId = intval($_SESSION['user']['id'] ?? 0);
        if ($this->classService) {
            try {
                $classes = $this->classService->getAllClassesWithUserStatus($userId);
            } catch (Exception $e) {
                error_log('ClassService error: ' . $e->getMessage());
                $classes = $this->model->getAllClassesWithUserStatus($userId);
            }
        } else {
            $classes = $this->model->getAllClassesWithUserStatus($userId);
        }
        $content = dirname(__DIR__) . '/views/pages/classes/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function join() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $isAjax = (!empty($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

            if (empty($_SESSION['user'])) {
                if ($isAjax) {
                    http_response_code(401);
                    header('Content-Type: application/json');
                    error_log('ClassController::join - unauthenticated AJAX request from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                    echo json_encode(['ok' => false, 'message' => 'Silakan login terlebih dahulu.']);
                    exit;
                }
                throw new \Exception('Silakan login terlebih dahulu.');
            }
            $user = $_SESSION['user'];
            $level = $user['level'] ?? 'user';

            $classId = intval($_POST['class_id'] ?? 0);
            $code = trim($_POST['class_code'] ?? trim($_POST['code'] ?? ''));

            if (($level !== 'admin' && $level !== 'guru') && empty($code)) {
                if ($isAjax) {
                    error_log('ClassController::join - missing code in AJAX request from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['ok' => false, 'message' => 'Kode kelas wajib diisi.']);
                    exit;
                }
                throw new \Exception('Kode kelas wajib diisi.');
            }

            if ($classId) {
                $class = $this->model->getById($classId);
            } else {
                $class = $this->model->findByCode($code);
            }

            if (!$class) {
                if ($isAjax) {
                    error_log('ClassController::join - invalid code "' . $code . '" from ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['ok' => false, 'message' => 'Kode kelas tidak valid atau kelas tidak ditemukan.']);
                    exit;
                }
                throw new \Exception('Kode kelas tidak valid atau kelas tidak ditemukan.');
            }

            $role = (is_teacher_level($level) || $level === 'admin') ? 'teacher' : 'user';

            if ($this->classService) {
                $result = $this->classService->joinClass(intval($user['id']), intval($class['id']), $role);
            } else {
                $result = $this->model->addMember(intval($class['id']), intval($user['id']), $role);
            }
            if ($result) {
                if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => true, 'message' => 'Berhasil bergabung ke kelas.', 'class_id' => intval($class['id'])]);
                    exit;
                }
                $_SESSION['success'] = 'Berhasil bergabung ke kelas.';
            } else {
                if (!empty($_GET['ajax']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
                    error_log('ClassController::join - failed to add member: class_id=' . intval($class['id'] ?? 0) . ' user_id=' . intval($user['id'] ?? 0));
                    header('Content-Type: application/json');
                    echo json_encode(['ok' => false, 'message' => 'Gagal bergabung ke kelas.']);
                    exit;
                }
                $_SESSION['error'] = 'Gagal bergabung ke kelas.';
            }
        } catch (\Exception $e) {
            if (!empty($isAjax)) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
                exit;
            }
            $_SESSION['error'] = $e->getMessage();
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

    public function detail($id = null) {
        if ($id === null) {
            $id = intval($_GET['id'] ?? 0);
            if (!$id && isset($_GET['page'])) {
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

        $currentUserId = intval($_SESSION['user']['id'] ?? 0);
        $currentUserLevel = $_SESSION['user']['level'] ?? 'user';

        $class = $this->model->getById($id, $currentUserId);

        if (!$class) {
            $_SESSION['flash'] = 'Kelas tidak ditemukan.';
            header('Location: index.php?page=class');
            exit;
        }

        $isMember = (isset($class['is_joined']) && $class['is_joined'] == 1);
        $isAdmin = ($currentUserLevel === 'admin');

        if (!$isMember && !$isAdmin) {
            $_SESSION['flash'] = 'Anda tidak memiliki akses untuk melihat kelas ini.';
            header('Location: index.php?page=class');
            exit;
        }

        $members = $this->model->getMembers($id);

        $canManage = false;
        if (!empty($currentUserId)) {
            if (intval($class['created_by'] ?? 0) === $currentUserId) $canManage = true;
            if (!$canManage && $isAdmin) $canManage = true;
            if (!$canManage) {
                $memberRole = $class['member_role'] ?? '';
                if (in_array(strtolower($memberRole), ['guru','admin','teacher'])) $canManage = true;
            }
        }
        $schedules = [];
        if (is_file(dirname(__DIR__) . '/model/ScheduleModel.php')) {
            require_once dirname(__DIR__) . '/model/ScheduleModel.php';
            $schedModel = new ScheduleModel($this->db);
            $schedules = $schedModel->getAllWithRelations(['class_id' => $id]);
        }

        $tasks = [];
        if (is_file(dirname(__DIR__) . '/model/TasksCompletedModel.php')) {
            require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
            $tkModel = new TasksCompletedModel($this->db);
            $tasks = $tkModel->getAll(['class_id' => $id]);
        }

        $content = dirname(__DIR__) . '/views/pages/classes/detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function updateMemberRole() {
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['user'])) throw new \Exception('Silakan login terlebih dahulu.');

            $currentUserId = intval($_SESSION['user']['id'] ?? 0);
            $classId = intval($_POST['class_id'] ?? 0);
            $userId = intval($_POST['user_id'] ?? 0);
            if (!$classId || !$userId) throw new \Exception('Parameter tidak lengkap.');

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

            $allowed = false;
            $currLevel = $_SESSION['user']['level'] ?? '';
            if ($currLevel === 'admin' || $currLevel === 'Guru' || $currLevel === 'guru') $allowed = true;

            $class = $this->model->getById($classId);
            if (!$allowed && $class && intval($class['created_by'] ?? 0) === $currentUserId) $allowed = true;

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
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (empty($_SESSION['user'])) {
                throw new \Exception('Silakan login terlebih dahulu.');
            }

            $id = intval($_POST['id'] ?? 0);
            if (!$id) {
                throw new \Exception('ID kelas tidak valid.');
            }

            $currentUserId = intval($_SESSION['user']['id'] ?? 0);
            $currentLevel = $_SESSION['user']['level'] ?? '';
            
            $class = $this->model->getById($id);
            if (!$class) {
                throw new \Exception('Kelas tidak ditemukan.');
            }

            $canDelete = ($currentLevel === 'admin') || (intval($class['created_by'] ?? 0) === $currentUserId);

            if (!$canDelete) {
                throw new \Exception('Anda tidak memiliki izin untuk menghapus kelas ini.');
            }

            $this->db->begin_transaction();
            
            try {
                $taskIds = [];
                $taskRes = $this->db->query('SELECT id FROM tasks_completed WHERE class_id = ' . intval($id));
                if ($taskRes) {
                    while ($row = $taskRes->fetch_assoc()) {
                        $taskIds[] = intval($row['id']);
                    }
                }
                
                if (!empty($taskIds)) {
                    $taskIdsStr = implode(',', $taskIds);
                    if (!$this->db->query('DELETE FROM task_reminders WHERE task_id IN (' . $taskIdsStr . ')')) {
                        throw new Exception('Gagal menghapus pengingat tugas terkait.');
                    }
                }
                
                if (!$this->db->query('DELETE FROM task_submissions WHERE class_id = ' . intval($id))) throw new Exception('Gagal menghapus pengumpulan tugas.');
                if (!$this->db->query('DELETE FROM tasks_completed WHERE class_id = ' . intval($id))) throw new Exception('Gagal menghapus tugas.');
                if (!$this->db->query('DELETE FROM schedule WHERE class_id = ' . intval($id))) throw new Exception('Gagal menghapus jadwal.');
                if (!$this->db->query('DELETE FROM class_members WHERE class_id = ' . intval($id))) throw new Exception('Gagal menghapus anggota kelas.');
                
                if (!$this->model->delete($id)) {
                    throw new \Exception('Gagal menghapus data kelas utama.');
                }

                $this->db->commit();
                
                echo json_encode(['success' => true, 'message' => 'Kelas dan semua data terkait berhasil dihapus.']);

            } catch (\Exception $e) {
                $this->db->rollback();
                throw $e;
            }
        } catch (\Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    public function members() {
        $class_id = intval($_GET['id'] ?? 0);
        $class = $this->model->getById($class_id);
        $members = $this->model->getMembers($class_id);
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
        header('Content-Type: application/json');
        
        $class_id = intval($_POST['class_id'] ?? 0);
        $user_id = intval($_POST['user_id'] ?? 0);
        
        if ($class_id <= 0 || $user_id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid.'
            ]);
            exit;
        }
        
        if ($this->classService) {
            $ok = $this->classService->leaveClass($user_id, $class_id);
        } else {
            $ok = $this->model->removeMember($class_id, $user_id);
        }
        
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Anggota berhasil dihapus dari kelas.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus anggota.'
            ]);
        }
        exit;
    }
}