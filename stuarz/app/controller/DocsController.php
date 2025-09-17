<?php
require_once dirname(__DIR__) . '/config/config.php';

class DocsController
{
    public function docs()
    {
        global $config;

        // Ambil query search
        $search = $_GET['q'] ?? null;

        if ($search) {
            $stmt = mysqli_prepare(
                $config,
                "SELECT * FROM documentation 
                 WHERE title LIKE CONCAT('%', ?, '%') 
                    OR description LIKE CONCAT('%', ?, '%') 
                    OR content LIKE CONCAT('%', ?, '%') 
                 ORDER BY section, title"
            );
            mysqli_stmt_bind_param($stmt, "sss", $search, $search, $search);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        } else {
            $sql = "SELECT * FROM documentation ORDER BY section, title";
            $result = mysqli_query($config, $sql);
        }

        $docs = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $docs[$row['section']][] = $row;
            }
        }

        // Jika ada slug di URL, ambil detail
        $slug = $_GET['doc'] ?? null;
        $currentDoc = null;

        if ($slug) {
            $stmt = mysqli_prepare($config, "SELECT * FROM documentation WHERE slug = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "s", $slug);
            mysqli_stmt_execute($stmt);
            $resDoc = mysqli_stmt_get_result($stmt);
            $currentDoc = mysqli_fetch_assoc($resDoc);
            mysqli_stmt_close($stmt);
        }

        // Data untuk layout
        $title = "Documentation - Stuarz";
        $description = "Panduan penggunaan Stuarz documentation";

        // Ini isi halaman
        $content = dirname(__DIR__) . '/../view/landing/page/docs.php';

        // Panggil layout utama
        include dirname(__DIR__) . '/../view/layout.php';
    }

    public function create()
    {
        global $config;
        $title = "Create Documentation";
        $description = "Tambah dokumentasi baru";
        $doc = null;
        $content = dirname(__DIR__) . '/../view/landing/page/docs_form.php';
        include dirname(__DIR__) . '/../view/layout.php';
    }

    public function store()
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
        $_SESSION['flash'] = 'Dokumentasi berhasil dibuat';
        header('Location: index.php?page=docs');
        exit;
    }

    public function edit()
    {
        global $config;
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: index.php?page=docs');
            exit;
        }
        $stmt = mysqli_prepare($config, "SELECT * FROM documentation WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $doc = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);
        $title = "Edit Documentation";
        $description = "Ubah dokumentasi";
        $content = dirname(__DIR__) . '/../view/landing/page/docs_form.php';
        include dirname(__DIR__) . '/../view/layout.php';
    }

    public function update()
    {
        global $config;
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
        if ($slug === '' && $title !== '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $title));
            $slug = trim($slug, '-');
        }
        $stmt = mysqli_prepare($config, "UPDATE documentation SET section = ?, title = ?, slug = ?, description = ?, content = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssssi", $section, $title, $slug, $description, $contentText, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['flash'] = 'Dokumentasi berhasil diupdate';
        header('Location: index.php?page=docs');
        exit;
    }

    public function delete()
    {
        global $config;
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = mysqli_prepare($config, "DELETE FROM documentation WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['flash'] = 'Dokumentasi dihapus';
        }
        header('Location: index.php?page=docs');
        exit;
    }
}
