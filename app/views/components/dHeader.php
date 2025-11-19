<?php
$pageTitles = [
    'dashboard' => 'Dashboard',
    'dashboard-admin' => 'Admin Dashboard',
    'profile' => 'Profile',
    'account' => 'Account Management',
    'certificates' => 'Sertifikat',
    'dashboard-admin-docs' => 'Dokumentasi',
    'class' => 'Kelas',
    'dashboard-admin-docs-create' => 'Documentations - Create',
    'dashboard-admin-news' => 'Berita',
    'announcement' => 'Pengumuman',
    'subjects' => 'Mata Pelajar',
    'grades' => 'Grades',
    'schedule' => 'Jadwal',
    'tasks' => 'Tugas',
    'attendance' => 'Kehadiran',
    'print' => 'Print'
];

$dashboardTitles = [
    'account'  => 'Daftar Akun',
    'overview' => 'Ringkasan',
    'settings' => 'Pengaturan Dashboard',
    'docs'     => 'Dokumentasi',
];


$currentPage = $_GET['page'] ?? 'home';
$currentSub  = $_GET['dashboard'] ?? null;

if ($currentPage === 'dashboard' && $currentSub) {
    $title = $dashboardTitles[$currentSub] ?? '-';
} else {
    $title = $pageTitles[$currentPage] ?? '-';
}

$breadcrumbs = [];
$breadcrumbs[] = ['label' => 'Home', 'href' => 'index.php?page=dashboard-admin'];

if ($currentPage === 'dashboard' && $currentSub) {
    $breadcrumbs[] = ['label' => 'Dashboard', 'href' => 'index.php?page=dashboard'];
    $breadcrumbs[] = ['label' => $title, 'href' => null];
} else {
    $mainLabel = $pageTitles[$currentPage] ?? ucwords(str_replace(['-', '_'], ' ', (string)$currentPage));
    $breadcrumbs[] = ['label' => $mainLabel, 'href' => "index.php?page=" . urlencode((string)$currentPage)];

    $actionParam = null;
    foreach (['action', 'mode', 'op', 'view'] as $p) {
        if (isset($_GET[$p]) && $_GET[$p] !== '') { $actionParam = (string) $_GET[$p]; break; }
    }
    if ($actionParam !== null) {
        $actionLabel = ucwords(str_replace(['-', '_'], ' ', $actionParam));
        $breadcrumbs[] = ['label' => $actionLabel, 'href' => null];
    }
}
?>
<header id="dHeader" class="sticky top-0 z-[60] bg-slate-900 opacity-100 text-white h-16 flex items-center border-b border-slate-700 justify-between px-4">
    <div class="flex items-center gap-3 sm:gap-4">
        <button id="mobileMenuToggle" class="lg:hidden p-2 hover:bg-slate-700 rounded" aria-label="Toggle mobile menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <nav aria-label="Breadcrumb" class="hidden md:flex items-center text-sm text-slate-300">
            <?php for ($i = 0; $i < count($breadcrumbs); $i++): ?>
                <?php $crumb = $breadcrumbs[$i]; ?>
                <?php if ($i === 0): ?>
                    <a href="<?= htmlspecialchars($crumb['href'], ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white flex items-center gap-1">
                        <span class="material-symbols-outlined text-base">home</span>
                        <span><?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?></span>
                    </a>
                <?php else: ?>
                    <span class="mx-2 text-slate-500">/</span>
                    <?php if (!empty($crumb['href'])): ?>
                        <a href="<?= htmlspecialchars($crumb['href'], ENT_QUOTES, 'UTF-8') ?>" class="hover:text-white truncate max-w-[28ch]" title="<?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>
                        </a>
                    <?php else: ?>
                        <span class="text-white font-semibold truncate max-w-[28ch]" title="<?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
        <div class="md:hidden font-bold"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
        <form action="index.php" method="get" class="hidden sm:flex items-center bg-slate-800/60 border border-slate-700 rounded-md px-2 h-9 focus-within:border-slate-500 transition-colors">
            <input type="hidden" name="page" value="<?= htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8') ?>" />
            <span class="material-symbols-outlined text-slate-300 mr-1">search</span>
            <input name="q" type="text" placeholder="Cari..." class="bg-transparent outline-none text-sm text-white placeholder:text-slate-400 w-48" />
        </form>

        <!-- Notifications -->
        <button id="notifBtn" class="p-2 hover:bg-slate-700 rounded relative" aria-label="Notifications" title="Notifications">
            <span class="material-symbols-outlined">notifications</span>
            <span id="notifBadge" class="absolute -top-0.5 -right-0.5 bg-red-500 text-[10px] rounded-full px-1 leading-4" style="display:none">0</span>
        </button>

        <?php
        $userLevel = $_SESSION['level'] ?? null;
        if ($userLevel === 'admin' || $userLevel === 'teacher') :
        ?>
        <div class="relative">
            <button id="printDropdownBtn" class="p-2 hover:bg-slate-700 rounded flex items-center gap-2" aria-haspopup="true" aria-expanded="false" title="Print Options">
                <span class="material-symbols-outlined">print</span>
            </button>
            <div id="printDropdownMenu" class="absolute right-0 mt-2 w-44 bg-white text-black rounded shadow-lg hidden z-10">
                <a href="index.php?page=print_all" class="block px-4 py-2 hover:bg-slate-100">Print All</a>
                <a href="index.php?page=print" class="block px-4 py-2 hover:bg-slate-100">Print Table</a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Support dropdown -->
        <div class="relative">
            <button id="supportDropdownBtn" class="p-2 hover:bg-slate-700 rounded flex items-center gap-2" aria-haspopup="true" aria-expanded="false">
                Support
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="supportDropdownMenu" class="absolute right-0 mt-2 w-44 bg-white text-black rounded shadow-lg hidden z-10">
                <a href="mailto:support@example.com" class="block px-4 py-2 hover:bg-slate-100">Email Support</a>
                <a href="https://wa.me/628123456789" class="block px-4 py-2 hover:bg-slate-100">WhatsApp</a>
                <a href="index.php?page=docs" class="block px-4 py-2 hover:bg-slate-100">Dokumentasi</a>
            </div>
        </div>
    </div>
