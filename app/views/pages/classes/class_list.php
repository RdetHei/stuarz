<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold">Daftar Kelas</h1>
            <a href="index.php?page=class_create" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Tambah Kelas</a>
        </div>
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <div class="overflow-hidden rounded-xl border border-gray-700">
            <table class="min-w-full">
                <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-left">Dibuat Oleh</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#111827] divide-y divide-gray-800">
                    <?php if (!empty($classes)): ?>
                        <?php foreach ($classes as $c): ?>
                            <tr class="hover:bg-[#1f2937] transition">
                                <td class="px-6 py-3 text-gray-100 font-medium"><?= htmlspecialchars($c['name']) ?></td>
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($c['code']) ?></td>
                                <td class="px-6 py-3 text-gray-400 text-sm" style="max-width:320px;"> <?= htmlspecialchars($c['description']) ?> </td>
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($c['creator']) ?></td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="index.php?page=class_edit&id=<?= (int)$c['id'] ?>" class="px-3 py-1 bg-blue-600/80 text-white text-xs rounded-md hover:bg-blue-500 border border-blue-500/30">Edit</a>
                                        <form method="post" action="index.php?page=class_delete" style="display:inline" onsubmit="return confirm('Hapus kelas ini?')">
                                            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
                                            <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-xs rounded-md hover:bg-red-500 border border-red-500/30">Hapus</button>
                                        </form>
                                        <a href="index.php?page=class_members&id=<?= (int)$c['id'] ?>" class="px-3 py-1 bg-green-600/80 text-white text-xs rounded-md hover:bg-green-500 border border-green-500/30">Anggota</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-400">Belum ada kelas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
