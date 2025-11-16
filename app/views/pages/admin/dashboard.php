<?php
// Partial view: only the content inserted into layouts/dLayout.php
// Expects $stats and $data to be provided by the controller.
?>

<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-100">Dashboard Overview</h1>
                    <p class="text-gray-400 mt-1">Welcome back, Admin</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.location.href='index.php?page=notifications'" 
                            class="p-2 text-gray-400 hover:text-gray-200 hover:bg-[#1f2937] rounded-lg transition-colors border border-gray-700"
                            title="Notifications">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    <button onclick="window.location.href='index.php?page=logout'" 
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg transition-colors font-medium text-sm text-white">
                        Logout
                    </button>
                </div>
            </div>
        </div>

        <!-- Announcement Box -->
        <?php if (!empty($latestAnnouncement)): ?>
        <div class="mb-6 flex justify-end">
            <div class="w-full max-w-md">
                <a href="index.php?page=announcement&id=<?= (int)($latestAnnouncement['id'] ?? 0) ?>" 
                   class="block bg-[#1f2937] border-l-4 border-[#5865F2] rounded-lg p-4 hover:bg-gray-800 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#5865F2]/10 flex items-center justify-center border border-[#5865F2]/20 flex-shrink-0">
                            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                Pengumuman Terbaru
                            </div>
                            <h4 class="text-base font-semibold text-gray-100 mb-1 line-clamp-2">
                                <?= htmlspecialchars($latestAnnouncement['title'] ?? '-') ?>
                            </h4>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= htmlspecialchars(date('d F Y', strtotime($latestAnnouncement['created_at'] ?? ''))) ?>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-600 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Combined Stats Card -->
        <div class="mb-6">
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg hover:border-gray-600 transition-colors">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <!-- Total Users -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-[#5865F2]/10 rounded-lg flex items-center justify-center border border-[#5865F2]/20">
                            <svg class="w-6 h-6 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Total Users</p>
                            <h3 class="text-2xl font-bold text-gray-100"><?= number_format($stats['total_users']) ?></h3>
                        </div>
                    </div>

                    <!-- Total Students -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center border border-emerald-500/20">
                            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Students</p>
                            <h3 class="text-2xl font-bold text-gray-100"><?= number_format($stats['total_students']) ?></h3>
                        </div>
                    </div>

                    <!-- Total Certificates -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-lg flex items-center justify-center border border-amber-500/20">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Certificates</p>
                            <h3 class="text-2xl font-bold text-gray-100"><?= number_format($stats['total_certificates']) ?></h3>
                        </div>
                    </div>

                    <!-- Average Grade -->
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-rose-500/10 rounded-lg flex items-center justify-center border border-rose-500/20">
                            <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs font-medium uppercase tracking-wider">Avg Grade</p>
                            <h3 class="text-2xl font-bold text-gray-100"><?= number_format($stats['average_grade'], 1) ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Attendance Chart (long) -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Kehadiran Harian</h3>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Average Grade Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Distribusi Nilai Rata-rata</h3>
                </div>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            <!-- Task Status Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Status Tugas</h3>
                </div>
                <div class="chart-container">
                    <canvas id="taskChart"></canvas>
                </div>
            </div>

            <!-- Students per Class Chart (long) -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Siswa per Kelas</h3>
                </div>
                <div class="chart-container">
                    <canvas id="studentChart"></canvas>
                </div>
            </div>

            <!-- Teaching Schedule Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Jadwal per Guru</h3>
                </div>
                <div class="chart-container">
                    <canvas id="teacherChart"></canvas>
                </div>
            </div>

            <!-- New Students Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Siswa Baru per Bulan</h3>
                </div>
                <div class="chart-container">
                    <canvas id="newStudentChart"></canvas>
                </div>
            </div>

            <!-- Certificates Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Sertifikat per Bulan</h3>
                </div>
                <div class="chart-container">
                    <canvas id="certificateChart"></canvas>
                </div>
            </div>

            <!-- Documentation Chart -->
            <div class="bg-[#1f2937] border border-gray-700 p-5 rounded-lg">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-100">Dokumentasi per Section</h3>
                </div>
                <div class="chart-container">
                    <canvas id="docChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctxGrad = (ctx, color1, color2) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
};

