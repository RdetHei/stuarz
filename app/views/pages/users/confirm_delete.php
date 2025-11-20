<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$userToDelete = $userToDelete ?? null;
if (!$userToDelete) {
    $_SESSION['flash'] = 'Pengguna tidak ditemukan.';
    header('Location: index.php?page=account');
    exit;
}
?>

<div class="max-w-2xl mx-auto px-6 py-8">
    <div class="bg-[#111827] border border-gray-700 rounded-lg p-6">
        <h2 class="text-xl font-semibold text-red-200">Konfirmasi Hapus Akun</h2>
        <p class="text-sm text-gray-300 mt-2">Anda akan menghapus akun berikut. Tindakan ini tidak dapat dikembalikan.</p>

        <div class="mt-4 p-4 bg-red-900/20 border border-red-700 rounded">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-700 rounded-full overflow-hidden">
                    <?php if (!empty($userToDelete['avatar'])): ?>
                        <img src="<?= htmlspecialchars($userToDelete['avatar']) ?>" alt="avatar" class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div>
                    <div class="text-sm text-red-100 font-semibold"><?= htmlspecialchars($userToDelete['username'] ?? $userToDelete['name'] ?? '') ?></div>
                    <div class="text-xs text-red-200"><?= htmlspecialchars($userToDelete['email'] ?? '') ?></div>
                </div>
            </div>
            <?php if (!empty($userToDelete['bio'])): ?>
                <div class="mt-3 text-sm text-red-100/80"><?= htmlspecialchars($userToDelete['bio']) ?></div>
            <?php endif; ?>
        </div>

        <form method="post" action="index.php?page=delete_user" class="mt-5 flex items-center gap-3">
            <input type="hidden" name="id" value="<?= htmlspecialchars($userToDelete['id']) ?>">
            <input type="hidden" name="confirm" value="1">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Ya, hapus akun</button>
            <a href="index.php?page=account" class="px-4 py-2 border border-gray-600 rounded text-gray-200">Batal</a>
        </form>
    </div>
</div>
