<div class="flex bg-gray-900 min-h-screen text-gray-100">

  <!-- Main Content -->
  <main class="flex-1 p-6 space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold">Overview</h2>
      <div class="flex items-center gap-3">
      </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
      <div class="bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-sm text-gray-400">Total Siswa</h3>
        <p class="text-2xl font-bold">120</p>
      </div>
      <div class="bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-sm text-gray-400">Total Guru</h3>
        <p class="text-2xl font-bold">15</p>
      </div>
      <div class="bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-sm text-gray-400">Tugas Selesai</h3>
        <p class="text-2xl font-bold">320</p>
      </div>
      <div class="bg-gray-800 p-4 rounded-xl shadow hover:shadow-lg transition">
        <h3 class="text-sm text-gray-400">Absensi Hari Ini</h3>
        <p class="text-2xl font-bold">98%</p>
      </div>
    </div>

    <!-- Chart & Announcements -->
    <div class="grid gap-6 lg:grid-cols-3">
      <!-- Chart -->
      <div class="lg:col-span-2 bg-gray-800 p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold mb-4">📈 Statistik Absensi Mingguan</h3>
        <canvas id="attendanceChart" class="w-full h-64"></canvas>
      </div>

      <!-- Announcements -->
      <div class="bg-gray-800 p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold mb-4">📢 Pengumuman Terbaru</h3>
        <ul class="space-y-3">
          <li class="p-3 bg-gray-700 rounded-lg">Ujian Matematika minggu depan</li>
          <li class="p-3 bg-gray-700 rounded-lg">Libur Nasional tanggal 20</li>
          <li class="p-3 bg-gray-700 rounded-lg">Pengumpulan tugas PPKN hari Jumat</li>
        </ul>
      </div>
    </div>
  </main>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('attendanceChart');
new Chart(ctx, {
  type: 'line',
  data: {
    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
    datasets: [{
      label: 'Absensi (%)',
      data: [95, 97, 92, 94, 98],
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59, 130, 246, 0.2)',
      tension: 0.3,
      fill: true
    }]
  },
  options: { responsive: true }
});
</script>
