<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold">Kelola Dokumentasi</h1>
            <a href="index.php?page=dashboard-admin-docs-create" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Tambah</a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-700">
            <table class="min-w-full">
                <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Section</th>
                        <th class="px-6 py-3 text-left">Title</th>
                        <th class="px-6 py-3 text-left">Slug</th>
                        <th class="px-6 py-3 text-left">Deskripsi</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#111827] divide-y divide-gray-800">
                    <?php if (!empty($docs)): ?>
                        <?php foreach ($docs as $d): ?>
                            <tr class="hover:bg-[#1f2937] transition">
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($d['section']) ?></td>
                                <td class="px-6 py-3 text-gray-100 font-medium"><?= htmlspecialchars($d['title']) ?></td>
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($d['slug']) ?></td>
                                <td class="px-6 py-3 text-gray-400 text-sm line-clamp-1" style="max-width:320px;"><?= htmlspecialchars($d['description']) ?></td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="index.php?page=dashboard-admin-docs-edit&id=<?= (int)$d['id'] ?>" class="px-3 py-1 bg-blue-600/80 text-white text-xs rounded-md hover:bg-blue-500 border border-blue-500/30">Edit</a>
                                        <form method="post" action="index.php?page=dashboard-admin-docs-delete" style="display:inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                            <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                                            <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-xs rounded-md hover:bg-red-500 border border-red-500/30">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-400">Belum ada dokumentasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
