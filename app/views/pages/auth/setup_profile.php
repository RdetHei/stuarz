<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Profile - Stuarz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="public/assets/diamond.ico">
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-2xl w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600 flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Setup Profile</h1>
            <p class="text-gray-400">Lengkapi informasi profile Anda untuk melanjutkan</p>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['setup_errors'])): ?>
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <?php foreach ($_SESSION['setup_errors'] as $error): ?>
                            <div><?= htmlspecialchars($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['setup_errors']); ?>
        <?php endif; ?>

        <!-- Setup Form -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-8">
            <form method="post" action="index.php?page=setup-profile/store" enctype="multipart/form-data" class="space-y-6" id="setupForm">
                
                <!-- Personal Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white mb-4">Informasi Pribadi</h3>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               required 
                               placeholder="Masukkan nama lengkap Anda"
                               class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               id="phone"
                               name="phone" 
                               required 
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500" />
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-300 mb-2">
                            Alamat <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" 
                                  id="address"
                                  rows="3"
                                  required
                                  placeholder="Masukkan alamat lengkap Anda"
                                  class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all resize-none placeholder:text-gray-500"></textarea>
                    </div>

                    <!-- Class -->
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-300 mb-2">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="class" 
                                id="class" 
                                required 
                                class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                            <option value="">Pilih Kelas</option>
                            <option value="X-PPLG">X-PPLG</option>
                            <option value="XI-PPLG">XI-PPLG</option>
                            <option value="XII-PPLG">XII-PPLG</option>
                            <option value="X-TKJ">X-TKJ</option>
                            <option value="XI-TKJ">XI-TKJ</option>
                            <option value="XII-TKJ">XII-TKJ</option>
                            <option value="X-MM">X-MM</option>
                            <option value="XI-MM">XI-MM</option>
                            <option value="XII-MM">XII-MM</option>
                            <option value="X-TKRO">X-TKRO</option>
                            <option value="XI-TKRO">XI-TKRO</option>
                            <option value="XII-TKRO">XII-TKRO</option>
                        </select>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-300 mb-2">
                            Bio (Opsional)
                        </label>
                        <textarea name="bio" 
                                  id="bio"
                                  rows="3"
                                  placeholder="Ceritakan sedikit tentang diri Anda..."
                                  class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all resize-none placeholder:text-gray-500"></textarea>
                    </div>
                </div>

                <!-- Profile Images -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-white mb-4">Foto Profile</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Avatar -->
                        <div>
                            <label for="avatar" class="block text-sm font-medium text-gray-300 mb-2">
                                Avatar (Opsional)
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 flex items-center justify-center px-4 py-3 bg-gray-900 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-indigo-600 transition-all">
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-sm">Choose Avatar</span>
                                    </div>
                                    <input type="file" 
                                           id="avatar"
                                           name="avatar" 
                                           class="hidden" 
                                           accept="image/*" />
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF (Max 5MB)</p>
                        </div>

                        <!-- Banner -->
                        <div>
                            <label for="banner" class="block text-sm font-medium text-gray-300 mb-2">
                                Banner (Opsional)
                            </label>
                            <div class="flex items-center gap-4">
                                <label class="flex-1 flex items-center justify-center px-4 py-3 bg-gray-900 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-indigo-600 transition-all">
                                    <div class="flex items-center gap-2 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm">Choose Banner</span>
                                    </div>
                                    <input type="file" 
                                           id="banner"
                                           name="banner" 
                                           class="hidden" 
                                           accept="image/*" />
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG, GIF (Max 5MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-700">
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Selesaikan Setup Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-lg mb-3">Mengapa Setup Profile Penting?</h3>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-2 text-sm">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                            <span class="text-gray-400">Memudahkan identifikasi dan komunikasi dengan guru/teman</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                            <span class="text-gray-400">Membantu guru dalam memberikan tugas yang sesuai dengan kelas Anda</span>
                        </li>
                        <li class="flex items-start gap-2 text-sm">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                            <span class="text-gray-400">Meningkatkan pengalaman belajar yang lebih personal</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('setupForm');
        const avatarInput = document.getElementById('avatar');
        const bannerInput = document.getElementById('banner');
        
        // File input preview
        function setupFilePreview(input, label) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    
                    // Check file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('File terlalu besar. Maksimal 5MB.');
                        input.value = '';
                        return;
                    }
                    
                    label.innerHTML = `
                        <div class="flex items-center gap-2 text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm">${fileName} (${fileSize}MB)</span>
                        </div>
                    `;
                }
            });
        }
        
        if (avatarInput) {
            setupFilePreview(avatarInput, avatarInput.closest('label'));
        }
        
        if (bannerInput) {
            setupFilePreview(bannerInput, bannerInput.closest('label'));
        }
        
        // Form validation
        if (form) {
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const address = document.getElementById('address').value.trim();
                const classSelect = document.getElementById('class').value;
                
                let errors = [];
                
                if (!name) {
                    errors.push('Nama lengkap wajib diisi');
                }
                
                if (!phone) {
                    errors.push('Nomor telepon wajib diisi');
                } else if (!/^[0-9+\-\s()]+$/.test(phone)) {
                    errors.push('Format nomor telepon tidak valid');
                }
                
                if (!address) {
                    errors.push('Alamat wajib diisi');
                }
                
                if (!classSelect) {
                    errors.push('Kelas wajib dipilih');
                }
                
                if (errors.length > 0) {
                    e.preventDefault();
                    alert('Error:\n' + errors.join('\n'));
                    return false;
                }
            });
        }
    });
    </script>
</body>
</html>

