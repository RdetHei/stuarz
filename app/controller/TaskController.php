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

        $ajax = false;
        if ((isset($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
            $ajax = true;
        }





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

            $missingClassIds = [];
            foreach ($classIds as $cid) {

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

        $tasks = $this->model->getByStudentClass($userId);

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

        $studentTasks = $this->model->getByStudentClass($userId);
        $allowed = false;
        foreach ($studentTasks as $st) if (intval($st['id']) === $taskId) { $allowed = true; break; }
        if (!$allowed) { $_SESSION['error'] = 'Anda tidak dapat melihat tugas ini.'; header('Location: index.php?page=student/tasks'); exit; }

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

        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'user') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat task.';
            header('Location: index.php?page=tasks');
            exit;
        }

        global $config;
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/SubjectsModel.php';
        $classModel = new ClassModel($config);
        $subjectModel = new SubjectsModel($config);
        $classes = $classModel->getAll();
        $subjects = $subjectModel->getAll();

        $teachers = [];
        $res = mysqli_query($config, "SELECT id, name, username, `level` FROM users WHERE `level` IN ('guru','admin') ORDER BY name ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $teachers[] = $r;
        }

        require_once dirname(__DIR__) . '/model/ScheduleModel.php';
        $scheduleModel = new ScheduleModel($config);

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

        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'user') {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk membuat task.';
            header('Location: index.php?page=tasks');
            exit;
        }

        try {

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
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => implode(' ', $errors)
                ]);
                exit;
            }

            $result = $this->model->create($data);
            if ($result) {
                $taskId = $this->db->insert_id;
                if ($taskId) {
                    $this->notifyClassMembers($taskId, $data);
                }
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Tugas berhasil disimpan.'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan tugas.'
                ]);
            }
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
        exit;
    }
    /**
     * Handle student submission (file upload) for a task
     */
    public function storeSubmission() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');
        
        $task_id = intval($_POST['task_id'] ?? 0);
        $user_id = intval($_SESSION['user_id'] ?? 0);
        $class_id = intval($_POST['class_id'] ?? 0);

        if (!$task_id || !$user_id) {
            echo json_encode(['success' => false, 'message' => 'Invalid submission.']);
            exit;
        }

        $task = $this->model->getById($task_id);
        if (!$task) {
            echo json_encode(['success' => false, 'message' => 'Task not found.']);
            exit;
        }

        if (!$this->canSubmitToTask($task)) {
            echo json_encode(['success' => false, 'message' => 'Pengumpulan belum dibuka atau sudah ditutup.']);
            exit;
        }

        $maxAttempts = intval($task['max_attempts'] ?? 1);
        $currentAttempt = $this->submissionModel->countAttempts($task_id, $user_id);
        if ($currentAttempt >= $maxAttempts) {
            echo json_encode(['success' => false, 'message' => 'Anda sudah mencapai batas pengumpulan.']);
            exit;
        }

        if (!$this->isWithinDeadline($task) && empty($task['allow_late'])) {
            echo json_encode(['success' => false, 'message' => 'Deadline sudah lewat.']);
            exit;
        }

        $filePath = null;
        $fileInputName = null;

        if (isset($_FILES['submission_file']) && $_FILES['submission_file']['error'] === UPLOAD_ERR_OK) {
            $fileInputName = 'submission_file';
        } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileInputName = 'file';
        }
        
        if ($fileInputName) {

            $allowedExt = ['pdf', 'doc', 'docx', 'txt', 'jpg', 'jpeg', 'png'];
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'text/plain',
                'image/png',
                'image/jpeg',
                'image/jpg'
            ];
            $maxSize = 5 * 1024 * 1024; // 5MB
            $originalName = $_FILES[$fileInputName]['name'];
            $size = $_FILES[$fileInputName]['size'];
            $mimeType = $_FILES[$fileInputName]['type'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            
            if (!in_array($ext, $allowedExt, true)) {
                echo json_encode(['success' => false, 'message' => 'Tipe file tidak didukung.']);
                exit;
            }
            
            if ($size > $maxSize) {
                echo json_encode(['success' => false, 'message' => 'Ukuran file melebihi batas 5MB.']);
                exit;
            }

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                echo json_encode(['success' => false, 'message' => 'Tipe file tidak valid.']);
                exit;
            }

            $uploadDirFs = dirname(__DIR__, 2) . '/public/uploads/submissions/';
            $uploadDirWeb = 'uploads/submissions/';
            if (!file_exists($uploadDirFs)) {
                mkdir($uploadDirFs, 0777, true);
            }

            $originalName = basename($_FILES[$fileInputName]['name']);
            $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
            $safeFileName = uniqid('submission_', true) . '.' . $fileExtension;
            $uploadPath = $uploadDirFs . $safeFileName;

            if (!move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadPath)) {
                error_log('move_uploaded_file failed, tmp_name: ' . ($_FILES[$fileInputName]['tmp_name'] ?? ''));
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal menyimpan file.'
                ]);
                exit;
            }
            
            $filePath = $uploadDirWeb . $safeFileName;
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

        $studentName = $_SESSION['user']['name'] ?? 'Siswa';
        if ($ok) {
            notify_event($this->db, 'submission', 'task', $task_id, intval($task['user_id']), $studentName . " mengumpulkan tugas (percobaan #{$attemptNo}).", 'index.php?page=tasks');
        }

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Pengumpulan berhasil disimpan.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan pengumpulan.'
            ]);
        }
        exit;
    }
    
    public function submissions() {
        $taskId = intval($_GET['task_id'] ?? 0);
        $userLevel = $_SESSION['level'] ?? 'user';

        if ($userLevel === 'user') {
            http_response_code(403);
            echo '<div class="text-center text-red-400">Access denied</div>';
            exit;
        }
        
        if (!$taskId) {
            echo '<div class="text-center text-red-400">Invalid task ID</div>';
            exit;
        }

        $task = $this->model->getById($taskId);
        if (!$task) {
            echo '<div class="text-center text-red-400">Task not found</div>';
            exit;
        }

        if ($userLevel === 'guru' && $task['user_id'] != $_SESSION['user_id']) {
            echo '<div class="text-center text-red-400">You can only view submissions for your own tasks</div>';
            exit;
        }

        $submissions = $this->submissionModel->getByTask($taskId);
        
        if (empty($submissions)) {
            echo '<div class="text-center text-gray-400 py-8">No submissions yet</div>';
            exit;
        }

        $statusConfig = [
            'pending' => ['label' => 'Pending', 'class' => 'bg-amber-500/10 text-amber-300 border-amber-500/20', 'icon' => 'schedule'],
            'in_review' => ['label' => 'In Review', 'class' => 'bg-blue-500/10 text-blue-300 border-blue-500/20', 'icon' => 'visibility'],
            'needs_revision' => ['label' => 'Needs Revision', 'class' => 'bg-rose-500/10 text-rose-300 border-rose-500/20', 'icon' => 'edit'],
            'approved' => ['label' => 'Approved', 'class' => 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20', 'icon' => 'check_circle'],
            'graded' => ['label' => 'Graded', 'class' => 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20', 'icon' => 'grade']
        ];
        
        echo '<div class="space-y-4">';
        foreach ($submissions as $submission) {
            $reviewStatus = strtolower($submission['review_status'] ?? 'pending');
            $status = $statusConfig[$reviewStatus] ?? $statusConfig['pending'];
            $hasGrade = $submission['grade'] !== null;
            $gradeValue = $hasGrade ? floatval($submission['grade']) : null;
            
            echo '<div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors">';

            echo '<div class="p-4 bg-gray-900 border-b border-gray-700">';
            echo '<div class="flex items-start justify-between gap-4">';

            echo '<div class="flex items-center gap-3 flex-1 min-w-0">';
            echo '<div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0 ring-2 ring-gray-700">';
            echo '<span class="text-white text-sm font-bold">' . strtoupper(substr($submission['name'] ?? 'N/A', 0, 1)) . '</span>';
            echo '</div>';
            echo '<div class="flex-1 min-w-0">';
            echo '<div class="text-white font-semibold text-sm truncate">' . htmlspecialchars($submission['name'] ?? 'N/A') . '</div>';
            echo '<div class="text-gray-400 text-xs mt-0.5 truncate">@' . htmlspecialchars($submission['username'] ?? '') . '</div>';
            echo '<div class="flex items-center gap-2 mt-1.5">';
            echo '<span class="text-xs text-gray-500 flex items-center gap-1">';
            echo '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            echo date('d M Y, H:i', strtotime($submission['submitted_at']));
            echo '</span>';
            if (!empty($submission['attempt_no'])) {
                echo '<span class="text-xs text-gray-500">• Attempt #' . intval($submission['attempt_no']) . '</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="flex flex-col items-end gap-2 flex-shrink-0">';
            echo '<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border ' . $status['class'] . '">';
            echo '<span class="material-symbols-outlined" style="font-size: 14px;">' . $status['icon'] . '</span>';
            echo $status['label'];
            echo '</span>';
            if ($hasGrade) {
                $gradeClass = $gradeValue >= 80 ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : ($gradeValue >= 60 ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30');
                echo '<div class="flex items-center gap-2">';
                echo '<span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-bold ' . $gradeClass . ' border">';
                echo '<span class="material-symbols-outlined mr-1" style="font-size: 16px;">star</span>';
                echo number_format($gradeValue, 2);
                echo '</span>';
                echo '</div>';
            } else {
                echo '<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-700/50 text-gray-400 border border-gray-600">Belum dinilai</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="p-4 bg-gray-800 border-b border-gray-700">';
            echo '<div class="flex items-center justify-between gap-4 flex-wrap">';
            echo '<a href="' . htmlspecialchars($submission['file_path'] ?? '#') . '" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-700 hover:bg-gray-600 border border-gray-600 rounded-md text-sm text-gray-200 transition-colors">';
            echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
            echo 'Download File';
            echo '</a>';
            echo '<div class="flex items-center gap-2">';
            if (!empty($submission['note'])) {
                echo '<span class="text-xs text-gray-400 flex items-center gap-1">';
                echo '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>';
                echo 'Ada catatan';
                echo '</span>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';

            if (!empty($submission['feedback'])) {
                echo '<div class="p-4 bg-gray-800/50 border-b border-gray-700">';
                echo '<div class="flex items-start gap-2">';
                echo '<svg class="w-4 h-4 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>';
                echo '<div class="flex-1">';
                echo '<div class="text-xs text-gray-400 mb-1 font-medium">Feedback:</div>';
                echo '<div class="text-sm text-gray-300 leading-relaxed">' . nl2br(htmlspecialchars($submission['feedback'])) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }

            if ($userLevel !== 'user') {
                echo '<div class="p-4 bg-gray-900 border-t border-gray-700">';
                echo '<form method="post" action="index.php?page=tasks/review" class="space-y-4 review-form">';
                echo '<input type="hidden" name="submission_id" value="' . intval($submission['id']) . '">';
                
                echo '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';

                echo '<div>';
                echo '<label class="block text-xs font-medium text-gray-300 mb-2">Status Review</label>';
                $statuses = [
                    'pending' => 'Pending',
                    'in_review' => 'In Review',
                    'needs_revision' => 'Needs Revision',
                    'approved' => 'Approved',
                    'graded' => 'Graded'
                ];
                echo '<select name="review_status" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">';
                foreach ($statuses as $key => $label) {
                    $selected = $reviewStatus === $key ? 'selected' : '';
                    echo '<option value="'.$key.'" '.$selected.'>'.$label.'</option>';
                }
                echo '</select>';
                echo '</div>';

                echo '<div>';
                echo '<label class="block text-xs font-medium text-gray-300 mb-2">Nilai (0-100)</label>';
                $gradeVal = $gradeValue !== null ? number_format($gradeValue, 2) : '';
                echo '<div class="relative">';
                echo '<input type="number" name="grade" min="0" max="100" step="0.01" value="'.$gradeVal.'" placeholder="Masukkan nilai" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">';
                if ($hasGrade) {
                    echo '<div class="absolute right-3 top-2 text-xs text-gray-500">/ 100</div>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div>';
                echo '<label class="block text-xs font-medium text-gray-300 mb-2">Feedback untuk Siswa</label>';
                echo '<textarea name="feedback" rows="4" placeholder="Berikan feedback yang konstruktif..." class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors resize-none">'.htmlspecialchars($submission['feedback'] ?? '').'</textarea>';
                echo '</div>';

                echo '<div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-700">';
                echo '<button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">';
                echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                echo 'Simpan Nilai & Feedback';
                echo '</button>';
                echo '</div>';
                
                echo '</form>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        echo '</div>';
        exit;
    }

    public function reviewSubmission() {
        $this->ensureTeacher();

        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
        
        $submissionId = intval($_POST['submission_id'] ?? 0);
        if (!$submissionId) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Submission tidak valid.']);
            } else {
                $_SESSION['error'] = 'Submission tidak valid.';
                header('Location: index.php?page=tasks');
            }
            exit;
        }
        $submission = $this->submissionModel->getById($submissionId);
        if (!$submission) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Submission tidak ditemukan.']);
            } else {
                $_SESSION['error'] = 'Submission tidak ditemukan.';
                header('Location: index.php?page=tasks');
            }
            exit;
        }
        $task = $this->model->getById($submission['task_id']);
        if (!$task) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Task tidak ditemukan.']);
            } else {
                $_SESSION['error'] = 'Task tidak ditemukan.';
                header('Location: index.php?page=tasks');
            }
            exit;
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

        $this->db->begin_transaction();
        $ok = false;
        try {
            $reviewOk = $this->submissionModel->updateReview($submissionId, $update);
            if (!$reviewOk) {
                throw new Exception("Failed to update submission review.");
            }

            if ($reviewStatus === 'graded' && $grade !== null) {
                $gradeOk = $this->recordGrade($submission, $task, $grade);
                if (!$gradeOk) {
                    throw new Exception("Failed to record grade.");
                }
            }

            $this->db->commit();
            $ok = true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('Grading transaction failed: ' . $e->getMessage());
        }

        if ($ok) {
            $message = $reviewStatus === 'graded' ? 'Nilai tugas Anda sudah tersedia.' : 'Status tugas Anda telah diperbarui.';
            $redirect = $reviewStatus === 'graded' ? 'index.php?page=grades' : 'index.php?page=tasks';
            notify_event($this->db, 'review', 'task', $submission['task_id'], intval($submission['user_id']), $message, $redirect);
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            if ($ok) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Review berhasil disimpan.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal memperbarui review. Terjadi kesalahan database.'
                ]);
            }
        } else {
            if ($ok) {
                $_SESSION['success'] = 'Review berhasil disimpan.';
            } else {
                $_SESSION['error'] = 'Gagal memperbarui review. Terjadi kesalahan database.';
            }
            header('Location: index.php?page=tasks');
        }
        exit;
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

        global $config;
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/SubjectsModel.php';
        $classModel = new ClassModel($config);
        $subjectModel = new SubjectsModel($config);
        $classes = $classModel->getAll();
        $subjects = $subjectModel->getAll();

        $teachers = [];
        $res = mysqli_query($config, "SELECT id, name, username, `level` FROM users WHERE `level` IN ('guru','admin') ORDER BY name ASC");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $teachers[] = $r;
        }

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

        $currentLevel = $_SESSION['level'] ?? 'user';
        if ($currentLevel === 'admin' && !empty($_POST['teacher_id'])) {
            $data['user_id'] = intval($_POST['teacher_id']);
        }

        $errors = [];
        if ($data['title'] === '' || $data['description'] === '' || $data['status'] === '' || $data['deadline'] === '' || !$data['class_id'] || !$data['subject_id']) {
            $errors[] = 'Semua field wajib diisi!';
        }
        if (!empty($data['allow_late']) && !empty($data['late_deadline']) && $data['late_deadline'] < $data['deadline']) {
            $errors[] = 'Late deadline harus setelah deadline utama.';
        }
        
        if (!empty($errors)) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => implode(' ', $errors)
            ]);
            exit;
        }
        
        $allowedStates = ['draft','published','in_review','closed'];
        if (!in_array($data['workflow_state'], $allowedStates, true)) {
            $data['workflow_state'] = 'published';
        }
        $ok = $this->model->update($id, $data);
        
        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Tugas berhasil disimpan.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menyimpan tugas.'
            ]);
        }
        exit;
    }
    public function delete() {
        header('Content-Type: application/json');
        
        $id = intval($_POST['id'] ?? 0);
        
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
                'message' => 'Tugas berhasil dihapus.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus tugas.'
            ]);
        }
        exit;
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
        $ok = $this->gradesModel->saveOrUpdate($payload);
        return $ok;
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
