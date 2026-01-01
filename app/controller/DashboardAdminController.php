<?php
class DashboardAdminController
{
    private $db;

    public function __construct($db = null) {
        if ($db === null) {
            global $config;
            $db = $config ?? null;
        }
        $this->db = $db;
    }

    public function dashboardAdmin()
    {
        require_once __DIR__ . '/../core/Cache.php';

        if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
            header('Location: index.php?page=dashboard-admin');
            exit;
        }

        $title = "Admin Dashboard - Stuarz";
        $description = "Welcome to your dashboard";
        $cacheKey = 'admin_dashboard_data';

        $cachedData = Cache::get($cacheKey);

        if ($cachedData !== null) {
            $stats = $cachedData['stats'];
            $data = $cachedData['data'];
        } else {
            $stats = $this->getStats();
            
            $data = [
                'attendance' => $this->getAttendanceData(),
                'grades' => $this->getGradesData(),
                'tasks' => $this->getTasksData(),
                'students' => $this->getStudentsPerClass(),
                'teaching' => $this->getTeachingSchedules(),
                'newStudents' => $this->getNewStudents(),
                'certificates' => $this->getCertificatesData(),
                'documentation' => $this->getDocumentationData()
            ];

            Cache::set($cacheKey, ['stats' => $stats, 'data' => $data], 3600);
        }

        $latestAnnouncements = [];
        try {
            require_once __DIR__ . '/../model/AnnouncementModel.php';
            $annModel = new AnnouncementModel($this->db);
            $allAnnouncements = $annModel->getAll();
            if (!empty($allAnnouncements)) {
                $latestAnnouncements = array_slice($allAnnouncements, 0, 3);
            }
        } catch (\Throwable $e) {
            $latestAnnouncements = [];
        }

        $content = dirname(__DIR__) . '/views/pages/dashboard/admin.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    private function getStats() {
        $stats = [
            'total_users' => 0,
            'total_teachers' => 0,
            'total_certificates' => 0,
            'average_grade' => 0
        ];

