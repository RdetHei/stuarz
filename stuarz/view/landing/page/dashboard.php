<div class="p-6 space-y-6 bg-gray-900 min-h-screen">
  <!-- Welcome -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 rounded-2xl shadow">
    <h1 class="text-2xl font-bold">Selamat Datang, <?= htmlspecialchars($user['username']) ?> 🎉</h1>
    <p class="mt-2 text-sm text-gray-200">Senang melihatmu kembali di Stuarz. Semoga harimu produktif!</p>
  </div>

  <!-- Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-3xl font-bold text-blue-600"><?= $user['tasks_completed'] ?? 0 ?></p>
      <p class="text-gray-600 text-sm">Tugas Diselesaikan</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-3xl font-bold text-green-600"><?= $user['attendance'] ?? 0 ?>%</p>
      <p class="text-gray-600 text-sm">Kehadiran</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-3xl font-bold text-purple-600"><?= $user['certificates'] ?? 0 ?></p>
      <p class="text-gray-600 text-sm">Sertifikat</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow text-center">
      <p class="text-3xl font-bold text-orange-600"><?= $user['average_grade'] ?? "-" ?></p>
      <p class="text-gray-600 text-sm">Nilai Rata-rata</p>
    </div>
  </div>

  <!-- Tugas Terbaru -->
  <div class="bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">📘 Tugas Terbaru</h2>
    <ul class="space-y-3">
      <li class="flex justify-between items-center border-b pb-2">
        <span>Matematika - Aljabar</span>
        <span class="text-sm text-gray-500">Deadline: 15 Sept 2025</span>
      </li>
      <li class="flex justify-between items-center border-b pb-2">
        <span>Bahasa Inggris - Essay</span>
        <span class="text-sm text-gray-500">Deadline: 18 Sept 2025</span>
      </li>
      <li class="flex justify-between items-center">
        <span>Biologi - Laporan Praktikum</span>
        <span class="text-sm text-gray-500">Deadline: 20 Sept 2025</span>
      </li>
    </ul>
  </div>

  <!-- Pengumuman -->
  <div class="bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">📢 Pengumuman</h2>
    <div class="space-y-4">
      <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="font-medium text-blue-700">Ujian Tengah Semester dimulai 25 Sept 2025.</p>
      </div>
      <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
        <p class="font-medium text-yellow-700">Perpustakaan akan tutup tanggal 12–14 Sept 2025.</p>
      </div>
    </div>
  </div>
</div>
