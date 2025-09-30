<?php if (!isset($newsItem)) { die('News not found'); } ?>
<div class="bg-gray-900 text-white min-h-screen">
    <header>
<div class="px-6 py-4 border border-gray-700 rounded-2xl bg-gray-800/60">
    <a href="index.php?page=news" class="text-indigo-400 hover:text-indigo-300">← Kembali ke berita</a>
    <h1 class="mt-3 text-3xl font-bold text-white"><?= htmlspecialchars($newsItem['title']) ?></h1>
    <div class="mt-2 text-sm text-gray-400">Kategori: <?= htmlspecialchars($newsItem['category']) ?> • Oleh <?= htmlspecialchars($newsItem['author']) ?> • <?= htmlspecialchars(date('d M Y', strtotime($newsItem['created_at']))) ?></div>
  </header>


  <main class="px-6 py-8 max-w-4xl mx-auto">
  <article class="bg-gray-800/60 border border-gray-700 rounded-2xl p-8 ring-1 ring-white/5">
    <?php if (!empty($newsItem['thumbnail'])): ?>
      <?php if (!isset($baseUrl)) { $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); if ($baseUrl === '/') $baseUrl = ''; } ?>
      <img class="mb-3" src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($newsItem['thumbnail'], '/')) ?>" alt="Thumbnail" class="w-full h-auto rounded-xl border border-gray-800">
    <?php endif; ?>
   
      <?= nl2br(htmlspecialchars($newsItem['content'])) ?>
    </article>
  </main>
</div>


