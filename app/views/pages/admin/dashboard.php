<?php
// Partial view: only the content inserted into layouts/dLayout.php (which already
// includes the top-level <html>/<head> and the sidebar/header components).
// Expects $stats and $data to be provided by the controller.
?>

<style>
    /* chart container used by Chart.js instances */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="p-6">
    <!-- Page title / small header inside content area -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Dashboard Overview</h1>
            <p class="text-gray-400">Welcome back, Admin</p>
        </div>
        <div class="flex items-center gap-4">
            <button class="p-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <button onclick="window.location.href='index.php?page=logout'" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                Logout
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Users</p>
                    <h3 class="text-2xl font-bold"><?= number_format($stats['total_users']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Students</p>
                    <h3 class="text-2xl font-bold"><?= number_format($stats['total_students']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Total Certificates -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Total Certificates</p>
                    <h3 class="text-2xl font-bold"><?= number_format($stats['total_certificates']) ?></h3>
                </div>
            </div>
        </div>

        <!-- Average Grade -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-gray-400 text-sm">Average Grade</p>
                    <h3 class="text-2xl font-bold"><?= number_format($stats['average_grade'], 1) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Attendance Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Kehadiran Harian</h3>
            <div class="chart-container">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <!-- Average Grade Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Distribusi Nilai Rata-rata</h3>
            <div class="chart-container">
                <canvas id="gradeChart"></canvas>
            </div>
        </div>

        <!-- Task Status Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Status Tugas</h3>
            <div class="chart-container">
                <canvas id="taskChart"></canvas>
            </div>
        </div>

        <!-- Students per Class Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Jumlah Siswa per Kelas</h3>
            <div class="chart-container">
                <canvas id="studentChart"></canvas>
            </div>
        </div>

        <!-- Teaching Schedule Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Jadwal Mengajar per Guru</h3>
            <div class="chart-container">
                <canvas id="teacherChart"></canvas>
            </div>
        </div>

        <!-- New Students Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Siswa Baru per Bulan</h3>
            <div class="chart-container">
                <canvas id="newStudentChart"></canvas>
            </div>
        </div>

        <!-- Certificates Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Sertifikat Diterbitkan per Bulan</h3>
            <div class="chart-container">
                <canvas id="certificateChart"></canvas>
            </div>
        </div>

        <!-- Documentation Chart -->
        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700">
            <h3 class="text-lg font-semibold mb-4">Dokumentasi per Section</h3>
            <div class="chart-container">
                <canvas id="docChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js library and init script (kept in the view so data is available here) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from PHP
const chartData = {
    attendance: {
        labels: <?= json_encode($data['attendance']['labels']) ?>,
        datasets: [{
            label: 'Hadir',
            data: <?= json_encode($data['attendance']['hadir']) ?>,
            borderColor: '#6366f1',
            tension: 0.4
        }, {
            label: 'Absen',
            data: <?= json_encode($data['attendance']['absen']) ?>,
            borderColor: '#ef4444',
            tension: 0.4
        }, {
            label: 'Terlambat',
            data: <?= json_encode($data['attendance']['terlambat']) ?>,
            borderColor: '#f59e0b',
            tension: 0.4
        }]
    },
    grades: {
        labels: <?= json_encode($data['grades']['labels']) ?>,
        datasets: [{
            label: 'Rata-rata Nilai',
            data: <?= json_encode($data['grades']['values']) ?>,
            backgroundColor: '#6366f1'
        }]
    },
    tasks: {
        labels: ['Completed', 'Pending'],
        datasets: [{
            data: [<?= $data['tasks']['completed'] ?>, <?= $data['tasks']['pending'] ?>],
            backgroundColor: ['#22c55e', '#f59e0b']
        }]
    },
    students: {
        labels: <?= json_encode($data['students']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Siswa',
            data: <?= json_encode($data['students']['values']) ?>,
            backgroundColor: '#6366f1'
        }]
    },
    teaching: {
        labels: <?= json_encode($data['teaching']['labels']) ?>,
        datasets: [{
            label: 'Jumlah Jadwal',
            data: <?= json_encode($data['teaching']['values']) ?>,
            backgroundColor: '#6366f1'
        }]
    },
    newStudents: {
        labels: <?= json_encode($data['newStudents']['labels']) ?>,
        datasets: [{
            label: 'Siswa Baru',
            data: <?= json_encode($data['newStudents']['values']) ?>,
            borderColor: '#6366f1',
            tension: 0.4
        }]
    },
    certificates: {
        labels: <?= json_encode($data['certificates']['labels']) ?>,
        datasets: [{
            label: 'Sertifikat',
            data: <?= json_encode($data['certificates']['values']) ?>,
            backgroundColor: '#6366f1'
        }]
    },
    documentation: {
        labels: <?= json_encode($data['documentation']['labels']) ?>,
        datasets: [{
            data: <?= json_encode($data['documentation']['values']) ?>,
            backgroundColor: ['#6366f1', '#22c55e', '#f59e0b', '#ef4444']
        }]
    }
};

// Chart Configuration
const defaultOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            labels: {
                color: '#9ca3af'
            }
        }
    },
    scales: {
        x: {
            grid: { color: 'rgba(75, 85, 99, 0.2)' },
            ticks: { color: '#9ca3af' }
        },
        y: {
            grid: { color: 'rgba(75, 85, 99, 0.2)' },
            ticks: { color: '#9ca3af' }
        }
    }
};

// Create Charts
new Chart(document.getElementById('attendanceChart'), { type: 'line', data: chartData.attendance, options: defaultOptions });
new Chart(document.getElementById('gradeChart'), { type: 'bar', data: chartData.grades, options: defaultOptions });
new Chart(document.getElementById('taskChart'), { type: 'pie', data: chartData.tasks, options: { ...defaultOptions, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af' } } } } });
new Chart(document.getElementById('studentChart'), { type: 'bar', data: chartData.students, options: { ...defaultOptions, indexAxis: 'y' } });
new Chart(document.getElementById('teacherChart'), { type: 'bar', data: chartData.teaching, options: defaultOptions });
new Chart(document.getElementById('newStudentChart'), { type: 'line', data: chartData.newStudents, options: defaultOptions });
new Chart(document.getElementById('certificateChart'), { type: 'bar', data: chartData.certificates, options: defaultOptions });
new Chart(document.getElementById('docChart'), { type: 'pie', data: chartData.documentation, options: { ...defaultOptions, plugins: { legend: { position: 'bottom', labels: { color: '#9ca3af' } } } } });
</script>