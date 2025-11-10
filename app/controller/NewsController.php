<?php
class NewsController{

    public function news(){
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        $title = "News - Stuarz";
        $description = "News";

        $model = new NewsModel($config);
        $q = trim($_GET['q'] ?? '');
        $cat = trim($_GET['cat'] ?? '');
        $page = max(1, (int)($_GET['p'] ?? 1));
        $perPage = 7;
        $cats = $model->categories();
        $res = $model->filterPaginated($q, $cat, $page, $perPage);
        $allNews = $res['rows'];
        $total = $res['total'];
        $totalPages = max(1, (int)ceil($total / $perPage));

        $content = dirname(__DIR__) . '/views/pages/news/news.php';
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function show(){
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $model = new NewsModel($config);
        $id = (int)($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID tidak valid.';
            header('Location: index.php?page=news');
            exit;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if ($method === 'GET' || !isset($_POST['confirm'])) {
            $newsItem = $model->find($id);
            if (!$newsItem) {
                $_SESSION['flash'] = 'Berita tidak ditemukan.';
                header('Location: index.php?page=news');
                exit;
            }
            $newsToDelete = $newsItem;
            $content = dirname(__DIR__) . '/views/pages/news/confirm_delete.php';
            include dirname(__DIR__) . '/views/layouts/dLayout.php';
            exit;
        }

        if (isset($_POST['confirm']) && (string)$_POST['confirm'] === '1') {
            $newsItem = $model->find($id);
            $title = $newsItem['title'] ?? '';
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Berita dihapus.' : 'Gagal menghapus berita.';
            if ($ok) {
                $uid = $_SESSION['user']['id'] ?? 0;
                notify_event($config, 'delete', 'news', $id, $uid, "Berita dihapus: {$title}", null);
            }
            header('Location: index.php?page=news');
            exit;
        }

        $_SESSION['flash'] = 'Aksi dibatalkan.';
        header('Location: index.php?page=news');
        exit;
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $model = new NewsModel($config);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            // fetch title for message if possible
            $newsItem = $model->find($id);
            $title = $newsItem['title'] ?? '';
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Berita dihapus.' : 'Gagal menghapus berita.';
            if ($ok) {
                $uid = $_SESSION['user']['id'] ?? 0;
                // avoid linking to localhost for delete notifications; use special card rendering
                notify_event($config, 'delete', 'news', $id, $uid, "Berita dihapus: {$title}", null);
            }
        } else {
            $_SESSION['flash'] = 'ID tidak valid.';
        }
        header('Location: index.php?page=news');
        exit;
    }
}


?>