</header>

<script>
    (function() {
        const btn = document.getElementById('supportDropdownBtn');
        const menu = document.getElementById('supportDropdownMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function() {
            menu.classList.add('hidden');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') menu.classList.add('hidden');
        });
    })();

    (function(){
        var btn = document.getElementById('printDropdownBtn');
        var menu = document.getElementById('printDropdownMenu');
        if (!btn || !menu) return;

        btn.addEventListener('click', function(e){
            e.stopPropagation();
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            menu.classList.toggle('hidden');
        });

        document.addEventListener('click', function(){
            menu.classList.add('hidden');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        });

        document.addEventListener('keydown', function(e){
            if (e.key === 'Escape') {
                menu.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>

<script>
(function(){
    const headerForm = document.querySelector('header form[action="index.php"]');
    if (!headerForm) return;
    const pageInput = headerForm.querySelector('input[name="page"]');
    const qInput = headerForm.querySelector('input[name="q"]');
    if (!pageInput || !qInput) return;

    let timer = null;

    function serialize() {
        const page = pageInput.value || 'home';
        const q = qInput.value || '';
        return { page, q };
    }

    function applyFragment(html) {
        try {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const fragmentIds = ['docsContent','adminNewsContent','adminDocsContent','tasksContent','newsContent'];
            for (let id of fragmentIds) {
                const node = doc.getElementById(id);
                if (node) {
                    const target = document.getElementById(id);
                    if (target) { target.innerHTML = node.innerHTML; return true; }
                }
            }

            // Fallback: replace <main> content if present in response
            const mainResp = doc.querySelector('main');
            const mainEl = document.querySelector('main');
            if (mainResp && mainEl) {
                mainEl.innerHTML = mainResp.innerHTML;
                return true;
            }

            // Last fallback: replace main with raw html
            if (mainEl) { mainEl.innerHTML = html; return true; }
        } catch (e) {
            return false;
        }
        return false;
    }

    function load(state, push = true) {
        const params = new URLSearchParams();
        params.set('page', state.page || 'home');
        if (state.q) params.set('q', state.q);
        params.set('ajax','1');

        fetch('index.php?' + params.toString(), { credentials: 'same-origin' }).then(r => r.text()).then(html => {
            const applied = applyFragment(html);
            if (push) {
                const friendly = 'index.php?page=' + encodeURIComponent(state.page) + (state.q ? '&q=' + encodeURIComponent(state.q) : '');
                try { history.pushState(state, '', friendly); } catch (e) {}
            }
        }).catch(()=>{});
    }

    headerForm.addEventListener('submit', function(e){ e.preventDefault(); clearTimeout(timer); load(serialize()); });
    qInput.addEventListener('input', function(){ clearTimeout(timer); timer = setTimeout(function(){ load(serialize()); }, 300); }, { passive: true });
    window.addEventListener('popstate', function(ev){ const state = ev.state || {}; if (state.q !== undefined) { qInput.value = state.q || ''; } if (state.page !== undefined) { pageInput.value = state.page || pageInput.value; } if (state.page) load(state, false); });
})();
</script>