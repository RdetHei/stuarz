<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/GradesModel.php';

class GradeController
{
    private $db;
    private $model;

    public function __construct()
    {
        global $config; // Use the global database connection
        $this->db = $config;
        $this->model = new GradesModel($config);
    }
    public function index()
    {
        $user = $this->requireUser();

        $level = $_SESSION['level'] ?? 'user';
        $requestedUser = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
        $userId = ($level === 'admin' || $level === 'guru') ? ($requestedUser ?: (int)$user['id']) : (int)$user['id'];

        $stats = $this->buildStats($userId);
        $subjectsSidebar = $this->fetchSubjectStats($userId);
        $recent = $this->fetchRecentGrades($userId);

        $filterSubject = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : null;
        $filterClass = isset($_GET['class_id']) ? intval($_GET['class_id']) : null;

        $grades = $this->fetchGradesList($level, $userId, $filterSubject, $filterClass);

        $subjectsList = $this->db->query('SELECT * FROM subjects ORDER BY name ASC');
        $subjects = $subjectsList ? $subjectsList->fetch_all(MYSQLI_ASSOC) : [];
        $classesList = $this->db->query('SELECT * FROM classes ORDER BY name ASC');
        $classes = $classesList ? $classesList->fetch_all(MYSQLI_ASSOC) : [];

        $data = [
            'totalGrades' => $stats['total'],
            'avgGrade' => $stats['avg'],
            'highGrade' => $stats['max'],
            'thisWeek' => $stats['week'],
            'subjects' => $subjectsSidebar,
            'recent' => $recent,
        ];

        $content = dirname(__DIR__) . '/views/pages/grades/index.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function print()
    {
        // Reuse same data gathering as index but render print view
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = $this->requireUser();
        $level = $_SESSION['level'] ?? 'user';
        $targetUser = ($level === 'admin' || $level === 'guru') && isset($_GET['user_id'])
            ? intval($_GET['user_id'])
            : intval($user['id']);

        $grades = $this->fetchGradesList($level, $targetUser);
        $subjectsList = $this->db->query('SELECT * FROM subjects ORDER BY name ASC');
        $subjects = $subjectsList ? $subjectsList->fetch_all(MYSQLI_ASSOC) : [];
        $classesList = $this->db->query('SELECT * FROM classes ORDER BY name ASC');
        $classes = $classesList ? $classesList->fetch_all(MYSQLI_ASSOC) : [];

        $content = dirname(__DIR__) . '/views/pages/grades/grades_print.php';
        include dirname(__DIR__) . '/views/layouts/print.php';
    }

    public function create() {
        $this->ensureCanManage();
        $content = dirname(__DIR__) . '/views/pages/grades/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function store() {
        $this->ensureCanManage();
        $payload = $this->validatePayload($_POST);
        if (isset($payload['error'])) {
            $_SESSION['error'] = $payload['error'];
            header('Location: index.php?page=grades/create');
            exit;
        }
        $ok = $this->model->create($payload);
        $_SESSION['success'] = $ok ? 'Nilai berhasil disimpan' : 'Gagal menyimpan nilai';
        header('Location: index.php?page=grades');
        exit;
    }

    public function edit() {
        $this->ensureCanManage();
        $id = intval($_GET['id'] ?? 0);
        $grade = $this->model->getById($id);
        if (!$grade) {
            $_SESSION['error'] = 'Data nilai tidak ditemukan';
            header('Location: index.php?page=grades');
            exit;
        }
        $content = dirname(__DIR__) . '/views/pages/grades/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update() {
        $this->ensureCanManage();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            $_SESSION['error'] = 'Data tidak valid';
            header('Location: index.php?page=grades');
            exit;
        }
        $payload = $this->validatePayload($_POST);
        if (isset($payload['error'])) {
            $_SESSION['error'] = $payload['error'];
            header('Location: index.php?page=grades/edit&id='.$id);
            exit;
        }
        $ok = $this->model->update($id, $payload);
        $_SESSION['success'] = $ok ? 'Nilai diperbarui' : 'Gagal memperbarui nilai';
        header('Location: index.php?page=grades');
        exit;
    }

    public function delete() {
        $this->ensureCanManage();
        $id = intval($_POST['id'] ?? 0);
        if (!$id) {
            $_SESSION['error'] = 'Data tidak valid';
            header('Location: index.php?page=grades');
            exit;
        }
        $ok = $this->model->delete($id);
        $_SESSION['success'] = $ok ? 'Nilai dihapus' : 'Gagal menghapus nilai';
        header('Location: index.php?page=grades');
        exit;
    }

