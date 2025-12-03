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

        // Generate notifications from recent changes (best-effort) with session throttle
        if (class_exists('NotificationsModel')) {
            $notifModel = new NotificationsModel($config);
            $lastScan = isset($_SESSION['notif_scan_last']) ? intval($_SESSION['notif_scan_last']) : 0;
            if ($lastScan <= 0 || (time() - $lastScan) >= 300) {
                $notifModel->generateFromSources(5);
                $_SESSION['notif_scan_last'] = time();
            }
        }

        if (!empty($user['level'])) {
            if ($user['level'] === 'guru') {
                header('Location: index.php?page=dashboard-guru');
                exit;
            } elseif ($user['level'] === 'admin') {
                header('Location: index.php?page=dashboard-admin');
                exit;
            }
        }

        require_once dirname(__DIR__) . '/model/TasksCompletedModel.php';
        require_once dirname(__DIR__) . '/model/TaskSubmissionsModel.php';
        require_once dirname(__DIR__) . '/model/AttendanceModel.php';
        require_once dirname(__DIR__) . '/model/ScheduleModel.php';
        require_once dirname(__DIR__) . '/model/ClassModel.php';
        require_once dirname(__DIR__) . '/model/certificates.php';

        $tasksModel = new TasksCompletedModel($config);
        $subsModel = new TaskSubmissionsModel($config);
        $attModel = new AttendanceModel($config);
        $schModel = new ScheduleModel($config);
        $classModel = new ClassModel($config);
        $certModel = new certificates($config);

        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $content = dirname(__DIR__) . '/views/pages/dashboard/user.php';

        // Stats
        $submissions = $subsModel->getByUser($userId);
        $currentMonth = date('Y-m');
        $distinctSubmittedThisMonth = [];
        foreach ($submissions as $s) {
            $ts = $s['submitted_at'] ?? $s['created_at'] ?? '';
            if ($ts && strpos($ts, $currentMonth) === 0) {
                $tid = intval($s['task_id'] ?? 0);
                if ($tid > 0) $distinctSubmittedThisMonth[$tid] = true;
            }
        }
        $attRows = $attModel->getByUser($userId, 300);
        $present = 0; $absent = 0; $late = 0;
        foreach ($attRows as $a) {
            $st = strtolower(trim($a['status'] ?? ''));
            if ($st === 'hadir' || $st === 'present') $present++; elseif ($st === 'absen' || $st === 'absent') $absent++; elseif ($st === 'terlambat' || $st === 'late') $late++;
        }
        $certCount = $certModel->getCountByUserId($userId);

        $avgGrade = 0;
        if ($stmt = $config->prepare("SELECT ROUND(AVG(score)) AS avg FROM grades WHERE user_id = ?")) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && ($row = $res->fetch_assoc())) $avgGrade = (int)($row['avg'] ?? 0);
            $stmt->close();
        }

        $stats = [
            'tasks_completed' => count($distinctSubmittedThisMonth),
            'attendance_present' => $present,
            'attendance_total' => ($present + $absent + $late),
            'certificates' => $certCount,
            'average_grade' => $avgGrade
        ];

        // Additional actionable stats for the user dashboard
        // Tasks for student's classes
        $allClassTasks = [];
        try {
            $allClassTasks = $tasksModel->getByStudentClass($userId);
        } catch (Exception $e) {
            $allClassTasks = [];
        }

        // Build submitted task ids for progress calculation
        $distinctSubmittedAll = [];
        foreach ($submissions as $s) {
            $tid = intval($s['task_id'] ?? 0);
            if ($tid > 0) $distinctSubmittedAll[$tid] = true;
        }

        $totalTasks = count($allClassTasks);
        $completedCount = count($distinctSubmittedAll);
        $progressPct = $totalTasks > 0 ? (int) round(($completedCount / $totalTasks) * 100) : 0;

        // Determine tasks due this week and overdue
        $tasksDueWeek = 0;
        $overdueTasks = 0;
        $now = time();
        // Start and end of current week (Mon-Sun)
        $monday = strtotime('monday this week');
        $sunday = strtotime('sunday this week');
        if ($monday === false) $monday = strtotime('today');
        if ($sunday === false) $sunday = strtotime('+6 days', $monday);

        foreach ($allClassTasks as $t) {
            $dl = $t['deadline'] ?? null;
            if (!$dl) continue;
            $ts = strtotime($dl);
            $status = strtolower(trim($t['status'] ?? ''));
            $isDone = in_array($status, ['done','completed','finished','submitted','done']);
            if ($ts === false) continue;
            if (!$isDone && $ts < $now) {
                $overdueTasks++;
            }
            if (!$isDone && $ts >= $monday && $ts <= $sunday) {
                $tasksDueWeek++;
            }
        }

        // Unread notifications count
        $unreadNotifications = 0;
        if (class_exists('NotificationsModel')) {
            try {
                $nm = new NotificationsModel($config);
                $unreadNotifications = (int) $nm->countUnread($userId);
            } catch (Exception $e) { $unreadNotifications = 0; }
        }

        // Next class (reuse previously computed $nextClass if available)
        $nextClassLabel = null;
        if (!empty($nextClass)) {
            $nextClassLabel = ($nextClass['subject'] ?? '') . (
                !empty($nextClass['time']) ? ' • ' . ($nextClass['time']) : '');
        } elseif (!empty($schedule) && is_array($schedule) && count($schedule) > 0) {
            $s0 = $schedule[0];
            $nextClassLabel = ($s0['subject'] ?? '') . (!empty($s0['time']) ? ' • ' . ($s0['time']) : '');
        }

        // Merge into stats (preserve existing keys)
        $stats['tasks_due_week'] = $tasksDueWeek;
        $stats['overdue_tasks'] = $overdueTasks;
        $stats['unread_notifications'] = $unreadNotifications;
        $stats['next_class'] = $nextClassLabel;
        $stats['progress_percent'] = $progressPct;

        // Activities (merge latest submissions and attendance)
        $activities = [];
        foreach (array_slice($submissions, 0, 10) as $s) {
            $time = $s['submitted_at'] ?? $s['created_at'] ?? '';
            $title = "Tugas " . ($s['task_title'] ?? 'Tugas') . " diselesaikan";
            $meta = ($s['status'] ?? 'submitted');
            $activities[] = ['title' => $title, 'meta' => $meta, 'time' => $this->relativeTime($time)];
        }
        foreach (array_slice($attRows, 0, 10) as $a) {
            $title = "Absensi: " . ucfirst(strtolower($a['status'] ?? '-'));
            $meta = "Tanggal " . ($a['date'] ?? '');
            $activities[] = ['title' => $title, 'meta' => $meta, 'time' => $this->relativeTime(($a['date'] ?? '') . ' ' . ($a['check_in'] ?? ''))];
        }
        usort($activities, function($x,$y){ return strcmp($y['time'],$x['time']); });
        $activities = array_slice($activities, 0, 8);

        // Today schedule
        $weekdayMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $todayName = $weekdayMap[(int)date('N')] ?? 'Senin';
        $joinedClasses = $classModel->getAll($userId);
        $schedule = [];
        $scheduleByDay = ['Senin'=>[],'Selasa'=>[],'Rabu'=>[],'Kamis'=>[],'Jumat'=>[],'Sabtu'=>[],'Minggu'=>[]];
        foreach ($joinedClasses as $cl) {
            $clsId = intval($cl['id'] ?? 0);
            if ($clsId <= 0) continue;
            $rowsToday = $schModel->getAllWithRelations(['class_id' => $clsId, 'day' => $todayName]);
            foreach ($rowsToday as $r) {
                $schedule[] = [
                    'time' => trim(($r['start_time'] ?? '') . ' — ' . ($r['end_time'] ?? '')),
                    'subject' => $r['subject'] ?? ($r['schedule_subject'] ?? 'Pelajaran'),
                    'teacher' => $r['teacher_name'] ?? 'Guru',
                    'room' => $cl['code'] ?? ($r['class_code'] ?? '-')
                ];
            }
            $rowsAll = $schModel->getAllWithRelations(['class_id' => $clsId]);
            foreach ($rowsAll as $r) {
                $dname = $r['day'] ?? '';
                if (!isset($scheduleByDay[$dname])) continue;
                $scheduleByDay[$dname][] = [
                    'start' => $r['start_time'] ?? '',
                    'end' => $r['end_time'] ?? '',
                    'time' => trim(($r['start_time'] ?? '') . ' — ' . ($r['end_time'] ?? '')),
                    'subject' => $r['subject'] ?? ($r['schedule_subject'] ?? 'Pelajaran'),
                    'teacher' => $r['teacher_name'] ?? 'Guru',
                    'room' => $cl['code'] ?? ($r['class_code'] ?? '-')
                ];
            }
        }
        foreach ($scheduleByDay as $dn => $items) {
            usort($items, function($a,$b){ return strcmp(substr($a['start'] ?? '',0,5), substr($b['start'] ?? '',0,5)); });
            $scheduleByDay[$dn] = $items;
        }

        $perDayCounts = ['Senin'=>0,'Selasa'=>0,'Rabu'=>0,'Kamis'=>0,'Jumat'=>0,'Sabtu'=>0];
        $weekCount = 0;
        foreach ($joinedClasses as $cl) {
            $clsId = intval($cl['id'] ?? 0);
            if ($clsId <= 0) continue;
            $rows = $schModel->getAllWithRelations(['class_id' => $clsId]);
            foreach ($rows as $r) {
                $d = $r['day'] ?? '';
                if (isset($perDayCounts[$d])) $perDayCounts[$d]++;
                $weekCount++;
            }
        }

        $nowTime = date('H:i');
        $nextClass = null;
        $scanDays = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $startIdx = array_search($todayName, $scanDays, true);
        $orderDays = $scanDays;
        if ($startIdx !== false) {
            $orderDays = array_merge(array_slice($scanDays, $startIdx), array_slice($scanDays, 0, $startIdx));
        }
        foreach ($orderDays as $dn) {
            $candidates = [];
            foreach ($joinedClasses as $cl) {
                $clsId = intval($cl['id'] ?? 0);
                if ($clsId <= 0) continue;
                $rows = $schModel->getAllWithRelations(['class_id' => $clsId, 'day' => $dn]);
                foreach ($rows as $r) {
                    $candidates[] = $r + ['class_code' => $cl['code'] ?? ($r['class_code'] ?? '-')];
                }
            }
            usort($candidates, function($a,$b){ return strcmp(($a['start_time'] ?? ''), ($b['start_time'] ?? '')); });
            foreach ($candidates as $r) {
                $st = substr($r['start_time'] ?? '', 0, 5);
                if ($dn !== $todayName || $st > $nowTime) {
                    $nextClass = [
                        'day' => $dn,
                        'time' => trim(($r['start_time'] ?? '') . ' — ' . ($r['end_time'] ?? '')),
                        'subject' => $r['subject'] ?? ($r['schedule_subject'] ?? 'Pelajaran'),
                        'teacher' => $r['teacher_name'] ?? 'Guru',
                        'room' => $r['class_code'] ?? '-'
                    ];
                    break 2;
                }
            }
        }

        $scheduleStats = [
            'today_count' => count($schedule),
            'week_count' => $weekCount,
            'per_day' => $perDayCounts,
            'next' => $nextClass
        ];

        // Learning stats
        $distinctSubjects = 0;
        if ($stmt2 = $config->prepare("SELECT COUNT(DISTINCT subject_id) AS c FROM grades WHERE user_id = ?")) {
            $stmt2->bind_param('i', $userId);
            $stmt2->execute();
            $res2 = $stmt2->get_result();
            if ($res2 && ($row2 = $res2->fetch_assoc())) $distinctSubjects = (int)($row2['c'] ?? 0);
            $stmt2->close();
        }
        $streak = 0; $lastDate = null;
        foreach ($attRows as $a) {
            $d = $a['date'] ?? null; $st = strtolower(trim($a['status'] ?? ''));
            if (!$d) continue;
            if ($lastDate === null) { $lastDate = $d; }
            if ($st === 'hadir' || $st === 'present') { $streak++; } else { break; }
        }
        $learning = ['words_read' => max(0, count($submissions) * 1200), 'chapters' => $distinctSubjects, 'streak' => $streak];

        $attendanceChart = [$present, $absent, $late];

        $subjectAverages = [];
        if ($stmt3 = $config->prepare("SELECT COALESCE(s.name,'-') AS subject, ROUND(AVG(g.score)) AS avg FROM grades g LEFT JOIN subjects s ON g.subject_id = s.id WHERE g.user_id = ? GROUP BY subject_id ORDER BY avg DESC LIMIT 5")) {
            $stmt3->bind_param('i', $userId);
            $stmt3->execute();
            $res3 = $stmt3->get_result();
            if ($res3) while ($row3 = $res3->fetch_assoc()) $subjectAverages[] = $row3;
            $stmt3->close();
        }

        $attendanceLast30 = ['present'=>0,'absent'=>0,'late'=>0];
        if ($stmt4 = $config->prepare("SELECT SUM(status='hadir')+SUM(status='present') AS p, SUM(status='absen')+SUM(status='absent') AS a, SUM(status='terlambat')+SUM(status='late') AS l FROM attendance WHERE user_id = ? AND date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)")) {
            $stmt4->bind_param('i', $userId);
            $stmt4->execute();
            $res4 = $stmt4->get_result();
            if ($res4 && ($row4 = $res4->fetch_assoc())) {
                $attendanceLast30['present'] = (int)($row4['p'] ?? 0);
                $attendanceLast30['absent'] = (int)($row4['a'] ?? 0);
                $attendanceLast30['late'] = (int)($row4['l'] ?? 0);
            }
            $stmt4->close();
        }

        // Normalize expected user fields
        $user['joined'] = $user['join_date'] ?? ($user['joined'] ?? '');

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
                $scheduleByDay = $cached['scheduleByDay'] ?? ['Senin'=>[],'Selasa'=>[],'Rabu'=>[],'Kamis'=>[],'Jumat'=>[],'Sabtu'=>[],'Minggu'=>[]];
            }
        } else {
            $classModel = new ClassModel($config);
            $tasksModel = new TasksCompletedModel($config);
            $submissionsModel = new TaskSubmissionsModel($config);
            $notificationsModel = new NotificationsModel($config);

            // Also generate notifications for recent changes (light-weight) with session throttle
            try {
                $lastScan = isset($_SESSION['notif_scan_last']) ? intval($_SESSION['notif_scan_last']) : 0;
                if ($lastScan <= 0 || (time() - $lastScan) >= 300) {
                    $notificationsModel->generateFromSources(5);
                    $_SESSION['notif_scan_last'] = time();
                }
            } catch (Exception $e) {
                error_log('generateFromSources failed: ' . $e->getMessage());
            }

            // Use getManagedClasses so we include classes the teacher created
            // or classes where they are a member with role guru/admin
            $rawClasses = $classModel->getManagedClasses($teacherId);

            // Enrich classes with student counts and a human-friendly time (from schedule)
            $scheduleModel = new ScheduleModel($config);
            $classes = [];
            $scheduleByDay = ['Senin'=>[],'Selasa'=>[],'Rabu'=>[],'Kamis'=>[],'Jumat'=>[],'Sabtu'=>[],'Minggu'=>[]];
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
                        $dname = $sch['day'] ?? '';
                        if (isset($scheduleByDay[$dname])) {
                            $scheduleByDay[$dname][] = [
                                'start' => $sch['start_time'] ?? '',
                                'end' => $sch['end_time'] ?? '',
                                'time' => trim(($sch['start_time'] ?? '') . ' — ' . ($sch['end_time'] ?? '')),
                                'subject' => $sch['subject'] ?? ($sch['schedule_subject'] ?? 'Pelajaran'),
                                'teacher' => $sch['teacher_name'] ?? ($teacher['name'] ?? 'Guru'),
                                'room' => $cl['code'] ?? ($sch['class_code'] ?? '-')
                            ];
                        }
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
            foreach ($scheduleByDay as $dn => $items) {
                usort($items, function($a,$b){ return strcmp(substr($a['start'] ?? '',0,5), substr($b['start'] ?? '',0,5)); });
                $scheduleByDay[$dn] = $items;
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
            // Compute task-related stats for teacher: due this week, overdue
            $tasksDueWeek = 0;
            $overdueTasks = 0;
            $now = time();
            $monday = strtotime('monday this week');
            $sunday = strtotime('sunday this week');
            if ($monday === false) $monday = strtotime('today');
            if ($sunday === false) $sunday = strtotime('+6 days', $monday);

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
                // Task deadline handling
                $dl = $t['deadline'] ?? null;
                if (!empty($dl)) {
                    $ts = strtotime($dl);
                    $status = strtolower(trim($t['status'] ?? ''));
                    $isDone = in_array($status, ['done','completed','finished','closed']);
                    if ($ts !== false) {
                        if (!$isDone && $ts < $now) $overdueTasks++;
                        if (!$isDone && $ts >= $monday && $ts <= $sunday) $tasksDueWeek++;
                    }
                }
            }

            // Determine next upcoming class for the teacher using $scheduleByDay
            $nextClass = null;
            $nowTime = date('H:i');
            $scanDays = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
            $todayName = $scanDays[(int)date('N')-1] ?? 'Senin';
            $startIdx = array_search($todayName, $scanDays, true);
            $orderDays = $scanDays;
            if ($startIdx !== false) {
                $orderDays = array_merge(array_slice($scanDays, $startIdx), array_slice($scanDays, 0, $startIdx));
            }
            foreach ($orderDays as $dn) {
                $cands = $scheduleByDay[$dn] ?? [];
                usort($cands, function($a,$b){ return strcmp(($a['start'] ?? ''), ($b['start'] ?? '')); });
                foreach ($cands as $r) {
                    $st = substr($r['start'] ?? '', 0, 5);
                    if ($dn !== $todayName || $st > $nowTime) {
                        $nextClass = [
                            'day' => $dn,
                            'time' => trim(($r['start'] ?? '') . ' — ' . ($r['end'] ?? '')),
                            'subject' => $r['subject'] ?? ($r['schedule_subject'] ?? 'Pelajaran'),
                            'teacher' => $r['teacher'] ?? ($teacher['name'] ?? 'Guru'),
                            'room' => $r['room'] ?? ($r['class_code'] ?? '-')
                        ];
                        break 2;
                    }
                }
            }

            $summary = [
                'classes' => count($classes),
                'students' => $studentsCount,
                'pending_grading' => $pendingGrading,
                'messages' => $notificationsModel->countUnread($teacherId),
                'tasks_due_week' => $tasksDueWeek,
                'overdue_tasks' => $overdueTasks,
                'next_class' => $nextClass
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
                'scheduleByDay' => $scheduleByDay
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

    private function relativeTime($ts)
    {
        if (!$ts) return 'N/A';
        $t = strtotime($ts);
        if ($t === false) return 'N/A';
        $diff = time() - $t;
        if ($diff < 60) return 'Baru';
        if ($diff < 3600) return floor($diff/60) . 'm';
        if ($diff < 86400) return floor($diff/3600) . 'h';
        return floor($diff/86400) . 'd';
    }

    private function relativeAge($ts)
    {
        $r = $this->relativeTime($ts);
        if ($r === 'Baru') return 'Today';
        return $r;
    }
}
