<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold">Daftar Kelas</h1>
            <a href="index.php?page=class_create" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Tambah Kelas</a>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <!-- Total Classes -->
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-indigo-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Total Kelas</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['classes'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-emerald-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Total Siswa</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['students'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Active Classes -->
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-yellow-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Kelas Aktif</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['activeClasses'] ?></p>
                    </div>
                </div>
            </div>

            <!-- Average Students -->
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-xl">
                <div class="flex items-center gap-4">
                    <div class="p-2 bg-rose-500/10 rounded-lg">
                        <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400">Rata-rata Siswa</p>
                        <p class="text-2xl font-semibold text-white"><?= $stats['averageStudents'] ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <div class="overflow-hidden rounded-xl border border-gray-700">
            <table class="min-w-full">
                <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">Kelas</th>
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
