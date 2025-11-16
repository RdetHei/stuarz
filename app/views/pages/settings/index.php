<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$user = $_SESSION['user'] ?? null;
if (!$user) {
    header("Location: index.php?page=login");
    exit;
}

$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
?>
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - Stuarz</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $baseUrl ?>/assets/diamond.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $baseUrl ?>/assets/diamond.png">
</head>
<body class="bg-gray-900 dark:bg-gray-900 bg-gray-50 min-h-screen transition-colors duration-200">
    <!-- Header -->
    <header class="bg-[#1f2937] dark:bg-[#1f2937] bg-white border-b border-gray-700 dark:border-gray-700 border-gray-200 sticky top-0 z-40 transition-colors duration-200">
        <div class="max-w-5xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="index.php?page=dashboard-admin" 
                       class="p-2 text-gray-400 dark:text-gray-400 text-gray-600 hover:text-gray-200 dark:hover:text-gray-200 hover:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-800 hover:bg-gray-100 rounded-md transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div class="h-6 w-px bg-gray-700 dark:bg-gray-700 bg-gray-300"></div>
                    <h1 class="text-xl font-bold text-gray-100 dark:text-gray-100 text-gray-900">Pengaturan</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-6 py-8">
        <!-- Flash Messages -->
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-emerald-400"><?= htmlspecialchars($_SESSION['flash']) ?></p>
                </div>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-red-400"><?= htmlspecialchars($_SESSION['error']) ?></p>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Settings Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-2 sticky top-24">
                    <nav class="space-y-1">
                        <a href="#profile" 
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-200 bg-[#5865F2]/10 border border-[#5865F2]/20 rounded-md transition-colors">
                            <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil
                        </a>
                        <a href="#password" 
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-800 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Password
                        </a>
                        <a href="#preferences" 
                           class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-800 rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Preferensi
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Profile Settings -->
                <div id="profile" class="bg-[#1f2937] border border-gray-700 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-100">Profil</h2>
                            <p class="text-xs text-gray-400">Kelola informasi profil Anda</p>
                        </div>
                    </div>

                    <form method="post" action="index.php?page=settings" class="space-y-4">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nama</label>
                            <input type="text" 
                                   name="name" 
                                   value="<?= htmlspecialchars($user['name'] ?? '') ?>" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Username</label>
                            <input type="text" 
                                   name="username" 
                                   value="<?= htmlspecialchars($user['username'] ?? '') ?>" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-700">
                            <button type="submit" 
                                    class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white text-sm font-medium rounded-md transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Settings -->
                <div id="password" class="bg-[#1f2937] border border-gray-700 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-100">Password</h2>
                            <p class="text-xs text-gray-400">Ubah password akun Anda</p>
                        </div>
                    </div>

                    <form method="post" action="index.php?page=settings" class="space-y-4">
                        <input type="hidden" name="update_password" value="1">
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password Saat Ini</label>
                            <input type="password" 
                                   name="current_password" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" 
                                   name="new_password" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                            <input type="password" 
                                   name="confirm_password" 
                                   class="w-full px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors"
                                   required>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-700">
                            <button type="submit" 
                                    class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white text-sm font-medium rounded-md transition-colors">
                                Ubah Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Preferences Settings -->
                <div id="preferences" class="bg-[#1f2937] border border-gray-700 rounded-lg p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-100">Preferensi</h2>
                            <p class="text-xs text-gray-400">Pengaturan tampilan dan notifikasi</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-700">
                            <div>
                                <h3 class="text-sm font-medium text-gray-200">Notifikasi Email</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Terima notifikasi melalui email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#5865F2] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5865F2]"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between py-3 border-b border-gray-700">
                            <div>
                                <h3 class="text-sm font-medium text-gray-200">Push Notifications</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Terima notifikasi push browser</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#5865F2] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5865F2]"></div>
                            </label>
                        </div>

                        <!-- Dark mode preference removed (feature rolled back) -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include AI Helper -->
    <?php 
    if (!defined('BASEPATH')) {
        define('BASEPATH', true);
    }
    include __DIR__ . '/../../components/ai-helper/chat-modal.php'; 
    ?>

    <script>
        // Smooth scroll for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    
                    // Update active state
                    document.querySelectorAll('nav a').forEach(link => {
                        link.classList.remove('text-gray-200', 'bg-[#5865F2]/10', 'border', 'border-[#5865F2]/20');
                        link.classList.add('text-gray-400', 'hover:text-gray-200', 'hover:bg-gray-800');
                        link.querySelector('svg').classList.remove('text-[#5865F2]');
                    });
                    
                    this.classList.remove('text-gray-400', 'hover:text-gray-200', 'hover:bg-gray-800');
                    this.classList.add('text-gray-200', 'bg-[#5865F2]/10', 'border', 'border-[#5865F2]/20');
                    this.querySelector('svg').classList.add('text-[#5865F2]');
                }
            });
        });
    </script>
</body>
</html>