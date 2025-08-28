<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title>Document</title>
</head>
<body>
    <?php include 'landing/page/header.php'; ?>

    <main>
        <?php include $content; ?>
    </main>

    <?php include 'landing/page/footer.php'; ?>
</body>
</html>