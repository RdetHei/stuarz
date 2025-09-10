<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ambil user dari session
$user = $_SESSION['user'] ?? [
    'username' => 'Guest',
    'role'     => 'murid',
    'avatar'   => 'https://i.pravatar.cc/100?img=3'
];
?>
<div>
  <!-- Sidebar -->
  <div id="sidebar"
    class="fixed inset-y-0 left-0 w-64 bg-[#0f172a] text-white transform -translate-x-full transition-transform duration-300 z-50 lg:translate-x-0">
    
    <!-- Header -->
    <div class="flex items-center justify-between h-16 border-b border-gray-700 px-4">
      <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 293.538 293.538" class="w-8 h-8">
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
    <nav class="flex-1 px-2 py-4 space-y-2">
      <a href="dashboard.php" class="flex items-center px-4 py-2 text-sm font-medium bg-gray-800 rounded-lg">
        <span class="material-icons mr-3">home</span> Dashboard
      </a>
      <a href="classes.php" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
        <span class="material-icons mr-3">school</span> Kelas
      </a>
      <a href="assignments.php" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
        <span class="material-icons mr-3">assignment</span> Tugas
      </a>
      <a href="attendance.php" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
        <span class="material-icons mr-3">event_available</span> Absensi
      </a>
      <a href="chat.php" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
        <span class="material-icons mr-3">chat</span> Chat
      </a>
      <a href="settings.php" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
        <span class="material-icons mr-3">settings</span> Pengaturan
      </a>
    </nav>

    <!-- User Profile -->
    <div class="flex items-center p-4 border-t border-gray-700">
      <img class="w-10 h-10 rounded-full" src="<?= htmlspecialchars($user['avatar']); ?>" alt="User" />
      <div class="ml-3">
        <p class="text-sm font-medium"><?= htmlspecialchars($user['username']); ?></p>
        <p class="text-xs text-gray-400 capitalize"><?= htmlspecialchars($user['role']); ?></p>
      </div>
    </div>
  </div>
</div>

<!-- Tambahkan Google Material Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script>
  function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    sidebar.classList.toggle("-translate-x-full");
  }
</script>
