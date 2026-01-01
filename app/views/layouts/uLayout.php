<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');
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
        <?php
            $student_mode = true;
            include __DIR__ . '/../components/sidebar.php';
        ?>

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

        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 z-[100000] flex flex-col gap-3 pointer-events-none"></div>

    <?php include __DIR__ . '/../components/modals/confirm_delete_modal.php'; ?>

    <script>
    (function(){
        var btn = document.getElementById('mobileSidebarToggle');
        var sidebar = document.getElementById('sidebar');
        if (!btn || !sidebar) return;
        btn.addEventListener('click', function(){
            sidebar.classList.toggle('hidden');
        });
    })();
    </script>

    <script src="js/toast.js"></script>
    
    <script>
    (function() {
        <?php if (!empty($_SESSION['flash'])): ?>
            const flashMessage = <?= json_encode($_SESSION['flash'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const flashType = <?= json_encode($_SESSION['flash_type'] ?? 'success', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            if (window.showToast) {
                window.showToast(flashMessage, flashType, 5000);
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.showToast) {
                        window.showToast(flashMessage, flashType, 5000);
                    }
                });
            }
            <?php unset($_SESSION['flash'], $_SESSION['flash_type']); ?>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['error'])): ?>
            const errorMessage = <?= json_encode($_SESSION['error'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            if (window.showToast) {
                window.showToast(errorMessage, 'error', 5000);
            } else {
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.showToast) {
                        window.showToast(errorMessage, 'error', 5000);
                    }
                });
            }
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    })();
    </script>
    
    <script src="js/notifications.js"></script>
</body>
</html>