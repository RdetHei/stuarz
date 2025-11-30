<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
require_once dirname(__DIR__) . '/model/GradesModel.php';
require_once dirname(__DIR__) . '/helpers/notifier.php';

class TaskController {
    private $model;
    private $submissionModel;
    private $gradesModel;
    private $db;
    public function __construct() {
        global $config;
        $this->db = $config;
        $this->model = new TasksCompletedModel($config);
        $this->submissionModel = new TaskSubmissionsModel($config);
        $this->gradesModel = new GradesModel($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }
    public function index() {
        $userLevel = $_SESSION['level'] ?? 'user';
        $userId = $_SESSION['user_id'] ?? 0;
        
        $filters = [];
        $progressSummary = [
            'total' => 0,
            'submitted' => 0,
            'in_review' => 0,
            'needs_revision' => 0,
            'graded' => 0
        ];
        $studentSubmissions = [];
        $teacherSubmissionStats = [];
        $pendingReviewTasks = [];
        $dueSoonTasks = [];
        
        if ($userLevel === 'guru') {
            $filters['teacher_id'] = $userId;
        } elseif ($userLevel === 'user') {
            $tasks = $this->model->getByStudentClass($userId);
            $summary = $this->compileStudentProgress($tasks, $userId);
            $studentSubmissions = $summary['submissions'];
            $progressSummary = $summary['progress'];
            $dueSoonTasks = $this->collectDueSoonTasks($tasks);
            $content = dirname(__DIR__) . '/views/pages/tasks/index.php';
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
            return;
        }
        
        $tasks = $this->model->getAll($filters);
        $progressSummary['total'] = count($tasks);
        if (!empty($tasks)) {
            $taskIds = array_column($tasks, 'id');
            $teacherSubmissionStats = $this->submissionModel->getStatsByTasks($taskIds);
            $teacherSummary = $this->summarizeTeacherQueue($tasks, $teacherSubmissionStats);
            $progressSummary = $teacherSummary['progress'];
            $pendingReviewTasks = $teacherSummary['pending'];
            $dueSoonTasks = $this->collectDueSoonTasks($tasks);
        }

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
    /**
     * Student-facing task list (simplified)
     */
    public function studentTasks()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }

        // Fetch tasks relevant to this student
        $tasks = $this->model->getByStudentClass($userId);
        // Enrich tasks with subject name if available
        foreach ($tasks as &$t) {
            if (empty($t['subject_name']) && !empty($t['subject_id'])) {
                $res = mysqli_query($this->db, "SELECT name FROM subjects WHERE id = " . intval($t['subject_id']) . " LIMIT 1");
                if ($res && $r = mysqli_fetch_assoc($res)) $t['subject_name'] = $r['name'];
            }
            $t['task_id'] = $t['id'];
        }
        unset($t);

        $content = dirname(__DIR__) . '/views/pages/student/tasks.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function studentTaskDetail($id = 0)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        $taskId = intval($id ?: ($_GET['id'] ?? 0));
        if (!$taskId) { $_SESSION['error'] = 'Task ID invalid'; header('Location: index.php?page=student/tasks'); exit; }

        $task = $this->model->getById($taskId);
        if (!$task) { $_SESSION['error'] = 'Task not found'; header('Location: index.php?page=student/tasks'); exit; }

        // Ensure task belongs to student's class (simple check)
        $studentTasks = $this->model->getByStudentClass($userId);
        $allowed = false;
        foreach ($studentTasks as $st) if (intval($st['id']) === $taskId) { $allowed = true; break; }
        if (!$allowed) { $_SESSION['error'] = 'Anda tidak dapat melihat tugas ini.'; header('Location: index.php?page=student/tasks'); exit; }

        // Get latest submission by this user for this task
        $submission = $this->submissionModel->getLatestByTaskIds([$taskId], $userId)[$taskId] ?? null;

        $content = dirname(__DIR__) . '/views/pages/student/task_detail.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function studentSubmit()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) { header('Location: index.php?page=login'); exit; }
        $taskId = intval($_GET['task_id'] ?? 0);
        $task = $this->model->getById($taskId);
        if (!$task) { $_SESSION['error'] = 'Task not found.'; header('Location: index.php?page=student/tasks'); exit; }
        $content = dirname(__DIR__) . '/views/pages/student/submit.php';
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
                'schedule_id' => !empty($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null,
                'approval_required' => !empty($_POST['approval_required']) ? 1 : 0,
                'max_attempts' => max(1, intval($_POST['max_attempts'] ?? 1)),
                'reminder_at' => $this->normalizeDateTimeInput($_POST['reminder_at'] ?? null),
                'allow_late' => !empty($_POST['allow_late']) ? 1 : 0,
                'late_deadline' => $this->normalizeDateTimeInput($_POST['late_deadline'] ?? null, true),
                'workflow_state' => $_POST['workflow_state'] ?? 'published',
                'grading_rubric' => $this->prepareRubricForStorage($_POST['grading_rubric'] ?? '')
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
            if (!empty($data['allow_late']) && !empty($data['late_deadline']) && $data['late_deadline'] < $data['deadline']) {
                $errors[] = 'Late deadline harus setelah deadline utama.';
            }
            
            $allowedStates = ['draft','published','in_review','closed'];
            if (!in_array($data['workflow_state'], $allowedStates, true)) {
                $data['workflow_state'] = 'published';
            }

            if (!empty($errors)) {
                $_SESSION['error'] = implode(' ', $errors);
                header('Location: index.php?page=tasks/create'); 
                exit;
            }

            $result = $this->model->create($data);
            if ($result) {
                $taskId = $this->db->insert_id;
                if ($taskId) {
                    $this->notifyClassMembers($taskId, $data);
                }
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

        $task = $this->model->getById($task_id);
        if (!$task) {
            $_SESSION['error'] = 'Task not found.';
            header('Location: index.php?page=tasks'); exit;
        }

        if (!$this->canSubmitToTask($task)) {
            $_SESSION['error'] = 'Pengumpulan belum dibuka atau sudah ditutup.';
            header('Location: index.php?page=tasks'); exit;
        }

        $maxAttempts = intval($task['max_attempts'] ?? 1);
        $currentAttempt = $this->submissionModel->countAttempts($task_id, $user_id);
        if ($currentAttempt >= $maxAttempts) {
            $_SESSION['error'] = 'Anda sudah mencapai batas pengumpulan.';
            header('Location: index.php?page=tasks'); exit;
        }

        if (!$this->isWithinDeadline($task) && empty($task['allow_late'])) {
            $_SESSION['error'] = 'Deadline sudah lewat.';
            header('Location: index.php?page=tasks'); exit;
        }

        // Handle file upload
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'No file uploaded or upload error.';
            header('Location: index.php?page=tasks'); exit;
        }

        // Server-side file validation
        $allowedExt = ['pdf','doc','docx','txt','jpg','jpeg','png'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        $originalName = $_FILES['file']['name'];
        $size = $_FILES['file']['size'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            $_SESSION['error'] = 'Tipe file tidak didukung.';
            header('Location: index.php?page=tasks'); exit;
        }
        if ($size > $maxSize) {
            $_SESSION['error'] = 'Ukuran file melebihi batas 5MB.';
            header('Location: index.php?page=tasks'); exit;
        }

        $uploadDir = 'public/uploads/task_submissions/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
        // generate safe filename
        $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $fileName = time() . '_' . $safeBase . '.' . $ext;
        $filePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            $_SESSION['error'] = 'Failed to move uploaded file.';
            header('Location: index.php?page=tasks'); exit;
        }

        $attemptNo = $currentAttempt + 1;
        $isFinal = isset($_POST['is_final']) ? 1 : 0;
        if ($attemptNo >= $maxAttempts) $isFinal = 1;
        $reviewStatus = !empty($task['approval_required']) ? 'pending' : 'in_review';

        $ok = $this->submissionModel->createAttempt([
            'task_id' => $task_id,
            'user_id' => $user_id,
            'class_id' => $class_id ?: intval($task['class_id'] ?? 0),
            'file_path' => $filePath,
            'status' => 'submitted',
            'attempt_no' => $attemptNo,
            'is_final' => $isFinal,
            'review_status' => $reviewStatus
        ]);

        if ($ok && $isFinal) {
            $this->submissionModel->markFinalAttempt($task_id, $user_id);
        }

        if ($ok) {
            $studentName = $_SESSION['user']['name'] ?? 'Siswa';
            notify_event($this->db, 'submission', 'task', $task_id, intval($task['user_id']), $studentName . " mengumpulkan tugas (percobaan #{$attemptNo}).", 'index.php?page=tasks');
            $finalNote = $isFinal ? ' (Final)' : '';
            $_SESSION['success'] = "Pengumpulan percobaan #{$attemptNo}{$finalNote} tersimpan.";
        } else {
            $_SESSION['error'] = 'Failed to save submission.';
        }
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
        $submissions = $this->submissionModel->getByTask($taskId);
        
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
            
            if ($userLevel !== 'user') {
                echo '<form method="post" action="index.php?page=tasks/review" class="mt-4 border-t border-gray-600 pt-4 space-y-3">';
                echo '<input type="hidden" name="submission_id" value="' . intval($submission['id']) . '">';
                echo '<label class="text-sm text-gray-300">Status Review</label>';
                $statuses = [
                    'pending' => 'Pending',
                    'in_review' => 'In Review',
                    'needs_revision' => 'Needs Revision',
                    'approved' => 'Approved',
                    'graded' => 'Graded'
                ];
                echo '<select name="review_status" class="w-full bg-gray-700 border border-gray-500 rounded-lg px-3 py-2 text-white">';
                foreach ($statuses as $key => $label) {
                    $selected = ($submission['review_status'] ?? '') === $key ? 'selected' : '';
                    echo '<option value="'.$key.'" '.$selected.'>'.$label.'</option>';
                }
                echo '</select>';
                echo '<label class="text-sm text-gray-300">Nilai</label>';
                $gradeVal = $submission['grade'] !== null ? floatval($submission['grade']) : '';
                echo '<input type="number" name="grade" step="0.01" value="'.$gradeVal.'" class="w-full bg-gray-700 border border-gray-500 rounded-lg px-3 py-2 text-white">';
                echo '<label class="text-sm text-gray-300">Feedback</label>';
                echo '<textarea name="feedback" rows="3" class="w-full bg-gray-700 border border-gray-500 rounded-lg px-3 py-2 text-white">'.htmlspecialchars($submission['feedback'] ?? '').'</textarea>';
                echo '<label class="text-sm text-gray-300">Rubric Breakdown (JSON atau format Kriteria:Skor per baris)</label>';
                echo '<textarea name="rubric_breakdown" rows="3" class="w-full bg-gray-700 border border-gray-500 rounded-lg px-3 py-2 text-white"></textarea>';
                echo '<button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg">Update Review</button>';
                echo '</form>';
            }
            
            echo '</div>';
        }
        echo '</div>';
        exit;
    }

