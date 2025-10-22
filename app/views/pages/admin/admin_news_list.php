<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
  <div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
      <h1 class="text-xl font-bold">Kelola Berita</h1>
      <a href="index.php?page=dashboard-admin-news-create" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Tambah</a>
    </div>

    <?php
    // compute base URL for asset resolution (same logic as other views)
    if (!isset($baseUrl)) {
      $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
      if ($baseUrl === '/') $baseUrl = '';
    }

    // normalize thumbnail URLs for each news item so the view can always use *_src fields
    if (!empty($news) && is_array($news)) {
      foreach ($news as $idx => $item) {
        $thumb = !empty($item['thumbnail']) ? $item['thumbnail'] : '';
        if ($thumb && (strpos($thumb, 'http://') === 0 || strpos($thumb, 'https://') === 0)) {
          $news[$idx]['thumbnail_src'] = $thumb;
        } elseif ($thumb) {
          $news[$idx]['thumbnail_src'] = ($baseUrl ? $baseUrl . '/' : '') . ltrim($thumb, '/');
        } else {
          $news[$idx]['thumbnail_src'] = '';
        }
      }
    }
    ?>

    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="overflow-hidden rounded-xl border border-gray-700">
      <table class="min-w-full">
        <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
          <tr>
            <th class="px-6 py-3 text-left">Judul</th>
            <th class="px-6 py-3 text-left">Kategori</th>
            <th class="px-6 py-3 text-left">Author</th>
            <th class="px-6 py-3 text-left">Dibuat</th>
            <th class="px-6 py-3 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-[#111827] divide-y divide-gray-800">
          <?php if (!empty($news)): ?>
            <?php foreach ($news as $n): ?>
              <tr class="hover:bg-[#1f2937] transition">
                <td class="px-6 py-3 text-gray-100 font-medium">
                  <div class="flex items-center gap-3">
                    <?php if (!empty($n['thumbnail_src'])): ?>
                      <img
                        src="<?= htmlspecialchars($n['thumbnail_src']) ?>"
                        class="w-10 h-10 rounded object-cover"
                        alt="thumb"
                        loading="lazy"
                        decoding="async"
                        width="40" height="40"
                        onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
                    <?php endif; ?>
                    <span><?= htmlspecialchars($n['title']) ?></span>
                  </div>
                </td>
                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($n['category'] ?? '') ?></td>
                <td class="px-6 py-3 text-white">
                  <div class="flex flex-col">
                    <span class="text-sm"><?= htmlspecialchars($n['author'] ?? '') ?></span>
                    <?php if (!empty($n['author_role'])): ?>
                      <span class="text-xs text-gray-400"><?= htmlspecialchars($n['author_role']) ?></span>
                    <?php endif; ?>
                  </div>
                </td>
                <td class="px-6 py-3 text-gray-400"><?= htmlspecialchars($n['created_at']) ?></td>
                <td class="px-6 py-3">
                  <div class="flex justify-end gap-2">
                    <a href="index.php?page=dashboard-admin-news-edit&id=<?= (int)$n['id'] ?>" class="px-3 py-1 bg-blue-600/80 text-white text-xs rounded-md hover:bg-blue-500 border border-blue-500/30">Edit</a>
          <form method="post" action="index.php?page=dashboard-admin-news-delete" style="display:inline" onsubmit="return confirm('Hapus berita ini?')">
            <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
            <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-xs rounded-md hover:bg-red-500 border border-red-500/30">Hapus</button>
          </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="px-6 py-6 text-center text-gray-400">Belum ada berita</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
