<?php
if (!isset($_SESSION['user'])) {
  header("Location: index.php?page=login");
  exit;
}
$user = $_SESSION['user'];
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

function navActive($nav, $page) {
  return $nav === $page ? 'bg-gray-800' : 'hover:bg-gray-700';
}
?>

<!-- Sidebar -->
<div id="sidebar"
  class="fixed inset-y-0 left-0 w-64 bg-[#0f172a] text-white flex flex-col transform -translate-x-full transition-transform duration-300 z-50 lg:translate-x-0">

  <!-- Header -->
  <div class="flex items-center justify-between h-16 border-b border-gray-700 px-4">
    <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 293.538 293.538" class="w-8 h-8">
      <g>
        <polygon points="210.084,88.631 146.622,284.844 81.491,88.631" />
        <polygon points="103.7,64.035 146.658,21.08 188.515,64.035" />
        <polygon points="55.581,88.631 107.681,245.608 0,88.631" />
        <polygon points="235.929,88.631 293.538,88.631 184.521,247.548" />
        <polygon points="283.648,64.035 222.851,64.035 168.938,8.695 219.079,8.695" />
        <polygon points="67.563,8.695 124.263,8.695 68.923,64.035 7.969,64.035" />
      </g>
    </svg>
    <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">✕</button>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
    <a href="index.php?page=dashboard"
      class="flex items-center px-4 py-2 text-sm font-medium rounded-lg <?= navActive('dashboard', $page) ?>">
      <span class="material-symbols-outlined mr-3">home</span> Dashboard
    </a>
    <a href="index.php?page=kelas"
      class="flex items-center px-4 py-2 text-sm rounded-lg <?= navActive('kelas', $page) ?>">
      <span class="material-symbols-outlined mr-3">school</span> Kelas
    </a>
    <a href="index.php?page=tugas"
      class="flex items-center px-4 py-2 text-sm rounded-lg <?= navActive('tugas', $page) ?>">
      <span class="material-symbols-outlined mr-3">assignment</span> Tugas
    </a>
    <a href="index.php?page=absensi"
      class="flex items-center px-4 py-2 text-sm rounded-lg <?= navActive('absensi', $page) ?>">
      <span class="material-symbols-outlined mr-3">event_available</span> Absensi
    </a>
    <a href="index.php?page=chat"
      class="flex items-center px-4 py-2 text-sm rounded-lg <?= navActive('chat', $page) ?>">
      <span class="material-symbols-outlined mr-3">chat</span> Chat
    </a>
    <a href="index.php?page=account"
      class="flex items-center px-4 py-2 text-sm rounded-lg <?= navActive('account', $page) ?>">
      <span class="material-symbols-outlined mr-3">list</span> Akun
    </a>
  </nav>

  <!-- User Profile (Bottom) -->
  <div class="border-t border-gray-700 relative">
    <button id="profileBtn"
      class="w-full flex items-center p-3 hover:bg-gray-800 transition-colors relative">
      <img class="w-10 h-10 rounded-full"
        src="<?= htmlspecialchars($user['avatar']); ?>"
        alt="User" />
      <div class="ml-3 text-left">
        <p class="text-sm font-medium"><?= htmlspecialchars($user['username']); ?></p>
        <p class="text-xs text-gray-400"><?= htmlspecialchars($user['level']); ?></p>
      </div>
    </button>

    <!-- Modal Profile (Dropdown Menu Style) -->
    <div id="profileModal"
      class="hidden absolute left-0 bottom-16 w-full bg-[#1f2937] rounded-t-xl shadow-xl py-2 z-[9999]">

      <!-- Email user -->
      <div class="px-4 py-2 text-sm text-gray-200 border-b border-gray-600">
        <?= htmlspecialchars($user['email']); ?>
      </div>

      <!-- Menu list -->
      <nav class="flex flex-col text-sm text-gray-200">
        <a href="index.php?page=profile"
          class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
          <span class="material-symbols-outlined mr-3">account_circle</span>
          Profil Saya
        </a>
        <a href="index.php?page=pengaturan"
          class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
          <span class="material-symbols-outlined mr-3">settings</span>
          Pengaturan
        </a>

        <hr class="my-1 border-gray-600" />

        <a href="index.php?page=docs" target ="_blank"
          class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors">
          <span class="material-symbols-outlined mr-3">help</span>
          Bantuan
        </a>
        <a href="index.php?page=logout"
          class="flex items-center px-4 py-2 hover:bg-gray-700 transition-colors text-red-400">
          <span class="material-symbols-outlined mr-3">logout</span>
          Keluar
        </a>
      </nav>
    </div>
  </div>
</div>

<script>
  // Toggle dropdown
  document.getElementById('profileBtn').addEventListener('click', function (e) {
    e.stopPropagation();
    const modal = document.getElementById('profileModal');
    modal.classList.toggle('hidden');
  });

  // Hide when click outside
  document.addEventListener('click', function (e) {
    const modal = document.getElementById('profileModal');
    const btn = document.getElementById('profileBtn');
    if (!modal.classList.contains('hidden') && !modal.contains(e.target) && !btn.contains(e.target)) {
      modal.classList.add('hidden');
    }
  });
</script>
