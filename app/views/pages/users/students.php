<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$me = $_SESSION['user'] ?? null;
$canAdmin = isset($me['level']) && $me['level'] === 'admin';

$rows = [];
$errorMsg = '';

if (!isset($users) || !is_array($users)) {
    $users = [];
}

$rows = $users;

if (empty($rows) && isset($config) && $config instanceof mysqli) {
    $sql = "SELECT id, username, email, level, avatar, join_date FROM users WHERE level = 'user' ORDER BY join_date DESC";
    $res = mysqli_query($config, $sql);
    if ($res) {
        while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
        mysqli_free_result($res);
    }
}

if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>

<div class="bg-gray-900 min-h-screen">
  <div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-100">Daftar Siswa</h1>
        <p class="text-sm text-gray-400 mt-1">Manage student accounts</p>
      </div>
      <a href="index.php?page=create_user" 
         class="bg-amber-600 hover:bg-amber-700 text-white rounded-md px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2 whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Siswa
      </a>
    </div>

    <div class="mb-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" id="studentSearchForm" class="flex flex-col sm:flex-row gap-3">
        <input type="hidden" name="page" value="students">
        
        <div class="flex-1 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          <input type="text" 
                 name="q" 
                 id="studentSearchInput"
                 placeholder="Search by name, username, or email..." 
                 value="<?= htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                 class="w-full pl-10 pr-4 py-2 bg-gray-900 border border-gray-700 text-sm text-gray-200 rounded-md focus:border-amber-500 focus:ring-1 focus:ring-amber-500 focus:outline-none transition-colors placeholder-gray-500">
        </div>

        <button type="submit" 
                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap">
          Search
        </button>
        
        <?php if (!empty($_GET['q'])): ?>
        <a href="index.php?page=students" 
           class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded-md transition-colors whitespace-nowrap">
          Clear
        </a>
        <?php endif; ?>
      </form>
    </div>

    <?php if (!empty($errorMsg)): ?>
    <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-400"><?= htmlspecialchars($errorMsg) ?></p>
      </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-amber-500/10 rounded-lg">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Siswa</p>
            <p class="text-xl font-semibold text-gray-100"><?= count($rows) ?></p>
          </div>
        </div>
      </div>

      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-blue-500/10 rounded-lg">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Status Aktif</p>
            <p class="text-xl font-semibold text-gray-100"><?= count($rows) ?></p>
          </div>
        </div>
      </div>
    </div>

    <?php if (count($rows) > 0): ?>
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-[#111827]">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Bergabung</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <?php foreach ($rows as $row): ?>
            <tr class="hover:bg-gray-800 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <?php
                  $avatar = $row['avatar'] ?? '';
                  $avatarSrc = ($baseUrl ? $baseUrl . '/' : '') . ltrim($avatar ?: 'assets/default-avatar.png', '/');
                  $username = $row['username'] ?? '';
                  $initial = strtoupper(substr($username, 0, 1));
                  ?>
                  <div class="relative flex-shrink-0 cursor-pointer" data-view-profile="<?= (int)($row['id'] ?? 0) ?>" title="View Profile">
                    <img src="<?= htmlspecialchars($avatarSrc) ?>"
                         class="w-10 h-10 rounded-full object-cover border-2 border-amber-700 hover:border-amber-500 transition-colors" 
                         alt="<?= htmlspecialchars($username) ?>"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center hidden">
                      <span class="text-sm font-semibold text-white"><?= $initial ?></span>
                    </div>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-200 cursor-pointer hover:text-amber-400 transition-colors" data-view-profile="<?= (int)($row['id'] ?? 0) ?>" title="View Profile"><?= htmlspecialchars($username) ?></p>
                    <p class="text-xs text-gray-500">ID: <?= htmlspecialchars($row['id'] ?? '') ?></p>
                  </div>
                </div>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  <span class="text-sm text-gray-400"><?= htmlspecialchars($row['email'] ?? '') ?></span>
                </div>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border bg-amber-500/10 text-amber-400 border-amber-500/20">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                  </svg>
                  Siswa
                </span>
              </td>

              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span class="text-sm text-gray-400"><?= htmlspecialchars($row['join_date'] ?? '-') ?></span>
                </div>
              </td>

              <td class="px-6 py-4 whitespace-nowrap text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="index.php?page=edit_user&id=<?= (int)($row['id'] ?? 0) ?>" 
                     class="p-2 text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 rounded-md transition-colors"
                     title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </a>
                  <button type="button" 
                          class="delete-btn p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-md transition-colors"
                          title="Delete"
                          data-id="<?= (int)($row['id'] ?? 0) ?>"
                          data-url="index.php?page=delete_user"
                          data-item-name="<?= htmlspecialchars($row['username'] ?? 'User', ENT_QUOTES, 'UTF-8') ?>"
                          data-row-selector="tr">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php else: ?>
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-8">
      <div class="flex flex-col items-center justify-center">
        <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center mb-3">
          <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <p class="text-gray-400 text-sm mb-3">Belum ada siswa terdaftar</p>
        <a href="index.php?page=create_user" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md text-sm font-medium transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Siswa Pertama
        </a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
(function() {
  const form = document.getElementById('studentSearchForm');
  const input = document.getElementById('studentSearchInput');
  let timer = null;

  if (form && input) {
    function performSearch() {
      const formData = new FormData(form);
      const params = new URLSearchParams();
      params.set('page', 'students');
      if (formData.get('q')) params.set('q', formData.get('q'));
      params.set('ajax', '1');

      fetch('index.php?' + params.toString(), { 
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.text())
      .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const newContent = doc.querySelector('.max-w-7xl');
        if (newContent) {
          const currentContent = document.querySelector('.max-w-7xl');
          if (currentContent) {
            currentContent.innerHTML = newContent.innerHTML;
            const scripts = doc.querySelectorAll('script');
            scripts.forEach(s => {
              const ns = document.createElement('script');
              if (s.src) ns.src = s.src;
              else ns.textContent = s.textContent;
              document.body.appendChild(ns);
            });
          }
        }
        const friendlyUrl = 'index.php?page=students' + 
          (params.get('q') ? '&q=' + encodeURIComponent(params.get('q')) : '');
        try { history.pushState({}, '', friendlyUrl); } catch (e) {}
      })
      .catch(err => console.error('Search error:', err));
    }

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      clearTimeout(timer);
      performSearch();
    });

    input.addEventListener('input', function() {
      clearTimeout(timer);
      timer = setTimeout(performSearch, 300);
    }, { passive: true });
  }
})();
</script>