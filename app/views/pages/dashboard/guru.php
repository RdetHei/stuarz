<?php
?>

<main class="max-w-7xl mx-auto p-6 bg-gray-900">
  
  
  <section class="bg-gradient-to-br from-gray-800 via-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 mb-6 shadow-xl hover:shadow-2xl transition-shadow">
    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
      <div class="relative">
        <img src="<?= htmlspecialchars($teacher['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($teacher['name']) . '&background=5865f2&color=fff')) ?>"
             alt="avatar" class="w-28 h-28 rounded-full border-4 border-gray-700 object-cover flex-shrink-0 shadow-lg ring-4 ring-blue-500/20">
        <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-4 border-gray-800"></div>
      </div>
      <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
          <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($teacher['name']) ?></h1>
          <span class="px-3 py-1 rounded-full bg-green-500/10 border border-green-500/30 text-green-400 text-xs font-semibold">Active</span>
        </div>
        <div class="text-sm text-gray-400 mb-3">
          <span class="text-blue-400 font-medium"><?= htmlspecialchars($teacher['subject'] ?? 'Teacher') ?></span> • 
          <span><?= htmlspecialchars($teacher['email'] ?? '') ?></span>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
          <div class="flex items-center gap-1.5">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-gray-400">Member since <?= htmlspecialchars($teacher['joined'] ?? '') ?></span>
          </div>
        </div>
        <?php if (!empty($teacher['bio'])): ?>
          <p class="mt-4 text-sm text-gray-300 max-w-2xl leading-relaxed"><?= htmlspecialchars($teacher['bio']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <a href="index.php?page=class" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-blue-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Total Classes</span>
        <div class="p-3 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 transition-colors">
          <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$summary['classes'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Active classes</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=classes" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-purple-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Total Students</span>
        <div class="p-3 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 transition-colors">
          <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M12 14a4 4 0 100-8 4 4 0 000 8z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$summary['students'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Enrolled</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=tasks" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-yellow-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Pending Grading</span>
        <div class="p-3 rounded-xl bg-yellow-500/10 group-hover:bg-yellow-500/20 transition-colors">
          <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$summary['pending_grading'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Needs grading</span>
        <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </div>
    </a>

    <a href="index.php?page=notifications" class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 hover:border-green-500/50 hover:shadow-lg transition-all group">
      <div class="flex items-center justify-between mb-4">
        <span class="text-gray-400 text-sm font-medium">Messages</span>
        <div class="p-3 rounded-xl bg-green-500/10 group-hover:bg-green-500/20 transition-colors">
          <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.85L3 20l1.25-3.2A7.967 7.967 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
          </svg>
        </div>
      </div>
      <div class="text-4xl font-bold text-white mb-2"><?= (int)$summary['messages'] ?></div>
      <div class="text-xs text-gray-500 flex items-center gap-1">
        <span>Unread</span>
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
            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Submissions to Grade
          </h2>
          <a href="index.php?page=tasks" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">View all →</a>
        </div>
        <div class="space-y-3">
          <?php if (empty($submissions)): ?>
          <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-sm">No submissions to grade</p>
          </div>
          <?php else: ?>
          <?php foreach ($submissions as $s): ?>
          <div class="bg-gray-750 hover:bg-gray-700 rounded-lg p-4 transition-all border border-gray-700 hover:border-yellow-500/30 group">
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="text-sm font-semibold text-white mb-1 group-hover:text-yellow-400 transition-colors"><?= htmlspecialchars($s['title'] ?? 'Untitled Task') ?></div>
                <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($s['class'] ?? 'N/A') ?> • <?= htmlspecialchars($s['meta'] ?? 'No info') ?></div>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-xs text-gray-500"><?= htmlspecialchars($s['age'] ?? 'N/A') ?> ago</span>
                <?php if (!empty($s['task_id'])): ?>
                <a href="index.php?page=tasks/submissions&task_id=<?= intval($s['task_id']) ?>" class="px-4 py-2 text-xs font-medium rounded-lg bg-blue-500 hover:bg-blue-600 text-white transition-all shadow-md hover:shadow-lg">Grade</a>
                <?php else: ?>
                <span class="px-4 py-2 text-xs font-medium rounded-lg bg-gray-600 text-gray-400 cursor-not-allowed">Grade</span>
                <?php endif; ?>
              </div>
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Class Performance
          </h2>
          <a href="index.php?page=grades" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">Details →</a>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="bg-gray-750 rounded-xl p-5 border border-gray-700">
            <div class="text-xs text-gray-400 mb-2 font-medium">Average Score</div>
            <div class="text-3xl font-bold text-white mb-1"><?= (int)$performance['average'] ?><span class="text-lg text-gray-500">%</span></div>
            <div class="text-xs text-gray-500 mt-2">Min: <?= (int)$performance['min'] ?> • Max: <?= (int)$performance['max'] ?></div>
          </div>
          
          <div class="bg-gray-750 rounded-xl p-5 border border-gray-700">
            <div class="text-xs text-gray-400 mb-2 font-medium">Grade Distribution</div>
            <div class="space-y-2 text-xs">
              <div class="flex justify-between items-center">
                <span class="text-gray-300">A (90-100)</span>
                <span class="text-emerald-400 font-semibold"><?= count(array_filter($performance['grades'] ?? [], function($g) { return $g >= 90; })) ?></span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-300">B (80-89)</span>
                <span class="text-blue-400 font-semibold"><?= count(array_filter($performance['grades'] ?? [], function($g) { return $g >= 80 && $g < 90; })) ?></span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-gray-300">C (70-79)</span>
                <span class="text-yellow-400 font-semibold"><?= count(array_filter($performance['grades'] ?? [], function($g) { return $g >= 70 && $g < 80; })) ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-gray-750 rounded-xl p-4 border border-gray-700">
          <canvas id="teacherPerformanceChart" width="600" height="200"></canvas>
        </div>
      </div>

    </div>

    
    <div class="space-y-6">
      
      
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            My Classes
          </h2>
          <a href="index.php?page=class" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">All →</a>
        </div>
        <div class="space-y-3">
          <?php if (empty($classes)): ?>
          <div class="text-center py-6 text-gray-500">
            <svg class="w-10 h-10 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <p class="text-xs">No classes yet</p>
          </div>
          <?php else: ?>
          <?php foreach ($classes as $c): ?>
          <a href="index.php?page=class/detail/<?= (int)($c['id'] ?? 0) ?>" class="block bg-gray-750 hover:bg-gray-700 rounded-lg p-4 transition-all border border-gray-700 hover:border-blue-500/30 group">
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <div class="text-sm font-semibold text-white mb-1 group-hover:text-blue-400 transition-colors"><?= htmlspecialchars($c['name']) ?></div>
                <div class="text-xs text-gray-400"><?= htmlspecialchars($c['time']) ?></div>
              </div>
              <div class="px-3 py-1 rounded-lg bg-gray-600/50 text-xs font-medium text-gray-300">
                <?= (int)$c['students'] ?> students
              </div>
            </div>
          </a>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      
      <?php
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $weekdayMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $activeDay = $weekdayMap[(int)date('N')] ?? 'Senin';
      ?>
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between mb-5">
          <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal Mengajar
          </h2>
          <a href="index.php?page=schedule" class="text-sm text-blue-400 hover:text-blue-300 transition-colors font-medium">Lihat semua →</a>
        </div>
        <div class="flex flex-wrap gap-2 mb-4">
          <?php foreach ($days as $d): ?>
            <button type="button" data-day="<?= $d ?>" class="t-day-tab px-3 py-1.5 rounded-lg text-xs font-medium border transition-all <?= $d === $activeDay ? 'bg-purple-500/10 border-purple-500/30 text-purple-300 shadow-md' : 'bg-gray-700/50 border-gray-600 text-gray-300 hover:bg-gray-700' ?>"><?= $d ?></button>
          <?php endforeach; ?>
        </div>
        <div>
          <?php foreach ($days as $d): $items = $scheduleByDay[$d] ?? []; ?>
          <div class="t-day-panel" data-day-panel="<?= $d ?>" style="display: <?= $d === $activeDay ? 'block' : 'none' ?>;">
            <?php if (empty($items)): ?>
              <div class="text-sm text-gray-500 text-center py-4">Tidak ada jadwal.</div>
            <?php else: ?>
            <div class="space-y-2">
              <?php foreach ($items as $s): ?>
              <div class="bg-gray-750 border border-gray-700 hover:border-purple-500/40 rounded-lg p-3 transition-all">
                <div class="flex items-center gap-2 mb-2">
                  <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-xs font-medium text-purple-400"><?= htmlspecialchars($s['time']) ?></span>
                </div>
                <div class="text-sm font-semibold text-white mb-1"><?= htmlspecialchars($s['subject']) ?></div>
                <div class="text-xs text-gray-400"><?= htmlspecialchars($s['teacher']) ?> • <?= htmlspecialchars($s['room']) ?></div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-xl p-6 shadow-lg">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
          <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
          Quick Actions
        </h2>
        <div class="space-y-2">
          <a href="index.php?page=tasks/create" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-blue-500/10 hover:border-blue-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-blue-400 transition-all group">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="font-medium">Create Assignment</span>
          </a>
          <a href="index.php?page=announcement_create" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-purple-500/10 hover:border-purple-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-purple-400 transition-all group">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <span class="font-medium">Send Announcement</span>
          </a>
          <a href="index.php?page=grades" class="flex items-center gap-3 w-full p-3 bg-gray-750 hover:bg-green-500/10 hover:border-green-500/20 border border-gray-700 rounded-lg text-sm text-gray-300 hover:text-green-400 transition-all group">
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="font-medium">View Grades</span>
          </a>
        </div>
      </div>

    </div>

  </div>

</main>

<script>
document.addEventListener('DOMContentLoaded', function(){
  if (typeof Chart === 'undefined') {
    return;
  }

  try{
    const performanceData = <?= json_encode($performance ?? ['grades' => []]) ?>;
    const grades = Array.isArray(performanceData.grades) && performanceData.grades.length > 0 
      ? performanceData.grades 
      : [0, 0, 0, 0, 0];
    
    const ctx = document.getElementById('teacherPerformanceChart');
    if (ctx) {
      new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: { 
          labels: grades.map((_,i) => 'S'+(i+1)), 
          datasets:[{ 
            label:'Scores', 
            data:grades, 
            backgroundColor:'#5865f2',
            borderRadius: 6,
            borderSkipped: false
          }] 
        },
        options: { 
          responsive:true, 
          maintainAspectRatio:false, 
          plugins:{
            legend:{display:false},
            tooltip: {
              backgroundColor: 'rgba(17, 24, 39, 0.95)',
              titleColor: '#f3f4f6',
              bodyColor: '#d1d5db',
              borderColor: 'rgba(75, 85, 99, 0.5)',
              borderWidth: 1,
              padding: 12,
              cornerRadius: 8
            }
          }, 
          scales:{
            y:{
              beginAtZero:true,
              grid: {
                color: '#374151',
                drawBorder: false
              },
              ticks: {
                color: '#9ca3af'
              }
            },
            x: {
              grid: {
                display: false
              },
              ticks: {
                color: '#9ca3af'
              }
            }
          }
        }
      });
    }
  }catch(e){
    console.error('Error initializing chart:', e);
  }
  
  var tTabs = document.querySelectorAll('.t-day-tab');
  var tPanels = document.querySelectorAll('.t-day-panel');
  tTabs.forEach(function(btn){
    btn.addEventListener('click', function(){
      var day = btn.getAttribute('data-day');
      tTabs.forEach(function(b){ 
        b.classList.remove('bg-purple-500/10','border-purple-500/30','text-purple-300','shadow-md'); 
        b.classList.add('bg-gray-700/50','border-gray-600','text-gray-300'); 
      });
      btn.classList.remove('bg-gray-700/50','border-gray-600','text-gray-300');
      btn.classList.add('bg-purple-500/10','border-purple-500/30','text-purple-300','shadow-md');
      tPanels.forEach(function(p){ p.style.display = (p.getAttribute('data-day-panel') === day) ? 'block' : 'none'; });
    });
  });
});
</script>
