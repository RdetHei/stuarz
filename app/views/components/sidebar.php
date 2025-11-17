<?php
// gunakan nama berbeda agar tidak menimpa $user dari controller
$sessionUser = $_SESSION['user'];

$page = isset($_GET['page']) && $_GET['page'] !== '' ? $_GET['page'] : 'home';
$sub  = isset($_GET['dashboard']) && $_GET['dashboard'] !== '' ? (string) $_GET['dashboard'] : null;

// groups for dropdown auto-open logic
$manajemenPages = ['account'];
$akademikPages  = ['class', 'subjects', 'schedule', 'attendance', 'grades', 'tasks', 'certificates'];
$informasiPages = ['announcement', 'notifications'];
$adminPages     = ['dashboard-admin-docs', 'dashboard-admin-news'];

function navActive(string $navPage, string $currentPage, ?string $currentSub = null): string
{
  if ($navPage === $currentPage) return 'bg-gray-800 dark:bg-gray-800 bg-gray-100 text-white dark:text-white text-gray-900';
  if ($currentPage === 'dashboard' && $currentSub !== null && $navPage === $currentSub) return 'bg-gray-800 dark:bg-gray-800 bg-gray-100 text-white dark:text-white text-gray-900';
  return 'hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200 text-white dark:text-white text-gray-900';
}

function initialsFromName(string $name, int $len = 2): string
{
  $parts = preg_split('/\s+/', trim($name));
  if (!$parts) return strtoupper(substr($name, 0, $len));
  $out = '';
  foreach ($parts as $p) {
    if ($p === '') continue;
    $out .= mb_substr($p, 0, 1);
    if (mb_strlen($out) >= $len) break;
  }
  return strtoupper($out);
}
$avatarSrc = $sessionUser['avatar'] ?? '';
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$imgPath = $avatarSrc ? $baseUrl . '/' . ltrim($avatarSrc, '/') : ''; // kosong jika tidak ada
?>

<!-- apply collapsed state early to avoid flash on refresh -->
<script>
  (function(){
    try {
      if (localStorage.getItem('sidebarCollapsed') === '1') {
        document.documentElement.classList.add('sidebar-collapsed');
      }
    } catch (e) { }
  })();
</script>

