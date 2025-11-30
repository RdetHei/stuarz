<?php
// Enhanced student sidebar matching admin/guru style but limited to student features
if (session_status() === PHP_SESSION_NONE) session_start();
$sessionUser = $_SESSION['user'] ?? [];
$page = isset($_GET['page']) && $_GET['page'] !== '' ? $_GET['page'] : 'dashboard';

function navActiveUser($navPage, string $currentPage): string
{
  $candidates = is_array($navPage) ? $navPage : [$navPage];
  foreach ($candidates as $nav) {
    if ($nav === $currentPage) return 'bg-gray-800 bg-gray-100 text-white text-gray-900';
    // support student routes like student/tasks
    if (strpos($currentPage, 'student/') === 0) {
      $parts = explode('/', $currentPage);
      if (($parts[1] ?? '') === $nav) return 'bg-gray-800 bg-gray-100 text-white text-gray-900';
    }
  }
  return 'hover:bg-gray-700 hover:bg-gray-200 text-gray-300';
}

function initialsFromNameUser(string $name, int $len = 2): string
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

$avatar = $sessionUser['avatar'] ?? '';
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$avatarTrim = ltrim((string)$avatar, '/');
$isRemote = filter_var($avatarTrim, FILTER_VALIDATE_URL) !== false || preg_match('#^https?://#i', $avatar);
$projectRoot = dirname(__DIR__, 3);
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

<div id="sidebar" class="fixed inset-y-0 left-0 bg-[#0f172a] text-white flex flex-col z-50 border-r border-gray-700">

  <div class="sidebar-header relative flex items-center justify-center h-16 border-b border-gray-700">
    <div id="sidebarLogo" class="flex items-center gap-2">
      <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="w-8 h-8" aria-hidden="true">
        <g>
          <polygon points="210.084,88.631 146.622,284.844 81.491,88.631" />
          <polygon points="103.7,64.035 146.658,21.08 188.515,64.035" />
        </g>
      </svg>
      <span class="menu-text text-white font-semibold">Stuarz</span>
    </div>
    <button id="sidebarToggle" class="absolute right-4 top-4 text-white focus:outline-none hover:bg-gray-700 rounded p-1 transition-colors" aria-label="Toggle sidebar">
      <div class="hamburger" aria-hidden="true">
        <span class="hamburger-line hamburger-line-1"></span>
        <span class="hamburger-line hamburger-line-2"></span>
        <span class="hamburger-line hamburger-line-3"></span>
      </div>
    </button>
  </div>

  <nav class="sidebar-scroll flex-1 px-2 py-4 space-y-2">
    <div class="px-3 mt-1 mb-1"><div class="menu-text text-[10px] tracking-wider uppercase text-gray-400">Utama</div></div>
    <a href="index.php?page=dashboard" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActiveUser('dashboard', $page) ?>">
      <span class="material-symbols-outlined mr-3">home</span>
      <span class="menu-text">Dashboard</span>
    </a>

    <div class="px-3 mt-3 mb-1"><div class="menu-text text-[10px] tracking-wider uppercase text-gray-400">Akademik</div></div>
    <a href="index.php?page=student/tasks" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActiveUser('tasks', $page) ?>">
      <span class="material-symbols-outlined mr-3">assignment</span>
      <span class="menu-text">Tugas Saya</span>
    </a>
    <a href="index.php?page=student/attendance" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActiveUser('attendance', $page) ?>">
      <span class="material-symbols-outlined mr-3">how_to_reg</span>
      <span class="menu-text">Kehadiran</span>
    </a>

    <div class="px-3 mt-3 mb-1"><div class="menu-text text-[10px] tracking-wider uppercase text-gray-400">Informasi</div></div>
    <a href="index.php?page=student/notifications" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActiveUser('notifications', $page) ?>">
      <span class="material-symbols-outlined mr-3">notifications</span>
      <span class="menu-text">Notifikasi</span>
    </a>
    <a href="index.php?page=student/profile" class="flex items-center px-3 py-2 text-sm rounded-lg <?= navActiveUser('profile', $page) ?>">
      <span class="material-symbols-outlined mr-3">account_circle</span>
      <span class="menu-text">Profil</span>
    </a>
  </nav>

  <div class="border-t border-gray-700 relative py-4 px-1">
    <button id="profileBtn" class="w-full flex items-center gap-3 px-2 py-2 rounded-md hover:bg-gray-800 transition-colors focus:outline-none" aria-haspopup="true" aria-expanded="false" title="<?= htmlspecialchars($sessionUser['username'] ?? ($sessionUser['name'] ?? 'User')) ?>">
      <?php if ($imgValid): ?>
        <div class="profile-avatar"><img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>" alt="User" /></div>
      <?php else: ?>
        <div class="profile-avatar inline-flex items-center justify-center bg-indigo-600 text-white font-bold"><?= htmlspecialchars(initialsFromNameUser($sessionUser['username'] ?? ($sessionUser['name'] ?? 'User'), 2), ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>
      <div class="ml-1 text-left menu-text"><p class="text-sm font-medium"><?= htmlspecialchars($sessionUser['username'] ?? $sessionUser['name'] ?? '') ?></p><p class="text-xs text-gray-400"><?= htmlspecialchars($sessionUser['level'] ?? 'user') ?></p></div>
    </button>
  </div>
</div>
<script src="<?= ($baseUrl !== '' ? $baseUrl . '/' : '/') ?>js/sidebar.js" defer></script>
