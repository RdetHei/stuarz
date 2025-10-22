<div class="min-h-screen bg-[#2f3136] py-10 px-4">
  <div class="max-w-4xl mx-auto">

    <!-- Flash Message -->
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="mb-6 px-4 py-3 rounded-lg text-white shadow
        <?php if (($_SESSION['flash_level'] ?? 'info') === 'success'): ?> bg-green-600 
        <?php elseif (($_SESSION['flash_level'] ?? 'info') === 'danger'): ?> bg-red-600 
        <?php elseif (($_SESSION['flash_level'] ?? 'info') === 'warning'): ?> bg-yellow-500 text-black 
        <?php else: ?> bg-blue-600 <?php endif; ?>">
        <?= htmlspecialchars($_SESSION['flash']) ?>
      </div>
      <?php unset($_SESSION['flash'], $_SESSION['flash_level']); ?>
    <?php endif; ?>

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl font-bold text-white">📢 Daftar Pengumuman</h2>
      <a href="index.php?page=create_announcement" 
         class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-md font-medium shadow hover:bg-indigo-700 transition">
         + Buat Pengumuman
      </a>
    </div>

    <!-- List -->
    <?php if (empty($announcements)): ?>
      <p class="text-gray-400 italic">Belum ada pengumuman.</p>
    <?php else: ?>
      <div class="space-y-6">
        <?php foreach ($announcements as $a): ?>
          <div class="p-5 bg-[#36393f] rounded-lg shadow border border-gray-700">
            <!-- Title -->
            <h4 class="text-lg font-semibold text-white mb-1">
              <?= htmlspecialchars($a['title']) ?>
            </h4>

            <!-- Meta -->
            <small class="block text-sm text-gray-400 mb-3">
              Oleh <span class="font-medium text-gray-200"><?= htmlspecialchars($a['username'] ?? 'Anonim') ?></span>
              • <?= date('d M Y H:i', strtotime($a['created_at'])) ?>
            </small>

            <!-- Content -->
            <p class="text-gray-300 mb-3 leading-relaxed">
              <?= nl2br(htmlspecialchars($a['content'])) ?>
            </p>

            <!-- Photo -->
            <?php if (!empty($a['photo'])): ?>
              <div class="mb-3">
                <img src="<?= $a['photo'] ?>" alt="Foto Pengumuman" 
                     class="max-w-xs rounded-md border border-gray-600 shadow">
              </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="flex space-x-4 text-sm">
              <a href="index.php?page=edit_announcement&id=<?= $a['id'] ?>" 
                 class="text-indigo-400 hover:text-indigo-300 font-medium">✏️ Edit</a>
              <a href="index.php?page=delete_announcement&id=<?= $a['id'] ?>" 
                 onclick="return confirm('Yakin hapus?')" 
                 class="text-red-400 hover:text-red-300 font-medium">🗑️ Hapus</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
