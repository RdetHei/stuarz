<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// compute base URL for preview src
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');
?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-2xl bg-gray-800 rounded p-0 overflow-hidden">
        <?php if ($flash): ?>
            <div class="p-4 text-sm text-yellow-300"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- TOP: Banner + Avatar (like profile) -->
        <div class="relative w-full border-b border-gray-700 cursor-pointer" style="height:clamp(140px,18vw,220px);">
            <img id="bannerPreview" src="<?= htmlspecialchars($prefix . 'assets/default-banner.png', ENT_QUOTES, 'UTF-8') ?>" alt="Banner preview" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/35"></div>
            <div class="absolute left-6 bottom-4 flex items-end gap-4">
                <div id="avatarDropNew" class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-gray-800 shadow-lg bg-gray-700 cursor-pointer">
                    <img id="avatarPreview" src="<?= htmlspecialchars($prefix . 'assets/default-avatar.png', ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" alt="Avatar preview">
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold leading-tight">Pengguna Baru</h2>
                    <div class="text-indigo-300 mt-1">user</div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Buat Akun Baru</h3>

            <form action="index.php?page=store_user" method="post" enctype="multipart/form-data" class="space-y-4">
                <!-- hidden file inputs remain so upload works -->
                <input name="avatar" id="avatarInputNew" type="file" accept="image/*" class="hidden">
                <input name="banner" id="bannerInputNew" type="file" accept="image/*" class="hidden">

                <div>
                    <label class="block text-sm">Username</label>
                    <input name="username" class="w-full mt-1 p-2 rounded bg-gray-700" required>
                </div>
                <div>
                    <label class="block text-sm">Email</label>
                    <input name="email" type="email" class="w-full mt-1 p-2 rounded bg-gray-700" required>
                </div>
                <div>
                    <label class="block text-sm">Password</label>
                    <input name="password" type="password" class="w-full mt-1 p-2 rounded bg-gray-700" required>
                </div>
                <div>
                    <label class="block text-sm">Level</label>
                    <select name="level" class="w-full mt-1 p-2 rounded bg-gray-700">
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded">Simpan</button>
                    <a href="index.php?page=account" class="px-4 py-2 bg-gray-600 rounded">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    function readPreview(file, previewEl){
        if (!file) return;
        previewEl.src = URL.createObjectURL(file);
    }

    // Avatar (create) top
    const avatarInputNew = document.getElementById('avatarInputNew');
    const avatarDropNew = document.getElementById('avatarDropNew');
    const avatarPreviewNew = document.getElementById('avatarPreview');

    avatarDropNew.addEventListener('click', () => avatarInputNew.click());
    avatarInputNew.addEventListener('change', function(){ readPreview(this.files[0], avatarPreviewNew); });

    ['dragenter','dragover'].forEach(ev => avatarDropNew.addEventListener(ev, e => { e.preventDefault(); avatarDropNew.classList.add('ring-2','ring-indigo-500'); }));
    ['dragleave','drop'].forEach(ev => avatarDropNew.addEventListener(ev, e => { e.preventDefault(); avatarDropNew.classList.remove('ring-2','ring-indigo-500'); }));
    avatarDropNew.addEventListener('drop', function(e){
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) { avatarInputNew.files = e.dataTransfer.files; readPreview(f, avatarPreviewNew); }
    });

    // Banner (create) top
    const bannerInputNew = document.getElementById('bannerInputNew');
    const bannerPreviewNew = document.getElementById('bannerPreview');
    const bannerContainerNew = bannerPreviewNew.parentElement;

    bannerContainerNew.addEventListener('click', () => bannerInputNew.click());
    bannerInputNew.addEventListener('change', function(){ readPreview(this.files[0], bannerPreviewNew); });

    ['dragenter','dragover'].forEach(ev => bannerContainerNew.addEventListener(ev, e => { e.preventDefault(); bannerContainerNew.classList.add('ring-2','ring-indigo-500'); }));
    ['dragleave','drop'].forEach(ev => bannerContainerNew.addEventListener(ev, e => { e.preventDefault(); bannerContainerNew.classList.remove('ring-2','ring-indigo-500'); }));
    bannerContainerNew.addEventListener('drop', function(e){
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) { bannerInputNew.files = e.dataTransfer.files; readPreview(f, bannerPreviewNew); }
    });
})();
</script>