<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/model/users.php';
require_once dirname(__DIR__) . '/model/ClassModel.php';
require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
require_once dirname(__DIR__) . '/model/NotificationsModel.php';
require_once dirname(__DIR__) . '/model/AttendanceModel.php';
require_once dirname(__DIR__) . '/model/ScheduleModel.php';

class DashboardController
{
    private $userModel;

    public function __construct()
    {
        global $config;
        $this->userModel = new users($config);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=setup-profile');
            exit;
        }

        global $config;

        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $user = $this->userModel->getUserById($userId);

        // Redirect based on user level
        if (!empty($user['level'])) {
            if ($user['level'] === 'guru') {
                header('Location: index.php?page=dashboard-guru');
                exit;
            } elseif ($user['level'] === 'admin') {
                header('Location: index.php?page=dashboard-admin');
                exit;
            }
        }

        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/views/pages/dashboard/user.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function dashboardAdmin()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=setup-profile');
            exit;
        }

        global $config;
        $userId = (int) ($_SESSION['user_id'] ?? 0);
        $user = $this->userModel->getUserById($userId);

        // If a teacher logs in, redirect to guru dashboard
        if (!empty($user['level']) && $user['level'] === 'guru') {
            header('Location: index.php?page=dashboard-guru');
            exit;
        }

        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/views/pages/dashboard/admin.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function guru()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        if (!$this->isProfileComplete($_SESSION['user_id'])) {
            header('Location: index.php?page=setup-profile');
            exit;
        }

        global $config;
        $teacherId = (int) ($_SESSION['user_id'] ?? 0);

        // Only allow access if user is guru
        $teacher = $this->userModel->getUserById($teacherId);
        if (!$teacher || ($teacher['level'] ?? '') !== 'guru') {
            header('Location: index.php?page=dashboard');
            exit;
        }

        $cacheTtl = 120; // seconds
        $cacheDir = dirname(__DIR__) . '/cache';
        if (!is_dir($cacheDir)) @mkdir($cacheDir, 0755, true);
        $cacheFile = $cacheDir . '/dashboard_guru_' . $teacherId . '.json';

        $classes = [];
        $submissions = [];
        $summary = ['classes' => 0, 'students' => 0, 'pending_grading' => 0, 'messages' => 0];
        $performance = ['average' => 0, 'min' => 0, 'max' => 0, 'grades' => [0]];

        // Always fetch teacher profile (do not rely on cache for profile validation)
        $teacher = $this->userModel->getUserById($teacherId);
        if (!$teacher || ($teacher['level'] ?? '') !== 'guru') {
            // Not a teacher — redirect to normal dashboard
            header('Location: index.php?page=dashboard');
            exit;
        }

        if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTtl) {
            $cached = json_decode(@file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                $classes = $cached['classes'] ?? [];
                $submissions = $cached['submissions'] ?? [];
                $summary = $cached['summary'] ?? $summary;
                $performance = $cached['performance'] ?? $performance;
            }
        } else {
            $classModel = new ClassModel($config);
            $tasksModel = new TasksCompletedModel($config);
            $submissionsModel = new TaskSubmissionsModel($config);
            $notificationsModel = new NotificationsModel($config);

            $allClasses = $classModel->getAll();
            $rawClasses = array_values(array_filter($allClasses, function ($c) use ($teacherId) {
                return (isset($c['created_by']) && intval($c['created_by']) === $teacherId) || (isset($c['creator']) && $c['creator'] == $teacherId);
            }));

            // Enrich classes with student counts and a human-friendly time (from schedule)
            $scheduleModel = new ScheduleModel($config);
            $classes = [];
            foreach ($rawClasses as $cl) {
                $students = $classModel->getMembers($cl['id']);
                $studentsCountLocal = is_array($students) ? count($students) : 0;
                $schedules = $scheduleModel->getAll(['class_id' => $cl['id']]);
                $timeStr = '';
                if (!empty($schedules)) {
                    // create a short summary like "Mon/Wed 08:00"
                    $parts = [];
                    foreach ($schedules as $sch) {
                        $parts[] = ($sch['day'] ?? '') . ' ' . (($sch['start_time'] ?? '') ? substr($sch['start_time'],0,5) : '');
                    }
                    $timeStr = implode(', ', array_slice($parts,0,2));
                }
                $classes[] = [
                    'id' => $cl['id'],
                    'name' => $cl['name'] ?? ($cl['title'] ?? 'Class'),
                    'students' => $studentsCountLocal,
                    'time' => $timeStr
                ];
            }

            $studentIds = [];
            foreach ($classes as $cl) {
                if (isset($cl['id'])) {
                    $members = $classModel->getMembers($cl['id']);
                    foreach ($members as $m) {
                        if (!empty($m['user_id'])) $studentIds[$m['user_id']] = true;
                    }
                }
            }
            $studentsCount = count($studentIds);

            $tasks = $tasksModel->getByTeacherId($teacherId);

            $recentSubmissions = [];
            $pendingGrading = 0;
            $grades = [];
            foreach ($tasks as $t) {
                if (!isset($t['id'])) continue;
                $subs = $submissionsModel->getByTask($t['id']);
                foreach ($subs as $s) {
                    if (!isset($s['grade']) || $s['grade'] === null || $s['grade'] === '') {
                        $pendingGrading++;
                    } else {
                        $grades[] = floatval($s['grade']);
                    }
                    $recentSubmissions[] = $s + ['task_title' => $t['title'] ?? ''];
                }
            }

            $summary = [
                'classes' => count($classes),
                'students' => $studentsCount,
                'pending_grading' => $pendingGrading,
                'messages' => $notificationsModel->countUnread($teacherId)
            ];

            $performance = [
                'average' => $grades ? round(array_sum($grades) / count($grades)) : 0,
                'min' => $grades ? min($grades) : 0,
                'max' => $grades ? max($grades) : 0,
                'grades' => $grades ?: [0]
            ];

            usort($recentSubmissions, function ($a, $b) {
                $ta = $a['submitted_at'] ?? $a['created_at'] ?? null;
                $tb = $b['submitted_at'] ?? $b['created_at'] ?? null;
                if ($ta === $tb) return 0;
                if ($ta === null) return 1;
                if ($tb === null) return -1;
                return strtotime($tb) <=> strtotime($ta);
            });
            $submissions = array_slice($recentSubmissions, 0, 8);

            $payload = [
                'classes' => $classes,
                'submissions' => $submissions,
                'summary' => $summary,
                'performance' => $performance,
            ];
            @file_put_contents($cacheFile, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }

        $title = "Dashboard Guru - Stuarz";
        $description = "Halaman dashboard untuk guru";
        $content = dirname(__DIR__) . '/views/pages/dashboard/guru.php';

        // Normalize teacher fields expected by the view
        $teacher['subject'] = $teacher['subject'] ?? ($teacher['role'] ?? '');
        $teacher['joined'] = $teacher['joined'] ?? ($teacher['join_date'] ?? '');
        $teacher['email'] = $teacher['email'] ?? ($teacher['username'] ?? '');
        $teacher['avatar'] = $teacher['avatar'] ?? null;

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    private function isProfileComplete($userId)
    {
        $user = $this->userModel->getUserById($userId);
        if (!$user) return false;
        $requiredFields = ['name', 'phone', 'address'];
        foreach ($requiredFields as $field) {
            if (empty($user[$field])) return false;
        }
        return true;
    }
}