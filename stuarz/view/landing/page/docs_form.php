<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<div class="p-6 min-h-screen text-gray-100">
    <div class="max-w-3xl mx-auto bg-gray-800/80 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
            <h1 class="text-xl font-bold">Documentation Form</h1>
            <a href="index.php?page=dashboard-admin-docs" class="text-sm text-indigo-400 hover:text-indigo-300">Kembali</a>
        </div>
        <form action="index.php?page=<?= isset($doc) && $doc ? 'dashboard-admin-docs-update' : 'dashboard-admin-docs-store' ?>" method="post" class="p-6 space-y-6">
            <?php if (!empty($doc)): ?>
                <input type="hidden" name="id" value="<?= (int)$doc['id'] ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 gap-4">
                <div class="flex flex-col">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Section</label>
                    <input name="section" value="<?= htmlspecialchars($doc['section'] ?? '') ?>" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="General" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Title</label>
                    <input name="title" value="<?= htmlspecialchars($doc['title'] ?? '') ?>" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                </div>
                <div class="flex flex-col">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Slug</label>
                    <input name="slug" value="<?= htmlspecialchars($doc['slug'] ?? '') ?>" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" placeholder="auto-generate if empty">
                </div>
                <div class="flex flex-col">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Description</label>
                    <textarea name="description" rows="2" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"><?= htmlspecialchars($doc['description'] ?? '') ?></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-xs uppercase tracking-wide text-gray-400">Content</label>
                    <textarea name="content" rows="10" class="mt-1 px-3 py-2 rounded bg-gray-700 border border-gray-600 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required><?= htmlspecialchars($doc['content'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 rounded border border-indigo-500/30">Simpan</button>
                <a href="index.php?page=dashboard-admin-docs" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded border border-gray-600">Batal</a>
            </div>
        </form>
    </div>
</div>