    /**
     * Halaman grading untuk melihat dan memberikan nilai pada task submissions
     */
    public function grading() {
        $this->ensureCanManage();
        $user = $this->requireUser();
        $level = $_SESSION['level'] ?? 'user';
        $userId = intval($user['id'] ?? 0);

        // Load models
        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/SubjectsModel.php';
        
        $submissionModel = new TaskSubmissionsModel($this->db);
        $taskModel = new TasksCompletedModel($this->db);
        $classModel = new ClassModel($this->db);
        $subjectModel = new SubjectsModel($this->db);

        // Get filter parameters
        $filterTask = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;
        $filterStatus = isset($_GET['status']) ? $_GET['status'] : 'pending';
        $filterClass = isset($_GET['class_id']) ? intval($_GET['class_id']) : null;

        // Fetch submissions that need grading
        $submissions = $this->fetchSubmissionsForGrading($userId, $level, $filterTask, $filterStatus, $filterClass);

        // Get related data for filters
        $tasks = [];
        $classes = $classModel->getAll();
        $subjects = $subjectModel->getAll();

        if ($level === 'guru') {
            // Guru hanya melihat tugas mereka sendiri
            $tasks = $taskModel->getByTeacherId($userId);
        } else {
            // Admin melihat semua
            $tasks = $taskModel->getAll();
        }

        $content = dirname(__DIR__) . '/views/pages/grades/grading.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    /**
     * Simpan nilai dari grading submission
     */
    public function gradeSubmission() {
        $this->ensureCanManage();
        if (session_status() === PHP_SESSION_NONE) session_start();

        $submissionId = intval($_POST['submission_id'] ?? 0);
        $score = isset($_POST['score']) && $_POST['score'] !== '' ? floatval($_POST['score']) : null;
        $feedback = trim($_POST['feedback'] ?? '');
        $reviewStatus = $_POST['review_status'] ?? 'graded';

        if (!$submissionId) {
            $_SESSION['error'] = 'Submission tidak valid.';
            header('Location: index.php?page=grades/grading');
            exit;
        }

        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        $submissionModel = new TaskSubmissionsModel($this->db);
        
        $submission = $submissionModel->getById($submissionId);
        if (!$submission) {
            $_SESSION['error'] = 'Submission tidak ditemukan.';
            header('Location: index.php?page=grades/grading');
            exit;
        }

        // Update submission with grade
        $updateData = [
            'review_status' => $reviewStatus,
            'feedback' => $feedback !== '' ? $feedback : null,
            'reviewed_by' => $_SESSION['user_id'],
            'reviewed_at' => date('Y-m-d H:i:s')
        ];

        if ($score !== null) {
            $updateData['grade'] = $score;
            $updateData['status'] = 'graded';
        }

        // Update review
        $ok = $submissionModel->updateReview($submissionId, $updateData);

        // If graded, also save to grades table
        if ($ok && $score !== null) {
            require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
            $taskModel = new TasksCompletedModel($this->db);
            $task = $taskModel->getById($submission['task_id']);

            if ($task) {
                $gradeData = [
                    'user_id' => intval($submission['user_id']),
                    'class_id' => intval($submission['class_id']),
                    'subject_id' => intval($task['subject_id'] ?? 0),
                    'task_id' => intval($submission['task_id']),
                    'score' => $score
                ];
                $this->model->saveOrUpdate($gradeData);
            }

            // Send notification
            require_once dirname(__DIR__) . '/helpers/notifier.php';
            notify_event(
                $this->db,
                'grade',
                'task',
                intval($submission['task_id']),
                intval($submission['user_id']),
                'Nilai untuk tugas Anda sudah tersedia.',
                'index.php?page=grades'
            );
        }

        $_SESSION['success'] = $ok ? 'Nilai berhasil disimpan.' : 'Gagal menyimpan nilai.';
        header('Location: index.php?page=grades/grading');
        exit;
    }

    private function fetchSubmissionsForGrading($userId, $level, $taskId = null, $status = 'pending', $classId = null) {
        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
        $submissionModel = new TaskSubmissionsModel($this->db);
        $taskModel = new TasksCompletedModel($this->db);

        // Build query to get submissions with task info
        $sql = "SELECT ts.*, 
                u.username, u.name AS student_name, 
                t.title AS task_title, t.class_id AS task_class_id, t.subject_id, t.user_id AS task_teacher_id,
                c.name AS class_name,
                s.name AS subject_name
                FROM task_submissions ts
                LEFT JOIN users u ON ts.user_id = u.id
                LEFT JOIN tasks_completed t ON ts.task_id = t.id
                LEFT JOIN classes c ON ts.class_id = c.id
                LEFT JOIN subjects s ON t.subject_id = s.id
                WHERE 1=1";

        $conditions = [];
        $params = [];
        $paramTypes = '';

        if ($level === 'guru') {
            $conditions[] = "t.user_id = ?";
            $params[] = $userId;
            $paramTypes .= 'i';
        }

        if ($taskId) {
            $conditions[] = "ts.task_id = ?";
            $params[] = $taskId;
            $paramTypes .= 'i';
        }

        if ($status && $status !== 'all') {
            $conditions[] = "ts.review_status = ?";
            $params[] = $status;
            $paramTypes .= 's';
        }

        if ($classId) {
            $conditions[] = "ts.class_id = ?";
            $params[] = $classId;
            $paramTypes .= 'i';
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY ts.submitted_at DESC";

        if (!empty($params)) {
            $stmt = $this->db->prepare($sql);
            if ($stmt) {
                $stmt->bind_param($paramTypes, ...$params);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            } else {
                $result = null;
            }
        } else {
            $result = $this->db->query($sql);
        }

        $submissions = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $submissions[] = $row;
            }
            if (method_exists($result, 'free')) {
                $result->free();
            }
        }

        return $submissions;
    }

