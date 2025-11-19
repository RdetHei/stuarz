<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php if (!isset($ajax)) $ajax = false; ?>

<?php
// Pagination controls
$total = $total ?? 0;
$limit = (int)($limit ?? 10);
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
$q = $q ?? '';
$totalPages = $limit > 0 ? (int)ceil($total / $limit) : 1;
$offset = ($page - 1) * $limit;
?>

<div class="max-w-7xl mx-auto p-6 pr-20">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Dokumentasi</h1>
                    <p class="text-gray-400 text-sm mt-1">Kelola dokumentasi sistem</p>
                </div>
            </div>

            <a href="index.php?page=dashboard-admin-docs-create" 
               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Dokumentasi
            </a>
        </div>
    </div>

<?php if (!$ajax): ?>
    <!-- Filter & Search Bar -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <!-- Show Entries -->
            <form method="get" class="flex items-center gap-3">
                <input type="hidden" name="page" value="dashboard-admin-docs">
                <label class="text-sm font-medium text-gray-400">Show</label>
                <select name="limit" 
                        onchange="this.form.submit()" 
                        class="px-4 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg focus:border-indigo-600 focus:outline-none transition-all">
                    <?php $sizes = [5,10,25,50,100]; foreach ($sizes as $s): ?>
                        <option value="<?= $s ?>" <?= (isset($limit) && (int)$limit === $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
                <label class="text-sm font-medium text-gray-400">entries</label>
            </form>

            <!-- Search Form -->
            <form method="get" class="flex items-center gap-2">
                <input type="hidden" name="page" value="dashboard-admin-docs">
                <input type="hidden" name="limit" value="<?= htmlspecialchars($limit ?? 10) ?>">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="q" 
                           placeholder="Search title, section or description" 
                           value="<?= htmlspecialchars($q ?? '') ?>" 
                           class="pl-10 pr-4 py-2 bg-gray-900 border border-gray-700 text-white rounded-lg w-80 focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500" />
                </div>
                <button type="submit" 
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </button>
            </form>
        </div>
    </div>
<?php endif; ?>

    <div id="adminDocsContent">

    <!-- Table -->
    <?php if (!empty($docs)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto" style="max-width: 100%;">
            <table class="w-full" style="table-layout: fixed; width: 100%;">
                <colgroup>
                    <col style="width: 15%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                    <col style="width: auto;">
                    <col style="width: 110px;">
                </colgroup>
                <thead>
                    <tr class="bg-gray-900 border-b border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-3 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($docs as $d): ?>
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <?= htmlspecialchars($d['section']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-white font-medium truncate"><?= htmlspecialchars($d['title']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400 font-mono text-sm truncate">
                            <?= htmlspecialchars($d['slug']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-400 text-sm truncate"><?= htmlspecialchars($d['description']) ?></p>
                        </td>
                        <td class="px-2 py-4" style="width: 110px; max-width: 110px; overflow: hidden; box-sizing: border-box;">
                            <div class="flex items-center justify-end gap-1" style="width: 100%; max-width: 100%; box-sizing: border-box;">
                                <a href="index.php?page=dashboard-admin-docs-edit&id=<?= (int)$d['id'] ?>" 
                                   class="p-1.5 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-200" 
                                   title="Edit"
                                   style="flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; box-sizing: border-box;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="post" 
                                      action="index.php?page=dashboard-admin-docs-delete" 
                                      class="inline-block m-0 p-0" 
                                      onsubmit="return confirm('Hapus dokumen ini?')"
                                      style="flex-shrink: 0; margin: 0; padding: 0; display: inline-block; box-sizing: border-box;">
                                    <input type="hidden" name="id" value="<?= (int)$d['id'] ?>">
                                    <button type="submit" 
                                            class="p-1.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg transition-all duration-200" 
                                            title="Delete"
                                            style="flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; box-sizing: border-box;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 bg-gray-900 border-t border-gray-700">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-400">
                    Showing <?= ($total > 0) ? ($offset + 1) : 0 ?> to <?= min($offset + $limit, $total) ?> of <?= $total ?> entries
                </div>
                
                <?php if ($totalPages > 1): ?>
                <nav class="flex items-center gap-2" aria-label="Pagination">
                        <?php if ((int)$page > 1): ?>
                        <a href="?page=dashboard-admin-docs&limit=<?= $limit ?>&p=<?= ((int)$page-1) ?>&q=<?= urlencode($q ?? '') ?>" 
                           class="px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-white rounded-lg transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <div class="flex items-center gap-1 flex-wrap">
                        <?php
                        // Always show first page
                        if ((int)$page > 3): ?>
                            <a href="?page=dashboard-admin-docs&limit=<?= $limit ?>&p=1&q=<?= urlencode($q ?? '') ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition-all bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700">
                                1
                            </a>
                            <?php if ((int)$page > 4): ?>
                                <span class="px-2 text-gray-500">...</span>
                            <?php endif; 
                        endif;

                        // Calculate range of pages to show
                        $start = max(1, (int)$page - 2);
                        $end = min($totalPages, (int)$page + 2);

                        // Show page numbers
                        for ($i = $start; $i <= $end; $i++): ?>
                            <a href="?page=dashboard-admin-docs&limit=<?= $limit ?>&p=<?= $i ?>&q=<?= urlencode($q ?? '') ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition-all <?= (int)$i === (int)$page ? 'bg-indigo-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor;

                        // Show last page if needed
                        if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?>
                                <span class="px-2 text-gray-500">...</span>
                            <?php endif; ?>
                            <a href="?page=dashboard-admin-docs&limit=<?= $limit ?>&p=<?= $totalPages ?>&q=<?= urlencode($q ?? '') ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition-all bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700">
                                <?= $totalPages ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ((int)$page < (int)$totalPages): ?>
                        <a href="?page=dashboard-admin-docs&limit=<?= $limit ?>&page_num=<?= ((int)$page+1) ?>&q=<?= urlencode($q ?? '') ?>" 
                           class="px-4 py-2 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-white rounded-lg transition-all flex items-center gap-2">
                            Next
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Dokumentasi</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">
            <?= !empty($q) ? 'Tidak ada hasil yang ditemukan untuk pencarian Anda.' : 'Belum ada dokumentasi tersedia. Mulai tambahkan dokumentasi pertama.' ?>
        </p>
        <?php if (empty($q)): ?>
        <a href="index.php?page=dashboard-admin-docs-create" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Dokumentasi Pertama
        </a>
        <?php else: ?>
        <a href="index.php?page=dashboard-admin-docs" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Clear Search
        </a>
        <?php endif; ?>
    </div>
        <?php endif; ?>
        </div>

<?php if (!$ajax): ?>
<script>
// Live search for Admin Docs (targets admin docs search form)
(function(){
    const pageInput = document.querySelector('input[name="page"][value="dashboard-admin-docs"]');
    const form = pageInput ? pageInput.closest('form') : null;
    const container = document.getElementById('adminDocsContent');
    if (!form || !container) return;
    const qInput = form.querySelector('input[name="q"]');
    const limitSelect = form.querySelector('select[name="limit"]');
    let timer = null;

    function serializeState() {
        return { q: (qInput && qInput.value) || '', limit: (limitSelect && limitSelect.value) || '' };
    }

    function load(state, push = true) {
        const params = new URLSearchParams();
        params.set('page','dashboard-admin-docs');
        if (state.q) params.set('q', state.q);
        if (state.limit) params.set('limit', state.limit);
        params.set('ajax','1');

        fetch('index.php?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('adminDocsContent');
            if (newContainer) container.innerHTML = newContainer.innerHTML;
            if (push) {
                const friendly = 'index.php?page=dashboard-admin-docs' + (state.q ? '&q=' + encodeURIComponent(state.q) : '') + (state.limit ? '&limit=' + encodeURIComponent(state.limit) : '');
                try { history.pushState(state, '', friendly); } catch (e) {}
            }
        }).catch(()=>{});
    }

    form.addEventListener('submit', function(e){ e.preventDefault(); clearTimeout(timer); load(serializeState()); });
    if (qInput) qInput.addEventListener('input', function(){ clearTimeout(timer); timer = setTimeout(function(){ load(serializeState()); }, 300); }, { passive:true });
    if (limitSelect) limitSelect.addEventListener('change', function(){ clearTimeout(timer); load(serializeState()); });
    window.addEventListener('popstate', function(ev){ const state = ev.state || {}; if (qInput) qInput.value = state.q || ''; if (limitSelect) limitSelect.value = state.limit || ''; load(state, false); });
})();
</script>
<?php endif; ?>