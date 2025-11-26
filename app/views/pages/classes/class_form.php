<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php $isEdit = isset($class); ?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-white"><?= $isEdit ? 'Edit Kelas' : 'Tambah Kelas Baru' ?></h1>
                <p class="text-gray-400 text-sm mt-1">
                    <?= $isEdit ? 'Perbarui informasi kelas' : 'Buat kelas baru untuk sistem' ?>
                </p>
            </div>
            <a href="index.php?page=class" 
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
            <form method="post" action="index.php?page=<?= $isEdit ? 'class_update' : 'class_store' ?>" class="p-6 space-y-6">
                <?php if (function_exists('csrf_field')) csrf_field(); ?>
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= (int)$class['id'] ?>">
                <?php endif; ?>

                <!-- Nama Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Kelas <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        required
                        value="<?= htmlspecialchars($class['name'] ?? '') ?>"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                        placeholder="Contoh: Kelas 10A, Pemrograman Web">
                </div>

                <!-- Kode Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Kode Kelas <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="code" 
                        required
                        value="<?= htmlspecialchars($class['code'] ?? '') ?>"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                        placeholder="Contoh: 10A, PW-01">
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
                    <textarea 
                        name="description" 
                        rows="4"
                        class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200"
                        placeholder="Deskripsi singkat tentang kelas ini..."><?= htmlspecialchars($class['description'] ?? '') ?></textarea>
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
                        <?= $isEdit ? 'Update Kelas' : 'Buat Kelas' ?>
                    </button>
                    
                    <a 
                        href="index.php?page=class" 
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