<!-- keep avatar fixed size and not scale when sidebar collapses -->
<style>
  /* avatar container fixed size */
  #sidebar .profile-avatar {
    width: 40px;
    height: 40px;
    flex: 0 0 40px;
  }

  #sidebar .profile-avatar img {
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
    border-radius: 9999px;
    display: block;
  }

  /* defensive rules if JS toggles a collapsed class */
  #sidebar.collapsed .profile-avatar,
  #sidebar.collapsed .profile-avatar img {
    width: 40px !important;
    height: 40px !important;
  }

  /* animated width/margin transitions for smooth collapse */
  #sidebar {
    /* faster, snappier collapse to remove perceived lag */
    transition: width 220ms cubic-bezier(.2,.8,.2,1), min-width 220ms cubic-bezier(.2,.8,.2,1), max-width 220ms cubic-bezier(.2,.8,.2,1);
    will-change: width;
    box-sizing: border-box; /* ensure background and borders follow width */
    overflow: hidden; /* prevent inner content from overflowing during animation */
    width: 16rem; /* w-64 equivalent */
    min-width: 16rem;
    max-width: 16rem;
  }
  /* apply same collapsed visual state early when html has the class (prevents flash) */
  html.sidebar-collapsed #sidebar {
    width: 4rem !important; /* w-16 equivalent */
    min-width: 4rem !important;
    max-width: 4rem !important;
  }
  html.sidebar-collapsed #sidebar .menu-text {
    opacity: 0;
    transform: translateX(-8px) scaleX(.96);
    max-width: 0;
    margin: 0;
    padding: 0;
    pointer-events: none;
    visibility: hidden;
  }
  html.sidebar-collapsed #sidebar .profile-avatar,
  html.sidebar-collapsed #sidebar .profile-avatar img {
    width: 40px !important;
    height: 40px !important;
  }
  /* ensure page containers follow collapsed state early */
  html.sidebar-collapsed #content { margin-left: 4rem; }
  html.sidebar-collapsed #dHeader { margin-left: 4rem; }
  /* mirror some collapsed internal rules so visuals match immediately */
  html.sidebar-collapsed #sidebar nav a .material-symbols-outlined { transform: scale(.96); opacity: .92; }
  html.sidebar-collapsed #sidebar #sidebarLogo { opacity: 0; transform: scale(.9); pointer-events: none; }
  html.sidebar-collapsed #sidebar details.sidebar-group .group-children::before { background-color: transparent; }
  html.sidebar-collapsed #sidebar .hamburger-line-1 { top: 4px; transform: rotate(0deg); }
  html.sidebar-collapsed #sidebar .hamburger-line-2 { top: 9px; opacity: 1; transform: none; }
  html.sidebar-collapsed #sidebar .hamburger-line-3 { top: 14px; transform: rotate(0deg); }
  
  /* collapsed state */
  #sidebar.collapsed {
    width: 4rem !important; /* w-16 equivalent */
    min-width: 4rem !important;
    max-width: 4rem !important;
  }
  
  /* prefer transitions on the layout containers that move when sidebar changes */
  main, #content, #dHeader {
    transition: margin-left 220ms cubic-bezier(.2,.8,.2,1);
    will-change: margin-left;
  }

  /* Ensure page content tracks the sidebar width even without JS inline styles */
  #content {
    /* default aligned to expanded sidebar (w-64 => 16rem) */
    margin-left: 16rem;
  }
  #dHeader {
    /* header (if present) aligns with content */
    margin-left: 16rem;
  }
  /* when sidebar is collapsed, reduce margins so content follows */
  #sidebar.collapsed ~ #content {
    margin-left: 4rem; /* w-16 => 4rem */
  }
  #sidebar.collapsed ~ #dHeader {
    margin-left: 4rem;
  }

  /* Responsive: on small screens, sidebar becomes off-canvas */
  /* Use 1023.98px to avoid clash at exactly 1024px where lg breakpoint begins */
  @media (max-width: 1023.98px) {
    #sidebar {
      position: fixed;
      left: -16rem; /* hidden off screen by default */
      top: 0;
      bottom: 0;
      width: 16rem !important; /* fixed width for slide-in */
      min-width: 16rem !important;
      max-width: 16rem !important;
      box-shadow: 0 0 0 rgba(0,0,0,0);
      transition: left 420ms cubic-bezier(.2,.8,.2,1), box-shadow 240ms ease;
      z-index: 50;
    }
    /* when mobile-open class is applied, slide in */
    #sidebar.mobile-open {
      left: 0;
      box-shadow: 0 0 0 9999px rgba(0,0,0,0.45); /* backdrop via big shadow, no extra element needed */
    }
    /* pre-apply mobile-open state early if html has class (prevents visual flicker on mobile) */
    html.sidebar-mobile-open #sidebar { left: 0; box-shadow: 0 0 0 9999px rgba(0,0,0,0.45); }
    html.sidebar-mobile-open #sidebar nav { opacity: 1; transform: none; }

    /* subtle fade/slide-in for the inner nav on mobile open */
    #sidebar nav { opacity: 0; transform: translateX(-6px); transition: opacity 280ms ease, transform 280ms ease; }
    #sidebar.mobile-open nav { opacity: 1; transform: none; }
    /* hide internal toggles on small screens to avoid double burger with header button */
    #sidebar #sidebarLogoToggle,
    #sidebar #sidebarToggle { display: none; }

    /* content should not be pushed on small screens */
    #content { margin-left: 0 !important; }
    #dHeader { margin-left: 0 !important; }

    /* ensure internal sidebar header icons adapt sizing */
    #sidebar .hamburger { width: 20px; height: 20px; }
    #sidebar .hamburger-line { height: 2px; }
    #sidebar .hamburger-line-1, #sidebar .hamburger-line-2, #sidebar .hamburger-line-3 { top: 9px; }
    
    /* disable collapsed state on mobile */
    #sidebar.collapsed {
      width: 16rem !important;
      min-width: 16rem !important;
      max-width: 16rem !important;
    }
  }

  /* let JS control width transition; avoid forcing width here to prevent snap */

  /* menu text: animate opacity + horizontal slide so it appears to collapse gracefully */
  .menu-text {
    display: inline-block;
    max-width: 160px; /* default max width of menu text */
    white-space: nowrap;
    overflow: hidden;
    vertical-align: middle;
    transition: max-width 220ms cubic-bezier(.2,.8,.2,1), opacity 200ms ease, transform 200ms ease, visibility 200ms ease;
    transform-origin: left center;
    opacity: 1;
    visibility: visible;
  }
  /* when collapsed, force the text to occupy zero width so it no longer affects layout */
  #sidebar.collapsed .menu-text {
    opacity: 0;
    transform: translateX(-8px) scaleX(.96);
    max-width: 0;
    margin: 0;
    padding: 0;
    pointer-events: none;
    visibility: hidden;
  }
  
  /* ensure menu text is visible when not collapsed */
  #sidebar:not(.collapsed) .menu-text {
    opacity: 1 !important;
    visibility: visible !important;
    max-width: 160px !important;
    transform: none !important;
  }

  /* also animate the menu icon subtle scale for a smoother feel */
  #sidebar nav a .material-symbols-outlined {
    transition: transform 360ms ease, opacity 360ms ease;
    transform-origin: center;
  }
  #sidebar.collapsed nav a .material-symbols-outlined {
    transform: scale(.96);
    opacity: .92;
  }

  /* logo/menu icon: use transforms and opacity instead of toggling 'hidden' */
  #sidebarLogo { transition: opacity 200ms ease, transform 200ms ease; }
  /* default (expanded): show logo */
  /* collapsed: hide logo */
  #sidebar.collapsed #sidebarLogo { opacity: 0; transform: scale(.9); pointer-events: none; }

  /* dropdown (details/summary) */
  #sidebar details.sidebar-group summary { list-style: none; cursor: pointer; }
  #sidebar details.sidebar-group summary::-webkit-details-marker { display: none; }
  #sidebar details.sidebar-group .chev { transition: transform 200ms ease, font-size 360ms ease; font-size: 1.25rem; }
  #sidebar details[open].sidebar-group .chev { transform: rotate(180deg); }
  #sidebar.collapsed details.sidebar-group .chev { font-size: 0.0rem; }
  /* smaller chevron when pre-applied class exists */
  html.sidebar-collapsed #sidebar details.sidebar-group .chev { font-size: 0.0rem; }

  /* sticky header inside sidebar + scrollable nav */
  #sidebar .sidebar-header {
    position: sticky;
    top: 0;
    z-index: 40;
    background-color: inherit; /* match sidebar background */
  }
  #sidebar nav.sidebar-scroll {
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    min-height: 0; /* enable flex child to shrink and allow scrolling */
    max-height: calc(100vh - 4rem - 4.5rem); /* fallback if flex fails: header(4rem) + approx footer */
  }

  /* vertical guideline for open groups */
  #sidebar details.sidebar-group .group-children { position: relative; }
  #sidebar details.sidebar-group .group-children::before {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    left: 1.25rem; /* closer to the summary (approx pl-5) */
    width: 2px;
    background-color: transparent;
    pointer-events: none;
    z-index: 0; /* behind children */
  }
  #sidebar details[open].sidebar-group .group-children::before {
    background-color: rgba(148,163,184,.35); /* slate-400-ish */
  }
  #sidebar details.sidebar-group .group-children > a { position: relative; z-index: 1; }
  #sidebar.collapsed details.sidebar-group .group-children::before { background-color: transparent; } /* hide when collapsed */
  /* floating panel style when sidebar is collapsed */
  .sidebar-floating-children-panel {
    position: absolute;
    z-index: 60;
    background: rgba(15,23,42,0.98); /* match sidebar dark tone */
    pointer-events: auto;
    padding: 8px 12px 8px 18px;
    border-radius: 8px;
    box-shadow: 0 6px 18px rgba(2,6,23,0.6);
    color: #e5e7eb;
    font-size: 14px;
    min-width: 180px;
    max-width: 360px;
    overflow: hidden;
  }
  /* vertical guideline inside the floating panel */
  .sidebar-floating-children-panel::before {
    content: "";
    position: absolute;
    left: 10px;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: rgba(148,163,184,.20);
    border-radius: 2px;
    pointer-events: none;
  }
  /* caret/arrow connecting panel to sidebar */
  .sidebar-floating-children-panel::after {
    content: "";
    position: absolute;
    left: -6px;
    top: 14px;
    width: 0;
    height: 0;
    border-top: 8px solid transparent;
    border-bottom: 8px solid transparent;
    border-right: 8px solid rgba(15,23,42,0.98);
    pointer-events: none;
  }
  .sidebar-floating-children-panel .group-children > a { display: block; padding-left: 18px; color: #d1d5db; }

  /* Popup card for collapsed sidebar (new system) */
  .sidebar-popup-card {
    position: absolute;
    z-index: 80;
    background: rgba(15,23,42,0.98);
    color: #e5e7eb;
    padding: 8px 10px;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(2,6,23,0.6);
    transform-origin: left top;
    opacity: 0;
    transform: scale(.96) translateY(-6px);
    transition: opacity 180ms ease, transform 180ms cubic-bezier(.2,.8,.2,1);
    pointer-events: auto;
    min-width: 180px;
    max-width: 360px;
  }
  .sidebar-popup-card.show { opacity: 1; transform: scale(1) translateY(0); }
  .sidebar-popup-card .sidebar-popup-list { display: flex; flex-direction: column; gap: 6px; }
  .sidebar-popup-card .sidebar-popup-item { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 6px; color: #e5e7eb; text-decoration: none; }
  .sidebar-popup-card .sidebar-popup-item:hover { background: rgba(255,255,255,0.03); }
  .sidebar-popup-card .sidebar-popup-item .menu-text { display: inline-block; opacity: 1; }

  /* hide scrollbar for sidebar nav while keeping scroll functionality */
  #sidebar nav.sidebar-scroll { scrollbar-width: none; -ms-overflow-style: none; }
  #sidebar nav.sidebar-scroll::-webkit-scrollbar { display: none; }

  /* animated hamburger -> X for sidebar toggle (smoother) */
  #sidebar .hamburger {
    position: relative;
    width: 20px;
    height: 20px;
  }
  #sidebar .hamburger-line {
    position: absolute;
    left: 2px;
    right: 2px;
    height: 2px;
    background-color: currentColor;
    border-radius: 2px;
    transition: transform 520ms cubic-bezier(.2,.8,.2,1), opacity 520ms ease, top 520ms ease;
  }
  /* default state (expanded): X */
  #sidebar .hamburger-line-1 { top: 9px; transform: rotate(45deg); }
  #sidebar .hamburger-line-2 { top: 9px; opacity: 0; transform: scaleX(0.4); }
  #sidebar .hamburger-line-3 { top: 9px; transform: rotate(-45deg); }
  /* collapsed state: hamburger */
  #sidebar.collapsed .hamburger-line-1 { top: 4px; transform: rotate(0deg); }
  #sidebar.collapsed .hamburger-line-2 { top: 9px; opacity: 1; transform: none; }
  #sidebar.collapsed .hamburger-line-3 { top: 14px; transform: rotate(0deg); }

