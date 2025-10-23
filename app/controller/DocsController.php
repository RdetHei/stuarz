<?php
require_once dirname(__DIR__) . '/config/config.php';

class DocsController
{
    private $db;

    public function __construct()
    {
        global $config;
        $this->db = $config;
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    private function stmt_fetch_all($stmt)
    {
        // Return array of assoc rows from a prepared statement, with fallback when get_result not available
        $rows = [];
        if (method_exists($stmt, 'get_result')) {
            $res = $stmt->get_result();
            if ($res) {
                while ($r = $res->fetch_assoc()) $rows[] = $r;
                $res->free();
            }
            return $rows;
        }

        // Fallback: use metadata + bind_result
        $meta = $stmt->result_metadata();
        if (!$meta) return $rows;
        $fields = [];
        $row = [];
        while ($f = $meta->fetch_field()) {
            $fields[] = &$row[$f->name];
        }
        $meta->free();
        call_user_func_array([$stmt, 'bind_result'], $fields);
        while ($stmt->fetch()) {
            $rec = [];
            foreach ($row as $k => $v) $rec[$k] = $v;
            $rows[] = $rec;
        }
        return $rows;
    }

    public function docs()
    {
        $search = trim((string)($_GET['q'] ?? ''));

        $docs = [];

        if ($search !== '') {
            // prepared search
            $sql = "SELECT * FROM documentation 
                    WHERE title LIKE CONCAT('%', ?, '%') 
                       OR description LIKE CONCAT('%', ?, '%') 
                       OR content LIKE CONCAT('%', ?, '%') 
                    ORDER BY section, title";
            $stmt = mysqli_prepare($this->db, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
                mysqli_stmt_execute($stmt);
                $rows = $this->stmt_fetch_all($stmt);
                foreach ($rows as $row) $docs[$row['section']][] = $row;
                mysqli_stmt_close($stmt);
            } else {
                // fallback: safe escaped query to avoid fatal error if prepare fails
                $esc = mysqli_real_escape_string($this->db, $search);
                $qsql = "SELECT * FROM documentation 
                         WHERE title LIKE '%{$esc}%' OR description LIKE '%{$esc}%' OR content LIKE '%{$esc}%'
                         ORDER BY section, title";
                if ($res = mysqli_query($this->db, $qsql)) {
                    while ($row = mysqli_fetch_assoc($res)) $docs[$row['section']][] = $row;
                    mysqli_free_result($res);
                }
            }
        } else {
            $sql = "SELECT * FROM documentation ORDER BY section, title";
            if ($res = mysqli_query($this->db, $sql)) {
                while ($row = mysqli_fetch_assoc($res)) $docs[$row['section']][] = $row;
                mysqli_free_result($res);
            }
        }

        // detail doc if slug provided
        $slug = trim((string)($_GET['doc'] ?? ''));
        $currentDoc = null;
        if ($slug !== '') {
            $stmt = mysqli_prepare($this->db, "SELECT * FROM documentation WHERE slug = ? LIMIT 1");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $slug);
                mysqli_stmt_execute($stmt);
                $rows = $this->stmt_fetch_all($stmt);
                if (!empty($rows)) $currentDoc = $rows[0];
                mysqli_stmt_close($stmt);
            } else {
                $esc = mysqli_real_escape_string($this->db, $slug);
                $res = mysqli_query($this->db, "SELECT * FROM documentation WHERE slug = '{$esc}' LIMIT 1");
                if ($res) {
                    $currentDoc = mysqli_fetch_assoc($res) ?: null;
                    mysqli_free_result($res);
                }
            }
        }

        // Data for layout
        $title = "Documentation - Stuarz";
        $description = "Panduan penggunaan Stuarz documentation";
        $content = dirname(__DIR__) . '/views/pages/docs/docs.php';
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function create()
    {
        $title = "Create Documentation";
        $description = "Tambah dokumentasi baru";
        $doc = null;
        $content = dirname(__DIR__) . '/views/pages/docs_form.php';
        include dirname(__DIR__) . '/views/layouts/dlayout.php';
    }

    public function store()
    {
        $section = trim($_POST['section'] ?? 'General');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $contentText = trim($_POST['content'] ?? '');

        if ($title === '') {
            $_SESSION['flash'] = 'Title is required';
            header('Location: index.php?page=dashboard-admin-docs-create');
            exit;
        }

        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }

        $stmt = mysqli_prepare($this->db, "INSERT INTO documentation (section, title, slug, description, content) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $section, $title, $slug, $description, $contentText);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['flash'] = 'Dokumentasi berhasil dibuat';
        } else {
            $_SESSION['flash'] = 'Gagal membuat dokumentasi';
        }

        header('Location: index.php?page=docs');
        exit;
    }

    public function edit()
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=docs');
            exit;
        }

        $stmt = mysqli_prepare($this->db, "SELECT * FROM documentation WHERE id = ? LIMIT 1");
        $doc = null;
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $rows = $this->stmt_fetch_all($stmt);
            if (!empty($rows)) $doc = $rows[0];
            mysqli_stmt_close($stmt);
        }

        $title = "Edit Documentation";
        $description = "Ubah dokumentasi";
        $content = dirname(__DIR__) . '/views/pages/docs/form.php';
        include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }

    public function update($id) {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=docs');
            exit;
        }

        $section = trim($_POST['section'] ?? 'General');
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $contentText = trim($_POST['content'] ?? '');

        if ($title === '') {
            $_SESSION['flash'] = 'Title is required';
            header('Location: index.php?page=dashboard-admin-docs-edit&id=' . $id);
            exit;
        }

        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }

        $stmt = mysqli_prepare($this->db, "UPDATE documentation SET section = ?, title = ?, slug = ?, description = ?, content = ? WHERE id = ?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssi", $section, $title, $slug, $description, $contentText, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['flash'] = 'Dokumentasi berhasil diupdate';
        } else {
            $_SESSION['flash'] = 'Gagal mengupdate dokumentasi';
        }

        header('Location: index.php?page=docs');
        exit;
    }

    public function delete()
    {
        require_once __DIR__ . '/../model/documentation.php';
        $id = (int)($_GET['id'] ?? 0);
        $model = new Documentation($this->db);
        if ($id > 0) {
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Dokumentasi dihapus' : 'Gagal menghapus dokumentasi';
        } else {
            $_SESSION['flash'] = 'ID tidak valid.';
        }
        header('Location: index.php?page=docs');
        exit;
    }
}
