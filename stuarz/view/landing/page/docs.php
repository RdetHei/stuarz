
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
  <meta charset="UTF-8">
  <title>Docs</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full flex">

  <!-- Sidebar -->
  <aside class="w-64 bg-white border-r h-screen p-4 overflow-y-auto">
    <h2 class="text-lg font-bold mb-4">Documentation</h2>
    <?php foreach ($docs as $section => $items): ?>
      <h3 class="text-sm font-semibold text-gray-500 mt-4"><?= $section ?></h3>
      <ul class="ml-2 mt-2 space-y-1">
        <?php foreach ($items as $doc): ?>
          <li>
            <a href="docs.php?doc=<?= $doc['slug'] ?>" 
               class="block px-2 py-1 rounded hover:bg-gray-100 text-gray-700">
              <?= $doc['title'] ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
  </aside>

  <!-- Content -->
  <main class="flex-1 p-8 overflow-y-auto">
    <?php if ($currentDoc): ?>
      <h1 class="text-2xl font-bold mb-4"><?= $currentDoc['title'] ?></h1>
      <div class="prose max-w-none">
        <?= $currentDoc['content'] ?>
      </div>
    <?php else: ?>
      <h1 class="text-2xl font-bold">Welcome to Documentation</h1>
      <p class="text-gray-600">Pilih salah satu menu di sidebar untuk membaca dokumentasi.</p>
    <?php endif; ?>
  </main>
</body>
</html>
