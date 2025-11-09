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
        // Cek apakah user adalah admin
        if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
            header('Location: index.php?page=dashboard-admin');
            exit;
        }

        $title = "Admin Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Ambil data untuk stats cards
        $stats = $this->getStats();
        
        // Ambil data untuk grafik
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

        // Tentukan file view utama
        $content = dirname(__DIR__) . '/views/pages/admin/dashboard.php';

        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    private function getStats() {
        $stats = [
            'total_users' => 0,
            'total_students' => 0,
            'total_certificates' => 0,
            'average_grade' => 0
        ];

        // Total Users
        $result = $this->db->query("SELECT COUNT(*) as total FROM users");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_users'] = $row['total'];
        }

        // Total Students (count users with level = 'student')
        $result = $this->db->query("SELECT COUNT(*) as total FROM users WHERE `level` = 'student'");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_students'] = $row['total'];
        }

        // Total Certificates
        $result = $this->db->query("SELECT COUNT(*) as total FROM certificates");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['total_certificates'] = $row['total'];
        }

        // Average Grade
        $result = $this->db->query("SELECT AVG(grade) as avg FROM average_grade");
        if ($result && $row = $result->fetch_assoc()) {
            $stats['average_grade'] = round($row['avg'], 1);
        }

        return $stats;
    }

    private function getAttendanceData() {
        // Ambil data kehadiran 7 hari terakhir
        $data = [
            'labels' => [],
            'hadir' => [],
            'absen' => [],
            'terlambat' => []
        ];

        $sql = "SELECT 
                    DATE(date) as tanggal,
                    SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                    SUM(CASE WHEN status = 'absen' THEN 1 ELSE 0 END) as absen,
                    SUM(CASE WHEN status = 'terlambat' THEN 1 ELSE 0 END) as terlambat
                FROM attendance 
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                GROUP BY DATE(date)
                ORDER BY date";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = date('d M', strtotime($row['tanggal']));
                $data['hadir'][] = (int)$row['hadir'];
                $data['absen'][] = (int)$row['absen'];
                $data['terlambat'][] = (int)$row['terlambat'];
            }
        }

        return $data;
    }

    private function getGradesData() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT subject, AVG(grade) as average_grade 
                FROM average_grade 
                GROUP BY subject 
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

        // Count members per class using classes and class_members tables
        $sql = "SELECT c.name as class, COUNT(cm.user_id) as total
                FROM classes c
                LEFT JOIN class_members cm ON c.id = cm.class_id
                GROUP BY c.id
                ORDER BY c.name";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['class'];
                $data['values'][] = (int)$row['total'];
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
                WHERE u.level = 'teacher'
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

        // New students per month - use users table's join_date and filter by student level
        $sql = "SELECT 
                    DATE_FORMAT(join_date, '%b') as month,
                    COUNT(*) as total
                FROM users
                WHERE `level` = 'student' AND join_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(join_date), MONTH(join_date)
                ORDER BY join_date";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['month'];
                $data['values'][] = (int)$row['total'];
            }
        }

        return $data;
    }

    private function getCertificatesData() {
        $data = [
            'labels' => [],
            'values' => []
        ];

        $sql = "SELECT 
                    DATE_FORMAT(issued_at, '%b') as month,
                    COUNT(*) as total
                FROM certificates
                WHERE issued_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY YEAR(issued_at), MONTH(issued_at)
                ORDER BY issued_at";

        $result = $this->db->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data['labels'][] = $row['month'];
                $data['values'][] = (int)$row['total'];
            }
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

        // Pagination and search parameters
        $limit = (int)($_GET['limit'] ?? 10);
        if ($limit <= 0) $limit = 10;

        // Accept either 'p' or 'page_num' for compatibility with view links
        $page = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1);
        if ($page <= 0) $page = 1;

        // Normalize for view which reads page_num
        $_GET['page_num'] = $page;

        $q = trim($_GET['q'] ?? '');
        $offset = ($page - 1) * $limit;

        $where = '';
        if ($q !== '') {
            $esc = mysqli_real_escape_string($config, $q);
            $where = "WHERE section LIKE '%{$esc}%' OR title LIKE '%{$esc}%' OR description LIKE '%{$esc}%'";
        }

        // Total count for pagination
        $total = 0;
        $resTotal = mysqli_query($config, "SELECT COUNT(*) as total FROM documentation {$where}");
        if ($resTotal && $row = mysqli_fetch_assoc($resTotal)) {
            $total = (int)$row['total'];
        }

        // Fetch paginated rows
        $docs = [];
        $sql = "SELECT * FROM documentation {$where} ORDER BY section, title LIMIT {$offset}, {$limit}";
        $res = mysqli_query($config, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $docs[] = $r;
            mysqli_free_result($res);
        }

        // Expose variables used by the view: $docs, $total, $limit, $q
        $content = dirname(__DIR__) . '/views/pages/admin/admin_docs_list.php';
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
        mysqli_stmt_bind_param($stmt, "sssss", $section, $title, $slug, $description, $contentText);
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
        mysqli_stmt_bind_param($stmt, "sssssi", $section, $title, $slug, $description, $contentText, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Dokumentasi diupdate';
        header('Location: index.php?page=dashboard-admin-docs');
        exit;
    }

    public function docsDelete()
    {
        global $config;
        require_once __DIR__ . '/../model/documentation.php';
        $id = (int)($_POST['id'] ?? 0);
        $model = new Documentation($config);
        if ($id > 0) {
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Dokumentasi dihapus' : 'Gagal menghapus dokumentasi';
        } else {
            $_SESSION['flash'] = 'ID tidak valid.';
        }
        header('Location: index.php?page=dashboard-admin-docs');
        exit;
    }

    // News CRUD
    public function news()
    {
        global $config;
        $title = "News — Admin";
        $description = "Kelola berita";

        // Pagination and search parameters
        $limit = (int)($_GET['limit'] ?? 10);
        if ($limit <= 0) $limit = 10;

        // Accept either 'p' or 'page_num' for compatibility with view links
        $page = isset($_GET['p']) ? (int)$_GET['p'] : (isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1);
        if ($page <= 0) $page = 1;

        // Normalize for view which reads page_num
        $_GET['page_num'] = $page;

        $q = trim($_GET['q'] ?? '');
        $offset = ($page - 1) * $limit;

        $where = '';
        if ($q !== '') {
            $esc = mysqli_real_escape_string($config, $q);
            $where = "WHERE title LIKE '%{$esc}%' OR content LIKE '%{$esc}%' OR category LIKE '%{$esc}%' OR author LIKE '%{$esc}%'";
        }

        // Total count for pagination
        $total = 0;
        $resTotal = mysqli_query($config, "SELECT COUNT(*) as total FROM news {$where}");
        if ($resTotal && $row = mysqli_fetch_assoc($resTotal)) {
            $total = (int)$row['total'];
        }

        // Fetch paginated rows
        $news = [];
        $sql = "SELECT * FROM news {$where} ORDER BY created_at DESC, id DESC LIMIT {$offset}, {$limit}";
        $res = mysqli_query($config, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $news[] = $r;
            mysqli_free_result($res);
        }

        // Expose variables used by the view: $news, $total, $limit, $q
        $content = dirname(__DIR__) . '/views/pages/admin/admin_news_list.php';
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

        // Handle thumbnail upload (optional)
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
        $titleField = trim($_POST['title'] ?? '');
        $contentField = trim($_POST['content'] ?? '');
        $category = trim($_POST['category'] ?? 'Umum');
        $author = trim($_POST['author'] ?? 'Admin');
        $createdAt = trim($_POST['created_at'] ?? date('Y-m-d H:i:s'));

        // Thumbnail update optional
        $thumbnailPath = trim($_POST['thumbnail_existing'] ?? '');
        if (!empty($_FILES['thumbnail']['name'] ?? '')) {
            $uploadDir = __DIR__ . '/../../public/uploads/news/';
            if (!is_dir($uploadDir)) @mkdir($uploadDir, 0777, true);
            $fname = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $_FILES['thumbnail']['name']);
            $dest = $uploadDir . $fname;
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $dest)) {
                $thumbnailPath = 'uploads/news/' . $fname;
            }
        }

        $stmt = mysqli_prepare($config, "UPDATE news SET title = ?, content = ?, category = ?, thumbnail = ?, author = ?, created_at = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssssi", $titleField, $contentField, $category, $thumbnailPath, $author, $createdAt, $id);
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
