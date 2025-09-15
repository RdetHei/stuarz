<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// $user harus disediakan oleh controller. Jangan menimpa $user dengan session di sini.
if (!isset($user) || !is_array($user)) {
    $_SESSION['flash'] = "Pengguna tidak ditemukan.";
    header("Location: index.php?page=account");
    exit;
}

// compute base URL for preview src
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');

$user['avatar'] = $user['avatar'] ?? 'assets/default-avatar.png';
$user['banner'] = $user['banner'] ?? 'assets/default-banner.png';
?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-2xl bg-gray-800/80 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
        <?php if ($flash): ?>
            <div class="p-4 text-sm text-yellow-300"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- TOP: Banner + Avatar (like profile) -->
        <div class="relative w-full border-b border-gray-700 cursor-pointer" style="height:clamp(140px,18vw,220px);">
            <img id="bannerPreview" src="<?= htmlspecialchars($prefix . ltrim($user['banner'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="Banner saat ini" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute left-6 bottom-4 right-6 flex items-end justify-between gap-4">
                <div class="flex items-end gap-4">
                    <div id="avatarDrop" class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-gray-900 shadow-xl bg-gray-700 cursor-pointer">
                        <img id="avatarPreview" src="<?= htmlspecialchars($prefix . ltrim($user['avatar'], '/'), ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" alt="Avatar">
                    </div>
                    <div class="text-white">
                        <h2 class="text-2xl font-bold leading-tight"><?= htmlspecialchars($user['username'] ?? $user['name'] ?? '') ?></h2>
                        <div class="text-indigo-300 mt-1"><?= htmlspecialchars($user['level'] ?? '') ?></div>
                        <p class="mt-2 text-gray-100/90 hidden sm:block"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-2 text-xs text-gray-300 bg-black/30 px-3 py-1.5 rounded-full border border-white/10">
                    <span class="material-symbols-outlined text-base">info</span>
                    Klik banner/avatar untuk mengganti
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Edit Akun — <?= htmlspecialchars($user['username']) ?></h3>
            </div>

            <form action="index.php?page=update_user" method="post" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                <!-- hidden file inputs remain so upload works -->
                <input name="avatar" id="avatarInput" type="file" accept="image/*" class="hidden">
                <input name="banner" id="bannerInput" type="file" accept="image/*" class="hidden">

                <div class="rounded-xl border border-gray-700">
                    <div class="px-4 py-3 border-b border-gray-700 text-sm font-medium text-gray-200">Akun</div>
                    <div class="p-4 grid grid-cols-1 gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Username</label>
                            <input name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Email</label>
                            <input name="email" type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-700">
                    <div class="px-4 py-3 border-b border-gray-700 text-sm font-medium text-gray-200">Keamanan</div>
                    <div class="p-4 grid grid-cols-1 gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Password (kosong = tidak diubah)</label>
                            <input name="password" type="password" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <span class="text-[11px] text-gray-400 mt-1">Biarkan kosong jika tidak ingin mengubah.</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-700">
                    <div class="px-4 py-3 border-b border-gray-700 text-sm font-medium text-gray-200">Peran</div>
                    <div class="p-4 grid grid-cols-1 gap-4">
                        <div class="flex flex-col">
                            <label class="text-xs uppercase tracking-wide text-gray-400">Level</label>
                            <select name="level" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="user" <?= (isset($user['level']) && $user['level'] === 'user') ? 'selected' : '' ?>>user</option>
                                <option value="admin" <?= (isset($user['level']) && $user['level'] === 'admin') ? 'selected' : '' ?>>admin</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 rounded-lg border border-indigo-500/40 text-white shadow">Update</button>
                    <a href="index.php?page=account" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg border border-gray-600 text-gray-100">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        function readPreview(file, previewEl) {
            if (!file) return;
            previewEl.src = URL.createObjectURL(file);
        }

        // Avatar drop/click (top avatar)
        const avatarInput = document.getElementById('avatarInput');
        const avatarDrop = document.getElementById('avatarDrop');
        const avatarPreview = document.getElementById('avatarPreview');

        avatarDrop.addEventListener('click', () => avatarInput.click());
        avatarInput.addEventListener('change', function() {
            readPreview(this.files[0], avatarPreview);
        });

        ['dragenter', 'dragover'].forEach(ev => avatarDrop.addEventListener(ev, e => {
            e.preventDefault();
            avatarDrop.classList.add('ring-2', 'ring-indigo-500');
        }));
        ['dragleave', 'drop'].forEach(ev => avatarDrop.addEventListener(ev, e => {
            e.preventDefault();
            avatarDrop.classList.remove('ring-2', 'ring-indigo-500');
        }));
        avatarDrop.addEventListener('drop', function(e) {
            const f = e.dataTransfer.files && e.dataTransfer.files[0];
            if (f) {
                avatarInput.files = e.dataTransfer.files;
                readPreview(f, avatarPreview);
            }
        });

        // Banner drop/click (top banner image)
        const bannerInput = document.getElementById('bannerInput');
        const bannerPreview = document.getElementById('bannerPreview');
        const bannerContainer = bannerPreview.parentElement; // the container image sits in

        bannerContainer.addEventListener('click', () => bannerInput.click());
        bannerInput.addEventListener('change', function() {
            readPreview(this.files[0], bannerPreview);
        });

        ['dragenter', 'dragover'].forEach(ev => bannerContainer.addEventListener(ev, e => {
            e.preventDefault();
            bannerContainer.classList.add('ring-2', 'ring-indigo-500');
        }));
        ['dragleave', 'drop'].forEach(ev => bannerContainer.addEventListener(ev, e => {
            e.preventDefault();
            bannerContainer.classList.remove('ring-2', 'ring-indigo-500');
        }));
        bannerContainer.addEventListener('drop', function(e) {
            const f = e.dataTransfer.files && e.dataTransfer.files[0];
            if (f) {
                bannerInput.files = e.dataTransfer.files;
                readPreview(f, bannerPreview);
            }
        });

        // No dropdowns; stacked form only
    })();
</script>