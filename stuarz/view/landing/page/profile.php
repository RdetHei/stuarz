<?php
if (!isset($_SESSION['user'])) {
  header("Location: index.php?page=login");
  exit;
}

$user = $_SESSION['user'];

$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');

$defaultUser = [
  'name'            => 'Pengguna Baru',
  'class'           => 'Siswa',
  'bio'             => 'Belum ada biodata.',
  'email'           => 'belum@diisi.com',
  'phone'           => '-',
  'address'         => '-',
  'join_date'       => null,
  'tasks_completed' => 0,
  'attendance'      => 0,
  'certificates'    => 0,
  'average_grade'   => '-',
  'avatar'          => ''
];

foreach ($defaultUser as $key => $value) {
  if (!isset($user[$key]) || $user[$key] === null || $user[$key] === '') {
    $user[$key] = $value;
  }
}

$avatarVal = $user['avatar'] ?? '';
$avatarSrc = $prefix . ltrim($avatarVal ?: 'assets/default-avatar.png', '/');

$bannerVal = $user['banner'] ?? '';
$bannerSrc = $prefix . ltrim($bannerVal ?: 'assets/default-banner.png', '/');
?>
<!-- Profile Page -->
<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
  <div class="max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl overflow-hidden profile-card">
    <!-- Banner: acts as the first divider (border-bottom) -->
    <div class="relative w-full border-b border-gray-700" style="height:clamp(140px,18vw,220px);">
      <img src="<?= htmlspecialchars($bannerSrc, ENT_QUOTES, 'UTF-8') ?>"
           alt="Banner"
           class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-black/35"></div>
      <div class="absolute left-0 right-0 bottom-0 h-10 bg-gradient-to-t from-black/60 to-transparent"></div>

      <!-- Name / Class / Bio overlay on banner -->
      <div class="absolute inset-0 flex items-end sm:items-center p-4 sm:p-6">
        <div class="flex items-center gap-4">
          <!-- avatar inside banner on >=sm -->
          <div class="hidden sm:block w-32 h-32 rounded-full ring-4 ring-gray-800 overflow-hidden shadow-lg flex-shrink-0">
            <img src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" class="w-full h-full object-cover" />
          </div>

          <div class="text-white max-w-2xl">
            <h1 class="text-2xl sm:text-3xl font-bold leading-tight"><?= htmlspecialchars($user['name']) ?></h1>
            <div class="text-indigo-300 mt-1"><?= htmlspecialchars($user['class'] ?? 'Siswa') ?></div>
            <p class="mt-2 text-gray-100/90 hidden sm:block"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="p-6 pt-4">
      <!-- On small screens show avatar below banner (since banner avatar hidden) -->
      <div class="sm:hidden mb-4 flex items-center gap-4">
        <div class="w-20 h-20 rounded-full ring-4 ring-gray-800 overflow-hidden shadow-lg flex-shrink-0">
          <img src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar" class="w-full h-full object-cover" />
        </div>
        <div>
          <h2 class="text-lg font-bold text-white"><?= htmlspecialchars($user['name']) ?></h2>
          <div class="text-indigo-300 text-sm"><?= htmlspecialchars($user['class'] ?? 'Siswa') ?></div>
          <p class="mt-1 text-gray-400 text-sm"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
        </div>
      </div>

      <!-- compact details -->
      <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Email</h3>
          <p class="text-white mt-1"><?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">No Telepon</h3>
          <p class="text-white mt-1"><?= htmlspecialchars($user['phone'] ?? '-') ?></p>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Alamat</h3>
          <p class="text-white mt-1"><?= htmlspecialchars($user['address'] ?? '-') ?></p>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide">Bergabung Sejak</h3>
          <p class="text-white mt-1">
            <?= $user['join_date'] ? htmlspecialchars(date("F Y", strtotime($user['join_date']))) : '-' ?>
          </p>
        </div>
      </div>

      <hr class="border-gray-700 my-6" />

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
        <div>
          <p class="text-2xl font-bold text-white"><?= (int)$user['tasks_completed'] ?></p>
          <p class="text-gray-400 text-sm">Tugas Diselesaikan</p>
        </div>
        <div>
          <p class="text-2xl font-bold text-white"><?= (int)$user['attendance'] ?></p>
          <p class="text-gray-400 text-sm">Absensi</p>
        </div>
        <div>
          <p class="text-2xl font-bold text-white"><?= (int)$user['certificates'] ?></p>
          <p class="text-gray-400 text-sm">Sertifikat</p>
        </div>
        <div>
          <p class="text-2xl font-bold text-white"><?= htmlspecialchars($user['average_grade']) ?></p>
          <p class="text-gray-400 text-sm">Nilai Rata-rata</p>
        </div>
      </div>

      <div class="mt-6 flex justify-center gap-4">
        <a href="index.php?page=edit_user&id=<?= (int)$user['id']; ?>"
          class="px-6 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow">
          Edit Profil
        </a>
        <a href="index.php"
          class="px-6 py-2 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-semibold shadow">
          Kembali
        </a>
      </div>
    </div>
  </div>
</div>