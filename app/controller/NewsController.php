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

        // support AJAX fragment for live search
        $isAjax = !empty($_GET['ajax']) || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
        if ($isAjax) {
            $ajax = true;
            include $content;
            return;
        }

        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function delete() {
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $model = new NewsModel($config);

        $id = intval($_POST['id'] ?? 0);
        $confirm = $_POST['confirm'] ?? null;

        if ($id <= 0 || $confirm !== '1') {
            $_SESSION['flash'] = 'ID tidak valid atau aksi tidak dikonfirmasi.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=news');
            exit;
        }

        $newsItem = $model->find($id);
        if (!$newsItem) {
            $_SESSION['flash'] = 'Berita tidak ditemukan.';
            $_SESSION['flash_level'] = 'danger';
            header('Location: index.php?page=news');
            exit;
        }

        $title = $newsItem['title'] ?? '';
        $ok = $model->delete($id);
        $_SESSION['flash'] = $ok ? 'Berita dihapus.' : 'Gagal menghapus berita.';
        $_SESSION['flash_level'] = $ok ? 'success' : 'danger';

        if ($ok) {
            $uid = $_SESSION['user']['id'] ?? 0;
            notify_event($config, 'delete', 'news', $id, $uid, "Berita dihapus: {$title}", null);
        }

        header('Location: index.php?page=news');
        exit;
    }

    public function show(){
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        require_once dirname(__DIR__) . '/helpers/notifier.php';
        $model = new NewsModel($config);
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID tidak valid.';
            header('Location: index.php?page=news');
            exit;
        }

        $newsItem = $model->find($id);
        if (!$newsItem) {
            $_SESSION['flash'] = 'Berita tidak ditemukan.';
            header('Location: index.php?page=news');
            exit;
        }

        $content = dirname(__DIR__) . '/views/pages/news/news_single.php';
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function print()
    {
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        $model = new NewsModel($config);
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['flash'] = 'ID tidak valid.';
            header('Location: index.php?page=news');
            exit;
        }

        $newsItem = $model->find($id);
        if (!$newsItem) {
            $_SESSION['flash'] = 'Berita tidak ditemukan.';
            header('Location: index.php?page=news');
            exit;
        }

        $content = dirname(__DIR__) . '/views/pages/news/news_print.php';
        include dirname(__DIR__) . '/views/layouts/print.php';
    }
}


?>