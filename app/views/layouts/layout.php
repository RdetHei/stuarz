<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <!-- Di bagian <head> -->
<link rel="stylesheet" href="/css/navbar.css">

<!-- Di bagian akhir file, sebelum </body> -->
<script src="/js/navbar.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
     <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
        <!-- print styles removed from landing layout; admin print rules live in dLayout.php -->
    
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main>
        <?php include $content; ?>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <!-- Include AI Helper -->
    <?php 
    define('BASEPATH', true); // Security check for AI Helper
    include __DIR__ . '/../components/ai-helper/chat-modal.php'; 
    ?>
    
</body>
</html>

