<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// compute base URL for preview src
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');
?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Flash Message -->
        <?php if ($flash): ?>
            <div class="mb-6 p-4 bg-yellow-900/50 border border-yellow-700 rounded-lg text-yellow-200 text-sm">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <?= htmlspecialchars($flash) ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Banner Preview Section -->
            <div class="relative w-full h-48 sm:h-56 lg:h-72 group cursor-pointer" id="bannerContainer">
                <img id="bannerPreview" 
                     src="<?= htmlspecialchars($prefix . 'assets/default-banner.png', ENT_QUOTES, 'UTF-8') ?>" 
                     alt="Banner" 
                     class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/60"></div>
                
                <!-- Upload Overlay -->
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                    <div class="text-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium">Klik atau drag & drop untuk mengganti banner</p>
                        <p class="text-sm text-gray-300 mt-1">Maksimal 5MB • JPG, PNG, GIF</p>
                    </div>
                </div>

                <!-- Avatar positioned at bottom -->
                <div class="absolute bottom-0 left-0 right-0 px-6 pb-6 flex items-end justify-between">
                    <div class="flex items-end gap-4">
                        <div id="avatarContainer" class="relative group/avatar cursor-pointer">
                            <div class="p-1.5 rounded-full bg-gray-800">
                                <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full overflow-hidden bg-gray-700 ring-[6px] ring-gray-800">
                                    <img id="avatarPreview" 
                                         src="<?= htmlspecialchars($prefix . 'assets/default-avatar.png', ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="Avatar" 
                                         class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <!-- Avatar Upload Overlay -->
                            <div class="absolute inset-0 bg-black/60 rounded-full opacity-0 group-hover/avatar:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="hidden sm:block text-white pb-2">
                            <h1 class="text-2xl lg:text-3xl font-bold">Pengguna Baru</h1>
                            <p class="text-gray-300 mt-1">user</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="p-6 bg-gray-800">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-white">Buat Akun Baru</h2>
                    <p class="text-gray-400 text-sm mt-1">Isi informasi untuk membuat akun pengguna baru</p>
                </div>

                <form action="index.php?page=store_user" method="post" enctype="multipart/form-data" class="space-y-6">
                    <input name="avatar" id="avatarInput" type="file" accept="image/*" class="hidden">
                    <input name="banner" id="bannerInput" type="file" accept="image/*" class="hidden">

                    <!-- Username & Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Username <span class="text-red-400">*</span>
                            </label>
                            <input 
                                name="username" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200" 
                                placeholder="username123"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                            <input 
                                name="name" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                                placeholder="John Doe">
                        </div>
                    </div>

                    <!-- Email & Phone -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Email <span class="text-red-400">*</span>
                            </label>
                            <input 
                                name="email" 
                                type="email" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                                placeholder="email@example.com"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">No Telepon</label>
                            <input 
                                name="phone" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                                placeholder="+62 812 3456 7890">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Alamat</label>
                        <input 
                            name="address" 
                            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                            placeholder="Jl. Contoh No. 123">
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Bio</label>
                        <textarea 
                            name="bio" 
                            rows="3" 
                            class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none resize-none transition-colors duration-200"
                            placeholder="Ceritakan tentang diri Anda..."></textarea>
                    </div>

                    <div class="border-t border-gray-700 pt-6"></div>

                    <!-- Password & Level -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Password <span class="text-red-400">*</span>
                            </label>
                            <input 
                                name="password" 
                                type="password" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white placeholder-gray-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200"
                                placeholder="Masukkan password"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Level</label>
                            <select 
                                name="level" 
                                class="w-full px-3 py-2 bg-gray-900/50 border border-gray-700 rounded-md text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors duration-200">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="guru">Guru</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Buat Akun
                        </button>
                        <a 
                            href="index.php?page=account" 
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
</div>

<script>
(function() {
    function readPreview(file, previewEl) {
        if (!file) return;
        
        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 5MB');
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('File harus berupa gambar!');
            return;
        }
        
        previewEl.src = URL.createObjectURL(file);
    }

    // Avatar upload
    const avatarInput = document.getElementById('avatarInput');
    const avatarContainer = document.getElementById('avatarContainer');
    const avatarPreview = document.getElementById('avatarPreview');

    avatarContainer.addEventListener('click', () => avatarInput.click());
    avatarInput.addEventListener('change', function() {
        readPreview(this.files[0], avatarPreview);
    });

    ['dragenter', 'dragover'].forEach(ev => {
        avatarContainer.addEventListener(ev, e => {
            e.preventDefault();
            avatarContainer.classList.add('ring-2', 'ring-indigo-500');
        });
    });
    
    ['dragleave', 'drop'].forEach(ev => {
        avatarContainer.addEventListener(ev, e => {
            e.preventDefault();
            avatarContainer.classList.remove('ring-2', 'ring-indigo-500');
        });
    });
    
    avatarContainer.addEventListener('drop', function(e) {
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) {
            avatarInput.files = e.dataTransfer.files;
            readPreview(f, avatarPreview);
        }
    });

    // Banner upload
    const bannerInput = document.getElementById('bannerInput');
    const bannerContainer = document.getElementById('bannerContainer');
    const bannerPreview = document.getElementById('bannerPreview');

    bannerContainer.addEventListener('click', () => bannerInput.click());
    bannerInput.addEventListener('change', function() {
        readPreview(this.files[0], bannerPreview);
    });

    ['dragenter', 'dragover'].forEach(ev => {
        bannerContainer.addEventListener(ev, e => {
            e.preventDefault();
            bannerContainer.classList.add('ring-2', 'ring-indigo-500', 'ring-inset');
        });
    });
    
    ['dragleave', 'drop'].forEach(ev => {
        bannerContainer.addEventListener(ev, e => {
            e.preventDefault();
            bannerContainer.classList.remove('ring-2', 'ring-indigo-500', 'ring-inset');
        });
    });
    
    bannerContainer.addEventListener('drop', function(e) {
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) {
            bannerInput.files = e.dataTransfer.files;
            readPreview(f, bannerPreview);
        }
    });
})();
</script>