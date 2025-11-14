<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Chart.js library and init script (kept in the view so data is available here) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
        <style>
        @media print {
            /* hide admin chrome */
            #sidebar, .sidebar, .hamburger, nav, .ai-helper, .chat-modal, #sidebarToggle, #sidebarLogoToggle { display: none !important; }
            /* hide dashboard header */
            #dHeader, .d-header, header { display: none !important; }
            /* make main content full width */
            #content, main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            /* normalize colors for printing */
            body, #content, article, .prose { background: #fff !important; color: #000 !important; }
            a:after { content: " (" attr(href) ")"; font-size: 90%; }
        }
        </style>
    
</head>
<body class="bg-gray-900 flex flex-col min-h-screen">
    <?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/dHeader.php'; ?>

    <main id="content" class="flex-1 transition-all duration-300 p-6">
        <?php
        $viewToInclude = $content ?? '';
        if ($viewToInclude && file_exists($viewToInclude)) {
            include $viewToInclude;
        } else {
            include __DIR__ . '/../pages/errors/notFound.php';
        }
        ?>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>

    <?php 
    define('BASEPATH', true);
    include __DIR__ . '/../components/ai-helper/chat-modal.php'; 
    ?>
</body>
</html>