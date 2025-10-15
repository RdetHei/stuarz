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

        $content = dirname(__DIR__) . '/views/pages/news.php';
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function show(){
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        $model = new NewsModel($config);
        $id = (int)($_GET['id'] ?? 0);
        $newsItem = $id ? $model->find($id) : null;
        if (!$newsItem) {
            http_response_code(404);
            header('Location: ../app/views/pages/notFound.php');
            return;
        }
        $title = htmlspecialchars($newsItem['title']) . ' - News - Stuarz';
        $description = mb_substr(strip_tags($newsItem['content']), 0, 140);
        $content = dirname(__DIR__) . '/views/pages/news_single.php';
        include dirname(__DIR__) . '/views/layouts/layout.php';
    }

    public function delete() {
        global $config;
        require_once __DIR__ . '/../model/newsModel.php';
        $model = new NewsModel($config);
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $ok = $model->delete($id);
            $_SESSION['flash'] = $ok ? 'Berita dihapus.' : 'Gagal menghapus berita.';
        } else {
            $_SESSION['flash'] = 'ID tidak valid.';
        }
        header('Location: index.php?page=news');
        exit;
    }
}


?>