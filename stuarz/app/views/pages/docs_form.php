<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php $isEdit = isset($doc) && $doc; ?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white"><?= $isEdit ? 'Edit Dokumentasi' : 'Tambah Dokumentasi Baru' ?></h1>
                <p class="text-gray-400 text-sm mt-1">
                    <?= $isEdit ? 'Perbarui dokumentasi yang ada' : 'Buat artikel dokumentasi baru' ?>
                </p>
            </div>
            <a href="index.php?page=dashboard-admin-docs" 
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-md transition-colors duration-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        <!-- Form -->
        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border border-gray-700">
            <form action="index.php?page=<?= $isEdit ? 'dashboard-admin-docs-update' : 'dashboard-admin-docs-store' ?>" 
                  method="post" 
                  class="p-6 space-y-6">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>">
                <?php endif; ?>

                <!-- Section & Title -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Section <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="text"
                            name="section" 
                            required
                            value="<?= htmlspecialchars($doc['section'] ?? '') ?>"
                            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                            placeholder="General, API, Tutorial">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            Title <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="text"
                            name="title" 
                            required
                            value="<?= htmlspecialchars($doc['title'] ?? '') ?>"
                            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                            placeholder="Getting Started">
                    </div>
                </div>

                <!-- Slug -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Slug</label>
                    <input 
                        type="text"
                        name="slug"
                        value="<?= htmlspecialchars($doc['slug'] ?? '') ?>"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                        placeholder="getting-started">
                    <p class="text-xs text-gray-500 mt-2">URL-friendly identifier. Akan dibuat otomatis jika dikosongkan</p>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea 
                        name="description" 
                        rows="3"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200"
                        placeholder="Deskripsi singkat tentang dokumentasi ini..."><?= htmlspecialchars($doc['description'] ?? '') ?></textarea>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Content <span class="text-red-400">*</span>
                    </label>
                    <textarea 
                        name="content" 
                        rows="16" 
                        required
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200 font-mono text-sm"
                        placeholder="Tulis konten dokumentasi di sini..."><?= htmlspecialchars($doc['content'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-2">Mendukung Markdown untuk formatting</p>
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
                        <?= $isEdit ? 'Update Dokumentasi' : 'Publikasikan Dokumentasi' ?>
                    </button>
                    
                    <a 
                        href="index.php?page=dashboard-admin-docs" 
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