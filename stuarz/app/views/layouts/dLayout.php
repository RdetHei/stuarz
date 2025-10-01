
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <title>Stuarz</title>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">
</head>
<body class="bg-gray-900">

    <?php include __DIR__ . '/../components/dHeader.php'; ?>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <main id="content" class="ml-64 transition-all duration-300 p-6">
        <?php include $content; ?>
    </main>

</body>
</html>