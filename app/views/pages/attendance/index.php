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

<div class="max-w-[1400px] mx-auto p-4 lg:p-6">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#5865F2] to-[#7289da] flex items-center justify-center shadow-lg">
          <span class="material-symbols-outlined text-white text-2xl">how_to_reg</span>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Absensi</h1>
          <p class="text-[#949ba4] text-sm mt-1">Kelola kehadiran siswa</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="index.php?page=attendance/mark" 
           class="px-5 py-2.5 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
          <span class="material-symbols-outlined text-xl">add</span>
          Input Absensi
        </a>
        <a href="index.php?page=attendance/report" 
           class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
          <span class="material-symbols-outlined text-xl">analytics</span>
          Laporan
        </a>
      </div>
    </div>

    <!-- Filter Section -->
    <div class="mt-6 flex flex-wrap gap-3">
      <form method="GET" action="index.php" class="flex flex-wrap gap-3 w-full">
        <input type="hidden" name="page" value="attendance">

        <div class="flex-1 min-w-[200px]">
          <select name="class_id" class="w-full px-4 py-2.5 bg-[#2b2d31] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
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
          <input type="date" name="date" 
                 value="<?= htmlspecialchars($_GET['date'] ?? date('Y-m-d')) ?>"
                 class="w-full px-4 py-2.5 bg-[#2b2d31] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
        </div>

        <button type="submit" class="px-6 py-2.5 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
          <span class="material-symbols-outlined text-xl">filter_alt</span>
          Filter
        </button>

        <?php if ($selectedClass || !empty($_GET['date'])): ?>
        <a href="index.php?page=attendance" class="px-6 py-2.5 bg-[#2b2d31] hover:bg-[#383a40] border border-[#3f4147] text-[#dbdee1] rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
          <span class="material-symbols-outlined text-xl">close</span>
          Reset
        </a>
        <?php endif; ?>
      </form>
    </div>
  </div>

  <!-- Stats grid -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php foreach ($displayStats as $s): ?>
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-5 flex items-center justify-between">
      <div>
        <div class="text-sm text-gray-300/80 mb-1"><?= htmlspecialchars($s['title']) ?></div>
        <div class="text-3xl font-bold text-white"><?= (int)$s['value'] ?> days</div>
      </div>
      <div class="w-12 h-12 rounded-lg flex items-center justify-center <?php
        echo $s['color']==='success' ? 'bg-emerald-500/20 text-emerald-300' : ($s['color']==='danger' ? 'bg-red-500/20 text-red-300' : 'bg-orange-500/20 text-orange-300');
      ?>">
        <!-- simple icons -->
        <?php if ($s['icon']==='check'): ?>
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <?php elseif ($s['icon']==='x'): ?>
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/></svg>
        <?php else: ?>
          <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 6v6l4 2" stroke-linecap="round"/><circle cx="12" cy="12" r="9"/></svg>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Check-in/out card -->
  <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6 mt-4">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <div class="text-sm text-gray-300/80">Current Time</div>
        <div id="attClock" class="text-4xl font-bold tracking-tight text-white">00:00:00</div>
        <div class="mt-1 text-sm" id="attStatus"><span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-300 border border-red-500/30">Not Checked In</span></div>
      </div>
      <div class="flex items-center gap-3">
        <div class="text-sm text-gray-300/80">Checked in at:</div>
        <div id="attCheckinTime" class="text-white font-medium">-</div>
      </div>
      <div>
        <button id="attActionBtn" class="px-6 py-3 rounded-lg font-semibold transition text-white bg-gradient-to-tr from-indigo-500 to-purple-500">Check In</button>
      </div>
    </div>
  </div>

  <!-- Attendance Records -->
  <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6 mt-4">
    <div class="flex items-center justify-between mb-4">
      <div class="text-white font-semibold">Data Absensi</div>
      <div class="text-sm text-[#949ba4]">
        <?php if (!empty($records)): ?>
          Menampilkan <?= count($records) ?> catatan
        <?php else: ?>
          Belum ada data absensi
        <?php endif; ?>
      </div>
    </div>
    
    <?php if (!empty($records)): ?>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="text-left text-gray-300/80">
          <tr class="border-b border-[#3f4147]">
            <th class="py-3 px-4 font-medium">Tanggal</th>
            <th class="py-3 px-4 font-medium">Nama Siswa</th>
            <th class="py-3 px-4 font-medium">Kelas</th>
            <th class="py-3 px-4 font-medium">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record): ?>
          <tr class="border-b border-[#3f4147]/50 hover:bg-[#313338]/50 transition-colors">
            <td class="py-3 px-4 text-[#e5e7eb]"><?= date('d/m/Y', strtotime($record['date'])) ?></td>
            <td class="py-3 px-4 text-white font-medium">
              <?php
              // Get student name
              global $config;
              $studentQuery = $config->query("SELECT name FROM users WHERE id=" . intval($record['user_id']));
              $student = $studentQuery->fetch_assoc();
              echo htmlspecialchars($student['name'] ?? 'N/A');
              ?>
            </td>
            <td class="py-3 px-4 text-[#949ba4]">
              <?php
              // Get class name
              $classQuery = $config->query("SELECT name FROM classes WHERE id=" . intval($record['class_id']));
              $class = $classQuery->fetch_assoc();
              echo htmlspecialchars($class['name'] ?? 'N/A');
              ?>
            </td>
            <td class="py-3 px-4">
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
              <span class="px-2 py-1 rounded-full text-xs border <?= $class ?>"><?= htmlspecialchars($status) ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="text-center py-12">
      <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#5865F2]/20 flex items-center justify-center">
        <span class="material-symbols-outlined text-[#8ea1f7] text-3xl">how_to_reg</span>
      </div>
      <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Data Absensi</h3>
      <p class="text-[#949ba4] mb-4">Mulai input absensi untuk melihat data di sini.</p>
      <a href="index.php?page=attendance/mark" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200">
        <span class="material-symbols-outlined text-xl">add</span>
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
    toast.textContent = msg; toast.classList.add('show');
    setTimeout(()=>toast.classList.remove('show'), 1600);
  }

  function render(){
    if (checkedIn){
      statusEl.innerHTML = '<span class="badge badge-success">Checked In</span>';
      btn.textContent = 'Check Out';
      btn.classList.remove('btn-gradient');
      btn.classList.add('btn-danger');
      checkinTimeEl.textContent = checkinAt ? new Date(checkinAt).toLocaleTimeString() : '-';
    } else {
      statusEl.innerHTML = '<span class="badge badge-danger">Not Checked In</span>';
      btn.textContent = 'Check In';
      btn.classList.remove('btn-danger');
      btn.classList.add('btn-gradient');
      checkinTimeEl.textContent = '-';
    }
  }

  btn.addEventListener('click', function(){
    if (!checkedIn){ checkedIn = true; checkinAt = Date.now(); showToast('Checked in successfully'); }
    else { checkedIn = false; showToast('Checked out successfully'); }
    render();
  });

  render();
})();
</script>
