<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php?page=login");
  exit;
}

// gunakan nama berbeda agar tidak menimpa $user dari controller
$sessionUser = $_SESSION['user'];

$page = isset($_GET['page']) && $_GET['page'] !== '' ? $_GET['page'] : 'home';
$sub  = isset($_GET['dashboard']) && $_GET['dashboard'] !== '' ? (string) $_GET['dashboard'] : null;

function navActive(string $navPage, string $currentPage, ?string $currentSub = null): string
{
  if ($navPage === $currentPage) return 'bg-gray-800';
  if ($currentPage === 'dashboard' && $currentSub !== null && $navPage === $currentSub) return 'bg-gray-800';
  return 'hover:bg-gray-700';
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

  /* animated hamburger -> X for sidebar toggle */
  #sidebar .hamburger {
    position: relative;
    width: 24px;
    height: 24px;
  }
  #sidebar .hamburger-line {
    position: absolute;
    left: 2px;
    right: 2px;
    height: 2px;
    background-color: currentColor;
    border-radius: 2px;
    transition: transform 200ms ease, opacity 200ms ease;
  }
  /* default state (expanded): show X */
  #sidebar .hamburger-line-1 {
    top: 11px;
    transform: rotate(45deg);
  }
  #sidebar .hamburger-line-2 {
    top: 11px;
    opacity: 0;
    transform: scaleX(0.4);
  }
  #sidebar .hamburger-line-3 {
    top: 11px;
    transform: rotate(-45deg);
  }
  /* collapsed state: show hamburger */
  #sidebar.collapsed .hamburger-line-1 {
    top: 6px;
    transform: rotate(0deg);
  }
  #sidebar.collapsed .hamburger-line-2 {
    top: 11px;
    opacity: 1;
    transform: none;
  }
  #sidebar.collapsed .hamburger-line-3 {
    top: 16px;
    transform: rotate(0deg);
  }
</style>

<!-- Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-[#0f172a] text-white flex flex-col transition-all duration-300 z-50">

  <!-- Header -->
  <div class="relative flex items-center justify-center h-16 border-b border-gray-700">
    <button id="sidebarLogoToggle" class="absolute left-6 top-4 text-white focus:outline-none" aria-label="Toggle sidebar logo/menu">
      <svg id="sidebarLogo" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="w-8 h-8">
        <g>
          <polygon points="210.084,88.631 146.622,284.844 81.491,88.631" />
          <polygon points="103.7,64.035 146.658,21.08 188.515,64.035" />
          <polygon points="55.581,88.631 107.681,245.608 0,88.631" />
          <polygon points="235.929,88.631 293.538,88.631 184.521,247.548" />
          <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695" />
          <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035" />
        </g>
      </svg>
      <svg id="sidebarMenuIcon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>

    <button id="sidebarToggle" class="absolute right-2 text-white focus:outline-none" aria-label="Toggle sidebar internal">
      <div class="hamburger" aria-hidden="true">
        <span class="hamburger-line hamburger-line-1"></span>
        <span class="hamburger-line hamburger-line-2"></span>
        <span class="hamburger-line hamburger-line-3"></span>
      </div>
    </button>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-2 py-4 space-y-2">
    <a href="index.php?page=dashboard" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg <?= navActive('dashboard', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">home</span>
      <span class="menu-text">Dashboard</span>
    </a>

    <a href="index.php?page=account" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('account', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">group</span>
      <span class="menu-text">Pengguna</span>
    </a>

    <!-- Kelas direct link -->
    <a href="index.php?page=class" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('class', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">school</span>
      <span class="menu-text">Kelas</span>
    </a>

    <a href="index.php?page=grades" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('nilai', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">bar_chart</span>
      <span class="menu-text">Nilai</span>
    </a>

    <a href="index.php?page=certificates" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('certificates', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">workspace_premium</span>
      <span class="menu-text">Sertifikat</span>
    </a>

    <a href="index.php?page=announcements" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('announcements', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">campaign</span>
      <span class="menu-text">Pengumuman</span>
    </a>

    <?php if (($sessionUser['level'] ?? '') === 'admin'): ?>
      <a href="index.php?page=dashboard-admin-docs" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('dashboard-admin-docs', $page, $sub) ?>">
        <span class="material-symbols-outlined mr-3">library_books</span>
        <span class="menu-text">Docs</span>
      </a>
      <a href="index.php?page=dashboard-admin-news" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('dashboard-admin-news', $page, $sub) ?>">
        <span class="material-symbols-outlined mr-3">newspaper</span>
        <span class="menu-text">News</span>
      </a>
    <?php endif; ?>

    <a href="index.php?page=chat" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActive('chat', $page, $sub) ?>">
      <span class="material-symbols-outlined mr-3">chat</span>
      <span class="menu-text">Chat</span>
    </a>
  </nav>

  <!-- User Profile (Bottom) -->
  <div class="border-t border-gray-700 relative py-4 px-1">
    <button id="profileBtn" class="w-full flex items-center gap-3 px-2 py-2 rounded-md hover:bg-gray-800 transition-colors focus:outline-none" aria-haspopup="true" aria-expanded="false" title="<?= htmlspecialchars($sessionUser['username'] ?? ($sessionUser['name'] ?? 'User')) ?>">
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

      <div id="profileModal" class="hidden z-[9999] w-60 bg-[#1f2937] rounded-t-xl shadow-xl">
        <div class="px-2 py-2 text-sm text-gray-200 border-b border-gray-600">
          <?= htmlspecialchars($userLocal['email'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <nav class="flex flex-col text-sm text-gray-200">
          <a href="index.php?page=profile" class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
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