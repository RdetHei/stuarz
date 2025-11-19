<?php if (!isset($allNews)) { $allNews = []; $cats = []; $q = ''; $cat = ''; $page = 1; $totalPages = 1; } ?>
<?php if (!isset($ajax)) $ajax = false; ?>
<?php
// Compute base URL similar to other pages
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>

<?php if (!$ajax): ?>
<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">

    <div class="flex items-center justify-between mb-6">
      <div>
    <h1 class="text-3xl font-bold text-gray-100">News</h1>
    <p class="text-gray-400 mb-6">Latest updates and articles</p>
  </div>
</div>

<?php endif; ?>
<?php if (!$ajax): ?>
    <!-- Search & Filter -->
    <div class="mb-6 bg-[#1f2937] border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="page" value="news">
        
        <div class="flex-1 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" 
                 name="q" 
                 placeholder="Search news..." 
                 value="<?= htmlspecialchars($q) ?>" 
                 class="w-full pl-10 pr-4 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors placeholder-gray-500">
        </div>
        
        <select name="cat" 
                class="px-3 py-2 bg-[#111827] border border-gray-700 text-sm text-gray-200 rounded-md focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
          <option value="">All Categories</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= htmlspecialchars($c) ?>" <?= $cat === $c ? 'selected' : '' ?> >
              <?= htmlspecialchars($c) ?>
            </option>
          <?php endforeach; ?>
        </select>
        
        <button type="submit" 
                class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap">
          Search
        </button>
      </form>
    </div>
<?php endif; ?>
    
<?php
// News content wrapper: used by header/global live-search to replace only this section
?>

