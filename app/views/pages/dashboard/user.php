<?php
// Sample data fallbacks (controllers should provide real data)
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

$schedule = $schedule ?? [
    ['time' => '07:30 — 08:15', 'subject' => 'Matematika', 'teacher' => 'Bu Sari', 'room' => 'R101'],
    ['time' => '08:30 — 09:15', 'subject' => 'Biologi', 'teacher' => 'Pak Anton', 'room' => 'Lab'],
    ['time' => '09:30 — 10:15', 'subject' => 'Fisika', 'teacher' => 'Pak Budi', 'room' => 'R102'],
    ['time' => '10:30 — 11:15', 'subject' => 'Kimia', 'teacher' => 'Bu Ani', 'room' => 'Lab Kimia'],
];

$learning = $learning ?? [
    'words_read' => 12340,
    'chapters' => 18,
    'streak' => 14
];

$attendanceChart = $attendanceChart ?? [88,8,4]; // present, absent, late

?>

<!-- Dashboard User view (partial) -->
<main class="max-w-7xl mx-auto p-6 bg-gray-900 min-h-screen">
  
  <!-- Header Profile -->
  <section class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 mb-6 shadow-xl">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <img src="<?= htmlspecialchars($user['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=5865f2&color=fff')) ?>"
           alt="avatar" class="w-28 h-28 rounded-full border-4 border-gray-700 object-cover flex-shrink-0 shadow-lg">
      
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($user['name']) ?></h1>
          <span class="px-3 py-1 rounded-md bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-medium">Active Student</span>
        </div>
        
        <div class="text-sm text-gray-400 mb-3">
          <span class="text-blue-400">@<?= htmlspecialchars($user['username']) ?></span> • 
          <span class="capitalize"><?= htmlspecialchars($user['level']) ?></span>
        </div>
        
        <div class="flex flex-wrap gap-4 text-xs text-gray-500 mb-3">
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-400">Joined <?= isset($user['joined']) ? htmlspecialchars($user['joined']) : 'N/A' ?></span>
          </div>
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="text-gray-400">Class: <span class="text-white"><?= htmlspecialchars($user['class']) ?></span></span>
          </div>
        </div>
        
        <?php if (!empty($user['bio'])): ?>
          <p class="text-sm text-gray-300 max-w-2xl leading-relaxed"><?= htmlspecialchars($user['bio']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Summary Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 hover:border-blue-500/50 transition-all group">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Tasks Done</span>
        <div class="p-2 rounded-lg bg-blue-500/10 group-hover:bg-blue-500/20 transition-colors">
          <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$stats['tasks_completed'] ?></div>
      <div class="text-xs text-gray-500">Completed this month</div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 hover:border-purple-500/50 transition-all group">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Attendance</span>
        <div class="p-2 rounded-lg bg-purple-500/10 group-hover:bg-purple-500/20 transition-colors">
          <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$stats['attendance_present'] ?><span class="text-lg text-gray-500"> / <?= (int)$stats['attendance_total'] ?></span></div>
      <div class="text-xs text-gray-500">Present days</div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 hover:border-emerald-500/50 transition-all group">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Certificates</span>
        <div class="p-2 rounded-lg bg-emerald-500/10 group-hover:bg-emerald-500/20 transition-colors">
          <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$stats['certificates'] ?></div>
      <div class="text-xs text-gray-500">Earned</div>
    </div>

    <div class="bg-gray-800 border border-gray-700 rounded-lg p-5 hover:border-amber-500/50 transition-all group">
      <div class="flex items-center justify-between mb-3">
        <span class="text-gray-400 text-sm font-medium">Avg. Grade</span>
        <div class="p-2 rounded-lg bg-amber-500/10 group-hover:bg-amber-500/20 transition-colors">
          <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
          </svg>
        </div>
      </div>
      <div class="text-3xl font-bold text-white mb-1"><?= (int)$stats['average_grade'] ?><span class="text-lg text-gray-500">%</span></div>
      <div class="text-xs text-gray-500">Semester average</div>
    </div>
  </section>

  

  <!-- Main Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Left Column (2/3) -->
    <div class="lg:col-span-2 space-y-6">
      
      <!-- Recent Activities -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Aktivitas Terbaru
          </h2>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Lihat semua →</a>
        </div>
        
        <div class="space-y-3">
          <?php foreach ($activities as $act): ?>
          <div class="bg-gray-750 border border-gray-700 hover:border-gray-600 rounded-lg p-4 transition-all">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-start gap-3 flex-1">
                <div class="mt-0.5 p-2 rounded-lg bg-blue-500/10">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="flex-1">
                  <div class="text-sm font-medium text-white mb-1"><?= htmlspecialchars($act['title']) ?></div>
                  <div class="text-xs text-gray-400"><?= htmlspecialchars($act['meta']) ?></div>
                </div>
              </div>
              <span class="text-xs text-gray-500 whitespace-nowrap"><?= htmlspecialchars($act['time']) ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Today's Schedule -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-lg font-bold text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Hari Ini
          </h2>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Full schedule →</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <?php foreach ($schedule as $s): ?>
          <div class="bg-gray-750 border border-gray-700 hover:border-purple-500/50 rounded-lg p-4 transition-all group cursor-pointer">
            <div class="flex items-center gap-2 mb-2">
              <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-xs font-medium text-purple-400"><?= htmlspecialchars($s['time']) ?></span>
            </div>
            <h4 class="text-sm font-semibold text-white mb-1 group-hover:text-purple-400 transition-colors"><?= htmlspecialchars($s['subject']) ?></h4>
            <div class="text-xs text-gray-400"><?= htmlspecialchars($s['teacher']) ?> • <?= htmlspecialchars($s['room']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>

    <!-- Right Column (1/3) -->
    <div class="space-y-6">
      
      <!-- Learning Statistics -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-5">
          <h3 class="text-lg font-bold text-white">Stats</h3>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Details →</a>
        </div>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-blue-500/10">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Words Read</span>
            </div>
            <span class="text-sm font-bold text-white"><?= number_format($learning['words_read']) ?></span>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-purple-500/10">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Chapters</span>
            </div>
            <span class="text-sm font-bold text-white"><?= (int)$learning['chapters'] ?></span>
          </div>

          <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-lg bg-orange-500/10">
                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                </svg>
              </div>
              <span class="text-sm text-gray-400">Streak</span>
            </div>
            <span class="text-sm font-bold text-orange-400"><?= (int)$learning['streak'] ?> days 🔥</span>
          </div>
        </div>

        <div class="mt-5 p-4 bg-gray-750 rounded-lg border border-gray-700">
          <h4 class="text-sm font-semibold text-gray-400 mb-3 text-center">Attendance Overview</h4>
          <div style="height: 180px;">
            <canvas id="userProgressChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-3">
          <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-blue-500/10 hover:border-blue-500/20 border border-gray-700 rounded-lg transition-all group">
            <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">📅</span>
            <span class="text-xs text-gray-400 group-hover:text-blue-400 font-medium transition-colors">Schedule</span>
          </a>
          <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-purple-500/10 hover:border-purple-500/20 border border-gray-700 rounded-lg transition-all group">
            <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">📝</span>
            <span class="text-xs text-gray-400 group-hover:text-purple-400 font-medium transition-colors">Grades</span>
          </a>
          <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-emerald-500/10 hover:border-emerald-500/20 border border-gray-700 rounded-lg transition-all group">
            <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">🏅</span>
            <span class="text-xs text-gray-400 group-hover:text-emerald-400 font-medium transition-colors">Awards</span>
          </a>
          <a href="#" class="flex flex-col items-center justify-center p-4 bg-gray-750 hover:bg-amber-500/10 hover:border-amber-500/20 border border-gray-700 rounded-lg transition-all group">
            <span class="text-2xl mb-2 group-hover:scale-110 transition-transform">📚</span>
            <span class="text-xs text-gray-400 group-hover:text-amber-400 font-medium transition-colors">Docs</span>
          </a>
        </div>
      </div>

      <!-- Certificates Preview -->
      <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-white">Certificates</h3>
          <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">View all →</a>
        </div>
        <div class="space-y-2">
          <div class="p-3 bg-gradient-to-r from-emerald-500/10 to-emerald-600/5 border border-emerald-500/20 rounded-lg">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
              <span class="text-sm text-gray-300 font-medium">Best Attendance</span>
            </div>
          </div>
          <div class="p-3 bg-gradient-to-r from-blue-500/10 to-blue-600/5 border border-blue-500/20 rounded-lg">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
              <span class="text-sm text-gray-300 font-medium">Olimpiade Kimia</span>
            </div>
          </div>
          <div class="p-3 bg-gradient-to-r from-purple-500/10 to-purple-600/5 border border-purple-500/20 rounded-lg">
            <div class="flex items-center gap-2">
              <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
              </svg>
              <span class="text-sm text-gray-300 font-medium">Essay Competition</span>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

</main>

<!-- Chart.js initialization -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  try{
    const attData = <?= json_encode(array_values($attendanceChart)) ?>; // [present, absent, late]
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
          plugins: {
            legend: { display: true, position: 'bottom', labels: { color: '#9ca3af', font: { size: 12 } } },
            tooltip: { enabled: true }
          }
        }
      });
    }
  } catch (e) {
    console.error('Error initializing charts:', e);
  }
});
</script>