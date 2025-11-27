<?php
// ScheduleTable component
// Expects: $schedules (array of ['day','start_time','end_time','subject','teacher_name'])
$schedules = $schedules ?? [];

// Group schedules by day
$dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$groupedSchedules = [];
foreach ($schedules as $s) {
  $day = $s['day'] ?? 'Lainnya';
  if (!isset($groupedSchedules[$day])) {
    $groupedSchedules[$day] = [];
  }
  $groupedSchedules[$day][] = $s;
}

// Sort by day order
uksort($groupedSchedules, function($a, $b) use ($dayOrder) {
  $posA = array_search($a, $dayOrder);
  $posB = array_search($b, $dayOrder);
  if ($posA === false) $posA = 999;
  if ($posB === false) $posB = 999;
  return $posA <=> $posB;
});

// Day colors for visual differentiation
$dayColors = [
  'Senin' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400'],
  'Monday' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400'],
  'Selasa' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'Tuesday' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'Rabu' => ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-400'],
  'Wednesday' => ['bg' => 'bg-emerald-500/10', 'border' => 'border-emerald-500/20', 'text' => 'text-emerald-400'],
  'Kamis' => ['bg' => 'bg-amber-500/10', 'border' => 'border-amber-500/20', 'text' => 'text-amber-400'],
  'Thursday' => ['bg' => 'bg-amber-500/10', 'border' => 'border-amber-500/20', 'text' => 'text-amber-400'],
  'Jumat' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
  'Friday' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
  'Sabtu' => ['bg' => 'bg-cyan-500/10', 'border' => 'border-cyan-500/20', 'text' => 'text-cyan-400'],
  'Saturday' => ['bg' => 'bg-cyan-500/10', 'border' => 'border-cyan-500/20', 'text' => 'text-cyan-400'],
  'Minggu' => ['bg' => 'bg-pink-500/10', 'border' => 'border-pink-500/20', 'text' => 'text-pink-400'],
  'Sunday' => ['bg' => 'bg-pink-500/10', 'border' => 'border-pink-500/20', 'text' => 'text-pink-400'],
];
?>

<?php if (empty($schedules)): ?>
  <!-- Empty State -->
  <div class="text-center py-12">
    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-700/50 flex items-center justify-center">
      <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
      </svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-2">Belum Ada Jadwal</h3>
    <p class="text-sm text-gray-400">Tidak ada jadwal pelajaran yang tersedia.</p>
  </div>
<?php else: ?>
  
  <!-- Schedule Summary -->
  <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Total Jadwal</div>
      <div class="text-2xl font-bold text-white"><?= count($schedules) ?></div>
    </div>
    
    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Hari Aktif</div>
      <div class="text-2xl font-bold text-blue-400"><?= count($groupedSchedules) ?></div>
    </div>
    
    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Mata Pelajaran</div>
      <div class="text-2xl font-bold text-purple-400"><?= count(array_unique(array_column($schedules, 'subject'))) ?></div>
    </div>
    
    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Pengajar</div>
      <div class="text-2xl font-bold text-emerald-400"><?= count(array_unique(array_filter(array_column($schedules, 'teacher_name')))) ?></div>
    </div>
  </div>

  <!-- Schedule by Day -->
  <div class="space-y-6">
    <?php foreach ($groupedSchedules as $day => $daySchedules): ?>
      <?php 
        $colors = $dayColors[$day] ?? ['bg' => 'bg-gray-500/10', 'border' => 'border-gray-500/20', 'text' => 'text-gray-400'];
      ?>
      
      <div class="space-y-3">
        <!-- Day Header -->
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 px-4 py-2 <?= $colors['bg'] ?> <?= $colors['border'] ?> border rounded-lg">
            <svg class="w-5 h-5 <?= $colors['text'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="font-bold <?= $colors['text'] ?>"><?= htmlspecialchars($day) ?></span>
            <span class="px-2 py-0.5 rounded bg-gray-700 text-gray-300 text-xs font-medium ml-1">
              <?= count($daySchedules) ?> sesi
            </span>
          </div>
          <div class="flex-1 h-px bg-gray-700"></div>
        </div>

        <!-- Schedule Cards for this day -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <?php foreach ($daySchedules as $s): ?>
            <div class="bg-gray-750 border border-gray-700 hover:border-gray-600 rounded-lg p-4 transition-all group">
              
              <!-- Time Badge -->
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-700 rounded-lg">
                  <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  <span class="text-sm font-mono font-medium text-gray-300">
                    <?= htmlspecialchars($s['start_time'] ?? '00:00') ?> - <?= htmlspecialchars($s['end_time'] ?? '00:00') ?>
                  </span>
                </div>
                
                <span class="px-2 py-1 rounded bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-semibold">
                  Active
                </span>
              </div>

              <!-- Subject -->
              <h4 class="text-base font-bold text-white mb-2 group-hover:text-blue-400 transition-colors">
                <?= htmlspecialchars($s['subject'] ?? '-') ?>
              </h4>

              <!-- Teacher -->
              <?php if (!empty($s['teacher_name'])): ?>
              <div class="flex items-center gap-2 text-sm text-gray-400">
                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center">
                  <span class="text-xs font-bold text-white">
                    <?= strtoupper(substr($s['teacher_name'], 0, 1)) ?>
                  </span>
                </div>
                <span class="font-medium text-gray-300"><?= htmlspecialchars($s['teacher_name']) ?></span>
              </div>
              <?php endif; ?>

              <?php if (!empty($s['room'])): ?>
              <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Ruang <?= htmlspecialchars($s['room']) ?></span>
              </div>
              <?php endif; ?>

            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Alternative: Table View Toggle -->
  <div class="mt-8">
    <button onclick="toggleTableView()" class="px-4 py-2 bg-gray-750 hover:bg-gray-700 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 transition-colors inline-flex items-center gap-2">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
      </svg>
      Lihat dalam Tabel
    </button>

    <div id="tableView" class="hidden mt-4 overflow-x-auto bg-gray-750 border border-gray-700 rounded-lg">
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-700">
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Hari</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Mata Pelajaran</th>
            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengajar</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php foreach ($schedules as $s): ?>
            <tr class="hover:bg-gray-700/50 transition-colors">
              <td class="px-4 py-3 text-gray-300 font-medium"><?= htmlspecialchars($s['day'] ?? '-') ?></td>
              <td class="px-4 py-3 text-gray-400 font-mono text-xs">
                <?= htmlspecialchars($s['start_time'] ?? '-') ?> - <?= htmlspecialchars($s['end_time'] ?? '-') ?>
              </td>
              <td class="px-4 py-3 text-white font-medium"><?= htmlspecialchars($s['subject'] ?? '-') ?></td>
              <td class="px-4 py-3 text-gray-300"><?= htmlspecialchars($s['teacher_name'] ?? '-') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>

<script>
function toggleTableView() {
  const tableView = document.getElementById('tableView');
  if (tableView) {
    tableView.classList.toggle('hidden');
  }
}
</script>