<?php $isEdit = isset($newsItem) && $newsItem; ?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
  <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-white"><?= $isEdit ? 'Edit Berita' : 'Tambah Berita Baru' ?></h1>
        <p class="text-gray-400 text-sm mt-1">
          <?= $isEdit ? 'Perbarui informasi berita' : 'Buat dan publikasikan berita baru' ?>
        </p>
      </div>
      <a href="index.php?page=dashboard-admin-news" 
         class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
      </a>
    </div>

    <!-- Form -->
    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
      <form action="index.php?page=<?= $isEdit ? 'dashboard-admin-news-update' : 'dashboard-admin-news-store' ?>" 
            method="POST" 
            enctype="multipart/form-data" 
            class="p-6 space-y-6">
        
        <?php if ($isEdit): ?>
          <input type="hidden" name="id" value="<?= (int)$newsItem['id'] ?>">
          <input type="hidden" name="thumbnail_existing" value="<?= htmlspecialchars($newsItem['thumbnail']) ?>">
        <?php endif; ?>

        <!-- Judul -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            Judul Berita <span class="text-red-400">*</span>
          </label>
          <input 
            type="text" 
            name="title" 
            required 
            value="<?= htmlspecialchars($newsItem['title'] ?? '') ?>" 
            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
            placeholder="Masukkan judul berita...">
        </div>

        <!-- Kategori, Author, Tanggal -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Kategori</label>
            <input 
              type="text" 
              name="category" 
              value="<?= htmlspecialchars($newsItem['category'] ?? '') ?>" 
              class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
              placeholder="Teknologi, Olahraga...">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Author</label>
            <input 
              type="text" 
              name="author" 
              value="<?= htmlspecialchars($newsItem['author'] ?? ($_SESSION['user']['username'] ?? 'Admin')) ?>" 
              class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
              placeholder="Nama penulis">
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Publikasi</label>
            <input 
              type="datetime-local" 
              name="created_at" 
              value="<?= isset($newsItem['created_at']) ? date('Y-m-d\TH:i', strtotime($newsItem['created_at'])) : date('Y-m-d\TH:i') ?>" 
              class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200">
          </div>
        </div>

        <!-- Thumbnail -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            Thumbnail
            <?php if (!$isEdit): ?>
              <span class="text-red-400">*</span>
            <?php endif; ?>
          </label>
          
          <!-- Preview Area -->
          <div id="thumbnailPreviewContainer" class="mb-3 <?= ($isEdit && !empty($newsItem['thumbnail'])) ? '' : 'hidden' ?>">
            <div class="relative w-full h-64 bg-gray-900/50 border border-gray-700 rounded-lg overflow-hidden group">
              <img 
                id="thumbnailPreview" 
                src="<?= ($isEdit && !empty($newsItem['thumbnail'])) ? htmlspecialchars(($baseUrl ?? '') . '/' . ltrim($newsItem['thumbnail'], '/')) : '' ?>" 
                alt="Preview" 
                class="w-full h-full object-cover"
                style="max-height:420px;object-fit:cover;"
                loading="lazy"
                decoding="async"
                onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
              <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                <button 
                  type="button" 
                  id="removeThumbnail"
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
              name="thumbnail" 
              id="thumbnailInput"
              accept="image/*"
              <?= !$isEdit ? 'required' : '' ?>
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
          
          <p class="text-xs text-gray-500 mt-2">Upload gambar thumbnail untuk berita (Format: JPG, PNG • Max: 5MB)</p>
        </div>

        <!-- Konten -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">
            Konten Berita <span class="text-red-400">*</span>
          </label>
          <textarea 
            name="content" 
            rows="12" 
            required
            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200"
            placeholder="Tulis konten berita di sini..."><?= htmlspecialchars($newsItem['content'] ?? '') ?></textarea>
          <p class="text-xs text-gray-500 mt-2">Anda dapat menggunakan format Markdown untuk styling teks</p>
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
            <?= $isEdit ? 'Update Berita' : 'Publikasikan Berita' ?>
          </button>
          
          <a 
            href="index.php?page=dashboard-admin-news" 
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
  const thumbnailInput = document.getElementById('thumbnailInput');
  const thumbnailPreview = document.getElementById('thumbnailPreview');
  const thumbnailPreviewContainer = document.getElementById('thumbnailPreviewContainer');
  const removeButton = document.getElementById('removeThumbnail');

  if (!thumbnailInput || !thumbnailPreview || !thumbnailPreviewContainer) {
    console.error('Preview elements not found');
    return;
  }

  // Handle file input change
  thumbnailInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (file) {
      // Validate file size (5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar! Maksimal 5MB');
        thumbnailInput.value = '';
        return;
      }
      
      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar!');
        thumbnailInput.value = '';
        return;
      }
      
      // Show preview
      const reader = new FileReader();
      reader.onload = function(event) {
        thumbnailPreview.src = event.target.result;
        thumbnailPreviewContainer.classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    }
  });

  // Handle remove button
  if (removeButton) {
    removeButton.addEventListener('click', function() {
      thumbnailInput.value = '';
      thumbnailPreview.src = '';
      thumbnailPreviewContainer.classList.add('hidden');
    });
  }
})();
</script>