    public function reviewSubmission() {
        $this->ensureTeacher();
        $submissionId = intval($_POST['submission_id'] ?? 0);
        if (!$submissionId) {
            $_SESSION['error'] = 'Submission tidak valid.';
            header('Location: index.php?page=tasks'); exit;
        }
        $submission = $this->submissionModel->getById($submissionId);
        if (!$submission) {
            $_SESSION['error'] = 'Submission tidak ditemukan.';
            header('Location: index.php?page=tasks'); exit;
        }
        $task = $this->model->getById($submission['task_id']);
        if (!$task) {
            $_SESSION['error'] = 'Task tidak ditemukan.';
            header('Location: index.php?page=tasks'); exit;
        }
        $this->authorizeTaskOwnership($task);

        $reviewStatus = $_POST['review_status'] ?? 'pending';
        $feedback = trim($_POST['feedback'] ?? '');
        $grade = isset($_POST['grade']) && $_POST['grade'] !== '' ? floatval($_POST['grade']) : null;
        $rubricRaw = trim($_POST['rubric_breakdown'] ?? '');

        $update = [
            'review_status' => $reviewStatus,
            'feedback' => $feedback !== '' ? $feedback : null,
            'reviewed_by' => $_SESSION['user_id'],
            'reviewed_at' => date('Y-m-d H:i:s')
        ];
        if ($grade !== null) {
            $update['grade'] = $grade;
            $update['status'] = 'graded';
        }
        if ($rubricRaw !== '') {
            $update['grade_breakdown'] = json_encode($this->parseRubricInput($rubricRaw));
        }

        $ok = $this->submissionModel->updateReview($submissionId, $update);

        if ($ok && $reviewStatus === 'graded' && $grade !== null) {
            $this->recordGrade($submission, $task, $grade);
        }

        $message = $reviewStatus === 'graded' ? 'Nilai tugas Anda sudah tersedia.' : 'Status tugas Anda telah diperbarui.';
        $redirect = $reviewStatus === 'graded' ? 'index.php?page=grades' : 'index.php?page=tasks';
        notify_event($this->db, 'review', 'task', $submission['task_id'], intval($submission['user_id']), $message, $redirect);

        $_SESSION['success'] = $ok ? 'Review submission diperbarui.' : 'Gagal memperbarui review.';
        header('Location: index.php?page=tasks'); exit;
    }

