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

<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white">Dashboard Overview</h1>
                <p class="text-gray-400 mt-1">Welcome back, Admin</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="p-2.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors border border-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
                <button onclick="window.location.href='index.php?page=logout'" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 rounded-lg transition-colors font-medium">
                    Logout
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-indigo-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total Users</p>
                    <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_users']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Total Students</p>
                    <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_students']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Certificates -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-yellow-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Certificates</p>
                    <h3 class="text-3xl font-bold text-white"><?= number_format($stats['total_certificates']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Average Grade -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-600/20 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm font-medium">Average Grade</p>
                    <h3 class="text-3xl font-bold text-white"><?= number_format($stats['average_grade'], 1) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Kehadiran Harian
            </h3>
            <div class="chart-container">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Average Grade Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Distribusi Nilai Rata-rata
            </h3>
            <div class="chart-container">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>

        <!-- Task Status Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Status Tugas
            </h3>
            <div class="chart-container">
                <canvas id="taskChart"></canvas>
            </div>
        </div>

        <!-- Students per Class Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Jumlah Siswa per Kelas
            </h3>
            <div class="chart-container">
                <canvas id="studentChart"></canvas>
            </div>
        </div>

        <!-- Teaching Schedule Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Jadwal Mengajar per Guru
            </h3>
            <div class="chart-container">
                <canvas id="teacherChart"></canvas>
            </div>
        </div>

        <!-- New Students Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Siswa Baru per Bulan
            </h3>
            <div class="chart-container">
                <canvas id="newStudentChart"></canvas>
            </div>
        </div>

        <!-- Certificates Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
                Sertifikat Diterbitkan per Bulan
            </h3>
            <div class="chart-container">
                <canvas id="certificateChart"></canvas>
            </div>
        </div>

        <!-- Documentation Chart -->
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2 text-white">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Dokumentasi per Section
            </h3>
            <div class="chart-container">
                <canvas id="docChart"></canvas>
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
                borderColor: '#4f46e5',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(99,102,241,0.35)', 'rgba(99,102,241,0.05)'),
                fill: true,
                tension: 0.35
            },
            {
                label: 'Absen',
                data: <?= json_encode($data['attendance']['absen']) ?>,
                borderColor: '#ef4444',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(239,68,68,0.3)', 'rgba(239,68,68,0.05)'),
                fill: true,
                tension: 0.35
            },
            {
                label: 'Terlambat',
                data: <?= json_encode($data['attendance']['terlambat']) ?>,
                borderColor: '#f59e0b',
                backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(245,158,11,0.3)', 'rgba(245,158,11,0.05)'),
                fill: true,
                tension: 0.35
            }
        ]
    },
    grades: {
        labels: <?= json_encode($data['grades']['labels']) ?>,
        datasets: [{
            label: 'Rata-rata Nilai',
            data: <?= json_encode($data['grades']['values']) ?>,
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, '#6366f1', '#4f46e5'),
            borderColor: '#818cf8',
            borderWidth: 1.5,
            borderRadius: 6
        }]
    },
    tasks: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [<?= $data['tasks']['completed'] ?>, <?= $data['tasks']['pending'] ?>],
            backgroundColor: ['#22c55e', '#f59e0b'],
            hoverBackgroundColor: ['#16a34a', '#d97706'],
            borderWidth: 0,
            cutout: '65%'
        }]
    },
    students: {
        labels: <?= json_encode($data['students']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Siswa',
            data: <?= json_encode($data['students']['values']) ?>,
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, '#4f46e5', '#3730a3'),
            borderRadius: 8,
            barThickness: 22
        }]
    },
    teaching: {
        labels: <?= json_encode($data['teaching']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Jadwal',
            data: <?= json_encode($data['teaching']['values']) ?>,
            backgroundColor: '#6366f1',
            borderRadius: 6,
            barThickness: 20
        }]
    },
    newStudents: {
        labels: <?= json_encode($data['newStudents']['labels']) ?>,
        datasets: [{
            label: 'Siswa Baru',
            data: <?= json_encode($data['newStudents']['values']) ?>,
            borderColor: '#6366f1',
            backgroundColor: (ctx) => ctxGrad(ctx.chart.ctx, 'rgba(99,102,241,0.25)', 'rgba(99,102,241,0.05)'),
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#6366f1',
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    certificates: {
        labels: <?= json_encode($data['certificates']['labels']) ?>,
        datasets: [{
            label: 'Sertifikat',
            data: <?= json_encode($data['certificates']['values']) ?>,
            backgroundColor: ['#4f46e5', '#4338ca', '#3730a3', '#312e81'],
            borderRadius: 5
        }]
    },
    documentation: {
        labels: <?= json_encode($data['documentation']['labels']) ?>,
        datasets: [{
            data: <?= json_encode($data['documentation']['values']) ?>,
            backgroundColor: ['#4f46e5', '#22c55e', '#f59e0b', '#ef4444'],
            borderWidth: 0
        }]
    }
};

// Default chart style
const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    animation: {
        duration: 900,
        easing: 'easeOutCubic'
    },
    plugins: {
        legend: {
            labels: {
                color: '#9ca3af',
                font: { size: 12, family: 'Inter, sans-serif' }
            }
        }
    },
    scales: {
        x: {
            grid: { color: 'rgba(75, 85, 99, 0.15)', drawBorder: false },
            ticks: { color: '#9ca3af', font: { size: 11 } }
        },
        y: {
            grid: { color: 'rgba(75, 85, 99, 0.15)', drawBorder: false },
            ticks: { color: '#9ca3af', font: { size: 11 } }
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
