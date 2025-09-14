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
    <div class="max-w-2xl bg-gray-800 rounded p-0 overflow-hidden">
        <?php if ($flash): ?>
            <div class="p-4 text-sm text-yellow-300"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- TOP: Banner + Avatar (like profile) -->
        <div class="relative w-full border-b border-gray-700 cursor-pointer" style="height:clamp(140px,18vw,220px);">
            <img id="bannerPreview" src="<?= htmlspecialchars($prefix . ltrim($user['banner'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="Banner saat ini" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/35"></div>
            <div class="absolute left-6 bottom-4 flex items-end gap-4">
                <div id="avatarDrop" class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-gray-800 shadow-lg bg-gray-700 cursor-pointer">
                    <img id="avatarPreview" src="<?= htmlspecialchars($prefix . ltrim($user['avatar'], '/'), ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" alt="Avatar">
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold leading-tight"><?= htmlspecialchars($user['username'] ?? $user['name'] ?? '') ?></h2>
                    <div class="text-indigo-300 mt-1"><?= htmlspecialchars($user['level'] ?? '') ?></div>
                    <p class="mt-2 text-gray-100/90 hidden sm:block"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-lg font-bold mb-4">Edit Akun — <?= htmlspecialchars($user['username']) ?></h3>

            <form action="index.php?page=update_user" method="post" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                <!-- hidden file inputs remain so upload works -->
                <input name="avatar" id="avatarInput" type="file" accept="image/*" class="hidden">
                <input name="banner" id="bannerInput" type="file" accept="image/*" class="hidden">

                <div>
                    <label class="block text-sm">Username</label>
                    <input name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="w-full mt-1 p-2 rounded bg-gray-700" required>
                </div>

                <div>
                    <label class="block text-sm">Email</label>
                    <input name="email" type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="w-full mt-1 p-2 rounded bg-gray-700" required>
                </div>

                <div>
                    <label class="block text-sm">Level</label>
                    <select name="level" class="w-full mt-1 p-2 rounded bg-gray-700">
                        <option value="user" <?= (isset($user['level']) && $user['level']==='user') ? 'selected' : '' ?>>user</option>
                        <option value="admin" <?= (isset($user['level']) && $user['level']==='admin') ? 'selected' : '' ?>>admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm">Password (kosong = tidak diubah)</label>
                    <input name="password" type="password" class="w-full mt-1 p-2 rounded bg-gray-700">
                </div>

                <!-- removed inline avatar/banner controls — they are now on top -->
                <!-- other fields remain -->
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded">Update</button>
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

    // Avatar drop/click (top avatar)
    const avatarInput = document.getElementById('avatarInput');
    const avatarDrop = document.getElementById('avatarDrop');
    const avatarPreview = document.getElementById('avatarPreview');

    avatarDrop.addEventListener('click', () => avatarInput.click());
    avatarInput.addEventListener('change', function(){ readPreview(this.files[0], avatarPreview); });

    ['dragenter','dragover'].forEach(ev => avatarDrop.addEventListener(ev, e => { e.preventDefault(); avatarDrop.classList.add('ring-2','ring-indigo-500'); }));
    ['dragleave','drop'].forEach(ev => avatarDrop.addEventListener(ev, e => { e.preventDefault(); avatarDrop.classList.remove('ring-2','ring-indigo-500'); }));
    avatarDrop.addEventListener('drop', function(e){
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) { avatarInput.files = e.dataTransfer.files; readPreview(f, avatarPreview); }
    });

    // Banner drop/click (top banner image)
    const bannerInput = document.getElementById('bannerInput');
    const bannerPreview = document.getElementById('bannerPreview');
    const bannerContainer = bannerPreview.parentElement; // the container image sits in

    bannerContainer.addEventListener('click', () => bannerInput.click());
    bannerInput.addEventListener('change', function(){ readPreview(this.files[0], bannerPreview); });

    ['dragenter','dragover'].forEach(ev => bannerContainer.addEventListener(ev, e => { e.preventDefault(); bannerContainer.classList.add('ring-2','ring-indigo-500'); }));
    ['dragleave','drop'].forEach(ev => bannerContainer.addEventListener(ev, e => { e.preventDefault(); bannerContainer.classList.remove('ring-2','ring-indigo-500'); }));
    bannerContainer.addEventListener('drop', function(e){
        const f = e.dataTransfer.files && e.dataTransfer.files[0];
        if (f) { bannerInput.files = e.dataTransfer.files; readPreview(f, bannerPreview); }
    });
})();
</script>