const chartData = {
    attendance: {
        labels: <?= json_encode($data['attendance']['labels']) ?>,
        datasets: [
            {
                label: 'Hadir',
                data: <?= json_encode($data['attendance']['hadir']) ?>,
                borderColor: '#5865F2',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(88,101,242,0.3)', 'rgba(88,101,242,0.05)'),
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'Absen',
                data: <?= json_encode($data['attendance']['absen']) ?>,
                borderColor: '#ef4444',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(239,68,68,0.25)', 'rgba(239,68,68,0.05)'),
                fill: true,
                tension: 0.4,
                borderWidth: 2
            },
            {
                label: 'Terlambat',
                data: <?= json_encode($data['attendance']['terlambat']) ?>,
                borderColor: '#f59e0b',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(245,158,11,0.25)', 'rgba(245,158,11,0.05)'),
                fill: true,
                tension: 0.4,
                borderWidth: 2
            }
        ]
    },
    grades: {
        labels: <?= json_encode($data['grades']['labels']) ?>,
        datasets: [{
            label: 'Rata-rata Nilai',
            data: <?= json_encode($data['grades']['values']) ?>,
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, '#5865F2', '#4752C4'),
            borderColor: '#5865F2',
            borderWidth: 0,
            borderRadius: 6
        }]
    },
    tasks: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [<?= $data['tasks']['completed'] ?>, <?= $data['tasks']['pending'] ?>],
            backgroundColor: ['#10b981', '#f59e0b'],
            hoverBackgroundColor: ['#059669', '#d97706'],
            borderWidth: 0,
            cutout: '70%'
        }]
    },
    students: {
        labels: <?= json_encode($data['students']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Siswa',
            data: <?= json_encode($data['students']['values']) ?>,
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, '#5865F2', '#4752C4'),
            borderRadius: 6,
            barThickness: 18
        }]
    },
    teaching: {
        labels: <?= json_encode($data['teaching']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Jadwal',
            data: <?= json_encode($data['teaching']['values']) ?>,
            backgroundColor: '#5865F2',
            borderRadius: 6,
            barThickness: 18
        }]
    },
    newStudents: {
        labels: <?= json_encode($data['newStudents']['labels']) ?>,
        datasets: [{
            label: 'Siswa Baru',
            data: <?= json_encode($data['newStudents']['values']) ?>,
            borderColor: '#5865F2',
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(88,101,242,0.2)', 'rgba(88,101,242,0.05)'),
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#5865F2',
            pointRadius: 3,
            pointHoverRadius: 5,
            borderWidth: 2
        }]
    },
    certificates: {
        labels: <?= json_encode($data['certificates']['labels']) ?>,
        datasets: [{
            label: 'Sertifikat',
            data: <?= json_encode($data['certificates']['values']) ?>,
            backgroundColor: ['#5865F2', '#4752C4', '#3c44b8', '#3137a3'],
            borderRadius: 5,
            barThickness: 18
        }]
    },
    documentation: {
        labels: <?= json_encode($data['documentation']['labels']) ?>,
        datasets: [{
            data: <?= json_encode($data['documentation']['values']) ?>,
            backgroundColor: ['#5865F2', '#10b981', '#f59e0b', '#ef4444'],
            borderWidth: 0
        }]
    }
};

// Default chart style
const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: {
        duration: 800,
        easing: 'easeOutQuart'
    },
    plugins: {
        legend: {
            labels: {
                color: '#9ca3af',
                font: { size: 11, family: 'Inter, system-ui, sans-serif' },
                padding: 12,
                usePointStyle: true
            }
        }
    },
    scales: {
        x: {
            grid: { color: 'rgba(75, 85, 99, 0.1)', drawBorder: false },
            ticks: { color: '#6b7280', font: { size: 10 } }
        },
        y: {
            grid: { color: 'rgba(75, 85, 99, 0.1)', drawBorder: false },
            ticks: { color: '#6b7280', font: { size: 10 } }
        }
    }
};

// Render charts
new Chart(document.getElementById('attendanceChart'), { type: 'line', data: chartData.attendance, options: defaultOptions });
new Chart(document.getElementById('gradeChart'), { type: 'bar', data: chartData.grades, options: defaultOptions });
new Chart(document.getElementById('taskChart'), { type: 'doughnut', data: chartData.tasks, options: { ...defaultOptions, plugins: { legend: { position: 'bottom' } } } });
new Chart(document.getElementById('studentChart'), { type: 'bar', data: chartData.students, options: { ...defaultOptions, indexAxis: 'y' } });
new Chart(document.getElementById('teacherChart'), { type: 'bar', data: chartData.teaching, options: defaultOptions });
new Chart(document.getElementById('newStudentChart'), { type: 'line', data: chartData.newStudents, options: defaultOptions });
new Chart(document.getElementById('certificateChart'), { type: 'bar', data: chartData.certificates, options: defaultOptions });
new Chart(document.getElementById('docChart'), { type: 'bar', data: chartData.documentation, options: { ...defaultOptions, plugins: { legend: { position: 'bottom' } } } });
</script>
