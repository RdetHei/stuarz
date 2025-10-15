<?php
class DashboardAdminController
{
    public function dashboardAdmin()
    {
        $title = "Documentations - Stuarz";
        $description = "Welcome to your dashboard";

        // Tentukan file view utama
        $content = '../app/views/pages/dashboard.php';

        include '../app/views/layouts/dLayout.php';
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
        $content = '../app/views/pages/admin_docs_list.php';
        include '../app/views/layouts/dLayout.php';
    }

    public function docsCreate()
    {
        $title = "Buat Dokumentasi";
        $description = "Tambah dokumentasi baru";
        $doc = null;
        $content = '../app/views/pages/docs_form.php';
        include '../app/views/layouts/dLayout.php';
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
        $content = '../app/views/pages/docs_form.php';
        include '../app/views/layouts/dLayout.php';
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
        $res = mysqli_query($config, "SELECT * FROM news ORDER BY created_at DESC, id DESC");
        $news = [];
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $news[] = $r;
        }
        $content = '../app/views/pages/admin_news_list.php';
        include '../app/views/layouts/dLayout.php';
    }

    public function newsCreate()
    {
        $title = "Buat Berita";
        $description = "Tambah berita baru";
        $newsItem = null;
        $content = '../app/views/pages/news_form.php';
        include '../app/views/layouts/dLayout.php';
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
        $content = '../app/views/pages/news_form.php';
        include '../app/views/layouts/dLayout.php';
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
