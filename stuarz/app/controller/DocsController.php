<?php
require_once dirname(__DIR__) . '/config/config.php';

class DocsController
{
    public function docs()
    {
        global $config;

        // Ambil semua dokumentasi
        $sql = "SELECT * FROM documentation ORDER BY section, title";
        $result = mysqli_query($config, $sql);

        $docs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $docs[$row['section']][] = $row;
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

        $view = dirname(__DIR__) . '/../view/landing/page/docs.php';
        if (!is_file($view)) {
            echo 'View tidak ditemukan di: ' . $view;
            return;
        }
        include $view;
    }
}
?>