<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold">Daftar Pengumuman</h1>
            <a href="index.php?page=announcement_create" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Tambah Pengumuman</a>
        </div>
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <div class="overflow-hidden rounded-xl border border-gray-700">
            <table class="min-w-full">
                <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Judul</th>
                        <th class="px-6 py-3 text-left">Oleh</th>
                        <th class="px-6 py-3 text-left">Tanggal</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#111827] divide-y divide-gray-800">
                    <?php if (!empty($announcements)): ?>
                        <?php foreach ($announcements as $a): ?>
                            <tr class="hover:bg-[#1f2937] transition">
                                <td class="px-6 py-3 text-gray-100 font-medium"><?= htmlspecialchars($a['title']) ?></td>
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($a['creator']) ?></td>
                                <td class="px-6 py-3 text-gray-400 text-sm"> <?= htmlspecialchars($a['created_at']) ?> </td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="index.php?page=announcement_show&id=<?= (int)$a['id'] ?>" class="px-3 py-1 bg-blue-600/80 text-white text-xs rounded-md hover:bg-blue-500 border border-blue-500/30">Detail</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-400">Belum ada pengumuman.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($announcement)): ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($announcement['title']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
            <p class="text-muted">
                Dibuat oleh: <?= htmlspecialchars($announcement['creator'] ?? 'Unknown') ?><br>
                Tanggal: <?= date('d/m/Y H:i', strtotime($announcement['created_at'])) ?>
            </p>
        </div>
    </div>
<?php endif; ?>