<div id="newsContent">

    <!-- News Grid -->
    <?php if (!empty($allNews)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
      <!-- Featured News (First Item) -->
      <?php $first = $allNews[0]; ?>
      <a href="index.php?page=news_show&id=<?= (int)$first['id'] ?>" 
         class="lg:col-span-2 group relative rounded-lg overflow-hidden bg-[#1f2937] border border-gray-700 hover:border-gray-600 transition-all">
        <?php if (!empty($first['thumbnail'])): ?>
          <div class="relative overflow-hidden" style="height: 360px;">
            <img
              src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($first['thumbnail'], '/')) ?>"
              alt="<?= htmlspecialchars($first['title']) ?>"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              loading="lazy"
              onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/40 to-transparent"></div>
          </div>
        <?php endif; ?>
        <div class="absolute inset-x-0 bottom-0 p-6">
          <span class="inline-block px-2.5 py-1 bg-[#5865F2]/10 text-[#5865F2] border border-[#5865F2]/20 rounded-md text-xs font-medium mb-3">
            <?= htmlspecialchars($first['category']) ?>
          </span>
          <h2 class="text-xl font-bold text-white mb-2 line-clamp-2">
            <?= htmlspecialchars($first['title']) ?>
          </h2>
          <p class="text-gray-300 text-sm mb-3 line-clamp-2">
            <?= htmlspecialchars(mb_substr(strip_tags($first['content']), 0, 160)) ?>...
          </p>
          <div class="flex items-center gap-2 text-xs text-gray-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span><?= htmlspecialchars($first['author']) ?></span>
            <span>•</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span><?= htmlspecialchars(date('d M Y', strtotime($first['created_at']))) ?></span>
          </div>
        </div>
      </a>

      <!-- Regular News Items -->
      <?php foreach (array_slice($allNews, 1) as $n): ?>
        <a href="index.php?page=news_show&id=<?= (int)$n['id'] ?>" 
           class="group rounded-lg overflow-hidden bg-[#1f2937] border border-gray-700 hover:border-gray-600 transition-all flex flex-col">
          <?php if (!empty($n['thumbnail'])): ?>
            <div class="relative overflow-hidden" style="height: 180px;">
              <img
                src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($n['thumbnail'], '/')) ?>"
                alt="<?= htmlspecialchars($n['title']) ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
                onerror="this.onerror=null;this.src='<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . 'assets/default-thumb.png') ?>'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            </div>
          <?php endif; ?>
          <div class="p-4 flex-1 flex flex-col">
            <span class="inline-block px-2.5 py-1 bg-[#5865F2]/10 text-[#5865F2] border border-[#5865F2]/20 rounded-md text-xs font-medium mb-2 self-start">
              <?= htmlspecialchars($n['category']) ?>
            </span>
            <h3 class="font-semibold text-gray-100 mb-2 line-clamp-2 group-hover:text-[#5865F2] transition-colors">
              <?= htmlspecialchars($n['title']) ?>
            </h3>
            <p class="text-gray-400 text-sm mb-3 line-clamp-2 flex-1">
              <?= htmlspecialchars(mb_substr(strip_tags($n['content']), 0, 100)) ?>...
            </p>
            <div class="flex items-center gap-2 text-xs text-gray-500 pt-3 border-t border-gray-700">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span class="truncate"><?= htmlspecialchars($n['author']) ?></span>
              <span>•</span>
              <span><?= htmlspecialchars(date('d M Y', strtotime($n['created_at']))) ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <!-- Empty State -->
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-12 text-center">
      <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-gray-100 mb-2">No News Found</h3>
      <p class="text-gray-400 mb-6">There are no news articles available at the moment.</p>
      <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
      <a href="index.php?page=news/create" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create First News
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    </div> <!-- #newsContent -->

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-center">
      <div class="inline-flex items-center gap-2 bg-[#1f2937] border border-gray-700 rounded-lg p-2">
        <?php $prev = max(1, $page - 1); $next = min($totalPages, $page + 1); ?>
        
        <?php if ($page > 1): ?>
        <a href="index.php?page=news&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&p=<?= $prev ?>" 
           class="px-3 py-1.5 bg-[#111827] hover:bg-gray-700 text-gray-300 text-sm font-medium rounded-md transition-colors flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Previous
        </a>
        <?php else: ?>
        <span class="px-3 py-1.5 bg-gray-800 text-gray-600 text-sm font-medium rounded-md cursor-not-allowed flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          Previous
        </span>
        <?php endif; ?>
        
        <span class="px-4 py-1.5 text-sm text-gray-400">
          Page <span class="text-gray-200 font-medium"><?= $page ?></span> of <span class="text-gray-200 font-medium"><?= $totalPages ?></span>
        </span>
        
        <?php if ($page < $totalPages): ?>
        <a href="index.php?page=news&q=<?= urlencode($q) ?>&cat=<?= urlencode($cat) ?>&p=<?= $next ?>" 
           class="px-3 py-1.5 bg-[#111827] hover:bg-gray-700 text-gray-300 text-sm font-medium rounded-md transition-colors flex items-center gap-1">
          Next
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </a>
        <?php else: ?>
        <span class="px-3 py-1.5 bg-gray-800 text-gray-600 text-sm font-medium rounded-md cursor-not-allowed flex items-center gap-1">
          Next
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </span>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php if (!$ajax): ?>
<script>
// Live search for News page (targets the news form only)
(function(){
  const pageInput = document.querySelector('input[name="page"][value="news"]');
  const form = pageInput ? pageInput.closest('form') : null;
  const contentEl = document.getElementById('newsContent') || document.querySelector('.grid.grid-cols-1') || document.querySelector('.bg-[#1f2937]');
  if (!form || !contentEl) return;

  const qInput = form.querySelector('input[name="q"]');
  const catSelect = form.querySelector('select[name="cat"]');
  let timer = null;

  function serializeState() {
    return { q: (qInput && qInput.value) || '', cat: (catSelect && catSelect.value) || '' };
  }

  function load(state, push = true) {
    const params = new URLSearchParams();
    params.set('page','news');
    if (state.q) params.set('q', state.q);
    if (state.cat) params.set('cat', state.cat);
    params.set('ajax','1');

    fetch('index.php?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      // Prefer exact fragment id
      const newWrapper = doc.getElementById('newsContent') || doc.querySelector('.grid.grid-cols-1') || doc.querySelector('.bg-[#1f2937]');
      if (newWrapper) {
        // If we have an existing #newsContent, update its innerHTML; otherwise replace fallback element
        const existingWrapper = document.getElementById('newsContent');
        if (existingWrapper) {
          existingWrapper.innerHTML = newWrapper.innerHTML;
        } else {
          const existingGrid = document.querySelector('.grid.grid-cols-1');
          if (existingGrid) existingGrid.replaceWith(newWrapper);
          else contentEl.innerHTML = newWrapper.outerHTML;
        }
      }

      if (push) {
        const friendly = 'index.php?page=news' + (state.q ? '&q=' + encodeURIComponent(state.q) : '') + (state.cat ? '&cat=' + encodeURIComponent(state.cat) : '');
        try { history.pushState(state, '', friendly); } catch (e) {}
      }
    }).catch(()=>{});
  }

  form.addEventListener('submit', function(e){ e.preventDefault(); clearTimeout(timer); load(serializeState()); });
  if (qInput) qInput.addEventListener('input', function(){ clearTimeout(timer); timer = setTimeout(function(){ load(serializeState()); }, 300); }, { passive:true });
  if (catSelect) catSelect.addEventListener('change', function(){ clearTimeout(timer); load(serializeState()); });
  window.addEventListener('popstate', function(ev){ const state = ev.state || {}; if (qInput) qInput.value = state.q || ''; if (catSelect) catSelect.value = state.cat || ''; load(state, false); });
})();
</script>
<?php endif; ?>