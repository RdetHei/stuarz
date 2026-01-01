<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Profile - Stuarz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="public/assets/diamond.ico">
    <style>
        body {
            overflow: auto !important;
            padding-right: 0 !important;
        }
        .fixed.inset-0[style*="z-index"]:not(#setup-profile-backdrop),
        .fixed.inset-0[class*="z-"]:not(#setup-profile-backdrop) {
            display: none !important;
        }
        [id*="backdrop"]:not(#setup-profile-backdrop),
        [id*="Backdrop"]:not(#setup-profile-backdrop),
        [id*="modal"]:not(.setup-profile-modal),
        [id*="Modal"]:not(.setup-profile-modal) {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-3xl w-full">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-[#5865F2] flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-100 mb-2">Setup Profile</h1>
            <p class="text-gray-400">Lengkapi informasi profile Anda untuk melanjutkan</p>
        </div>

        
        <?php if (isset($_SESSION['setup_errors'])): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <?php foreach ($_SESSION['setup_errors'] as $error): ?>
                            <p class="text-sm text-red-400"><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['setup_errors']); ?>
        <?php endif; ?>

        
        <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
            <form method="post" action="index.php?page=setup-profile/store" enctype="multipart/form-data" id="setupForm">
                
                
                <div class="px-6 py-5 border-b border-gray-700 bg-[#111827]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-100">Informasi Pribadi</h3>
                            <p class="text-xs text-gray-400">Data diri dan kontak Anda</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">
                    
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" 
                               id="name"
                               name="name" 
                               required 
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors" />
                    </div>

                    
                    <div>
                        <label for="phone" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Nomor Telepon <span class="text-red-400">*</span>
                        </label>
                        <input type="tel" 
                               id="phone"
                               name="phone" 
                               required 
                               placeholder="081234567890"
                               class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors" />
                    </div>

                    
                    <div>
                        <label for="address" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Alamat <span class="text-red-400">*</span>
                        </label>
                        <textarea name="address" 
                                  id="address"
                                  rows="3"
                                  required
                                  placeholder="Masukkan alamat lengkap"
                                  class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors resize-none"></textarea>
                    </div>

                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="class" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Kode Kelas (Opsional)
                            </label>
                            <span class="text-[11px] text-gray-500">Bisa dilewati, isi jika sudah punya kode kelas.</span>
                        </div>
                        <input type="text" 
                               id="class"
                               name="class" 
                               placeholder="Contoh: ABC123"
                               maxlength="12"
                               class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors" />
                    </div>

                    
                    <div>
                        <label for="bio" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                            Bio
                        </label>
                        <textarea name="bio" 
                                  id="bio"
                                  rows="3"
                                  placeholder="Ceritakan sedikit tentang diri Anda..."
                                  class="w-full px-3 py-2.5 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors resize-none"></textarea>
                    </div>
                </div>

                
                <div class="px-6 py-5 border-t border-gray-700 bg-[#111827]">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center border border-amber-500/20">
                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-100">Foto Profile</h3>
                            <p class="text-xs text-gray-400">Avatar dan banner (opsional)</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        
                        <div>
                            <label for="avatar" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Avatar
                            </label>
                            <label for="avatar" class="flex flex-col items-center justify-center px-4 py-8 bg-[#111827] border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-[#5865F2] transition-colors group">
                                <svg class="w-10 h-10 text-gray-600 group-hover:text-[#5865F2] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-sm text-gray-400 group-hover:text-gray-300 mb-1">Choose Avatar</span>
                                <span class="text-xs text-gray-600">Max 5MB</span>
                                <input type="file" 
                                       id="avatar"
                                       name="avatar" 
                                       class="hidden" 
                                       accept="image/*" />
                            </label>
                        </div>

                        
                        <div>
                            <label for="banner" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Banner
                            </label>
                            <label for="banner" class="flex flex-col items-center justify-center px-4 py-8 bg-[#111827] border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-[#5865F2] transition-colors group">
                                <svg class="w-10 h-10 text-gray-600 group-hover:text-[#5865F2] mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm text-gray-400 group-hover:text-gray-300 mb-1">Choose Banner</span>
                                <span class="text-xs text-gray-600">Max 5MB</span>
                                <input type="file" 
                                       id="banner"
                                       name="banner" 
                                       class="hidden" 
                                       accept="image/*" />
                            </label>
                        </div>
                    </div>
                </div>

                
                <div class="px-6 py-5 border-t border-gray-700 bg-[#111827]">
                    <button type="submit" 
                            class="w-full px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md font-medium transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Selesaikan Setup Profile
                    </button>
                </div>
            </form>
        </div>

        
        <div class="mt-6 bg-[#1f2937] border border-gray-700 rounded-lg p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-[#5865F2]/10 rounded-lg flex items-center justify-center border border-[#5865F2]/20">
                    <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-gray-100 font-semibold text-base mb-3">Mengapa Setup Profile Penting?</h3>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-400">Memudahkan identifikasi dan komunikasi dengan guru/teman</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-sm">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-400">Membantu guru dalam memberikan tugas yang sesuai dengan kelas</span>
                        </li>
                        <li class="flex items-start gap-2.5 text-sm">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-gray-400">Meningkatkan pengalaman belajar yang lebih personal</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        function removeOverlays() {
            const selectors = [
                '[id*="backdrop"]:not(#setup-profile-backdrop)',
                '[id*="Backdrop"]:not(#setup-profile-backdrop)',
                '[id*="modal"]:not(.setup-profile-modal)',
                '[id*="Modal"]:not(.setup-profile-modal)',
                '.fixed.inset-0.bg-black',
                '.fixed.inset-0[class*="bg-black"]',
                '.fixed.inset-0[class*="backdrop"]'
            ];
            
            selectors.forEach(selector => {
                try {
                    document.querySelectorAll(selector).forEach(el => {
                        const zIndex = parseInt(window.getComputedStyle(el).zIndex) || 0;
                        if (zIndex >= 9999 || el.classList.contains('hidden')) {
                            el.remove();
                        }
                    });
                } catch(e) {}
            });
            
            const allFixed = document.querySelectorAll('.fixed.inset-0');
            allFixed.forEach(el => {
                const zIndex = parseInt(window.getComputedStyle(el).zIndex) || 0;
                if (zIndex >= 9999 && !el.closest('form')) {
                    el.remove();
                }
            });
        }
        
        removeOverlays();
        
        document.addEventListener('DOMContentLoaded', function() {
            removeOverlays();
            setTimeout(removeOverlays, 100);
            setTimeout(removeOverlays, 500);
        });
    })();
    
    document.addEventListener('DOMContentLoaded', function() {
        
        const form = document.getElementById('setupForm');
        const avatarInput = document.getElementById('avatar');
        const bannerInput = document.getElementById('banner');

        function setupFilePreview(input) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                const label = input.closest('label');
                
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);

                    if (file.size > 5 * 1024 * 1024) {
                        alert('File terlalu besar. Maksimal 5MB.');
                        input.value = '';
                        return;
                    }

                    const textSpan = label.querySelector('span.text-sm');
                    if (textSpan) {
                        textSpan.textContent = `${fileName} (${fileSize}MB)`;
                        textSpan.classList.add('text-emerald-400');
                    }
                }
            });
        }
        
        if (avatarInput) setupFilePreview(avatarInput);
        if (bannerInput) setupFilePreview(bannerInput);

        if (form) {
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const address = document.getElementById('address').value.trim();
                const classValue = document.getElementById('class').value.trim();
                
                let errors = [];
                
                if (!name) errors.push('Nama lengkap wajib diisi');
                if (!phone) {
                    errors.push('Nomor telepon wajib diisi');
                } else if (!/^[0-9+\-\s()]+$/.test(phone)) {
                    errors.push('Format nomor telepon tidak valid');
                }
                if (!address) errors.push('Alamat wajib diisi');
                if (classValue && !/^[A-Za-z0-9_-]{3,}$/.test(classValue)) {
                    errors.push('Kode kelas minimal 3 karakter dan hanya huruf/angka.');
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

