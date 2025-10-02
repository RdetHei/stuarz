<div class="min-h-screen bg-[#2f3136] flex items-center justify-center py-10 px-4">
  <div class="w-full max-w-lg bg-[#36393f] rounded-xl shadow-lg p-6">
    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
      📢 <?= isset($announcement) ? 'Edit Pengumuman' : 'Buat Pengumuman Baru' ?>
    </h2>

    <form method="POST" 
          action="index.php?page=<?= isset($announcement) ? 'update_announcement' : 'upload_announcement' ?>" 
          enctype="multipart/form-data"
          class="space-y-5">

      <?php if (isset($announcement)): ?>
        <input type="hidden" name="id" value="<?= $announcement['id'] ?>">
      <?php endif; ?>

      <!-- Judul -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Judul</label>
        <input type="text" name="title" 
               value="<?= htmlspecialchars($announcement['title'] ?? '') ?>" 
               required
               class="w-full px-4 py-2 rounded-md bg-[#202225] text-white placeholder-gray-400 
                      focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      </div>

      <!-- Isi -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Isi</label>
        <textarea name="content" rows="4" required
                  class="w-full px-4 py-2 rounded-md bg-[#202225] text-white placeholder-gray-400 
                         focus:ring-2 focus:ring-indigo-500 focus:outline-none"><?= htmlspecialchars($announcement['content'] ?? '') ?></textarea>
      </div>

      <!-- Kelas -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Kelas</label>
        <input type="number" name="class_id" 
               value="<?= htmlspecialchars($announcement['class_id'] ?? '') ?>" 
               class="w-full px-4 py-2 rounded-md bg-[#202225] text-white placeholder-gray-400 
                      focus:ring-2 focus:ring-indigo-500 focus:outline-none">
      </div>

      <!-- Foto -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-1">Foto (opsional)</label>
        <input type="file" name="photo" accept="image/*"
               class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4
                      file:rounded-md file:border-0
                      file:text-sm file:font-semibold
                      file:bg-indigo-600 file:text-white
                      hover:file:bg-indigo-700
                      cursor-pointer">

        <?php if (!empty($announcement['photo'])): ?>
          <div class="mt-3">
            <img src="<?= $announcement['photo'] ?>" alt="Foto Pengumuman" class="max-h-32 rounded-md border border-gray-700">
          </div>
        <?php endif; ?>
      </div>

      <!-- Tombol -->
      <div class="flex justify-end gap-3 pt-4">
        <a href="index.php?page=announcements"
           class="px-4 py-2 rounded-md bg-gray-600 text-white hover:bg-gray-700 transition">
           Batal
        </a>
        <button type="submit"
                class="px-5 py-2 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
          <?= isset($announcement) ? 'Update' : 'Simpan' ?>
        </button>
      </div>
    </form>
  </div>
</div>
