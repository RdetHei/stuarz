<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php $isEdit = isset($announcement) && $announcement; ?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white"><?= $isEdit ? 'Edit Pengumuman' : 'Tambah Pengumuman' ?></h1>
                <p class="text-gray-400 text-sm mt-1">
                    <?= $isEdit ? 'Perbarui pengumuman yang ada' : 'Buat pengumuman baru untuk kelas' ?>
                </p>
            </div>
            <a href="index.php?page=announcement" 
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="mb-6 p-4 bg-yellow-900/50 border border-yellow-700 rounded-lg text-yellow-200 text-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?= htmlspecialchars($_SESSION['flash']) ?>
                </div>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Form -->
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            <form method="post" 
                action="index.php?page=<?= $isEdit ? 'announcement_update' : 'announcement_store' ?>" 
                enctype="multipart/form-data"
                class="p-6 space-y-6">

                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= (int)$announcement['id'] ?>">
                    <input type="hidden" name="photo_existing" value="<?= htmlspecialchars($announcement['photo'] ?? '') ?>">
                <?php endif; ?>

                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Judul Pengumuman <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        required
                        value="<?= htmlspecialchars($announcement['title'] ?? '') ?>"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                        placeholder="Masukkan judul pengumuman...">
                </div>

                <!-- Isi Pengumuman -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Isi Pengumuman <span class="text-red-400">*</span>
                    </label>
                    <textarea 
                        name="content" 
                        rows="6" 
                        required
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200"
                        placeholder="Tulis isi pengumuman di sini..."><?= htmlspecialchars($announcement['content'] ?? '') ?></textarea>
                </div>

                <!-- Foto Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Foto</label>
                    
                    <!-- Preview Area -->
                    <div id="photoPreviewContainer" class="mb-3 <?= ($isEdit && !empty($announcement['photo'])) ? '' : 'hidden' ?>">
                        <div class="relative w-full h-64 bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden group">
                            <img 
                                id="photoPreview" 
                                src="<?= ($isEdit && !empty($announcement['photo'])) ? htmlspecialchars($announcement['photo']) : '' ?>" 
                                alt="Preview" 
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <button 
                                    type="button" 
                                    id="removePhoto"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <input 
                            type="file" 
                            name="photo" 
                            id="photoInput"
                            accept="image/*"
                            class="block w-full text-sm text-gray-300 
                                   file:mr-4 file:py-2 file:px-4 
                                   file:rounded-md file:border-0 
                                   file:text-sm file:font-medium
                                   file:bg-indigo-600 file:text-white
                                   hover:file:bg-indigo-700
                                   file:transition-colors file:duration-200
                                   cursor-pointer
                                   bg-gray-900/50 border border-gray-700 rounded-md
                                   focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                    </div>
                    
                    <p class="text-xs text-gray-500 mt-2">Upload gambar untuk pengumuman (Format: JPG, PNG • Max: 5MB)</p>
                </div>

                <div class="border-t border-gray-700 pt-6"></div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-200 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <?= $isEdit ? 'Update Pengumuman' : 'Publikasikan Pengumuman' ?>
                    </button>
                    
                    <a 
                        href="index.php?page=announcement" 
                        class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-md transition-colors duration-200 text-center flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
  const photoInput = document.getElementById('photoInput');
  const photoPreview = document.getElementById('photoPreview');
  const photoPreviewContainer = document.getElementById('photoPreviewContainer');
  const removeButton = document.getElementById('removePhoto');

  if (!photoInput || !photoPreview || !photoPreviewContainer) {
    return;
  }

  // Handle file input change
  photoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (file) {
      // Validate file size (5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB');
        photoInput.value = '';
        return;
      }
      
      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar!');
        photoInput.value = '';
        return;
      }
      
      // Show preview
      const reader = new FileReader();
      reader.onload = function(event) {
        photoPreview.src = event.target.result;
        photoPreviewContainer.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }
  });

  // Handle remove button
  if (removeButton) {
    removeButton.addEventListener('click', function() {
      photoInput.value = '';
      photoPreview.src = '';
      photoPreviewContainer.classList.add('hidden');
    });
  }
})();
</script>