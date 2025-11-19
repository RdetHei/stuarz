<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php if (!isset($ajax)) $ajax = false; ?>

<?php
// Pagination controls
$total = $total ?? 0;
$limit = (int)($limit ?? 10);
$page = isset($_GET['page_num']) ? (int)$_GET['page_num'] : (isset($_GET['p']) ? (int)$_GET['p'] : 1);
$q = $q ?? '';
$totalPages = $limit > 0 ? (int)ceil($total / $limit) : 1;
$offset = ($page - 1) * $limit;

// compute base URL for asset resolution (same logic as other views)
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}

// normalize thumbnail URLs for each news item so the view can always use *_src fields
if (!empty($news) && is_array($news)) {
  foreach ($news as $idx => $item) {
    $thumb = !empty($item['thumbnail']) ? $item['thumbnail'] : '';
    if ($thumb && (strpos($thumb, 'http://') === 0 || strpos($thumb, 'https://') === 0)) {
      $news[$idx]['thumbnail_src'] = $thumb;
    } elseif ($thumb) {
      $news[$idx]['thumbnail_src'] = ($baseUrl ? $baseUrl . '/' : '') . ltrim($thumb, '/');
    } else {
      $news[$idx]['thumbnail_src'] = '';
    }
  }
}
?>

<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Berita</h1>
                    <p class="text-gray-400 text-sm mt-1">Kelola berita sistem</p>
                </div>
            </div>

            <a href="index.php?page=dashboard-admin-news-create" 
               class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Berita
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    <?php if (!empty($_SESSION['flash'])): ?>
      <div class="mb-6 px-6 py-4 bg-gray-800/60 border border-gray-700 rounded-lg text-sm text-gray-200">
          <?= htmlspecialchars($_SESSION['flash']) ?>
      </div>
      <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

<?php if (!$ajax): ?>
    <!-- Filter & Search Bar -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <!-- Show Entries -->
            <form method="get" class="flex items-center gap-3">
                <input type="hidden" name="page" value="dashboard-admin-news">
                <input type="hidden" name="q" value="<?= htmlspecialchars($q) ?>">
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
                <input type="hidden" name="page" value="dashboard-admin-news">
                <input type="hidden" name="limit" value="<?= htmlspecialchars($limit ?? 10) ?>">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" 
                           name="q" 
                           placeholder="Search title, category, author or content" 
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

    <div id="adminNewsContent">

    <!-- Table -->
    <?php if (!empty($news)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-900 border-b border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Thumbnail</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($news as $n): ?>
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <?php if (!empty($n['thumbnail_src'])): ?>
                                <img
                                    src="<?= htmlspecialchars($n['thumbnail_src']) ?>"
                                    class="w-12 h-12 rounded-lg object-cover border border-gray-700"
                                    alt="<?= htmlspecialchars($n['title']) ?>"
                                    loading="lazy"
                                    decoding="async"
                                    width="48" height="48"
                                    onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
                            <?php else: ?>
                                <div class="w-12 h-12 rounded-lg bg-gray-700 border border-gray-700 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                <span class="text-white font-medium"><?= htmlspecialchars($n['title']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30">
                                <?= htmlspecialchars($n['category'] ?? 'Uncategorized') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="text-gray-300"><?= htmlspecialchars($n['author'] ?? 'Unknown') ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-sm">
                            <?= htmlspecialchars(date('d M Y', strtotime($n['created_at']))) ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="index.php?page=dashboard-admin-news-edit&id=<?= (int)$n['id'] ?>" 
                                   class="p-2 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-200" 
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="post" 
                                      action="index.php?page=dashboard-admin-news-delete" 
                                      class="inline" 
                                      onsubmit="return confirm('Hapus berita ini?')">
                                    <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                                    <button type="submit" 
                                            class="p-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg transition-all duration-200" 
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <a href="?page=dashboard-admin-news&limit=<?= $limit ?>&p=<?= ((int)$page-1) ?>&q=<?= urlencode($q ?? '') ?>" 
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
                            <a href="?page=dashboard-admin-news&limit=<?= $limit ?>&p=1&q=<?= urlencode($q ?? '') ?>" 
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
                            <a href="?page=dashboard-admin-news&limit=<?= $limit ?>&p=<?= $i ?>&q=<?= urlencode($q ?? '') ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition-all <?= (int)$i === (int)$page ? 'bg-indigo-600 text-white' : 'bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor;

                        // Show last page if needed
                        if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?>
                                <span class="px-2 text-gray-500">...</span>
                            <?php endif; ?>
                            <a href="?page=dashboard-admin-news&limit=<?= $limit ?>&p=<?= $totalPages ?>&q=<?= urlencode($q ?? '') ?>" 
                               class="px-4 py-2 rounded-lg font-medium transition-all bg-gray-800 hover:bg-gray-700 text-gray-300 border border-gray-700">
                                <?= $totalPages ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ((int)$page < (int)$totalPages): ?>
                        <a href="?page=dashboard-admin-news&limit=<?= $limit ?>&page_num=<?= ((int)$page+1) ?>&q=<?= urlencode($q ?? '') ?>" 
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Berita</h3>
        <p class="text-gray-400 mb-6 max-w-md mx-auto">
            <?= !empty($q) ? 'Tidak ada hasil yang ditemukan untuk pencarian Anda.' : 'Belum ada berita tersedia. Mulai tambahkan berita pertama.' ?>
        </p>
        <?php if (empty($q)): ?>
        <a href="index.php?page=dashboard-admin-news-create" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Berita Pertama
        </a>
        <?php else: ?>
        <a href="index.php?page=dashboard-admin-news" 
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
// Live search for Admin News (targets admin news search form)
(function(){
    const pageInput = document.querySelector('input[name="page"][value="dashboard-admin-news"]');
    const form = pageInput ? pageInput.closest('form') : null;
    const container = document.getElementById('adminNewsContent');
    if (!form || !container) return;
    const qInput = form.querySelector('input[name="q"]');
    const limitInput = form.querySelector('input[name="limit"]');
    let timer = null;

    function serializeState() {
        return { q: (qInput && qInput.value) || '', limit: (limitInput && limitInput.value) || '' };
    }

    function load(state, push = true) {
        const params = new URLSearchParams();
        params.set('page','dashboard-admin-news');
        if (state.q) params.set('q', state.q);
        if (state.limit) params.set('limit', state.limit);
        params.set('ajax','1');

        fetch('index.php?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('adminNewsContent');
            if (newContainer) container.innerHTML = newContainer.innerHTML;
            if (push) {
                const friendly = 'index.php?page=dashboard-admin-news' + (state.q ? '&q=' + encodeURIComponent(state.q) : '') + (state.limit ? '&limit=' + encodeURIComponent(state.limit) : '');
                try { history.pushState(state, '', friendly); } catch (e) {}
            }
        }).catch(()=>{});
    }

    form.addEventListener('submit', function(e){ e.preventDefault(); clearTimeout(timer); load(serializeState()); });
    if (qInput) qInput.addEventListener('input', function(){ clearTimeout(timer); timer = setTimeout(function(){ load(serializeState()); }, 300); }, { passive:true });
    if (limitInput) limitInput.addEventListener('change', function(){ clearTimeout(timer); load(serializeState()); });
    window.addEventListener('popstate', function(ev){ const state = ev.state || {}; if (qInput) qInput.value = state.q || ''; if (limitInput) limitInput.value = state.limit || ''; load(state, false); });
})();
</script>
<?php endif; ?>
