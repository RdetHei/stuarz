<?php
// Class detail page
// Expected: $class (array), $members (array), $schedules (array), $sessionUser
$sessionUser = $_SESSION['user'] ?? [];
$class = $class ?? [];
$members = $members ?? [];
$schedules = $schedules ?? [];
$role = $role ?? ($class['member_role'] ?? ($sessionUser['level'] ?? 'student'));

// Role colors
$roleColors = [
  'admin' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
  'teacher' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'guru' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'student' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400']
];
$colors = $roleColors[$role] ?? $roleColors['student'];
?>

<div class="min-h-screen bg-gray-900 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <header class="mb-8">
      <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
          
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
              <div class="p-3 rounded-xl bg-blue-500/10 border border-blue-500/20 ring-1 ring-blue-500/20 shadow-lg shadow-blue-500/10">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
              </div>
              <h1 class="text-3xl font-bold text-white tracking-tight"><?= htmlspecialchars($class['name'] ?? 'Kelas', ENT_QUOTES, 'UTF-8') ?></h1>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium <?= $colors['bg'] ?> <?= $colors['border'] ?> <?= $colors['text'] ?> border">
                <?php if ($role === 'admin'): ?>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                <?php elseif ($role === 'teacher' || $role === 'guru'): ?>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                <?php else: ?>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                  </svg>
                <?php endif; ?>
                <span class="capitalize"><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></span>
              </span>
              
              <?php if (!empty($class['code'])): ?>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-mono font-medium bg-gray-700/60 hover:bg-gray-700 text-gray-300 border border-gray-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <?= htmlspecialchars($class['code'], ENT_QUOTES, 'UTF-8') ?>
              </span>
              <?php endif; ?>
            </div>
          </div>

          <div class="flex gap-3">
            <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
            <button id="addStudentBtn" class="px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors flex items-center gap-2 shadow-lg shadow-blue-500/20">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah Siswa
            </button>
            <?php endif; ?>
            
            <a href="index.php?page=class" class="px-5 py-2.5 rounded-lg bg-gray-700 hover:bg-gray-600 text-white font-medium transition-colors flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
              </svg>
              Kembali
            </a>
          </div>

        </div>
      </div>
    </header>

    <!-- Add Student Modal -->
    <div id="addStudentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
      <div class="max-w-md w-full bg-gray-800 rounded-lg p-6 border border-gray-700">
        <h3 class="text-lg font-bold text-white mb-3">Tambah Siswa ke Kelas</h3>
        <form id="addStudentForm" action="index.php?page=class_add_member" method="post">
          <input type="hidden" name="class_id" value="<?= intval($class['id'] ?? 0) ?>" />
          <div class="mb-3">
            <label class="block text-sm text-gray-300 mb-1">User ID</label>
            <input name="user_id" type="number" required class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700 text-white" placeholder="Masukkan ID siswa" />
          </div>
          <div class="mb-4">
            <label class="block text-sm text-gray-300 mb-1">Role</label>
            <select name="role" class="w-full px-3 py-2 rounded bg-gray-900 border border-gray-700 text-white">
              <option value="student" selected>Student</option>
              <option value="teacher">Teacher</option>
            </select>
          </div>
          <div class="flex justify-end gap-2">
            <button type="button" id="cancelAddStudent" class="px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">Batal</button>
            <button type="submit" class="px-4 py-2 rounded bg-blue-500 hover:bg-blue-600 text-white">Tambah</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 bg-gray-800 border border-gray-700 rounded-xl p-2 shadow-lg">
      <div class="flex flex-wrap gap-2">
        <button class="tab-btn px-4 py-2.5 rounded-lg text-sm font-medium transition-all bg-gray-700 text-white" data-tab="overview">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Overview
          </span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all" data-tab="jadwal">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Jadwal
          </span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all" data-tab="anggota">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Anggota
          </span>
        </button>
        <button class="tab-btn px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all" data-tab="tugas">
          <span class="flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Tugas
          </span>
        </button>
        
      </div>
    </div>

    <!-- Tab Contents -->
    <div id="tab-contents">
      
      <!-- Overview Tab -->
      <div data-tab-content="overview">
        <div class="grid gap-6 lg:grid-cols-3">
          
          <!-- Main Content -->
          <div class="lg:col-span-2 space-y-6">
            
            <!-- Attendance Quick Actions -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
              <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-emerald-500/10">
                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-bold text-white">Absensi Kelas Ini</h3>
                    <p class="text-xs text-gray-400">Catatan kehadiran akan otomatis dikaitkan dengan kelas ini.</p>
                  </div>
                </div>
                <a href="index.php?page=attendance" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Lihat Riwayat →</a>
              </div>
              <div class="flex flex-wrap items-center gap-3">
                <button id="classCheckInBtn" data-class-id="<?= intval($class['id'] ?? 0) ?>" class="px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                  </svg>
                  Check In
                </button>
                <button id="classCheckOutBtn" data-class-id="<?= intval($class['id'] ?? 0) ?>" class="px-5 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors flex items-center gap-2">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                  </svg>
                  Check Out
                </button>
                <p class="text-xs text-gray-500">Tekan setelah masuk / selesai sesi belajar.</p>
              </div>
              <div id="classAttendanceMessage" class="mt-4"></div>
            </div>
            
            <!-- Description Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 rounded-lg bg-blue-500/10">
                  <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                  </svg>
                </div>
                <h3 class="text-lg font-bold text-white">Deskripsi Kelas</h3>
              </div>
              <p class="text-sm text-gray-300 leading-relaxed"><?= nl2br(htmlspecialchars($class['description'] ?? 'Tidak ada deskripsi untuk kelas ini.', ENT_QUOTES, 'UTF-8')) ?></p>
            </div>

            <!-- Recent Activity Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-purple-500/10">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                  </div>
                  <h3 class="text-lg font-bold text-white">Aktivitas Terbaru</h3>
                </div>
                <a href="#" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Lihat semua →</a>
              </div>
              
              <div class="space-y-3">
                <div class="text-center py-8">
                  <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-700/50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                  </div>
                  <p class="text-sm text-gray-400">Belum ada aktivitas</p>
                </div>
              </div>
            </div>

          </div>

          <!-- Sidebar -->
          <aside class="space-y-6">
            
            

            <!-- Stats Card -->
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
              <div class="flex items-center gap-3 mb-4">
                <div class="p-2 rounded-lg bg-amber-500/10">
                  <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                  </svg>
                </div>
                <h3 class="text-sm font-bold text-white">Statistik</h3>
              </div>
              <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
                  <span class="text-sm text-gray-400">Jumlah Murid</span>
                  <span class="text-sm font-bold text-white"><?= intval($class['members_count'] ?? count($members)) ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
                  <span class="text-sm text-gray-400">Jumlah Tugas</span>
                  <span class="text-sm font-bold text-white"><?= intval($class['tasks_count'] ?? 0) ?></span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-750 rounded-lg border border-gray-700">
                  <span class="text-sm text-gray-400">Jumlah Jadwal</span>
                  <span class="text-sm font-bold text-white"><?= intval(count($schedules)) ?></span>
                </div>
              </div>
            </div>

          </aside>

        </div>
      </div>

      <!-- Members Tab -->
      <div data-tab-content="anggota" class="hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
          <?php $members = $members; $class_id = $class['id'] ?? 0; include __DIR__ . '/../../components/class/MemberList.php'; ?>
        </div>
      </div>

      <!-- Schedule Tab -->
      <div data-tab-content="jadwal" class="hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
          <?php $schedules = $schedules; include __DIR__ . '/../../components/class/ScheduleTable.php'; ?>
          <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
            <div class="mt-6 text-right">
              <a href="index.php?page=schedule/create&class_id=<?= intval($class['id'] ?? 0) ?>" class="px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Jadwal
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Tasks Tab -->
      <div data-tab-content="tugas" class="hidden">
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 shadow-lg">
          <?php 
          $classTasks = $tasks ?? [];
          $userLevel = $_SESSION['level'] ?? 'user';
          $userId = $_SESSION['user_id'] ?? 0;
          ?>
          
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-bold text-white">Daftar Tugas</h3>
              <p class="text-sm text-gray-400">Tugas untuk kelas <?= htmlspecialchars($class['name'] ?? '') ?></p>
            </div>
            <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
            <a href="index.php?page=tasks/create&class_id=<?= intval($class['id'] ?? 0) ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Buat Tugas Baru
            </a>
            <?php endif; ?>
          </div>

          <?php if (!empty($classTasks)): ?>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gray-900 border-b border-gray-700">
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Judul</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Mata Pelajaran</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Guru</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Deadline</th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase">Status</th>
                  <th class="px-4 py-3 text-right text-xs font-semibold text-gray-400 uppercase">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-700">
                <?php foreach ($classTasks as $t): ?>
                <tr class="hover:bg-gray-700/50 transition-colors">
                  <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                      </svg>
                      <span class="text-white font-medium"><?= htmlspecialchars($t['title'] ?? '') ?></span>
                    </div>
                  </td>
                  <td class="px-4 py-3 text-gray-400 text-sm"><?= htmlspecialchars($t['subject_name'] ?? '-') ?></td>
                  <td class="px-4 py-3 text-gray-400 text-sm"><?= htmlspecialchars($t['teacher_name'] ?? '-') ?></td>
                  <td class="px-4 py-3 text-gray-400 text-sm">
                    <?= htmlspecialchars($t['deadline'] ?? '-') ?>
                  </td>
                  <td class="px-4 py-3">
                    <?php 
                    $workflowState = strtolower($t['workflow_state'] ?? 'published');
                    $stateLabels = [
                      'draft' => ['Draft','bg-gray-600/30 text-gray-200 border border-gray-500/40'],
                      'published' => ['Published','bg-indigo-600/20 text-indigo-200 border border-indigo-500/40'],
                      'in_review' => ['In Review','bg-blue-600/20 text-blue-200 border border-blue-500/40'],
                      'closed' => ['Closed','bg-gray-500/30 text-gray-100 border border-gray-400/30'],
                    ];
                    $stateMeta = $stateLabels[$workflowState] ?? $stateLabels['published'];
                    ?>
                    <span class="px-2 py-1 rounded-full text-xs <?= $stateMeta[1] ?>"><?= $stateMeta[0] ?></span>
                  </td>
                  <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                      <?php 
                      $canEdit = ($userLevel === 'admin') || ($userLevel === 'guru' && ($t['user_id'] ?? 0) == $userId);
                      ?>
                      <?php if ($canEdit): ?>
                      <a href="index.php?page=tasks/edit&id=<?= $t['id'] ?>" 
                         class="p-2 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all" 
                         title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </a>
                      <?php endif; ?>
                      <a href="index.php?page=tasks" 
                         class="p-2 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-600/30 text-blue-400 rounded-lg transition-all" 
                         title="Lihat Detail">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php else: ?>
          <div class="text-center py-12">
            <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-700/50 flex items-center justify-center">
              <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-white mb-2">Belum Ada Tugas</h3>
            <p class="text-sm text-gray-400 mb-6">Tidak ada tugas yang tersedia untuk kelas ini.</p>
            <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
            <a href="index.php?page=tasks/create&class_id=<?= intval($class['id'] ?? 0) ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-medium transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Buat Tugas Baru
            </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>

      

    </div>

  </div>
