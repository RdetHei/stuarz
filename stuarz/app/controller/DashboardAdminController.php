<?php
class DashboardAdminController
{
    public function dashboardAdmin()
    {
        $title = "Dashboard - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../view/landing/page/dashboard.php';

        include '../view/dLayout.php';
    }

    public function docsIndex()
    {
        global $config;
        $title = "Docs — Admin";
        $description = "Kelola dokumentasi";
        $res = mysqli_query($config, "SELECT * FROM documentation ORDER BY section, title");
        $docs = [];
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $docs[] = $r;
        }
        $content = '../view/landing/page/admin_docs_list.php';
        include '../view/dLayout.php';
    }

    public function docsCreate()
    {
        $title = "Buat Dokumentasi";
        $description = "Tambah dokumentasi baru";
        $doc = null;
        $content = '../view/landing/page/docs_form.php';
        include '../view/dLayout.php';
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
        $content = '../view/landing/page/docs_form.php';
        include '../view/dLayout.php';
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
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = mysqli_prepare($config, "DELETE FROM documentation WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['flash'] = 'Dokumentasi dihapus';
        }
        header('Location: index.php?page=dashboard-admin-docs');
        exit;
    }
}
