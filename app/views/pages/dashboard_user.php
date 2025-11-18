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
];

$learning = $learning ?? [
    'words_read' => 12340,
    'chapters' => 18,
    'streak' => 14
];

$attendanceChart = $attendanceChart ?? [88,8,4]; // present, absent, late

?>

<!-- Dashboard User view (partial) -->
<main class="max-w-7xl mx-auto p-8">
  <!-- Header Profile -->
  <section class="bg-[#11131a] border border-gray-800 rounded-xl p-8 flex flex-col md:flex-row items-start md:items-center gap-6">
    <img src="<?= htmlspecialchars($user['avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=3b82f6&color=fff')) ?>"
         alt="avatar" class="w-28 h-28 rounded-full border-2 border-gray-800 object-cover flex-shrink-0">
    <div class="flex-1">
      <div class="text-2xl font-semibold leading-tight"><?= htmlspecialchars($user['name']) ?></div>
      <div class="text-sm text-gray-300 mt-1">@<?= htmlspecialchars($user['username']) ?> • <span class="text-gray-400"><?= htmlspecialchars($user['level']) ?></span></div>
      <div class="mt-3 text-sm text-gray-400">Bergabung: <span class="text-gray-300"><?= htmlspecialchars($user['joined']) ?></span> • Kelas: <span class="text-gray-300"><?= htmlspecialchars($user['class']) ?></span></div>
      <?php if (!empty($user['bio'])): ?><p class="mt-4 text-sm text-gray-300 max-w-2xl"><?= htmlspecialchars($user['bio']) ?></p><?php endif; ?>
    </div>
    <div class="w-full md:w-auto text-right">
      <div class="inline-flex items-center gap-2">
        <span class="px-4 py-2 rounded-full bg-[#0f1724] border border-gray-800 text-indigo-300 text-sm">Siswa Aktif</span>
      </div>
    </div>
  </section>

  <!-- Summary Cards -->
  <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-8">
    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-300">Tasks Completed</div>
        <div class="inline-flex items-center justify-center p-2 rounded-lg bg-gradient-to-br from-[#4e6bff] to-[#3b82f6] text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
        </div>
      </div>
      <div class="mt-4 text-4xl font-semibold text-[#4e6bff]"><?= (int)$stats['tasks_completed'] ?></div>
      <div class="mt-2 text-sm text-gray-400">Completed this month</div>
    </div>

    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-300">Attendance</div>
        <div class="inline-flex items-center justify-center p-2 rounded-lg bg-gradient-to-br from-[#4e6bff] to-[#3b82f6] text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z"/></svg>
        </div>
      </div>
      <div class="mt-4 text-4xl font-semibold text-[#4e6bff]"><?= (int)$stats['attendance_present'] ?> / <?= (int)$stats['attendance_total'] ?></div>
      <div class="mt-2 text-sm text-gray-400">Present this month</div>
    </div>

    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-300">Certificates</div>
        <div class="inline-flex items-center justify-center p-2 rounded-lg bg-gradient-to-br from-[#4e6bff] to-[#3b82f6] text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
        </div>
      </div>
      <div class="mt-4 text-4xl font-semibold text-[#4e6bff]"><?= (int)$stats['certificates'] ?></div>
      <div class="mt-2 text-sm text-gray-400">Earned</div>
    </div>

    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-300">Average Grade</div>
        <div class="inline-flex items-center justify-center p-2 rounded-lg bg-gradient-to-br from-[#4e6bff] to-[#3b82f6] text-white">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/></svg>
        </div>
      </div>
      <div class="mt-4 text-4xl font-semibold text-[#4e6bff]"><?= (int)$stats['average_grade'] ?>%</div>
      <div class="mt-2 text-sm text-gray-400">Semester Average</div>
    </div>
  </section>

  <!-- Recent Activities and side panels -->
  <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
    <div class="lg:col-span-2 bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Aktivitas Terbaru</h3>
        <a href="#" class="text-sm text-[#4e6bff]">Lihat semua</a>
      </div>
      <ul class="space-y-3">
        <?php foreach ($activities as $act): ?>
        <li class="bg-[#0f1219] p-3 rounded-lg flex items-start justify-between">
          <div class="flex items-start gap-3">
            <div class="text-[#4e6bff] pt-1">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
            </div>
            <div>
              <div class="text-sm text-gray-300 font-medium"><?= htmlspecialchars($act['title']) ?></div>
              <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($act['meta']) ?></div>
            </div>
          </div>
          <div class="text-xs text-gray-500"><?= htmlspecialchars($act['time']) ?></div>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="space-y-6">
      <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
          <h4 class="text-lg font-semibold">Jadwal Hari Ini</h4>
          <a href="#" class="text-sm text-[#4e6bff]">Lihat semua</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <?php foreach ($schedule as $s): ?>
          <div class="p-3 bg-[#0f1219] rounded-lg">
            <div class="text-sm text-gray-300 font-medium"><?= htmlspecialchars($s['time']) ?></div>
            <div class="text-sm text-gray-400"><?= htmlspecialchars($s['subject']) ?> • <?= htmlspecialchars($s['teacher']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
          <h4 class="text-lg font-semibold">Learning Statistics</h4>
          <a href="#" class="text-sm text-[#4e6bff]">Rincian</a>
        </div>
        <div class="space-y-3">
          <div class="flex items-center justify-between text-sm text-gray-300"><span>Words Read</span><span class="font-semibold text-gray-200"><?= number_format($learning['words_read']) ?></span></div>
          <div class="flex items-center justify-between text-sm text-gray-300"><span>Chapters Completed</span><span class="font-semibold text-gray-200"><?= (int)$learning['chapters'] ?></span></div>
          <div class="flex items-center justify-between text-sm text-gray-300"><span>Streak</span><span class="font-semibold text-[#4e6bff]"><?= (int)$learning['streak'] ?> days</span></div>
          <div class="mt-3 p-3 bg-[#0f1219] rounded-lg text-sm text-gray-400 text-center">
            <canvas id="userProgressChart" width="400" height="180"></canvas>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Certificates and quick links -->
  <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-6">
    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <h4 class="text-lg font-semibold mb-4">Sertifikat</h4>
      <div class="flex gap-3 overflow-x-auto">
        <div class="min-w-[140px] p-3 bg-[#0f1219] rounded-lg text-sm">Best Attendance</div>
        <div class="min-w-[140px] p-3 bg-[#0f1219] rounded-lg text-sm">Olimpiade Kimia</div>
        <div class="min-w-[140px] p-3 bg-[#0f1219] rounded-lg text-sm">Essay Competition</div>
      </div>
    </div>

    <div class="bg-[#11131a] border border-gray-800 rounded-xl p-6">
      <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
      <div class="grid grid-cols-2 gap-3">
        <a class="p-3 rounded-xl bg-[#0f1219] text-center text-sm" href="#">📅 Lihat Jadwal</a>
        <a class="p-3 rounded-xl bg-[#0f1219] text-center text-sm" href="#">📝 Lihat Nilai</a>
        <a class="p-3 rounded-xl bg-[#0f1219] text-center text-sm" href="#">🏅 Lihat Sertifikat</a>
        <a class="p-3 rounded-xl bg-[#0f1219] text-center text-sm" href="#">📚 Dokumentasi</a>
      </div>
    </div>
  </section>

</main>

<!-- Chart.js initialization (dLayout.php should load Chart.js) -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  try{
    const attData = <?= json_encode(array_values($attendanceChart)) ?>; // [present, absent, late]
    const userProgress = document.getElementById('userProgressChart');
    if (userProgress) {
      new Chart(userProgress.getContext('2d'), {
        type: 'doughnut',
        data: { labels: ['Present','Absent','Late'], datasets:[{ data: attData, backgroundColor:['#10b981','#ef4444','#f59e0b'] }] },
        options: { maintainAspectRatio: false, cutout: '70%', plugins:{legend:{display:false}} }
      });
    }
  }catch(e){console.error(e)}
});
</script>

<?php
// end of dashboard_user.php
?>
