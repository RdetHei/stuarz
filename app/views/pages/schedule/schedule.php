<?php
// Ambil data jadwal dari controller
$schedules = $schedules ?? [];

// Kelompokkan berdasarkan hari
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$groupedSchedules = [];
foreach ($days as $day) {
    $groupedSchedules[$day] = [];
}
foreach ($schedules as $schedule) {
    $day = $schedule['day'] ?? '';
    if (isset($groupedSchedules[$day])) {
        $groupedSchedules[$day][] = $schedule;
    }
}

// Ambil filter dari query string
$selectedClass = $_GET['class_id'] ?? '';
$selectedTeacher = $_GET['teacher_id'] ?? '';
?>

<style>
  .schedule-card { 
    transition: all 0.2s ease; 
  }
  .schedule-card:hover { 
    background: rgba(55, 53, 47, 0.08);
    border-color: #5865F2;
    transform: translateY(-2px);
  }
</style>

<div class="max-w-7xl mx-auto p-6">
  <!-- Header Section -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Jadwal Pelajaran</h1>
          <p class="text-gray-400 text-sm mt-1">Semester Genap 2024/2025</p>
        </div>
      </div>

      <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
      <a href="index.php?page=schedule/create" 
         class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jadwal
      </a>
      <?php endif; ?>
    </div>

    <!-- Filter Section -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="schedule">

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Kelas</label>
          <select name="class_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Kelas</option>
            <?php
            $classQuery = $config->query("SELECT id, name FROM classes ORDER BY name");
            while ($class = $classQuery->fetch_assoc()):
            ?>
            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($class['name']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Guru</label>
          <select name="teacher_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Guru</option>
            <?php
            $teacherQuery = $config->query("SELECT id, name FROM users WHERE level='teacher' ORDER BY name");
            while ($teacher = $teacherQuery->fetch_assoc()):
            ?>
            <option value="<?= $teacher['id'] ?>" <?= $selectedTeacher == $teacher['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($teacher['name']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
          </button>

          <?php if ($selectedClass || $selectedTeacher): ?>
          <a href="index.php?page=schedule" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Reset
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <!-- Schedule Grid -->
  <div class="space-y-4">
    <?php foreach ($days as $day): ?>
    <?php if (!empty($groupedSchedules[$day])): ?>
    <div class="bg-gray-800 rounded-xl overflow-hidden border border-gray-700">
      <!-- Day Header -->
      <div class="px-6 py-4 bg-gray-900 border-b border-gray-700 border-l-4 border-l-indigo-600">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h2 class="text-xl font-bold text-white"><?= $day ?></h2>
          </div>
          <span class="bg-indigo-600/20 text-indigo-400 px-3 py-1 rounded-full text-sm font-semibold">
            <?= count($groupedSchedules[$day]) ?> Jadwal
          </span>
        </div>
      </div>

      <!-- Schedule Items -->
      <div class="p-4 space-y-3">
        <?php foreach ($groupedSchedules[$day] as $schedule): ?>
        <?php
        $badgeClass = 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30';
        $badgeText = 'Lecture';
        if (stripos($schedule['subject'], 'lab') !== false || stripos($schedule['subject'], 'praktikum') !== false) {
            $badgeClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30';
            $badgeText = 'Lab';
        } elseif (stripos($schedule['subject'], 'tutorial') !== false) {
            $badgeClass = 'bg-yellow-500/20 text-yellow-300 border-yellow-500/30';
            $badgeText = 'Tutorial';
        }
        ?>
        <div class="schedule-card p-5 bg-gray-900 rounded-lg border border-gray-700">
          <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex-1 min-w-[300px]">
              <div class="flex items-center gap-2 flex-wrap mb-3">
                <h3 class="text-lg font-semibold text-white"><?= htmlspecialchars($schedule['subject']) ?></h3>
                <span class="px-3 py-1 rounded-full text-xs font-semibold border <?= $badgeClass ?>">
                  <?= $badgeText ?>
                </span>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div class="flex items-center gap-2 text-gray-400">
                  <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span><?= htmlspecialchars($schedule['start_time']) ?> - <?= htmlspecialchars($schedule['end_time']) ?></span>
                </div>

                <div class="flex items-center gap-2 text-gray-400">
                  <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                  </svg>
                  <span>
                    <?php
                    $teacherQuery = $config->query("SELECT name FROM users WHERE id=" . intval($schedule['teacher_id']));
                    $teacher = $teacherQuery->fetch_assoc();
                    echo htmlspecialchars($teacher['name'] ?? 'N/A');
                    ?>
                  </span>
                </div>

                <div class="flex items-center gap-2 text-gray-400">
                  <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                  </svg>
                  <span><?= htmlspecialchars($schedule['class']) ?></span>
                </div>
              </div>
            </div>

            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
            <div class="flex items-center gap-2">
              <a href="index.php?page=schedule/edit/<?= $schedule['id'] ?>" 
                 class="p-2.5 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </a>
              <form method="POST" action="index.php?page=schedule/delete/<?= $schedule['id'] ?>" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
                <button type="submit" class="p-2.5 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg transition-all duration-200">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </form>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>

    <?php if (empty(array_filter($groupedSchedules))): ?>
    <!-- Empty State -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
      <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Belum Ada Jadwal</h3>
      <p class="text-gray-400 mb-6 max-w-md mx-auto">Belum ada jadwal pelajaran yang tersedia. Silakan tambahkan jadwal baru.</p>
      <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
      <a href="index.php?page=schedule/create" 
         class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jadwal Pertama
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Info Footer -->
  <div class="mt-8 bg-gray-800 border border-gray-700 rounded-xl p-6">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-white font-semibold text-lg mb-2">Informasi Penting</h3>
        <p class="text-gray-400 leading-relaxed">
          Jadwal dapat berubah sewaktu-waktu. Harap selalu cek pembaruan dari admin atau sistem notifikasi.
          Untuk pertanyaan lebih lanjut, silakan hubungi bagian akademik.
        </p>
      </div>
    </div>
  </div>
</div>