    public function sendReminders() {
        $this->ensureTeacher();
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        $classModel = new ClassModel($this->db);
        $tasks = $this->model->getTasksRequiringReminder();
        $sent = 0;
        foreach ($tasks as $task) {
            $members = $classModel->getMembers($task['class_id'] ?? 0);
            foreach ($members as $member) {
                notify_event($this->db, 'reminder', 'task', intval($task['id']), intval($member['user_id']), 'Reminder tugas: '.$task['title'], 'index.php?page=tasks');
                $sent++;
                $message = $this->db->real_escape_string('Reminder for '.$task['title']);
                $this->db->query("INSERT INTO task_reminders (task_id, user_id, reminder_type, message) VALUES (" . intval($task['id']) . ", " . intval($member['user_id']) . ", 'deadline', '{$message}')");
            }
            $this->model->markReminderSent($task['id']);
        }
        $_SESSION['success'] = $sent ? "Reminder dikirim ke {$sent} siswa." : 'Tidak ada reminder yang perlu dikirim.';
        header('Location: index.php?page=tasks'); exit;
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
            'schedule_id' => isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null,
            'approval_required' => !empty($_POST['approval_required']) ? 1 : 0,
            'max_attempts' => max(1, intval($_POST['max_attempts'] ?? 1)),
            'reminder_at' => $this->normalizeDateTimeInput($_POST['reminder_at'] ?? null),
            'allow_late' => !empty($_POST['allow_late']) ? 1 : 0,
            'late_deadline' => $this->normalizeDateTimeInput($_POST['late_deadline'] ?? null, true),
            'workflow_state' => $_POST['workflow_state'] ?? 'published',
            'grading_rubric' => $this->prepareRubricForStorage($_POST['grading_rubric'] ?? '')
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
        if (!empty($data['allow_late']) && !empty($data['late_deadline']) && $data['late_deadline'] < $data['deadline']) {
            $_SESSION['flash'] = 'Late deadline harus setelah deadline utama.';
            header('Location: index.php?page=tasks/edit&id='.$id); exit;
        }
        $allowedStates = ['draft','published','in_review','closed'];
        if (!in_array($data['workflow_state'], $allowedStates, true)) {
            $data['workflow_state'] = 'published';
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

    private function compileStudentProgress(array &$tasks, $userId) {
        $progress = ['total' => count($tasks), 'submitted' => 0, 'in_review' => 0, 'needs_revision' => 0, 'graded' => 0];
        if (empty($tasks)) return ['progress' => $progress, 'submissions' => []];
        $taskIds = array_column($tasks, 'id');
        $map = $this->submissionModel->getLatestByTaskIds($taskIds, $userId);
        foreach ($tasks as &$task) {
            $sub = $map[$task['id']] ?? null;
            $task['student_submission'] = $sub;
            if (!$sub) continue;
            $progress['submitted']++;
            $status = $sub['review_status'] ?? 'pending';
            if ($status === 'graded') $progress['graded']++;
            elseif ($status === 'needs_revision') $progress['needs_revision']++;
            else $progress['in_review']++;
        }
        return ['progress' => $progress, 'submissions' => $map];
    }

    private function canSubmitToTask($task) {
        $state = $task['workflow_state'] ?? 'published';
        if (in_array($state, ['draft','closed'], true)) return false;
        return true;
    }

    private function isWithinDeadline($task) {
        if (empty($task['deadline'])) return true;
        $now = date('Y-m-d');
        if ($now <= $task['deadline']) return true;
        if (!empty($task['allow_late']) && !empty($task['late_deadline'])) {
            return $now <= $task['late_deadline'];
        }
        return false;
    }

    private function ensureTeacher() {
        $level = $_SESSION['level'] ?? 'user';
        if (!in_array($level, ['admin','guru'], true)) {
            $_SESSION['error'] = 'Aksi ini hanya untuk guru/admin.';
            header('Location: index.php?page=tasks'); exit;
        }
    }

    private function authorizeTaskOwnership($task) {
        $level = $_SESSION['level'] ?? 'user';
        if ($level === 'admin') return true;
        if ($level === 'guru' && intval($task['user_id']) === intval($_SESSION['user_id'])) return true;
        $_SESSION['error'] = 'Anda tidak berhak mengelola tugas ini.';
        header('Location: index.php?page=tasks'); exit;
    }

    private function parseRubricInput($input) {
        if ($input === '') return [];
        $decoded = json_decode($input, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        $criteria = [];
        $lines = preg_split('/\r\n|\r|\n/', $input);
        foreach ($lines as $line) {
            if (strpos($line, ':') === false) continue;
            list($label, $score) = array_map('trim', explode(':', $line, 2));
            if ($label === '') continue;
            $criteria[] = ['label' => $label, 'score' => floatval($score)];
        }
        return $criteria;
    }

    private function prepareRubricForStorage($input) {
        $parsed = $this->parseRubricInput(trim($input));
        if (empty($parsed)) return null;
        return json_encode($parsed);
    }

    private function recordGrade($submission, $task, $grade) {
        $payload = [
            'user_id' => intval($submission['user_id']),
            'class_id' => intval($submission['class_id'] ?? $task['class_id'] ?? 0),
            'subject_id' => intval($task['subject_id'] ?? 0),
            'task_id' => intval($submission['task_id']),
            'score' => floatval($grade)
        ];
        $this->gradesModel->saveOrUpdate($payload);
        notify_event(
            $this->db,
            'grade',
            'task',
            $payload['task_id'],
            $payload['user_id'],
            'Nilai untuk "' . ($task['title'] ?? 'Tugas') . '" sudah tersedia.',
            'index.php?page=grades'
        );
    }

    private function collectDueSoonTasks(array $tasks, int $days = 3) {
        if (empty($tasks)) return [];
        $now = new \DateTimeImmutable('today');
        $limit = $now->modify('+' . $days . ' days');
        $soon = [];
        foreach ($tasks as $task) {
            if (empty($task['deadline'])) continue;
            $deadline = \DateTimeImmutable::createFromFormat('Y-m-d', substr($task['deadline'], 0, 10));
            if (!$deadline) continue;
            if ($deadline >= $now && $deadline <= $limit) {
                $soon[] = $task;
            }
        }
        return $soon;
    }

    private function summarizeTeacherQueue(array &$tasks, array $stats) {
        $summary = [
            'total' => count($tasks),
            'submitted' => 0,
            'in_review' => 0,
            'needs_revision' => 0,
            'graded' => 0
        ];
        $pending = [];
        foreach ($tasks as &$task) {
            $taskStat = $stats[$task['id']] ?? null;
            if ($taskStat) {
                $summary['submitted'] += intval($taskStat['total'] ?? 0);
                $summary['in_review'] += intval($taskStat['in_review'] ?? 0);
                $summary['needs_revision'] += intval($taskStat['needs_revision'] ?? 0);
                $summary['graded'] += intval($taskStat['graded'] ?? 0);
                if (($taskStat['pending'] ?? 0) > 0 || ($taskStat['needs_revision'] ?? 0) > 0) {
                    $pending[] = ['task' => $task, 'stats' => $taskStat];
                }
            }
        }
        return ['progress' => $summary, 'pending' => $pending];
    }

    private function normalizeDateTimeInput($value, $endOfDay = false) {
        $value = trim((string)$value);
        if ($value === '') return null;
        $timestamp = strtotime($value);
        if ($timestamp === false) return null;
        if ($endOfDay) {
            return date('Y-m-d 23:59:59', $timestamp);
        }
        return date('Y-m-d H:i:s', $timestamp);
    }

    private function notifyClassMembers(int $taskId, array $taskData) {
        $studentIds = $this->getClassStudentIds($taskData['class_id'] ?? 0);
        if (empty($studentIds)) return;
        $message = 'Tugas baru: ' . ($taskData['title'] ?? 'Tanpa Judul');
        foreach ($studentIds as $studentId) {
            notify_event($this->db, 'task_new', 'task', $taskId, $studentId, $message, 'index.php?page=tasks');
        }
    }

    private function getClassStudentIds($classId) {
        $classId = intval($classId);
        if ($classId <= 0) return [];
        $stmt = $this->db->prepare("SELECT name FROM classes WHERE id = ? LIMIT 1");
        if (!$stmt) return [];
        $stmt->bind_param('i', $classId);
        $stmt->execute();
        $res = $stmt->get_result();
        $classRow = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        if (!$classRow || empty($classRow['name'])) return [];
        $stmt2 = $this->db->prepare("SELECT id FROM users WHERE class = ? AND level = 'user'");
        if (!$stmt2) return [];
        $stmt2->bind_param('s', $classRow['name']);
        $stmt2->execute();
        $res2 = $stmt2->get_result();
        $ids = [];
        if ($res2) while ($row = $res2->fetch_assoc()) $ids[] = intval($row['id']);
        $stmt2->close();
        return $ids;
    }
}