        $result = $this->db->query("SELECT COUNT(*) as total FROM users WHERE level = 'user'");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_users'] = $row['total'];
        }

        $result = $this->db->query("SELECT COUNT(*) as total FROM users WHERE level = 'guru'");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_teachers'] = $row['total'];
        }

        $result = $this->db->query("SELECT COUNT(*) as total FROM certificates");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_certificates'] = $row['total'];
        }

        $result = $this->db->query("SELECT COUNT(*) as total FROM classes");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_classes'] = $row['total'];
        }

        return $stats;
    }

    private function getAttendanceData() {
        $data = [
            'labels' => [],
            'hadir' => [],
            'absen' => [],
            'terlambat' => []
        ];

        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $last7Days[$date] = [
                'label' => date('d M', strtotime($date)),
                'hadir' => 0,
                'absen' => 0,
                'terlambat' => 0
            ];
        }

        $sql = "SELECT 
                    DATE(date) as tanggal,
                    SUM(CASE WHEN status = 'hadir' OR status = 'present' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'absen' OR status = 'absent' THEN 1 ELSE 0 END) as absen,
                    SUM(CASE WHEN status = 'terlambat' OR status = 'late' THEN 1 ELSE 0 END) as terlambat
                FROM attendance 
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(date)
                ORDER BY tanggal";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $tanggal = $row['tanggal'];
                if (isset($last7Days[$tanggal])) {
                    $last7Days[$tanggal]['hadir'] = (int)$row['hadir'];
                    $last7Days[$tanggal]['absen'] = (int)$row['absen'];
                    $last7Days[$tanggal]['terlambat'] = (int)$row['terlambat'];
                }
            }
        }

        foreach ($last7Days as $dayData) {
            $data['labels'][] = $dayData['label'];
            $data['hadir'][] = $dayData['hadir'];
            $data['absen'][] = $dayData['absen'];
            $data['terlambat'][] = $dayData['terlambat'];
        }

        return $data;
    }

    private function getGradesData() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT s.name as subject, AVG(g.score) as average_grade 
                FROM grades g
                JOIN subjects s ON g.subject_id = s.id
                GROUP BY s.name 
                ORDER BY average_grade DESC";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['subject'];
                $data['values'][] = round($row['average_grade'], 1);
            }
        }

        return $data;
    }

    private function getTasksData() {
        $data = ['completed' => 0, 'pending' => 0];

        $sql = "SELECT 
                    status,
                    COUNT(*) as total
                FROM tasks_completed
                GROUP BY status";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if ($row['status'] === 'completed') {
                    $data['completed'] = (int)$row['total'];
                } else {
                    $data['pending'] = (int)$row['total'];
                }
            }
        }

        return $data;
    }

    private function getStudentsPerClass() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT 
                    c.id, 
                    c.name as class, 
                    COUNT(DISTINCT cm.user_id) as total
                FROM classes c
                LEFT JOIN class_members cm ON c.id = cm.class_id
                LEFT JOIN users u ON cm.user_id = u.id
                WHERE (u.level IN ('user', 'siswa', 'student') 
                       OR cm.role IN ('student', 'user', 'siswa') 
                       OR (cm.role IS NULL AND u.level IS NOT NULL AND u.level NOT IN ('admin', 'guru', 'teacher')))
                GROUP BY c.id, c.name
                HAVING total > 0
                ORDER BY c.name";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (!empty($row['class'])) {
                    $data['labels'][] = $row['class'];
                    $data['values'][] = (int)$row['total'];
                }
            }
        }

        if (empty($data['labels']) || array_sum($data['values']) === 0) {
            $data = ['labels' => [], 'values' => []];
            $sql2 = "SELECT 
                        c.id, 
                        c.name as class, 
                        COUNT(DISTINCT cm.user_id) as total
                     FROM classes c
                     INNER JOIN class_members cm ON c.id = cm.class_id
                     LEFT JOIN users u ON cm.user_id = u.id
                     WHERE u.level IS NOT NULL 
                       AND u.level NOT IN ('admin', 'guru', 'teacher')
                     GROUP BY c.id, c.name
                     ORDER BY c.name";
            $res2 = $this->db->query($sql2);
            if ($res2) {
                while ($r = $res2->fetch_assoc()) {
                    if (!empty($r['class']) && (int)$r['total'] > 0) {
                        $data['labels'][] = $r['class'];
                        $data['values'][] = (int)$r['total'];
                    }
                }
            }
        }

        if (empty($data['labels'])) {
            $sql3 = "SELECT 
                        c.id, 
                        c.name as class, 
                        COUNT(DISTINCT cm.user_id) as total
                     FROM classes c
                     INNER JOIN class_members cm ON c.id = cm.class_id
                     GROUP BY c.id, c.name
                     ORDER BY c.name";
            $res3 = $this->db->query($sql3);
            if ($res3) {
                while ($r3 = $res3->fetch_assoc()) {
                    if (!empty($r3['class']) && (int)$r3['total'] > 0) {
                        $data['labels'][] = $r3['class'];
                        $data['values'][] = (int)$r3['total'];
                    }
                }
            }
        }

        return $data;
    }

    private function getTeachingSchedules() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT 
                    u.name,
                    COUNT(s.id) as total_schedules
                FROM users u
                LEFT JOIN schedule s ON u.id = s.teacher_id
                WHERE u.level = 'guru'
                GROUP BY u.id
                ORDER BY total_schedules DESC
                LIMIT 10";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['name'];
                $data['values'][] = (int)$row['total_schedules'];
            }
        }

        return $data;
    }

    private function getNewStudents() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $last6Months[$date] = [
                'label' => $monthName,
                'total' => 0
            ];
        }

        $sql = "SELECT 
                    DATE_FORMAT(join_date, '%Y-%m') as month_key,
                    DATE_FORMAT(join_date, '%b') as month,
                    COUNT(*) as total
                FROM users
                WHERE `level` = 'user' AND join_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(join_date), MONTH(join_date)
                ORDER BY month_key";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthKey = $row['month_key'];
                if (isset($last6Months[$monthKey])) {
                    $last6Months[$monthKey]['total'] = (int)$row['total'];
                }
            }
        }

        foreach ($last6Months as $monthData) {
            $data['labels'][] = $monthData['label'];
            $data['values'][] = $monthData['total'];
        }

        return $data;
    }

    private function getCertificatesData() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $last6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $last6Months[$date] = [
                'label' => $monthName,
                'total' => 0
            ];
        }

        $sql = "SELECT 
                    DATE_FORMAT(issued_at, '%Y-%m') as month_key,
                    DATE_FORMAT(issued_at, '%b') as month,
                    COUNT(*) as total
                FROM certificates
                WHERE issued_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(issued_at), MONTH(issued_at)
                ORDER BY month_key";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $monthKey = $row['month_key'];
                if (isset($last6Months[$monthKey])) {
                    $last6Months[$monthKey]['total'] = (int)$row['total'];
                }
            }
        }

        foreach ($last6Months as $monthData) {
            $data['labels'][] = $monthData['label'];
            $data['values'][] = $monthData['total'];
        }

        return $data;
    }

    private function getDocumentationData() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT 
                    section,
                    COUNT(*) as total
                FROM documentation
                GROUP BY section
                ORDER BY total DESC";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['section'];
                $data['values'][] = (int)$row['total'];
            }
        }

        return $data;
    }

    public function docsIndex()
    {
        global $config;
        $title = "Docs — Admin";
        $description = "Kelola dokumentasi";

        $limit = (int)($_GET['limit'] ?? 10);
        if ($limit <= 0) $limit = 10;

        $page = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1);
        if ($page <= 0) $page = 1;

        $_GET['page_num'] = $page;

        $q = trim($_GET['q'] ?? '');
        $offset = ($page - 1) * $limit;

        $where = '';
        if ($q !== '') {
            $esc = mysqli_real_escape_string($config, $q);
            $where = "WHERE section LIKE '%{$esc}%' OR title LIKE '%{$esc}%' OR description LIKE '%{$esc}%'";
        }

        $total = 0;
        $resTotal = mysqli_query($config, "SELECT COUNT(*) as total FROM documentation {$where}");
        if ($resTotal && $row = mysqli_fetch_assoc($resTotal)) {
            $total = (int)$row['total'];
        }

        $docs = [];
        $sql = "SELECT * FROM documentation {$where} ORDER BY section, title LIMIT {$offset}, {$limit}";
        $res = mysqli_query($config, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $docs[] = $r;
            mysqli_free_result($res);
        }

        $content = dirname(__DIR__) . '/views/pages/admin/admin_docs_list.php';

        $isAjax = !empty($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        if ($isAjax) {
            $ajax = true;
            include $content;
            return;
        }

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function docsCreate()
    {
        $title = "Buat Dokumentasi";
        $description = "Tambah dokumentasi baru";
        $doc = null;
    $content = dirname(__DIR__) . '/views/pages/docs/docs_form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function docsStore()
    {
        global $config;
        $section = trim($_POST['section'] ?? 'General');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $contentText = trim($_POST['content'] ?? '');
        if ($slug === '' && $title !== '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        $stmt = mysqli_prepare($config, "INSERT INTO documentation (section, title, slug, description, content) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $section, $title, $slug, $description, $contentText);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Dokumentasi dibuat';
        header('Location: index.php?page=dashboard-admin-docs');
        exit;
    }

    public function docsEdit()
    {
        global $config;
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=dashboard-admin-docs');
            exit;
        }
        $stmt = mysqli_prepare($config, "SELECT * FROM documentation WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $doc = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        $title = "Edit Dokumentasi";
        $description = "Ubah dokumentasi";
    $content = dirname(__DIR__) . '/views/pages/docs/docs_form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function docsUpdate()
    {
        global $config;
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=dashboard-admin-docs');
            exit;
        }
        $section = trim($_POST['section'] ?? 'General');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $contentText = trim($_POST['content'] ?? '');
        if ($slug === '' && $title !== '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        $stmt = mysqli_prepare($config, "UPDATE documentation SET section = ?, title = ?, slug = ?, description = ?, content = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssssi", $section, $title, $slug, $description, $contentText, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Dokumentasi diupdate';
        header('Location: index.php?page=dashboard-admin-docs');
        exit;
    }

    public function docsDelete()
    {
        header('Content-Type: application/json');
        
        global $config;
        require_once __DIR__ . '/../model/documentation.php';
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak valid.'
            ]);
            exit;
        }
        
        $model = new Documentation($config);
        $ok = $model->delete($id);
        
        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Dokumentasi berhasil dihapus'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal menghapus dokumentasi. Silakan coba lagi.'
            ]);
        }
        exit;
    }

    public function news()
    {
        global $config;
        $title = "News — Admin";
        $description = "Kelola berita";

        $limit = (int)($_GET['limit'] ?? 10);
        if ($limit <= 0) $limit = 10;

        $page = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1);
        if ($page <= 0) $page = 1;

        $_GET['page_num'] = $page;

        $q = trim($_GET['q'] ?? '');
        $offset = ($page - 1) * $limit;

        $where = '';
        if ($q !== '') {
            $esc = mysqli_real_escape_string($config, $q);
            $where = "WHERE title LIKE '%{$esc}%' OR content LIKE '%{$esc}%' OR category LIKE '%{$esc}%' OR author LIKE '%{$esc}%'";
        }

        $total = 0;
        $resTotal = mysqli_query($config, "SELECT COUNT(*) as total FROM news {$where}");
        if ($resTotal && $row = mysqli_fetch_assoc($resTotal)) {
            $total = (int)$row['total'];
        }

        $news = [];
        $sql = "SELECT * FROM news {$where} ORDER BY created_at DESC, id DESC LIMIT {$offset}, {$limit}";
        $res = mysqli_query($config, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $news[] = $r;
            mysqli_free_result($res);
        }

        $content = dirname(__DIR__) . '/views/pages/admin/admin_news_list.php';

        $isAjax = !empty($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        if ($isAjax) {
            $ajax = true;
            include $content;
            return;
        }

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function newsCreate()
    {
        $title = "Buat Berita";
        $description = "Tambah berita baru";
        $newsItem = null;
    $content = dirname(__DIR__) . '/views/pages/news/news_form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function newsStore()
    {
        global $config;
        $titleField = trim($_POST['title'] ?? '');
        $contentField = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'Umum');
        $author = trim($_POST['author'] ?? 'Admin');
        $createdAt = trim($_POST['created_at'] ?? date('Y-m-d H:i:s'));

        $thumbnailPath = '';
        if (!empty($_FILES['thumbnail']['name'] ?? '')) {
            $uploadDir = __DIR__ . '/../../public/uploads/news/';
            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
            $fname = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $_FILES['thumbnail']['name']);
            $dest = $uploadDir . $fname;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest)) {
                $thumbnailPath = 'uploads/news/' . $fname;
            }
        }

        $stmt = mysqli_prepare($config, "INSERT INTO news (title, content, category, thumbnail, author, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $titleField, $contentField, $category, $thumbnailPath, $author, $createdAt);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Berita dibuat';
        header('Location: index.php?page=dashboard-admin-news');
        exit;
    }

    public function newsEdit()
    {
        global $config;
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=dashboard-admin-news');
            exit;
        }
        $stmt = mysqli_prepare($config, "SELECT * FROM news WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $newsItem = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        $title = "Edit Berita";
        $description = "Ubah berita";
    $content = dirname(__DIR__) . '/views/pages/news/news_form.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function newsUpdate()
    {
        global $config;
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=dashboard-admin-news');
            exit;
        }
        $section = trim($_POST['section'] ?? 'General');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $contentText = trim($_POST['content'] ?? '');
        if ($slug === '' && $title !== '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        $stmt = mysqli_prepare($config, "UPDATE documentation SET section = ?, title = ?, slug = ?, description = ?, content = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssssi", $section, $title, $slug, $description, $contentText, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Berita diupdate';
        header('Location: index.php?page=dashboard-admin-news');
        exit;
    }

    public function newsDelete()
    {
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        $id = (int)($_POST['id'] ?? 0);
        $model = new NewsModel($config);
        if ($id > 0) {
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Berita dihapus' : 'Gagal menghapus berita.';
        } else {
            $_SESSION['flash'] = 'ID tidak valid.';
        }
        header('Location: index.php?page=dashboard-admin-news');
        exit;
    }
}