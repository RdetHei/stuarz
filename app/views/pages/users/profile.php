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
  'avatar'          => 'default-avatar.png'
];

foreach ($defaultUser as $key => $value) {
  if (!isset($user[$key]) || $user[$key] === null || $user[$key] === '') {
    $user[$key] = $value;
  }
}

$avatarVal = $user['avatar'] ?? '';
$avatarSrc = $prefix . ltrim($avatarVal ?: '/assets/default-avatar.png', '/');

$bannerVal = $user['banner'] ?? '';
$bannerSrc = $prefix . ltrim($bannerVal ?: '/assets/default-banner.png', '/');
?>

<div class="bg-gray-900 min-h-screen py-8 px-4 lg:px-8">
  <div class="max-w-5xl mx-auto">
    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
      
      
      <div class="relative w-full h-48 sm:h-56 lg:h-72">
        <img src="<?= htmlspecialchars($bannerSrc, ENT_QUOTES, 'UTF-8') ?>"
             alt="Banner"
             class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/60"></div>

        
        <div class="absolute bottom-0 left-0 right-0 px-6 pb-6 flex items-end">
          <div class="p-1.5 rounded-full bg-gray-800">
            <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-full overflow-hidden bg-gray-700 ring-[6px] ring-gray-800">
              <img src="<?= htmlspecialchars($avatarSrc, ENT_QUOTES, 'UTF-8') ?>" 
                   alt="Avatar" 
                   class="w-full h-full object-cover" />
            </div>
          </div>
          
          
          <div class="hidden sm:block ml-6 pb-4 text-white">
            <h1 class="text-3xl lg:text-4xl font-bold mb-1"><?= htmlspecialchars($user['name']) ?></h1>
            <p class="text-gray-300 text-lg">@<?= htmlspecialchars(strtolower(str_replace(' ', '', $user['class'] ?? 'user'))) ?></p>
          </div>
        </div>
      </div>

      
      <div class="px-6 pt-6 pb-8 bg-gray-800">
        
        <div class="sm:hidden mb-6">
          <h1 class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($user['name']) ?></h1>
          <p class="text-gray-300 mb-3">@<?= htmlspecialchars(strtolower(str_replace(' ', '', $user['class'] ?? 'user'))) ?></p>
          <p class="text-gray-400"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
        </div>

        
        <div class="hidden sm:block mb-6">
          <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700">
            <h3 class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">Tentang Saya</h3>
            <p class="text-gray-300"><?= htmlspecialchars($user['bio'] ?? '') ?></p>
          </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
          
          <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              Email
            </div>
            <p class="text-white"><?= htmlspecialchars($user['email']) ?></p>
          </div>

          
          <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              No Telepon
            </div>
            <p class="text-white"><?= htmlspecialchars($user['phone'] ?? '-') ?></p>
          </div>

          
          <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Alamat
            </div>
            <p class="text-white"><?= htmlspecialchars($user['address'] ?? '-') ?></p>
          </div>

          
          <div class="bg-gray-900/50 p-4 rounded-lg border border-gray-700">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Bergabung Sejak
            </div>
            <p class="text-white">
              <?= $user['join_date'] ? htmlspecialchars(date("F Y", strtotime($user['join_date']))) : '-' ?>
            </p>
          </div>
        </div>

        <div class="border-t border-gray-700 my-6"></div>

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          
          <div class="bg-gray-900/50 rounded-lg p-5 text-center border border-gray-700">
            <p class="text-3xl lg:text-4xl font-bold text-white mb-2"><?= (int)$user['tasks_completed'] ?></p>
            <p class="text-gray-400 text-sm">Tugas Diselesaikan</p>
          </div>

          
          <div class="bg-gray-900/50 rounded-lg p-5 text-center border border-gray-700">
            <p class="text-3xl lg:text-4xl font-bold text-white mb-2"><?= (int)$user['attendance'] ?></p>
            <p class="text-gray-400 text-sm">Absensi</p>
          </div>

          
          <div class="bg-gray-900/50 rounded-lg p-5 text-center border border-gray-700">
            <p class="text-3xl lg:text-4xl font-bold text-white mb-2"><?= (int)$user['certificates'] ?></p>
            <p class="text-gray-400 text-sm">Sertifikat</p>
          </div>

          
          <div class="bg-gray-900/50 rounded-lg p-5 text-center border border-gray-700">
            <p class="text-3xl lg:text-4xl font-bold text-white mb-2"><?= htmlspecialchars($user['average_grade']) ?></p>
            <p class="text-gray-400 text-sm">Nilai Rata-rata</p>
          </div>
        </div>

        
        <div class="flex flex-col sm:flex-row justify-center gap-3">
          <a href="index.php?page=edit_user&id=<?= (int)$user['id']; ?>"
            class="px-6 py-2.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-center transition-colors duration-200">
            <span class="flex items-center justify-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit Profil
            </span>
          </a>
          
          <a href="index.php"
            class="px-6 py-2.5 rounded-md bg-gray-700 hover:bg-gray-600 text-white font-medium text-center transition-colors duration-200">
            <span class="flex items-center justify-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              Kembali
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>