<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
            <h1 class="text-xl font-bold">Anggota Kelas: <?= htmlspecialchars($class['name'] ?? '') ?></h1>
            <a href="index.php?page=class" class="px-3 py-2 bg-gray-700 rounded text-sm hover:bg-gray-600">Kembali</a>
        </div>
        <?php if (!empty($_SESSION['flash'])): ?>
            <div class="px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg mb-4 text-sm text-gray-200"><?= htmlspecialchars($_SESSION['flash']) ?></div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        <div class="mb-6">
            <form method="post" action="index.php?page=class_add_member" class="flex gap-2 items-end">
                <input type="hidden" name="class_id" value="<?= (int)($class['id'] ?? 0) ?>">
                <div>
                    <label class="block mb-1">User ID</label>
                    <input type="number" name="user_id" class="px-3 py-2 rounded bg-gray-900 border border-gray-700 text-gray-100" required>
                </div>
                <div>
                    <label class="block mb-1">Role</label>
                    <input type="text" name="role" class="px-3 py-2 rounded bg-gray-900 border border-gray-700 text-gray-100" value="member" required>
                </div>
                <button type="submit" class="px-3 py-2 bg-green-600 rounded text-sm hover:bg-green-500 text-white">Tambah Anggota</button>
            </form>
        </div>
        <div class="overflow-hidden rounded-xl border border-gray-700">
            <table class="min-w-full">
                <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="px-6 py-3 text-left">User ID</th>
                        <th class="px-6 py-3 text-left">Username</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Role</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-[#111827] divide-y divide-gray-800">
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $m): ?>
                            <tr class="hover:bg-[#1f2937] transition">
                                <td class="px-6 py-3 text-gray-300"><?= (int)$m['user_id'] ?></td>
                                <td class="px-6 py-3 text-gray-100 font-medium"><?= htmlspecialchars($m['username']) ?></td>
                                <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($m['email']) ?></td>
                                <td class="px-6 py-3 text-gray-400 text-sm">
                                    <?php if (!empty($canManage) && !empty($_SESSION['user']) && intval($_SESSION['user']['id'] ?? 0) !== intval($m['user_id'])): ?>
                                        <form method="post" action="index.php?page=class_update_role" class="inline-block">
                                            <input type="hidden" name="class_id" value="<?= (int)$class['id'] ?>">
                                            <input type="hidden" name="user_id" value="<?= (int)$m['user_id'] ?>">
                                            <select name="new_role" class="px-2 py-1 rounded bg-gray-900 border border-gray-700 text-gray-100 text-sm">
                                                <option value="user" <?= strtolower($m['role'] ?? '') === 'user' ? 'selected' : '' ?>>Student</option>
                                                <option value="guru" <?= in_array(strtolower($m['role'] ?? ''), ['guru','teacher']) ? 'selected' : '' ?>>Teacher</option>
                                            </select>
                                            <button type="submit" class="px-2 py-1 ml-2 bg-blue-600 rounded text-xs text-white hover:bg-blue-500">Ubah</button>
                                        </form>
                                    <?php else: ?>
                                        <?= htmlspecialchars($m['role']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-3">
                                    <form method="post" action="index.php?page=class_remove_member" style="display:inline" onsubmit="return confirm('Hapus anggota ini?')">
                                        <input type="hidden" name="class_id" value="<?= (int)$class['id'] ?>">
                                        <input type="hidden" name="user_id" value="<?= (int)$m['user_id'] ?>">
                                        <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-xs rounded-md hover:bg-red-500 border border-red-500/30">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-400">Belum ada anggota.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