</style>

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 bg-[#0f172a] opacity-100 text-white flex flex-col z-50 border-r border-gray-700 dark:border-gray-700 border-gray-200">

  <!-- Header -->
  <div class="sidebar-header relative flex items-center justify-center h-16 border-b border-gray-700 dark:border-gray-700 border-gray-200">
    <button id="sidebarLogoToggle" class="absolute left-6 top-4 text-white dark:text-white text-gray-900 focus:outline-none" aria-label="Toggle sidebar logo/menu">
      <!-- Treat this as a brand mark; keep it visible only when expanded -->
      <svg id="sidebarLogo" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="w-8 h-8" aria-hidden="true">
        <g>
          <polygon points="210.084,88.631 146.622,284.844 81.491,88.631" />
          <polygon points="103.7,64.035 146.658,21.08 188.515,64.035" />
          <polygon points="55.581,88.631 107.681,245.608 0,88.631" />
          <polygon points="235.929,88.631 293.538,88.631 184.521,247.548" />
          <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695" />
          <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035" />
        </g>
      </svg>
    </button>

    <button id="sidebarToggle" class="absolute right-4 top-4 text-white dark:text-white text-gray-900 focus:outline-none hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200 rounded p-1 transition-colors" aria-label="Toggle sidebar">
      <div class="hamburger" aria-hidden="true">
        <span class="hamburger-line hamburger-line-1"></span>
        <span class="hamburger-line hamburger-line-2"></span>
        <span class="hamburger-line hamburger-line-3"></span>
      </div>
    </button>

  </div>

  <!-- Navigation -->
  <nav class="sidebar-scroll flex-1 px-2 py-4 space-y-2">
    <!-- Utama -->
    <div class="px-3 mt-1 mb-1">
      <div class="menu-text text-[10px] tracking-wider uppercase text-gray-400">Utama</div>
    </div>
    <a href="index.php?page=dashboard-admin" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?= navActive('dashboard-admin', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">home</span>
      <span class="menu-text">Dashboard</span>
    </a>

    <!-- Manajemen (dropdown) -->
<?php if (($sessionUser['level'] ?? '') === 'admin'): ?>
    <details class="sidebar-group" <?= in_array($page, $manajemenPages, true) ? 'open' : '' ?>>
      <summary class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200 <?= navActive('account', $page, $sub)?>">
        <span class="material-symbols-outlined mr-3">group</span>
        <a href="index.php?page=account" class="menu-text hover:underline">Pengguna</a>
        <span class="ml-auto material-symbols-outlined chev">expand_more</span>
      </summary>
        <div class="mt-1 space-y-1 pl-10 group-children">
        <a href="index.php?page=students" title="Siswa" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200 <?= navActive('students', $page, $sub)?>">
          <span class="material-symbols-outlined mr-3">school</span>
          <span class="menu-text">Siswa</span>
        </a>
        <a href="index.php?page=teachers" title="Guru" class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200 <?= navActive('teachers', $page, $sub)?>">
          <span class="material-symbols-outlined mr-3">co_present</span>
          <span class="menu-text">Guru</span>
        </a>
      </div>
    </details>
    <?php endif; ?>

    <!-- Akademik (dropdown) -->
    <details class="sidebar-group" <?= in_array($page, $akademikPages, true) ? 'open' : '' ?>>
      <summary class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200">
        <span class="material-symbols-outlined mr-3">school</span>
        <span class="menu-text">Akademik</span>
        <span class="ml-auto material-symbols-outlined chev">expand_more</span>
      </summary>
      <div class="mt-1 space-y-1 pl-10 group-children">
        <a href="index.php?page=class" title="Kelas" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('class', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">school</span>
          <span class="menu-text">Kelas</span>
        </a>
        <a href="index.php?page=subjects" title="Mata Pelajaran" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('subjects', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">menu_book</span>
          <span class="menu-text">Mata Pelajaran</span>
        </a>
        <a href="index.php?page=schedule" title="Jadwal" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('schedule', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">calendar_month</span>
          <span class="menu-text">Jadwal</span>
        </a>
        <a href="index.php?page=attendance" title="Absensi" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('attendance', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">how_to_reg</span>
          <span class="menu-text">Absensi</span>
        </a>
        <a href="index.php?page=grades" title="Nilai" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('grades', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">bar_chart</span>
          <span class="menu-text">Nilai</span>
        </a>
        <a href="index.php?page=tasks" title="Tugas" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('tasks', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">assignment</span>
          <span class="menu-text">Tugas</span>
        </a>
        <a href="index.php?page=certificates&scope=all" title="Sertifikat" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('certificates', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">workspace_premium</span>
          <span class="menu-text">Sertifikat</span>
        </a>
      </div>
    </details>

    <!-- Informasi (dropdown) -->
    <details class="sidebar-group" <?= in_array($page, $informasiPages, true) ? 'open' : '' ?>>
      <summary class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200">
        <span class="material-symbols-outlined mr-3">info</span>
        <span class="menu-text">Informasi</span>
        <span class="ml-auto material-symbols-outlined chev">expand_more</span>
      </summary>
      <div class="mt-1 space-y-1 pl-10 group-children">
        <a href="index.php?page=announcement" title="Pengumuman" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('announcement', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">campaign</span>
          <span class="menu-text">Pengumuman</span>
        </a>
        <a href="index.php?page=notifications" title="Notifikasi" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('notifications', $page, $sub) ?>">
          <span class="material-symbols-outlined mr-3">notifications</span>
          <span class="menu-text">Notifikasi</span>
        </a>
      </div>
    </details>

    <!-- Admin -->
    <?php if (($sessionUser['level'] ?? '') === 'admin'): ?>
      <details class="sidebar-group" <?= in_array($page, $adminPages, true) ? 'open' : '' ?>>
        <summary class="flex items-center px-3 py-2 text-sm rounded-lg hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-200">
          <span class="material-symbols-outlined mr-3">admin_panel_settings</span>
          <span class="menu-text">Admin</span>
          <span class="ml-auto material-symbols-outlined chev">expand_more</span>
        </summary>
        <div class="mt-1 space-y-1 pl-10 group-children">
          <a href="index.php?page=dashboard-admin-docs" title="Docs" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('dashboard-admin-docs', $page, $sub) ?>">
            <span class="material-symbols-outlined mr-3">library_books</span>
            <span class="menu-text">Docs</span>
          </a>
          <a href="index.php?page=dashboard-admin-news" title="News" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('dashboard-admin-news', $page, $sub) ?>">
            <span class="material-symbols-outlined mr-3">newspaper</span>
            <span class="menu-text">News</span>
          </a>
        </div>
      </details>
    <?php endif; ?>
  </nav>

  <!-- User Profile (Bottom) -->
  <div class="border-t border-gray-700 dark:border-gray-700 border-gray-200 relative py-4 px-1">
    <button id="profileBtn" class="w-full flex items-center gap-3 px-2 py-2 rounded-md hover:bg-gray-800 dark:hover:bg-gray-800 hover:bg-gray-200 transition-colors focus:outline-none" aria-haspopup="true" aria-expanded="false" title="<?= htmlspecialchars($sessionUser['username'] ?? ($sessionUser['name'] ?? 'User')) ?>">
      <?php
      $userLocal = $sessionUser; // local alias for clarity in template
      $avatar = $userLocal['avatar'] ?? '';

      // compute base URL same as profile
      $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
      if ($baseUrl === '/') $baseUrl = '';

      $avatarTrim = ltrim((string)$avatar, '/');
      $isRemote = filter_var($avatarTrim, FILTER_VALIDATE_URL) !== false || preg_match('#^https?://#i', $avatar);
      $projectRoot = dirname(__DIR__, 3); // c:\xampp\htdocs\stuarz
      $fsCandidate = $projectRoot . '/public/' . $avatarTrim;
      $imgValid = $isRemote || ($avatarTrim !== '' && is_file($fsCandidate));

      $imgSrc = '';
      if ($isRemote) {
        $imgSrc = $avatarTrim;
      } elseif ($avatarTrim !== '') {
        $imgSrc = ($baseUrl !== '' ? $baseUrl . '/' : '/') . $avatarTrim;
      } else {
        $imgSrc = ($baseUrl !== '' ? $baseUrl . '/' : '/') . 'assets/default-avatar.png';
      }
      ?>

      <?php if ($imgValid): ?>
        <div class="profile-avatar">
          <img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>" alt="User" />
        </div>
      <?php else: ?>
        <div class="profile-avatar inline-flex items-center justify-center bg-indigo-600 text-white font-bold">
          <?= htmlspecialchars(initialsFromName($userLocal['username'] ?? ($userLocal['name'] ?? 'User'), 2), ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <div class="ml-1 text-left menu-text">
        <p class="text-sm font-medium"><?= htmlspecialchars($userLocal['username'] ?? '') ?></p>
        <p class="text-xs text-gray-400"><?= htmlspecialchars($userLocal['level'] ?? '') ?></p>
      </div>

      <div id="profileModal" class="hidden z-[9999] w-60 bg-[#1f2937] dark:bg-[#1f2937] bg-white rounded-t-xl shadow-xl border border-gray-700 dark:border-gray-700 border-gray-200">
        <div class="px-2 py-2 text-sm text-gray-200 dark:text-gray-200 text-gray-900 border-b border-gray-600 dark:border-gray-600 border-gray-200">
          <?= htmlspecialchars($userLocal['email'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <nav class="flex flex-col text-sm text-gray-200 dark:text-gray-200 text-gray-900">
          <a href="index.php?page=profile" class="flex items-center px-4 py-2 hover:bg-gray-700 dark:hover:bg-gray-700 hover:bg-gray-100 transition-colors">
            <span class="material-symbols-outlined mr-3">account_circle</span>
            Profil Saya
          </a>
          <a href="index.php?page=settings" class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
            <span class="material-symbols-outlined mr-3">settings</span>
            Pengaturan
          </a>
          <hr class="my-1 border-gray-600" />
          <a href="index.php?page=docs" target="_blank" class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
            <span class="material-symbols-outlined mr-3">help</span>
            Bantuan
          </a>
          <a href="index.php?page=logout" class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors text-red-400">
            <span class="material-symbols-outlined mr-3">logout</span>
            Keluar
          </a>
        </nav>
      </div>
    </button>
  </div>
</div>
<!-- akhir sidebar HTML -->
<?php
// compute baseUrl (file sudah punya $baseUrl earlier); jika belum ada, compute here:
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>
<script src="<?= ($baseUrl !== '' ? $baseUrl . '/' : '/') ?>js/sidebar.js" defer></script>