</div>

<script>
// Tab switching logic with active state
document.addEventListener('DOMContentLoaded', function(){
  var tabBtns = document.querySelectorAll('.tab-btn');
  var contents = document.querySelectorAll('[data-tab-content]');
  
  tabBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
      var tabId = btn.getAttribute('data-tab');
      
      // Update button states
      tabBtns.forEach(function(b){
        b.classList.remove('bg-gray-700', 'text-white');
        b.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-700/50');
      });
      btn.classList.add('bg-gray-700', 'text-white');
      btn.classList.remove('text-gray-400', 'hover:text-white', 'hover:bg-gray-700/50');
      
      // Update content visibility
      contents.forEach(function(content){
        if (content.getAttribute('data-tab-content') === tabId) {
          content.classList.remove('hidden');
        } else {
          content.classList.add('hidden');
        }
      });
    });
  });

  var classCheckInBtn = document.getElementById('classCheckInBtn');
  var classCheckOutBtn = document.getElementById('classCheckOutBtn');
  var classAttendanceMessage = document.getElementById('classAttendanceMessage');
  function showClassAttendanceMessage(message, isSuccess) {
    if (!classAttendanceMessage) return;
    classAttendanceMessage.innerHTML = `
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg ${isSuccess ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          ${isSuccess ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'}
        </svg>
        <span>${message}</span>
      </div>
    `;
  }
  async function handleClassAttendance(type) {
    var classIdSource = (classCheckInBtn && classCheckInBtn.dataset.classId) || (classCheckOutBtn && classCheckOutBtn.dataset.classId) || '0';
    var classId = parseInt(classIdSource, 10);
    if (!classId) {
      showClassAttendanceMessage('Kelas tidak valid untuk absensi.', false);
      return;
    }
    try {
      var response = await fetch(`index.php?page=attendance_${type}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `class_id=${classId}`
      });
      var data = await response.json();
      showClassAttendanceMessage(data.message || 'Permintaan diproses.', !!data.success);
    } catch (err) {
      console.error('Attendance error:', err);
      showClassAttendanceMessage('Terjadi kesalahan saat mencatat absensi.', false);
    }
  }
  if (classCheckInBtn) {
    classCheckInBtn.addEventListener('click', function(){ handleClassAttendance('checkin'); });
  }
  if (classCheckOutBtn) {
    classCheckOutBtn.addEventListener('click', function(){ handleClassAttendance('checkout'); });
  }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
  var addBtn = document.getElementById('addStudentBtn');
  var modal = document.getElementById('addStudentModal');
  var cancel = document.getElementById('cancelAddStudent');
  if (addBtn && modal) {
    addBtn.addEventListener('click', function(){ modal.classList.remove('hidden'); });
  }
  if (cancel && modal) {
    cancel.addEventListener('click', function(){ modal.classList.add('hidden'); });
  }
  // Close modal when clicking outside the dialog
  if (modal) {
    modal.addEventListener('click', function(e){ if (e.target === modal) modal.classList.add('hidden'); });
  }
});
</script>

</div>
