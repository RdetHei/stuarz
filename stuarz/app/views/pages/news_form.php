<?php $isEdit = isset($newsItem) && $newsItem; ?>
<div class="p-6 space-y-6 bg-gray-900 min-h-screen text-gray-100">
  <div class="flex items-center justify-between">
    <h1 class="text-2xl font-bold tracking-tight"><?= $isEdit ? 'Edit Berita' : 'Tambah Berita' ?></h1>
    <a href="index.php?page=dashboard-admin-news" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">Kembali</a>
  </div>

  <form action="index.php?page=<?= $isEdit ? 'dashboard-admin-news-update' : 'dashboard-admin-news-store' ?>" method="POST" enctype="multipart/form-data" class="bg-gray-800/60 border border-gray-700 rounded-xl p-6 grid grid-cols-1 gap-4">
    <?php if ($isEdit): ?>
      <input type="hidden" name="id" value="<?= (int)$newsItem['id'] ?>">
      <input type="hidden" name="thumbnail_existing" value="<?= htmlspecialchars($newsItem['thumbnail']) ?>">
    <?php endif; ?>
    <div>
      <label class="block text-sm mb-1">Judul</label>
      <input type="text" name="title" required value="<?= htmlspecialchars($newsItem['title'] ?? '') ?>" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm mb-1">Kategori</label>
        <input type="text" name="category" value="<?= htmlspecialchars($newsItem['category'] ?? '') ?>" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-indigo-500">
      </div>
      <div>
        <label class="block text-sm mb-1">Author</label>
        <input type="text" name="author" value="<?= htmlspecialchars($newsItem['author'] ?? ($_SESSION['user']['username'] ?? 'Admin')) ?>" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-indigo-500">
      </div>
      <div>
        <label class="block text-sm mb-1">Tanggal</label>
        <input type="datetime-local" name="created_at" value="<?= isset($newsItem['created_at']) ? date('Y-m-d\TH:i', strtotime($newsItem['created_at'])) : date('Y-m-d\TH:i') ?>" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-indigo-500">
      </div>
    </div>
    <div>
      <label class="block text-sm mb-1">Thumbnail</label>
      <input type="file" name="thumbnail" accept="image/*" class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-600/30 file:text-indigo-200 hover:file:bg-indigo-600/50">
      <?php if ($isEdit && !empty($newsItem['thumbnail'])): ?>
        <div class="mt-2 text-sm text-gray-400">Saat ini: <a class="underline" target="_blank" href="<?= htmlspecialchars(($baseUrl ?? '') . '/' . ltrim($newsItem['thumbnail'], '/')) ?>">lihat gambar</a></div>
      <?php endif; ?>
    </div>
    <div>
      <label class="block text-sm mb-1">Konten</label>
      <textarea name="content" rows="10" class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-indigo-500"><?= htmlspecialchars($newsItem['content'] ?? '') ?></textarea>
    </div>
    <div class="pt-2">
      <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-500 rounded-lg">Simpan</button>
    </div>
  </form>
</div>



