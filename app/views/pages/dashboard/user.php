<?php
$user = $user ?? [
    'name' => 'Nama User',
    'username' => 'username',
    'level' => 'siswa',
    'joined' => '2024-01-12',
    'class' => 'X IPA 2',
    'bio' => 'Pelajar antusias yang aktif di klub sains.'
];

$stats = $stats ?? [
    'tasks_completed' => 18,
    'attendance_present' => 22,
    'attendance_total' => 22,
    'certificates' => 5,
    'average_grade' => 88
];

$activities = $activities ?? [
    ['title' => "Tugas Matematika diselesaikan", 'meta' => "'Persamaan Kuadrat' • 2 jam lalu", 'time' => '2h'],
    ['title' => "Absensi: Hadir", 'meta' => "Kelas Matematika • Hari ini", 'time' => 'Today'],
    ['title' => "Sertifikat baru diterima", 'meta' => "Best Attendance • 3 hari lalu", 'time' => '3d']
];


if (!isset($schedule) || !is_array($schedule)) {
    $schedule = [];
}

$attendanceChart = $attendanceChart ?? [88,8,4];

?>

<main class="max-w-7xl mx-auto p-6 bg-gray-900 min-h-screen">
  
  <section class="bg-gradient-to-br from-gray-800 via-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 mb-6 shadow-xl hover:shadow-2xl transition-shadow">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <div class="relative">
        <img src="<?= htmlspecialchars($user['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=5865f2&color=fff')) ?>"
             alt="avatar" class="w-28 h-28 rounded-full border-4 border-gray-700 object-cover flex-shrink-0 shadow-lg ring-4 ring-blue-500/20">
        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-4 border-gray-800"></div>
      </div>
      
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($user['name']) ?></h1>
          <span class="px-3 py-1 rounded-full bg-green-500/10 border border-green-500/30 text-green-400 text-xs font-semibold">Active Student</span>
        </div>
        
        <div class="text-sm text-gray-400 mb-3">
          <span class="text-blue-400 font-medium">@<?= htmlspecialchars($user['username']) ?></span> • 
          <span class="capitalize"><?= htmlspecialchars($user['level']) ?></span>
        </div>
        
        <div class="flex flex-wrap gap-4 text-xs text-gray-500 mb-3">
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-400">Joined <?= isset($user['joined']) ? htmlspecialchars($user['joined']) : 'N/A' ?></span>
          </div>
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="text-gray-400">Class: <span class="text-white font-medium"><?= htmlspecialchars($user['class'] ?? 'N/A') ?></span></span>
          </div>
        </div>
        
        <?php if (!empty($user['bio'])): ?>
          <p class="text-sm text-gray-300 max-w-2xl leading-relaxed"><?= htmlspecialchars($user['bio']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <a href="index.php?page=student/tasks" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-blue-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Tasks Done</span>
        <div class="p-3 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 transition-colors">
          <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$stats['tasks_completed'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Completed this month</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=student/attendance" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-purple-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Attendance</span>
        <div class="p-3 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 transition-colors">
          <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$stats['attendance_present'] ?><span class="text-lg text-gray-500"> / <?= (int)$stats['attendance_total'] ?></span></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Present days</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=certificates&scope=all" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-emerald-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Certificates</span>
        <div class="p-3 rounded-xl bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-colors">
          <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$stats['certificates'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Earned</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=grades" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-amber-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Avg. Grade</span>
        <div class="p-3 rounded-xl bg-amber-500/10 group-hover:bg-amber-500/20 transition-colors">
          <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$stats['average_grade'] ?><span class="text-lg text-gray-500">%</span></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Semester average</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>
  </section>

  <?php
  $announcementsList = [];
  if (!empty($latestAnnouncements) && is_array($latestAnnouncements)) {
      $announcementsList = $latestAnnouncements;
  } elseif (!empty($latestAnnouncement)) {
      $announcementsList = [ $latestAnnouncement ];
  }
  ?>

  <?php if (!empty($announcementsList)): ?>
  <div class="mb-8">
      <div class="flex items-center justify-between mb-4">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
              <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
              </svg>
              Pengumuman Terbaru
          </h2>
          <a href="index.php?page=announcement" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Lihat semua →</a>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
          <?php foreach ($announcementsList as $a): ?>
          <a href="index.php?page=announcement&id=<?= (int)($a['id'] ?? 0) ?>"
             class="group bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-blue-500/50 hover:bg-gray-750 transition-all">

              <div class="flex items-center gap-3 mb-3">
                  <?php
                      $creatorAvatar = $a['creator_avatar'] ?? 'assets/default-avatar.png';
                      $creatorAvatarSrc = ($prefix ?? '') . ltrim($creatorAvatar, '/');
                  ?>
                  <img src="<?= htmlspecialchars($creatorAvatarSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Creator Avatar" class="w-9 h-9 rounded-full border border-gray-700 object-cover" />
                  <div>
                      <div class="text-sm font-semibold text-white"><?= htmlspecialchars($a['creator'] ?? '-') ?></div>
                      <div class="text-xs text-gray-500"><?= htmlspecialchars(date('d F Y', strtotime($a['created_at'] ?? ''))) ?></div>
                  </div>
              </div>

              <?php if (!empty($a['photo'])): ?>
              <div class="w-full h-32 bg-gray-900 rounded-lg overflow-hidden mb-4 border border-gray-700">
                  <img src="<?= htmlspecialchars(($prefix ?? '') . ltrim($a['photo'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                       alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
              </div>
              <?php else: ?>
              <div class="w-full h-32 rounded-lg bg-gradient-to-br from-blue-500/10 to-purple-500/10 flex items-center justify-center border border-blue-500/20 mb-4">
                  <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                  </svg>
              </div>
              <?php endif; ?>

              <div class="flex items-start justify-between gap-2 mb-2">
                  <span class="px-2 py-1 rounded-md bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold uppercase tracking-wider">Pengumuman</span>
                  <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-400 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                  </svg>
              </div>

              <h4 class="text-sm font-semibold text-white mb-2 line-clamp-2 group-hover:text-blue-400 transition-colors">
                  <?= htmlspecialchars($a['title'] ?? '-') ?>
              </h4>

              <div class="flex items-center gap-2 text-xs text-gray-500">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <?= htmlspecialchars(date('d F Y', strtotime($a['created_at'] ?? ''))) ?>
              </div>
          </a>
          <?php endforeach; ?>
      </div>
  </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 space-y-6">
      
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Aktivitas Terbaru
          </h2>
          <a href="index.php?page=student/tasks" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">Lihat semua →</a>
        </div>
        
        <div class="space-y-3">
          <?php if (empty($activities)): ?>
          <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm">No recent activities</p>
          </div>
          <?php else: ?>
          <?php foreach ($activities as $act): ?>
          <div class="bg-gray-750 border border-gray-700 hover:border-gray-600 rounded-lg p-4 transition-all group">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-start gap-3 flex-1">
                <div class="mt-0.5 p-2 rounded-lg bg-blue-500/10">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-semibold text-white mb-1 group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($act['title']) ?></div>
                  <div class="text-xs text-gray-400"><?= htmlspecialchars($act['meta']) ?></div>
                </div>
              </div>
              <span class="text-xs text-gray-500 whitespace-nowrap"><?= htmlspecialchars($act['time']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Hari Ini
          </h2>
          <a href="index.php?page=schedule" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">Full schedule →</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <?php if (empty($schedule) || !is_array($schedule)): ?>
          <div class="col-span-2 text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm font-medium text-gray-400 mb-2">Tidak ada jadwal untuk hari ini</p>
            <a href="index.php?page=schedule" class="text-xs text-purple-400 hover:text-purple-300 transition-colors inline-flex items-center gap-1">
              Lihat jadwal lengkap
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
            </a>
          </div>
          <?php else: ?>
          <?php foreach ($schedule as $s): ?>
          <div class="bg-gray-750 border border-gray-700 hover:border-purple-500/50 rounded-lg p-4 transition-all group">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-xs font-medium text-purple-400"><?= htmlspecialchars($s['time'] ?? 'TBA') ?></span>
            </div>
            <h4 class="text-sm font-semibold text-white mb-1 group-hover:text-purple-400 transition-colors"><?= htmlspecialchars($s['subject'] ?? 'Pelajaran') ?></h4>
            <div class="text-xs text-gray-400">
              <?= htmlspecialchars($s['teacher'] ?? 'Guru') ?> • <?= htmlspecialchars($s['room'] ?? '-') ?>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="space-y-6">
      
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-xl font-bold text-white">Performance</h3>
          <a href="index.php?page=grades" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">Details →</a>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-green-500/10">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Tasks Completed</span>
            </div>
            <span class="text-sm font-bold text-white"><?= (int)($stats['tasks_completed'] ?? 0) ?></span>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-purple-500/10">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Attendance Rate</span>
            </div>
            <span class="text-sm font-bold text-white">
              <?php 
                $attRate = 0;
                if (isset($stats['attendance_total']) && (int)$stats['attendance_total'] > 0) {
                  $attRate = round((($stats['attendance_present'] ?? 0) / $stats['attendance_total']) * 100);
                }
                echo $attRate . '%';
              ?>
            </span>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-amber-500/10">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Average Grade</span>
            </div>
            <span class="text-sm font-bold text-white"><?= (int)($stats['average_grade'] ?? 0) ?>%</span>
          </div>
        </div>

        <div class="mt-5 p-4 bg-gray-750 rounded-lg border border-gray-700">
          <h4 class="text-sm font-semibold text-gray-400 mb-3 text-center">Attendance Overview</h4>
          <div style="height: 180px;">
            <canvas id="userProgressChart"></canvas>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
          <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          Quick Actions
        </h3>
        <div class="grid grid-cols-2 gap-3">
          <a href="index.php?page=student/tasks" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-blue-500/10 hover:border-blue-500/20 border border-gray-700 rounded-lg transition-all group">
            <svg class="w-6 h-6 mb-2 text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-blue-400 font-medium transition-colors">Tasks</span>
          </a>
          <a href="index.php?page=schedule" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-purple-500/10 hover:border-purple-500/20 border border-gray-700 rounded-lg transition-all group">
            <svg class="w-6 h-6 mb-2 text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-purple-400 font-medium transition-colors">Schedule</span>
          </a>
          <a href="index.php?page=grades" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-amber-500/10 hover:border-amber-500/20 border border-gray-700 rounded-lg transition-all group">
            <svg class="w-6 h-6 mb-2 text-amber-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-amber-400 font-medium transition-colors">Grades</span>
          </a>
          <a href="index.php?page=student/attendance" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-green-500/10 hover:border-green-500/20 border border-gray-700 rounded-lg transition-all group">
            <svg class="w-6 h-6 mb-2 text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs text-gray-400 group-hover:text-green-400 font-medium transition-colors">Attendance</span>
          </a>
        </div>
      </div>

      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-xl font-bold text-white">Certificates</h3>
          <a href="index.php?page=certificates&scope=all" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">View all →</a>
        </div>
        <div class="space-y-2">
          <?php if ((int)$stats['certificates'] > 0): ?>
          <div class="p-3 bg-gradient-to-r from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 rounded-lg">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
              <span class="text-sm text-gray-300 font-medium">You have <?= (int)$stats['certificates'] ?> certificate(s)</span>
            </div>
          </div>
          <?php else: ?>
          <div class="p-3 bg-gray-750 border border-gray-700 rounded-lg text-center">
            <p class="text-xs text-gray-500">No certificates yet</p>
          </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

  </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function(){
  if (typeof Chart === 'undefined') {
    console.error('Chart.js is not available. Charts will not be initialized.');
    return;
  }

  try{
    const attendanceChartData = <?= json_encode($attendanceChart ?? [0, 0, 0]) ?>;
    const attData = Array.isArray(attendanceChartData) && attendanceChartData.length >= 3
      ? attendanceChartData 
      : [0, 0, 0];
    
    const userProgress = document.getElementById('userProgressChart');
    
    if (userProgress) {
      new Chart(userProgress.getContext('2d'), {
        type: 'doughnut',
        data: { 
          labels: ['Present', 'Absent', 'Late'], 
          datasets: [{ 
            data: attData, 
            backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
            hoverBackgroundColor: ['#059669', '#dc2626', '#d97706'],
            borderWidth: 4,
            borderColor: '#1f2937',
            spacing: 2
          }] 
        },
        options: { 
          cutout: '70%',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { 
              display: true, 
              position: 'bottom', 
              labels: { 
                color: '#9ca3af', 
                font: { size: 12 },
                padding: 15,
                usePointStyle: true
              } 
            },
            tooltip: { 
              enabled: true,
              backgroundColor: 'rgba(17, 24, 39, 0.95)',
              titleColor: '#f3f4f6',
              bodyColor: '#d1d5db',
              borderColor: 'rgba(75, 85, 99, 0.5)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8
            }
          }
        }
      });
    }
  } catch (e) {
    console.error('Error initializing user progress chart:', e);
  }
});
</script>

