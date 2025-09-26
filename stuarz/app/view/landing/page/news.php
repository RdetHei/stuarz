<?php if (!isset($allNews)) { $allNews = []; $cats = []; $q = ''; $cat = ''; $page = 1; $totalPages = 1; } ?>
<?php
// Compute base URL similar to other pages
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>

<div class="bg-gray-900 text-white min-h-screen">
  <!-- Header -->
  <div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-white">News</h1>
    </div>

  <!-- Search -->
  <section class="px-6 pb-6 pl-0">
    <form method="GET" action="index.php" class="max-w-3xl">
      <input type="hidden" name="page" value="news">
      <div class="relative flex items-stretch">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
          <span class="material-symbols-outlined">Search</span>
        </div>
        <input type="text" name="q" placeholder="Search news...." value="<?= htmlspecialchars($q) ?>" class="flex-1 pl-11 pr-4 py-2 rounded-l-xl bg-gray-800/70 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <select name="cat" class="mx-2 px-3 py-2 rounded-1-xl bg-gray-800/70 border border-gray-700 text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="">Semua Kategori</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $cat === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="px-5 py-2 rounded-r-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold border border-gray-700">Cari</button>
      </div>
    </form>
  </section>

  <!-- News Grid -->
  <section class="px-6 pb-12 pl-0 grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php if (!empty($allNews)): ?>
      <?php $first = $allNews[0]; ?>
      <a href="index.php?page=news_show&id=<?= (int)$first['id'] ?>" class="md:col-span-2 relative rounded-xl overflow-hidden shadow-lg bg-[#313338] border border-gray-800 block group">
        <?php if (!empty($first['thumbnail'])): ?>
          <img src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($first['thumbnail'], '/')) ?>" alt="Thumbnail" class="w-full h-75 object-cover opacity-90">
        <?php endif; ?>
        <div class="absolute inset-x-0 bottom-0 p-6 bg-gradient-to-t from-black/60 to-transparent">
          <span class="px-3 py-1 border border-gray-700 bg-gray-800/60 rounded text-sm"><?= htmlspecialchars($first['category']) ?></span>
          <h2 class="text-2xl font-bold mt-2"><?= htmlspecialchars($first['title']) ?></h2>
          <p class="text-gray-300 mt-1 line-clamp-2"><?= htmlspecialchars(mb_substr(strip_tags($first['content']), 0, 160)) ?>...</p>
          <div class="text-sm text-gray-400 mt-2">👤 <?= htmlspecialchars($first['author']) ?> • <?= htmlspecialchars(date('d M Y', strtotime($first['created_at']))) ?></div>
        </div>
      </a>

      <?php foreach (array_slice($allNews, 1) as $n): ?>
        <a href="index.php?page=news_show&id=<?= (int)$n['id'] ?>" class="rounded-xl overflow-hidden shadow-lg bg-[#313338] border border-gray-800 block hover:bg-[#35373b]">
          <?php if (!empty($n['thumbnail'])): ?>
            <img src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($n['thumbnail'], '/')) ?>" alt="Thumbnail" class="w-full h-40 object-cover">
          <?php endif; ?>
          <div class="p-4 ">
            <span class="px-3 py-1 border border-gray-700 bg-gray-800/60 rounded text-sm"><?= htmlspecialchars($n['category']) ?></span>
            <h3 class="font-bold mt-2 text-white"><?= htmlspecialchars($n['title']) ?></h3>
            <p class="text-gray-300 text-sm mt-1 line-clamp-3"><?= htmlspecialchars(mb_substr(strip_tags($n['content']), 0, 120)) ?>...</p>
            <div class="text-sm text-gray-400 mt-2">👤 <?= htmlspecialchars($n['author']) ?> • <?= htmlspecialchars(date('d M Y', strtotime($n['created_at']))) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="md:col-span-3 text-center text-gray-400 py-16">Belum ada berita.</div>
    <?php endif; ?>
  </section>

  <?php if ($totalPages > 1): ?>
  <div class="px-6 pb-10">
    <div class="inline-flex items-center gap-2 bg-[#1e1f22] border border-gray-800 rounded-xl p-2">
      <?php $prev = max(1, $page - 1); $next = min($totalPages, $page + 1); ?>
      <a href="index.php?page=news&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&p=<?= $prev ?>" class="px-3 py-1 rounded bg-[#2b2d31] text-gray-200 hover:bg-[#32343a]">Prev</a>
      <span class="px-2 text-gray-400">Hal <?= $page ?> / <?= $totalPages ?></span>
      <a href="index.php?page=news&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&p=<?= $next ?>" class="px-3 py-1 rounded bg-[#2b2d31] text-gray-200 hover:bg-[#32343a]">Next</a>
    </div>
  </div>
  <?php endif; ?>
</div>
