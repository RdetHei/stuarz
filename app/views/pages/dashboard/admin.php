<?php
?>

<style>
    .chart-container {
        position: relative;
        height: 280px;
        width: 100%;
    }
    
    .stat-card {
        background: linear-gradient(135deg, rgba(88, 101, 242, 0.1) 0%, rgba(88, 101, 242, 0.05) 100%);
        border: 1px solid rgba(88, 101, 242, 0.2);
    }
</style>

<div class="bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
       
        
        <div class="mb-8">
            <div class="bg-gradient-to-br from-gray-800 to-gray-850 border border-gray-700 rounded-2xl p-8 shadow-xl">
                
                
                <div class="flex items-center gap-5 mb-8">
                    <div class="bg-gray-900/50 border border-gray-700 rounded-2xl p-4 backdrop-blur-sm">
                        <img src="<?= htmlspecialchars(($prefix ?? '') . 'assets/diamond.png', ENT_QUOTES, 'UTF-8') ?>"
                             alt="Logo"
                             class="w-20 h-20 object-contain" />
                    </div>

                    <div>
                        <h1 class="text-4xl font-bold text-white mb-2">Stuarz</h1>
                        <p class="text-gray-400 text-sm">Sistem Informasi Sekolah — Kelola siswa, tugas, dan absensi dengan mudah</p>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

                    <div class="bg-gray-900/30 backdrop-blur-sm border border-blue-500/20 rounded-xl p-5 hover:border-blue-500/40 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Users</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_users']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Active Students</p>
                    </div>

                    <div class="bg-gray-900/30 backdrop-blur-sm border border-purple-500/20 rounded-xl p-5 hover:border-purple-500/40 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Teachers</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_teachers']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Active Teachers</p>
                    </div>

                    <div class="bg-gray-900/30 backdrop-blur-sm border border-emerald-500/20 rounded-xl p-5 hover:border-emerald-500/40 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-emerald-500/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Certificates</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_certificates']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Total Certificates</p>
                    </div>

                    <div class="bg-gray-900/30 backdrop-blur-sm border border-amber-500/20 rounded-xl p-5 hover:border-amber-500/40 transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-amber-500/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Classes</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_classes']) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Active classes</p>
                    </div>

                </div>
            </div>
        </div>

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

        
        <div class="mb-6">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Analytics Dashboard
            </h2>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500/20 to-blue-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Kehadiran Harian</h3>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500/20 to-purple-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Distribusi Nilai</h3>
                </div>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500/20 to-emerald-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Status Tugas</h3>
                </div>
                <div class="chart-container">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500/20 to-amber-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Siswa per Kelas</h3>
                </div>
                <div class="chart-container">
                    <canvas id="studentChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-rose-500/20 to-rose-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Jadwal per Guru</h3>
                </div>
                <div class="chart-container">
                    <canvas id="teacherChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-cyan-500/20 to-cyan-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Siswa Baru</h3>
                </div>
                <div class="chart-container">
                    <canvas id="newStudentChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500/20 to-indigo-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Sertifikat</h3>
                </div>
                <div class="chart-container">
                    <canvas id="certificateChart"></canvas>
                </div>
            </div>

            
            <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-500/20 to-violet-600/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-white">Dokumentasi</h3>
                </div>
                <div class="chart-container">
                    <canvas id="docChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

if (typeof Chart === 'undefined') {
    console.error('Chart.js library is not loaded. Please check if the script is included in the layout.');
}

