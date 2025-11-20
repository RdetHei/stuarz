
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$newsToDelete = $newsToDelete ?? null;
if (!$newsToDelete) {
    $_SESSION['flash'] = 'Berita tidak ditemukan.';
    header('Location: index.php?page=news');
    exit;
}
?>

<div class="max-w-3xl mx-auto px-6 py-8">
    <div class="bg-[#0f172a] border border-gray-700 rounded-lg p-6">
        <h2 class="text-xl font-semibold text-red-200">Konfirmasi Hapus Berita</h2>
        <p class="text-sm text-gray-300 mt-2">Anda akan menghapus berita berikut. Tindakan ini tidak dapat dikembalikan.</p>

        <div class="mt-4 p-4 bg-red-900/10 border border-red-700 rounded">
            <div class="text-sm text-red-100 font-semibold"><?= htmlspecialchars($newsToDelete['title'] ?? '') ?></div>
            <div class="text-xs text-red-200 mt-1"><?= htmlspecialchars(mb_substr(strip_tags($newsToDelete['content'] ?? ''), 0, 200)) ?></div>
        </div>

        <form method="post" action="index.php?page=news_delete" class="mt-5 flex items-center gap-3">
            <input type="hidden" name="id" value="<?= htmlspecialchars($newsToDelete['id']) ?>">
            <input type="hidden" name="confirm" value="1">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Ya, hapus berita</button>
            <a href="index.php?page=news" class="px-4 py-2 border border-gray-600 rounded text-gray-200">Batal</a>
        </form>
    </div>
</div>
