<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
class TaskController {
    private $model;
    private $db;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new TasksCompletedModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public function index() {
        $userLevel = $_SESSION['level'] ?? 'user';
        $userId = $_SESSION['user_id'] ?? 0;
        
        // Apply filters based on user level
        $filters = [];
        
        if ($userLevel === 'guru') {
            // Guru hanya melihat task yang mereka buat
            $filters['teacher_id'] = $userId;
        } elseif ($userLevel === 'user') {
            // Siswa hanya melihat task kelas mereka
            $tasks = $this->model->getByStudentClass($userId);
            $content = dirname(__DIR__) . '/views/pages/tasks/index.php';
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
            return;
        }
        // Admin melihat semua task (tidak ada filter)
        
        $tasks = $this->model->getAll($filters);

        // detect AJAX fragment requests (used by header live-search / page fragments)
        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }

        // If the tasks table doesn't store schedule_id/subject_id, try to enrich tasks
        // with a best-effort schedule lookup. Use a batched approach to avoid one DB
        // query per task: 1) collect (class,teacher) pairs and class ids, 2) query
        // schedules for those pairs, 3) fallback to class-only schedules for classes
        // without a teacher-specific schedule.
        if (!empty($tasks)) {
            $pairs = [];
            $classIds = [];
            foreach ($tasks as $tk) {
                if (!empty($tk['schedule_subject'])) continue; // already provided
                $classId = intval($tk['class_id'] ?? 0);
                $teacherId = intval($tk['user_id'] ?? 0);
                if ($classId <= 0) continue;
                $key = $classId . '_' . $teacherId;
                $pairs[$key] = ['class' => $classId, 'teacher' => $teacherId];
                $classIds[$classId] = $classId;
            }

            $mapByPair = []; // map: "class_teacher" => schedule row
            $mapByClass = []; // map: class_id => schedule row (fallback)

            // Query schedules matching specific (class, teacher) pairs
            if (!empty($pairs)) {
                $conds = [];
                foreach ($pairs as $p) {
                    $c = intval($p['class']);
                    $t = intval($p['teacher']);
                    $conds[] = "(class_id = " . $c . " AND teacher_id = " . $t . ")";
                }
                $sql = "SELECT class_id, teacher_id, subject, day, start_time, end_time FROM schedule WHERE " . implode(' OR ', $conds);
                $res = $this->db->query($sql);
                if ($res && $res->num_rows > 0) {
                    while ($r = $res->fetch_assoc()) {
                        $k = intval($r['class_id']) . '_' . intval($r['teacher_id']);
                        if (!isset($mapByPair[$k])) $mapByPair[$k] = $r;
                    }
                }
            }

            // For classes that still lack a schedule match, query one schedule per class (fallback)
            $missingClassIds = [];
            foreach ($classIds as $cid) {
                // check if any pair for this class has a schedule
                $found = false;
                foreach ($pairs as $p) {
                    if ($p['class'] == $cid) {
                        $k = $p['class'] . '_' . $p['teacher'];
                        if (isset($mapByPair[$k])) { $found = true; break; }
                    }
                }
                if (!$found) $missingClassIds[] = intval($cid);
            }

            if (!empty($missingClassIds)) {
                $ids = implode(',', array_map('intval', $missingClassIds));
                $sql2 = "SELECT class_id, subject, day, start_time, end_time FROM schedule WHERE class_id IN (" . $ids . ")";
                $res2 = $this->db->query($sql2);
                if ($res2 && $res2->num_rows > 0) {
                    while ($r = $res2->fetch_assoc()) {
                        $cid = intval($r['class_id']);
                        if (!isset($mapByClass[$cid])) $mapByClass[$cid] = $r;
                    }
                }
            }

            // Merge schedules into tasks
            foreach ($tasks as &$tk) {
                if (!empty($tk['schedule_subject'])) continue;
                $classId = intval($tk['class_id'] ?? 0);
                $teacherId = intval($tk['user_id'] ?? 0);
                if ($classId <= 0) continue;
                $pairKey = $classId . '_' . $teacherId;
                $sch = null;
                if (isset($mapByPair[$pairKey])) {
                    $sch = $mapByPair[$pairKey];
                } elseif (isset($mapByClass[$classId])) {
                    $sch = $mapByClass[$classId];
                }
                if ($sch) {
                    $tk['schedule_subject'] = $sch['subject'] ?? '';
                    $tk['schedule_day'] = $sch['day'] ?? '';
                    $tk['schedule_start'] = $sch['start_time'] ?? '';
                    $tk['schedule_end'] = $sch['end_time'] ?? '';
                }
            }
            unset($tk);
        }
        $content = dirname(__DIR__) . '/views/pages/tasks/index.php';
        if ($ajax) {
            // include the page fragment directly (view checks for $ajax if needed)
            include $content;
            return;
        }

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function create() {
        // Check if user can create tasks (admin or guru only)
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'user') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat task.';
            header('Location: index.php?page=tasks');
            exit;
        }
        
        // Load classes and subjects for the form
        global $config;
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/SubjectsModel.php';
        $classModel = new ClassModel($config);
        $subjectModel = new SubjectsModel($config);
        $classes = $classModel->getAll();
        $subjects = $subjectModel->getAll();

        // fetch teachers for assignment (users from users table with role guru or admin)
        $teachers = [];
        $res = mysqli_query($config, "SELECT id, name, username, `level` FROM users WHERE `level` IN ('guru','admin') ORDER BY name ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $teachers[] = $r;
        }

        // fetch schedules so tasks can be linked to a schedule entry
        require_once dirname(__DIR__) . '/model/ScheduleModel.php';
        $scheduleModel = new ScheduleModel($config);
        // if current user is guru, limit schedules to their own
        $schedules = [];
        if (isset($_SESSION['level']) && $_SESSION['level'] === 'guru') {
            $schedules = $scheduleModel->getAll(['teacher_id' => intval($_SESSION['user_id'] ?? 0)]);
        } else {
            $schedules = $scheduleModel->getAll();
        }

        $content = dirname(__DIR__) . '/views/pages/tasks/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function store() {
        // Check if user can create tasks (admin or guru only)
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'user') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat task.';
            header('Location: index.php?page=tasks');
            exit;
        }
        
        // Create a new task (assignment). File uploads belong to submissions.
        try {
            // Determine teacher (task owner). Admins may set teacher_id in the form.
            $currentUserId = $_SESSION['user_id'] ?? 0;
            $currentLevel = $_SESSION['level'] ?? 'user';
            $teacherId = $currentUserId;
            if ($currentLevel === 'admin' && !empty($_POST['teacher_id'])) {
                $teacherId = intval($_POST['teacher_id']);
            }

            $data = [
                'user_id' => $teacherId,
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'status' => strtolower(trim($_POST['status'] ?? 'pending')),
                'deadline' => trim($_POST['deadline'] ?? null),
                'class_id' => intval($_POST['class_id'] ?? 0),
                'subject_id' => intval($_POST['subject_id'] ?? 0),
                'schedule_id' => !empty($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null
            ];

            // Enhanced validation
            $errors = [];
            
            if ($data['title'] === '') {
                $errors[] = 'Judul task wajib diisi.';
            }
            
            if ($data['description'] === '') {
                $errors[] = 'Deskripsi task wajib diisi.';
            }
            
            if (!$data['class_id']) {
                $errors[] = 'Kelas wajib dipilih.';
            }
            
            if (!$data['subject_id']) {
                $errors[] = 'Mata pelajaran wajib dipilih.';
            }
            
            if ($data['deadline'] === '') {
                $errors[] = 'Deadline wajib diisi.';
            } elseif ($data['deadline'] < date('Y-m-d')) {
                $errors[] = 'Deadline tidak boleh lebih awal dari hari ini.';
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode(' ', $errors);
                header('Location: index.php?page=tasks/create'); 
                exit;
            }

            $result = $this->model->create($data);
            if ($result) {
                $_SESSION['success'] = "Task berhasil dibuat!";
            } else {
                $_SESSION['error'] = "Gagal membuat task. Silakan coba lagi.";
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        }

        header('Location: index.php?page=tasks');
        exit;
    }
    /**
     * Handle student submission (file upload) for a task
     */
    public function storeSubmission() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $task_id = intval($_POST['task_id'] ?? 0);
        $user_id = intval($_SESSION['user_id'] ?? 0);
        $class_id = intval($_POST['class_id'] ?? 0);

        if (!$task_id || !$user_id) {
            $_SESSION['error'] = 'Invalid submission.';
            header('Location: index.php?page=tasks'); exit;
        }

        // Handle file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No file uploaded or upload error.';
            header('Location: index.php?page=tasks'); exit;
        }

        $uploadDir = 'public/uploads/task_submissions/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileName = time() . '_' . basename($_FILES['file']['name']);
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            $_SESSION['error'] = 'Failed to move uploaded file.';
            header('Location: index.php?page=tasks'); exit;
        }

        // Save submission record
        global $config;
        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        $subModel = new TaskSubmissionsModel($config);
        $ok = $subModel->create([
            'task_id' => $task_id,
            'user_id' => $user_id,
            'class_id' => $class_id,
            'file_path' => $filePath,
            'status' => 'submitted'
        ]);

        $_SESSION['success'] = $ok ? 'Submission saved.' : 'Failed to save submission.';
        header('Location: index.php?page=tasks'); exit;
    }
    
    public function submissions() {
        $taskId = intval($_GET['task_id'] ?? 0);
        $userLevel = $_SESSION['level'] ?? 'user';
        
        // Only admin and guru can view submissions
        if ($userLevel === 'user') {
            http_response_code(403);
            echo '<div class="text-center text-red-400">Access denied</div>';
            exit;
        }
        
        if (!$taskId) {
            echo '<div class="text-center text-red-400">Invalid task ID</div>';
            exit;
        }
        
        // Get task info
        $task = $this->model->getById($taskId);
        if (!$task) {
            echo '<div class="text-center text-red-400">Task not found</div>';
            exit;
        }
        
        // Check if user can view this task's submissions
        if ($userLevel === 'guru' && $task['user_id'] != $_SESSION['user_id']) {
            echo '<div class="text-center text-red-400">You can only view submissions for your own tasks</div>';
            exit;
        }
        
        // Get submissions
        global $config;
        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        $subModel = new TaskSubmissionsModel($config);
        $submissions = $subModel->getByTask($taskId);
        
        if (empty($submissions)) {
            echo '<div class="text-center text-gray-400 py-8">No submissions yet</div>';
            exit;
        }
        
        echo '<div class="space-y-4">';
        foreach ($submissions as $submission) {
            echo '<div class="bg-gray-700 rounded-lg p-4 border border-gray-600">';
            echo '<div class="flex items-center justify-between mb-3">';
            echo '<div class="flex items-center gap-3">';
            echo '<div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center">';
            echo '<span class="text-white text-sm font-bold">' . strtoupper(substr($submission['name'] ?? 'N/A', 0, 1)) . '</span>';
            echo '</div>';
            echo '<div>';
            echo '<div class="text-white font-medium">' . htmlspecialchars($submission['name'] ?? 'N/A') . '</div>';
            echo '<div class="text-gray-400 text-sm">' . htmlspecialchars($submission['username'] ?? '') . '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="text-gray-400 text-sm">' . date('d M Y, H:i', strtotime($submission['submitted_at'])) . '</div>';
            echo '</div>';
            
            echo '<div class="flex items-center gap-4">';
            echo '<a href="' . htmlspecialchars($submission['file_path']) . '" target="_blank" class="flex items-center gap-2 text-indigo-400 hover:text-indigo-300">';
            echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>';
            echo '</svg>';
            echo 'Download File';
            echo '</a>';
            
            if ($submission['grade'] !== null) {
                echo '<span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm">Grade: ' . $submission['grade'] . '</span>';
            } else {
                echo '<span class="px-3 py-1 bg-yellow-500/20 text-yellow-300 rounded-full text-sm">Pending Grade</span>';
            }
            echo '</div>';
            
            if ($submission['feedback']) {
                echo '<div class="mt-3 p-3 bg-gray-600 rounded-lg">';
                echo '<div class="text-gray-300 text-sm">' . htmlspecialchars($submission['feedback']) . '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        echo '</div>';
        exit;
    }
    
    public function edit() {
        $id = intval($_GET['id'] ?? 0);
        $task = $this->model->getById($id);
        // Load classes and subjects for the form
        global $config;
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/SubjectsModel.php';
        $classModel = new ClassModel($config);
        $subjectModel = new SubjectsModel($config);
        $classes = $classModel->getAll();
        $subjects = $subjectModel->getAll();

        // fetch teachers for assignment (users from users table with role guru or admin)
        $teachers = [];
        $res = mysqli_query($config, "SELECT id, name, username, `level` FROM users WHERE `level` IN ('guru','admin') ORDER BY name ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $teachers[] = $r;
        }

        // fetch schedules for edit form as well
        require_once dirname(__DIR__) . '/model/ScheduleModel.php';
        $scheduleModel = new ScheduleModel($config);
        if (isset($_SESSION['level']) && $_SESSION['level'] === 'guru') {
            $schedules = $scheduleModel->getAll(['teacher_id' => intval($_SESSION['user_id'] ?? 0)]);
        } else {
            $schedules = $scheduleModel->getAll();
        }

        $content = dirname(__DIR__) . '/views/pages/tasks/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
    public function update() {
        $id = intval($_POST['id'] ?? 0);
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => trim($_POST['status'] ?? ''),
            'deadline' => trim($_POST['deadline'] ?? ''),
            'class_id' => intval($_POST['class_id'] ?? 0),
            'subject_id' => intval($_POST['subject_id'] ?? 0),
            'schedule_id' => isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null
        ];
        // allow admin to reassign teacher
        $currentLevel = $_SESSION['level'] ?? 'user';
        if ($currentLevel === 'admin' && !empty($_POST['teacher_id'])) {
            $data['user_id'] = intval($_POST['teacher_id']);
        }
        if ($data['title'] === '' || $data['description'] === '' || $data['status'] === '' || $data['deadline'] === '' || !$data['class_id'] || !$data['subject_id']) {
            $_SESSION['flash'] = 'Semua field wajib diisi!';
            header('Location: index.php?page=tasks/edit&id='.$id); exit;
        }
        $ok = $this->model->update($id, $data);
        $_SESSION['flash'] = $ok ? 'Tugas berhasil diupdate.' : 'Gagal update tugas.';
        header('Location: index.php?page=tasks'); exit;
    }
    public function delete() {
        $id = intval($_POST['id'] ?? 0);
        $ok = $this->model->delete($id);
        $_SESSION['flash'] = $ok ? 'Tugas dihapus.' : 'Gagal hapus tugas.';
        header('Location: index.php?page=tasks'); exit;
    }
}
