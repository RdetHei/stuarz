<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
<link rel="stylesheet" href="/css/navbar.css">

<script src="/js/navbar.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
     <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
    
</head>
<body>
    <?php include __DIR__ . '/../components/header.php'; ?>

    <main>
        <?php include $content; ?>
    </main>

    <?php include __DIR__ . '/../components/footer.php'; ?>
    
    <div id="toast-container" class="fixed top-4 right-4 z-[100000] flex flex-col gap-3 pointer-events-none"></div>
    
    <script src="js/toast.js"></script>
    
    <script>
    (function() {
        <?php if (!empty($_SESSION['flash'])): ?>
            const flashMessage = <?= json_encode($_SESSION['flash'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const flashType = <?= json_encode($_SESSION['flash_type'] ?? 'info', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
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
    
</body>
</html>