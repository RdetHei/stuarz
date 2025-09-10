<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stuarz</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>
<body>
    <?php include 'landing/layout/dHeader.php'; ?>
    <?php include 'landing/layout/sidebar.php'; ?>

    <main class="ml-64">
        <?php include $content; ?>
    </main>

 
</body>
</html>