document.addEventListener('DOMContentLoaded', function() {

    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not available. Charts will not be initialized.');
        return;
    }

    const ctxGrad = (ctx, color1, color2) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };

    const attendanceData = <?= json_encode($data['attendance'] ?? ['labels' => [], 'hadir' => [], 'absen' => [], 'terlambat' => []]) ?>;
    const gradesData = <?= json_encode($data['grades'] ?? ['labels' => [], 'values' => []]) ?>;
    const tasksData = <?= json_encode($data['tasks'] ?? ['completed' => 0, 'pending' => 0]) ?>;
    const studentsData = <?= json_encode($data['students'] ?? ['labels' => [], 'values' => []]) ?>;
    const teachingData = <?= json_encode($data['teaching'] ?? ['labels' => [], 'values' => []]) ?>;
    const newStudentsData = <?= json_encode($data['newStudents'] ?? ['labels' => [], 'values' => []]) ?>;
    const certificatesData = <?= json_encode($data['certificates'] ?? ['labels' => [], 'values' => []]) ?>;
    const documentationData = <?= json_encode($data['documentation'] ?? ['labels' => [], 'values' => []]) ?>;

    if (!attendanceData.labels || attendanceData.labels.length === 0) {
        const last7Days = [];
        for (let i = 6; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            last7Days.push(date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
        }
        attendanceData.labels = last7Days;
        attendanceData.hadir = [0, 0, 0, 0, 0, 0, 0];
        attendanceData.absen = [0, 0, 0, 0, 0, 0, 0];
        attendanceData.terlambat = [0, 0, 0, 0, 0, 0, 0];
    }

    if (!gradesData.labels || gradesData.labels.length === 0) {
        gradesData.labels = ['Tidak ada data'];
        gradesData.values = [0];
    }
    if (!studentsData.labels || studentsData.labels.length === 0) {
        studentsData.labels = ['Tidak ada data'];
        studentsData.values = [0];
    }
    if (!teachingData.labels || teachingData.labels.length === 0) {
        teachingData.labels = ['Tidak ada data'];
        teachingData.values = [0];
    }
    if (!newStudentsData.labels || newStudentsData.labels.length === 0) {
        const last6Months = [];
        for (let i = 5; i >= 0; i--) {
            const date = new Date();
            date.setMonth(date.getMonth() - i);
            last6Months.push(date.toLocaleDateString('id-ID', { month: 'short' }));
        }
        newStudentsData.labels = last6Months;
        newStudentsData.values = [0, 0, 0, 0, 0, 0];
    }
    if (!certificatesData.labels || certificatesData.labels.length === 0) {
        const last6Months = [];
        for (let i = 5; i >= 0; i--) {
            const date = new Date();
            date.setMonth(date.getMonth() - i);
            last6Months.push(date.toLocaleDateString('id-ID', { month: 'short' }));
        }
        certificatesData.labels = last6Months;
        certificatesData.values = [0, 0, 0, 0, 0, 0];
    }
    if (!documentationData.labels || documentationData.labels.length === 0) {
        documentationData.labels = ['Tidak ada data'];
        documentationData.values = [0];
    }

    const chartData = {
        attendance: {
            labels: attendanceData.labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: attendanceData.hadir,
                    borderColor: '#3b82f6',
                    backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(59,130,246,0.3)', 'rgba(59,130,246,0.0)'),
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Absen',
                    data: attendanceData.absen,
                    borderColor: '#ef4444',
                    backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(239,68,68,0.25)', 'rgba(239,68,68,0.0)'),
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                },
                {
                    label: 'Terlambat',
                    data: attendanceData.terlambat,
                    borderColor: '#f59e0b',
                    backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(245,158,11,0.25)', 'rgba(245,158,11,0.0)'),
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }
            ]
        },
        grades: {
            labels: gradesData.labels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: gradesData.values,
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                    gradient.addColorStop(0, '#a855f7');
                    gradient.addColorStop(1, '#7c3aed');
                    return gradient;
                },
                borderColor: '#a855f7',
                borderWidth: 0,
                borderRadius: 8,
                barThickness: 32
            }]
        },
        tasks: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                data: [tasksData.completed || 0, tasksData.pending || 0],
                backgroundColor: ['#10b981', '#f59e0b'],
                hoverBackgroundColor: ['#059669', '#d97706'],
                borderWidth: 4,
                borderColor: '#1f2937',
                cutout: '75%'
            }]
        },
        students: {
            labels: studentsData.labels,
            datasets: [{
                label: 'Jumlah Siswa',
                data: studentsData.values,
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 280, 0);
                    gradient.addColorStop(0, '#f59e0b');
                    gradient.addColorStop(1, '#d97706');
                    return gradient;
                },
                borderRadius: 6,
                barThickness: 24
            }]
        },
        teaching: {
            labels: teachingData.labels,
            datasets: [{
                label: 'Jumlah Jadwal',
                data: teachingData.values,
                backgroundColor: (ctx) => {
                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 280);
                    gradient.addColorStop(0, '#f43f5e');
                    gradient.addColorStop(1, '#be123c');
                    return gradient;
                },
                borderRadius: 8,
                barThickness: 32
            }]
        },
        newStudents: {
            labels: newStudentsData.labels,
            datasets: [{
                label: 'Siswa Baru',
                data: newStudentsData.values,
                borderColor: '#06b6d4',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(6,182,212,0.3)', 'rgba(6,182,212,0.0)'),
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#06b6d4',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 7,
                borderWidth: 3
            }]
        },
        certificates: {
            labels: certificatesData.labels,
            datasets: [{
                label: 'Sertifikat',
                data: certificatesData.values,
                backgroundColor: (ctx) => {
                    const colors = ['#6366f1', '#8b5cf6', '#a855f7', '#c084fc'];
                    return colors[ctx.dataIndex % colors.length];
                },
                borderRadius: 8,
                barThickness: 32
            }]
        },
        documentation: {
            labels: documentationData.labels,
            datasets: [{
                data: documentationData.values,
                backgroundColor: ['#8b5cf6', '#10b981', '#f59e0b', '#ef4444'],
                hoverBackgroundColor: ['#7c3aed', '#059669', '#d97706', '#dc2626'],
                borderWidth: 4,
                borderColor: '#1f2937'
            }]
        }
    };

    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 1000,
            easing: 'easeInOutQuart'
        },
        plugins: {
            legend: {
                labels: {
                    color: '#9ca3af',
                    font: { size: 12, family: 'system-ui, -apple-system, sans-serif', weight: '500' },
                    padding: 16,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                titleColor: '#f3f4f6',
                bodyColor: '#d1d5db',
                borderColor: 'rgba(75, 85, 99, 0.5)',
                borderWidth: 1,
                padding: 12,
                cornerRadius: 8,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 }
            }
        },
        scales: {
            x: {
                grid: { 
                    color: 'rgba(75, 85, 99, 0.15)', 
                    drawBorder: false,
                    lineWidth: 1
                },
                ticks: { 
                    color: '#9ca3af', 
                    font: { size: 11, weight: '500' },
                    padding: 8
                }
            },
            y: {
                grid: { 
                    color: 'rgba(75, 85, 99, 0.15)', 
                    drawBorder: false,
                    lineWidth: 1
                },
                ticks: { 
                    color: '#9ca3af', 
                    font: { size: 11, weight: '500' },
                    padding: 8
                }
            }
        }
    };

    const doughnutOptions = {
        ...defaultOptions,
        plugins: { 
            legend: { 
                position: 'bottom',
                labels: {
                    ...defaultOptions.plugins.legend.labels,
                    padding: 20
                }
            },
            tooltip: defaultOptions.plugins.tooltip
        },
        scales: undefined
    };

    function createChart(canvasId, type, data, options) {
        try {
            const canvas = document.getElementById(canvasId);
            if (!canvas) {
                console.warn(`Canvas element '${canvasId}' not found`);
                return null;
            }
            return new Chart(canvas, { 
                type: type, 
                data: data, 
                options: options 
            });
        } catch (error) {
            console.error(`Error creating chart '${canvasId}':`, error);
            return null;
        }
    }

    createChart('attendanceChart', 'line', chartData.attendance, defaultOptions);
    createChart('gradeChart', 'bar', chartData.grades, defaultOptions);
    createChart('taskChart', 'doughnut', chartData.tasks, doughnutOptions);
    createChart('studentChart', 'bar', chartData.students, { ...defaultOptions, indexAxis: 'y' });
    createChart('teacherChart', 'bar', chartData.teaching, defaultOptions);
    createChart('newStudentChart', 'line', chartData.newStudents, defaultOptions);
    createChart('certificateChart', 'bar', chartData.certificates, defaultOptions);
    createChart('docChart', 'pie', chartData.documentation, doughnutOptions);
});
</script>