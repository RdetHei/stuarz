<?php
// Get attendance statistics from controller
$records = $records ?? [];
$classes = $classes ?? [];

// Calculate stats from records
$stats = [
  'Hadir' => 0,
  'Absen' => 0,
  'Terlambat' => 0,
  'Izin' => 0,
  'Sakit' => 0
];

foreach ($records as $record) {
    $status = $record['status'] ?? '';
    if (isset($stats[$status])) {
        $stats[$status]++;
    }
}

// Convert to display format
$displayStats = [
  ['title' => 'Hari Hadir', 'value' => $stats['Hadir'], 'color' => 'success', 'icon' => 'check'],
  ['title' => 'Hari Absen',  'value' => $stats['Absen'],  'color' => 'danger',  'icon' => 'x'],
  ['title' => 'Hari Terlambat',    'value' => $stats['Terlambat'],  'color' => 'warning','icon' => 'clock'],
];
?>

<style>
  .glass-card {
    background: rgba(43, 45, 49, 0.6);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.05);
  }
  .toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: #2b2d31;
    border: 1px solid #3f4147;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }
  .toast.show {
    opacity: 1;
    transform: translateY(0);
  }
  .badge {
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
  }
  .badge-success {
    background: rgba(34, 197, 94, 0.2);
    color: #86efac;
    border: 1px solid rgba(34, 197, 94, 0.3);
  }
  .badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, 0.3);
  }
</style>

<div class="max-w-7xl mx-auto p-6">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Absensi</h1>
          <p class="text-gray-400 text-sm mt-1">Kelola kehadiran siswa dengan mudah</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="index.php?page=attendance/mark" 
           class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Input Absensi
        </a>
        <a href="index.php?page=attendance/report" 
           class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 border border-gray-600">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
          </svg>
          Laporan
        </a>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="GET" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="attendance">

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Kelas</label>
          <select name="class_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Kelas</option>
            <?php
            global $config;
            $classQuery = $config->query("SELECT id, name FROM classes ORDER BY name");
            $selectedClass = $_GET['class_id'] ?? '';
            while ($class = $classQuery->fetch_assoc()):
            ?>
            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($class['name']) ?>
            </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal</label>
          <input type="date" name="date" 
                 value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>"
                 class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
          </button>

          <?php if ($selectedClass || !empty($_GET['date'])): ?>
          <a href="index.php?page=attendance" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
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

  <!-- Stats grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <?php foreach ($displayStats as $s): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-all">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm font-medium text-gray-400 mb-2"><?= htmlspecialchars($s['title']) ?></div>
          <div class="text-3xl font-bold text-white"><?= (int)$s['value'] ?></div>
          <div class="text-xs text-gray-500 mt-1">total hari</div>
        </div>
        <div class="w-14 h-14 rounded-xl flex items-center justify-center <?php
          echo $s['color']==='success' ? 'bg-emerald-500/20' : ($s['color']==='danger' ? 'bg-red-500/20' : 'bg-orange-500/20');
        ?>">
          <?php if ($s['icon']==='check'): ?>
            <svg class="w-7 h-7 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          <?php elseif ($s['icon']==='x'): ?>
            <svg class="w-7 h-7 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/>
            </svg>
          <?php else: ?>
            <svg class="w-7 h-7 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 6v6l4 2" stroke-linecap="round"/>
              <circle cx="12" cy="12" r="9"/>
            </svg>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Check-in/out card -->
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
      <div class="flex-1">
        <div class="text-sm font-medium text-gray-400 mb-2">Waktu Sekarang</div>
        <div id="attClock" class="text-5xl font-bold text-white mb-2">00:00:00</div>
        <div id="attStatus">
          <span class="badge badge-danger">Not Checked In</span>
        </div>
      </div>
      
      <div class="flex items-center gap-4 px-6 py-4 bg-gray-900 rounded-lg border border-gray-700">
        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
          <div class="text-xs text-gray-400">Check-in Time</div>
          <div id="attCheckinTime" class="text-white font-semibold">--:--:--</div>
        </div>
      </div>
      
      <button id="attActionBtn" class="px-8 py-3.5 rounded-lg font-semibold transition-all text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg min-w-[140px]">
        Check In
      </button>
    </div>
  </div>

  <!-- Attendance Records -->
  <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="p-6 border-b border-gray-700">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-xl font-bold text-white">Data Absensi</h2>
          <p class="text-sm text-gray-400 mt-1">
            <?php if (!empty($records)): ?>
              Menampilkan <?= count($records) ?> catatan absensi
            <?php else: ?>
              Belum ada data absensi tersedia
            <?php endif; ?>
          </p>
        </div>
        <?php if (!empty($records)): ?>
        <div class="flex items-center gap-2 text-sm text-gray-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          <span>Total: <?= count($records) ?></span>
        </div>
        <?php endif; ?>
      </div>
    </div>
    
    <?php if (!empty($records)): ?>
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-900 border-b border-gray-700">
            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Siswa</th>
            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas</th>
            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php foreach ($records as $record): ?>
          <tr class="hover:bg-gray-700/50 transition-colors">
            <td class="py-4 px-6 text-gray-300">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <?= date('d/m/Y', strtotime($record['date'])) ?>
              </div>
            </td>
            <td class="py-4 px-6">
              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                  <?php
                  global $config;
                  $studentQuery = $config->query("SELECT name FROM users WHERE id=" . intval($record['user_id']));
                  $student = $studentQuery->fetch_assoc();
                  $studentName = $student['name'] ?? 'N/A';
                  echo strtoupper(substr($studentName, 0, 1));
                  ?>
                </div>
                <span class="text-white font-medium"><?= htmlspecialchars($studentName) ?></span>
              </div>
            </td>
            <td class="py-4 px-6 text-gray-400">
              <?php
              $classQuery = $config->query("SELECT name FROM classes WHERE id=" . intval($record['class_id']));
              $class = $classQuery->fetch_assoc();
              echo htmlspecialchars($class['name'] ?? 'N/A');
              ?>
            </td>
            <td class="py-4 px-6">
              <?php
              $status = $record['status'] ?? 'Absen';
              $statusClasses = [
                'Hadir' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                'Absen' => 'bg-red-500/20 text-red-300 border-red-500/30',
                'Terlambat' => 'bg-orange-500/20 text-orange-300 border-orange-500/30',
                'Izin' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                'Sakit' => 'bg-purple-500/20 text-purple-300 border-purple-500/30'
              ];
              $class = $statusClasses[$status] ?? $statusClasses['Absen'];
              ?>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?= $class ?>">
                <?= htmlspecialchars($status) ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
      <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
        <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <h3 class="text-xl font-bold text-white mb-2">Belum Ada Data Absensi</h3>
      <p class="text-gray-400 mb-6 max-w-md mx-auto">Mulai input absensi untuk melihat data kehadiran siswa di sini.</p>
      <a href="index.php?page=attendance/mark" 
         class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Input Absensi Pertama
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>

