
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

// Check for admin access on admin-only pages
$current_page = $_GET['page'] ?? '';
if (strpos($current_page, 'attendance_manage') === 0) {
    if (!isset($_SESSION['level']) || $_SESSION['level'] !== 'admin') {
        header("Location: index.php?page=attendance");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
        <style>
        @media print {
            #sidebar, .sidebar, .hamburger, nav, .ai-helper, .chat-modal, #sidebarToggle, #sidebarLogoToggle, .menu-text { display: none !important; }
            #dHeader, .d-header, header { display: none !important; }
            #content, main { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            body, #content, article, .prose, .card { background: #fff !important; color: #000 !important; }
            a:after { content: " (" attr(href) ")"; font-size: 90%; }
        }
        </style>
    
</head>
<body class="bg-gray-900">
    
<?php include __DIR__ . '/../components/sidebar.php'; ?>
    <?php include __DIR__ . '/../components/dHeader.php'; ?>
    

    <main id="content" class="p-6">
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
        <script src="js/notifications.js"></script>
</body>
</html>