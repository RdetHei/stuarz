<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-2xl mx-auto">
        <div class="px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold mb-2"><?= htmlspecialchars($announcement['title'] ?? '') ?></h1>
            <div class="mb-2 text-gray-400 text-sm">Oleh: <?= htmlspecialchars($announcement['creator'] ?? '-') ?> | <?= htmlspecialchars($announcement['created_at'] ?? '') ?></div>
            <?php if (!empty($announcement['photo'])): ?>
                <img src="<?= htmlspecialchars($announcement['photo']) ?>" alt="photo" class="mb-4 rounded w-full max-h-64 object-cover">
            <?php endif; ?>
            <div class="mb-4 text-gray-200"><?= nl2br(htmlspecialchars($announcement['content'] ?? '')) ?></div>
        </div>
        <div class="px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h2 class="text-lg font-bold mb-2">Komentar</h2>
            <?php if (!empty($_SESSION['flash'])): ?>
                <div class="px-4 py-2 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            <form method="post" action="index.php?page=announcement_add_comment" class="mb-4">
                <input type="hidden" name="announcement_id" value="<?= (int)($announcement['id'] ?? 0) ?>">
                <?php if (function_exists('csrf_field')) csrf_field(); ?>
                <textarea name="content" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700 text-gray-100 mb-2" rows="2" required></textarea>
                <button type="submit" class="px-3 py-2 bg-green-600 rounded text-sm hover:bg-green-500 text-white">Tambah Komentar</button>
            </form>
            <div>
                <?php if (!empty($comments)): ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="mb-3 p-3 bg-gray-900 rounded border border-gray-800">
                            <div class="text-sm text-gray-300 font-semibold mb-1"><?= htmlspecialchars($c['username']) ?> <span class="text-xs text-gray-500">#<?= (int)$c['id'] ?></span></div>
                            <div class="text-gray-200 text-sm mb-1"><?= nl2br(htmlspecialchars($c['content'])) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($c['created_at']) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-400">Belum ada komentar.</div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-4">
            <a href="index.php?page=announcement" class="px-3 py-2 bg-gray-700 rounded text-sm hover:bg-gray-600">Kembali ke daftar</a>
        </div>
    </div>
</div>