<div id="attToast" class="toast">Saved</div>

<script>
(function(){
  const clock = document.getElementById('attClock');
  const statusEl = document.getElementById('attStatus');
  const checkinTimeEl = document.getElementById('attCheckinTime');
  const btn = document.getElementById('attActionBtn');
  const toast = document.getElementById('attToast');
  let checkedIn = false;
  let checkinAt = null;

  function tick(){
    const d = new Date();
    const pad = n => String(n).padStart(2,'0');
    clock.textContent = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
  }
  setInterval(tick, 1000); tick();

  function showToast(msg){
    toast.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2000);
  }

  function render(){
    if (checkedIn){
      statusEl.innerHTML = '<span class="badge badge-success">Checked In</span>';
      btn.textContent = 'Check Out';
      btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
      btn.classList.add('bg-red-600', 'hover:bg-red-700');
      const d = new Date(checkinAt);
      const pad = n => String(n).padStart(2,'0');
      checkinTimeEl.textContent = `${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())}`;
    } else {
      statusEl.innerHTML = '<span class="badge badge-danger">Not Checked In</span>';
      btn.textContent = 'Check In';
      btn.classList.remove('bg-red-600', 'hover:bg-red-700');
      btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
      checkinTimeEl.textContent = '--:--:--';
    }
  }

  btn.addEventListener('click', function(){
    if (!checkedIn){
      checkedIn = true;
      checkinAt = Date.now();
      showToast('✓ Checked in successfully');
    } else {
      checkedIn = false;
      showToast('✓ Checked out successfully');
    }
    render();
  });

  render();
})();
</script>