    private function buildStats($userId) {
        global $config;
        $stats = ['total' => 0, 'avg' => 0, 'max' => 0, 'week' => 0];

        $stmt = mysqli_prepare($config, 'SELECT COUNT(*) AS total FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $stats['total'] = (int)($row['total'] ?? 0); }

        $stmt = mysqli_prepare($config, 'SELECT ROUND(AVG(score), 1) AS avg_score FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $stats['avg'] = (float)($row['avg_score'] ?? 0); }

        $stmt = mysqli_prepare($config, 'SELECT MAX(score) AS max_score FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $stats['max'] = (float)($row['max_score'] ?? 0); }

        $stmt = mysqli_prepare($config, 'SELECT COUNT(*) AS cnt FROM grades WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $stats['week'] = (int)($row['cnt'] ?? 0); }

        return $stats;
    }

    private function fetchSubjectStats($userId) {
        global $config;
        $subjects = [];
        $sql = 'SELECT s.id, s.name, COUNT(g.id) AS total, ROUND(AVG(g.score),1) AS avg_score
                FROM subjects s
                LEFT JOIN grades g ON g.subject_id = s.id AND g.user_id = ?
                GROUP BY s.id, s.name
                ORDER BY s.name ASC';
        $stmt = mysqli_prepare($config, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) { $subjects[] = $row; }
        }
        return $subjects;
    }

    private function fetchRecentGrades($userId) {
        $stmt = mysqli_prepare($this->db, "
            SELECT 
                g.id,
                s.name AS subject_name,
                t.title AS task_title,
                g.score,
                g.created_at
            FROM grades g
            JOIN subjects s ON g.subject_id = s.id
            LEFT JOIN tasks_completed t ON g.task_id = t.id
            WHERE g.user_id = ?
            ORDER BY g.created_at DESC
            LIMIT 8
        ");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $recent = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
        mysqli_stmt_close($stmt);
        return $recent;
    }

    private function fetchGradesList($level, $userId, $subjectId = null, $classId = null) {
        $sql = 'SELECT g.*, u.username, s.name AS subject_name, t.title AS task_title, c.name AS class_name
            FROM grades g
            LEFT JOIN users u ON g.user_id = u.id
            LEFT JOIN subjects s ON g.subject_id = s.id
            LEFT JOIN tasks_completed t ON g.task_id = t.id
            LEFT JOIN classes c ON g.class_id = c.id';

        $conditions = [];
        if ($level === 'user') {
            $conditions[] = 'g.user_id = ' . intval($userId);
        }
        if ($subjectId) $conditions[] = 'g.subject_id = ' . intval($subjectId);
        if ($classId) $conditions[] = 'g.class_id = ' . intval($classId);

        if (!empty($conditions)) $sql .= ' WHERE ' . implode(' AND ', $conditions);
        $sql .= ' ORDER BY g.created_at DESC';

        $result = $this->db->query($sql);
        $rows = [];
        if ($result) while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    private function validatePayload($data) {
        $required = ['user_id','class_id','subject_id','task_id','score'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                return ['error' => 'Field ' . $field . ' wajib diisi'];
            }
        }
        return [
            'user_id' => intval($data['user_id']),
            'class_id' => intval($data['class_id']),
            'subject_id' => intval($data['subject_id']),
            'task_id' => intval($data['task_id']),
            'score' => floatval($data['score'])
        ];
    }

    private function requireUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }
        return $_SESSION['user'];
    }

    private function ensureCanManage() {
        $user = $this->requireUser();
        $level = $_SESSION['level'] ?? 'user';
        if (!in_array($level, ['admin','guru'], true)) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengelola nilai.';
            header('Location: index.php?page=grades');
            exit;
        }
        return $user;
    }
}
