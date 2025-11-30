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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
        <style>
        @media print {
            #sidebar, .sidebar, .hamburger, nav, .ai-helper, .chat-modal, #sidebarToggle, #sidebarLogoToggle { display: none !important; }
            #dHeader, .d-header, header { display: none !important; }
            #content, main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            body, #content, article, .prose { background: #fff !important; color: #000 !important; }
            a:after { content: " (" attr(href) ")"; font-size: 90%; }
        }
        </style>
    
</head>
<body class="bg-gray-900">

    <?php
    // Determine active page for highlighting student sidebar items
    $page = $_GET['page'] ?? '';
    $active = '';
    if (strpos($page, 'student/') === 0) {
            $parts = explode('/', $page);
            $active = $parts[1] ?? '';
    } elseif ($page === 'dashboard' || $page === '/dashboard') {
            $active = 'dashboard';
    }
    ?>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <?php
            // Use the main sidebar implementation but render it in student mode
            $student_mode = true;
            include __DIR__ . '/../components/sidebar.php';
        ?>

        <!-- Right column: header + content -->
        <div class="flex-1 flex flex-col">
            <?php include __DIR__ . '/../components/dHeader.php'; ?>

            <main id="content" class="flex-1 p-6">
        <?php
        
        $viewToInclude = $content ?? '';
        if ($viewToInclude && file_exists($viewToInclude)) {
            include $viewToInclude;
        } else {
            
            include __DIR__ . '/../pages/errors/notFound.php';
        }
        ?>
    </main>

    <?php 
        define('BASEPATH', true);
        include __DIR__ . '/../components/ai-helper/chat-modal.php'; 
        ?>
        </div>
    </div>

    <script>
    // Toggle mobile sidebar visibility (button placed in header)
    (function(){
        var btn = document.getElementById('mobileSidebarToggle');
        var sidebar = document.getElementById('sidebar');
        if (!btn || !sidebar) return;
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('hidden');
        });
    })();
    </script>
    </body>
    </html>
    ?>
        <script src="js/notifications.js"></script>
</body>
</html>