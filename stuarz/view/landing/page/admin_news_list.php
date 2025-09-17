<div class="p-6 space-y-6 bg-gray-900 min-h-screen text-gray-100">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold tracking-tight">Kelola Berita</h1>
    <a href="index.php?page=dashboard-admin-news-create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">Tambah Berita</a>
  </div>

  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="bg-gray-800/60 border border-gray-700 rounded-lg p-4"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>

  <div class="bg-gray-800/60 border border-gray-700 rounded-xl overflow-hidden">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-800">
        <tr class="text-left text-gray-300">
          <th class="px-4 py-3">Judul</th>
          <th class="px-4 py-3">Kategori</th>
          <th class="px-4 py-3">Author</th>
          <th class="px-4 py-3">Dibuat</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php foreach (($news ?? []) as $n): ?>
          <tr>
            <td class="px-4 py-3 text-white">
              <div class="flex items-center gap-3">
                <?php if (!empty($n['thumbnail'])): ?>
                  <?php $thumbSrc = ($baseUrl ?? ''); if ($thumbSrc !== '') { $thumbSrc .= '/'; } $thumbSrc .= ltrim($n['thumbnail'], '/'); ?>
                  <img src="<?= htmlspecialchars($thumbSrc) ?>" class="w-10 h-10 rounded object-cover" alt="thumb">
                <?php endif; ?>
                <span><?= htmlspecialchars($n['title']) ?></span>
              </div>
            </td>
            <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars($n['category']) ?></td>
            <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars($n['author']) ?></td>
            <td class="px-4 py-3 text-gray-400"><?= htmlspecialchars($n['created_at']) ?></td>
            <td class="px-4 py-3 text-right">
              <a href="index.php?page=dashboard-admin-news-edit&id=<?= (int)$n['id'] ?>" class="px-3 py-1 rounded bg-gray-700 hover:bg-gray-600">Edit</a>
              <a href="index.php?page=dashboard-admin-news-delete&id=<?= (int)$n['id'] ?>" class="px-3 py-1 rounded bg-red-600/80 hover:bg-red-600" onclick="return confirm('Hapus berita ini?')">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($news)): ?>
          <tr>
            <td class="px-4 py-6 text-center text-gray-400" colspan="5">Belum ada berita</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


