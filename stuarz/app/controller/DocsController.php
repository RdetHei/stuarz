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
            $stmt = mysqli_prepare($config, 
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
}
