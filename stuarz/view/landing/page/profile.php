<?php
if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

$user = $_SESSION['user'];

// Default data
$defaultUser = [
    'profile_picture' => 'https://i.pravatar.cc/200',
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
    'average_grade'   => '-'
];

// Ganti nilai NULL/empty string dari DB dengan default
foreach ($defaultUser as $key => $value) {
    if (!isset($user[$key]) || $user[$key] === null || $user[$key] === '') {
        $user[$key] = $value;
    }
}
?>


<!-- Profile Page -->
<div class="bg-gray-900 min-h-screen py-12 px-6 lg:px-8">
  <div class="max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-xl p-8">
    <!-- Header Profile -->
    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
      <!-- Foto Profil -->
      <img src="<?= htmlspecialchars($user['profile_picture'] ?? 'https://i.pravatar.cc/200') ?>" 
           alt="Foto Profil" 
           class="w-40 h-40 rounded-full ring-4 ring-gray-700 shadow-lg" />

      <!-- Info Utama -->
      <div class="text-center sm:text-left">
        <h2 class="text-3xl font-bold text-white"><?= htmlspecialchars($user['name']) ?></h2>
        <p class="text-indigo-400 mt-1"><?= htmlspecialchars($user['class'] ?? 'Siswa') ?></p>
        <p class="text-gray-400 mt-3 max-w-md">
          <?= htmlspecialchars($user['bio'] ?? 'Siswa aktif di SMA Stuarz.') ?>
        </p>
      </div>
    </div>

    <!-- Divider -->
    <hr class="border-gray-700 my-8" />

    <!-- Detail Info -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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

    <!-- Divider -->
    <hr class="border-gray-700 my-8" />

    <!-- Statistik -->
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

    <!-- Tombol Aksi -->
    <div class="mt-10 flex justify-center gap-4">
      <a href="index.php?page=edit-profile" 
         class="px-6 py-3 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold shadow">
        Edit Profil
      </a>
      <a href="index.php" 
         class="px-6 py-3 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-semibold shadow">
        Kembali
      </a>
    </div>
  </div>
</div>
