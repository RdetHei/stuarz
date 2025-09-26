
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stuarz</title>>

  <!-- Favicon PNG -->
  <link rel="icon" type="image/png" sizes="32x32" href="assets/diamond.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/diamond.png">

  <!-- Fallback ICO -->
  <link rel="shortcut icon" href="assets/diamond.ico">
</head>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <!-- Optional: additional UI kit if needed -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
</head>
<body class="bg-gray-900">

    <?php include 'landing/layout/dHeader.php'; ?>
    <?php include 'landing/layout/sidebar.php'; ?>

    <main id="content" class="ml-64 transition-all duration-300 p-6">
        <?php include $content; ?>
    </main>

</body>
</html>