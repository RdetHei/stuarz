<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Ambil data user dari session, gunakan default jika tidak ada
$user = [
  'username' => $_SESSION['username'] ?? 'username',
  'level'    => $_SESSION['level'] ?? 'user',
  'avatar'   => $_SESSION['avatar'] ?? 'https://i.pravatar.cc/100?img=3',
  'email'    => $_SESSION['email'] ?? 'belum ada',
  'created_at' => $_SESSION['created_at'] ?? '2025'
];
?>

<!-- Sidebar -->
<div id="sidebar"
  class="fixed inset-y-0 left-0 w-64 bg-[#0f172a] text-white flex flex-col transform -translate-x-full transition-transform duration-300 z-50 lg:translate-x-0">

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
  <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
    <a href="index.php?page=dashboard" class="flex items-center px-4 py-2 text-sm font-medium bg-gray-800 rounded-lg">
      <span class="material-symbols-outlined mr-3">home</span> Dashboard
    </a>
    <a href="index.php?page=kelas" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
      <span class="material-symbols-outlined mr-3">school</span> Kelas
    </a>
    <a href="index.php?page=tugas" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
      <span class="material-symbols-outlined mr-3">assignment</span> Tugas
    </a>
    <a href="index.php?page=absensi" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
      <span class="material-symbols-outlined mr-3">event_available</span> Absensi
    </a>
    <a href="index.php?page=chat" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
      <span class="material-symbols-outlined mr-3">chat</span> Chat
    </a>
    <a href="index.php?page=pengaturan" class="flex items-center px-4 py-2 text-sm hover:bg-gray-700 rounded-lg">
      <span class="material-symbols-outlined mr-3">settings</span> Pengaturan
    </a>
  </nav>

  <!-- User Profile (Bottom) -->
  <div class="border-t border-gray-700">
    <button onclick="openProfile()"
      class="w-full flex items-center p-3 hover:bg-gray-800 transition-colors">
      <img class="w-10 h-10 rounded-full"
        src="<?= htmlspecialchars($user['avatar']); ?>"
        alt="User" />
      <div class="ml-3 text-left">
        <p class="text-sm font-medium"><?= htmlspecialchars($user['username']); ?></p>
        <p class="text-xs text-gray-400"><?= htmlspecialchars($user['level']); ?></p>
      </div>
      <span class="ml-auto material-icons text-gray-400"></span>
    </button>
  </div>
</div>

<!-- Modal Profile -->
<div id="profileModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[100]">
  <div class="bg-white rounded-2xl shadow-xl w-96 p-6">
    <div class="flex flex-col items-center">
      <img class="w-20 h-20 rounded-full mb-4" src="<?= htmlspecialchars($user['avatar']); ?>" alt="User" />
      <h2 class="text-xl font-semibold"><?= htmlspecialchars($user['username']); ?></h2>
      <p class="text-gray-500 text-sm mb-4"><?= htmlspecialchars($user['level']); ?></p>

      <div class="w-full space-y-2 text-sm">
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Bergabung Sejak:</strong> <?= htmlspecialchars($user['created_at']); ?></p>
      </div>

      <div class="mt-6 flex space-x-3">
        <a href="index.php?page=profile"
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Lihat Profile</a>
        <a href="logout.php"
          class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">Logout</a>
      </div>
    </div>
  </div>
</div>

<script>
  function openProfile() {
    document.getElementById('profileModal').classList.remove('hidden');
  }
  document.addEventListener('click', function(e) {
    const modal = document.getElementById('profileModal');
    if (!modal.classList.contains('hidden') && e.target === modal) {
      modal.classList.add('hidden');
    }
  });
</script>