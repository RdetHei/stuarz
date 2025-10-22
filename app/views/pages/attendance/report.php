<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get data from controller
$report = $report ?? null;
$students = $students ?? [];
$selectedUserId = $_GET['user_id'] ?? '';
$fromDate = $_GET['from'] ?? '';
$toDate = $_GET['to'] ?? '';

// Get student name if selected
$selectedStudentName = '';
if ($selectedUserId) {
    foreach ($students as $student) {
        if ($student['id'] == $selectedUserId) {
            $selectedStudentName = $student['name'];
            break;
        }
    }
}
?>

<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#5865F2] to-[#7289da] flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-2xl">analytics</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">Laporan Absensi</h1>
                <p class="text-[#949ba4] text-sm mt-1">Analisis kehadiran siswa</p>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6 mb-6">
        <h2 class="text-xl font-semibold text-white mb-4">Filter Laporan</h2>
        
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="page" value="attendance/report">
            
            <div>
                <label for="user_id" class="block text-sm font-medium text-[#dbdee1] mb-2">
                    Siswa <span class="text-red-400">*</span>
                </label>
                <select name="user_id" id="user_id" required 
                        class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                    <option value="">Pilih Siswa</option>
                    <?php foreach ($students as $student): ?>
                    <option value="<?= $student['id'] ?>" 
                            <?= $selectedUserId == $student['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($student['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="from" class="block text-sm font-medium text-[#dbdee1] mb-2">
                    Dari Tanggal
                </label>
                <input type="date" name="from" id="from" 
                       value="<?= htmlspecialchars($fromDate) ?>"
                       class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
            </div>
            
            <div>
                <label for="to" class="block text-sm font-medium text-[#dbdee1] mb-2">
                    Sampai Tanggal
                </label>
                <input type="date" name="to" id="to" 
                       value="<?= htmlspecialchars($toDate) ?>"
                       class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-xl">search</span>
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <?php if ($report && $selectedStudentName): ?>
    <!-- Report Results -->
    <div class="space-y-6">
        <!-- Student Info -->
        <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-white">Laporan Absensi</h2>
                    <p class="text-[#949ba4] text-sm mt-1">
                        Siswa: <?= htmlspecialchars($selectedStudentName) ?>
                        <?php if ($fromDate || $toDate): ?>
                        | Periode: <?= $fromDate ? date('d/m/Y', strtotime($fromDate)) : 'Awal' ?> - <?= $toDate ? date('d/m/Y', strtotime($toDate)) : 'Sekarang' ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-[#949ba4]">Total Hari</div>
                    <div class="text-2xl font-bold text-white"><?= array_sum($report) ?></div>
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <?php
            $statusConfig = [
                'Hadir' => ['color' => 'emerald', 'icon' => 'check_circle'],
                'Absen' => ['color' => 'red', 'icon' => 'cancel'],
                'Terlambat' => ['color' => 'orange', 'icon' => 'schedule'],
                'Izin' => ['color' => 'blue', 'icon' => 'info'],
                'Sakit' => ['color' => 'purple', 'icon' => 'local_hospital']
            ];
            
            foreach ($statusConfig as $status => $config):
                $count = $report[$status] ?? 0;
                $total = array_sum($report);
                $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
            ?>
            <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-<?= $config['color'] ?>-500/20">
                        <span class="material-symbols-outlined text-<?= $config['color'] ?>-400 text-2xl"><?= $config['icon'] ?></span>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-white"><?= $count ?></div>
                        <div class="text-sm text-<?= $config['color'] ?>-400"><?= $percentage ?>%</div>
                    </div>
                </div>
                <div class="text-sm text-[#dbdee1] font-medium"><?= $status ?></div>
                <div class="w-full bg-[#313338] rounded-full h-2 mt-2">
                    <div class="bg-<?= $config['color'] ?>-500 h-2 rounded-full transition-all duration-500" 
                         style="width: <?= $percentage ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Summary Card -->
        <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Ringkasan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-400 mb-2"><?= $report['Hadir'] ?? 0 ?></div>
                    <div class="text-sm text-[#949ba4]">Hari Hadir</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-400 mb-2"><?= ($report['Absen'] ?? 0) + ($report['Terlambat'] ?? 0) ?></div>
                    <div class="text-sm text-[#949ba4]">Hari Tidak Hadir/Terlambat</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2"><?= ($report['Izin'] ?? 0) + ($report['Sakit'] ?? 0) ?></div>
                    <div class="text-sm text-[#949ba4]">Hari Izin/Sakit</div>
                </div>
            </div>
        </div>

        <!-- Export Options -->
        <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Ekspor Laporan</h3>
            <div class="flex items-center gap-4">
                <button onclick="window.print()" 
                        class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">print</span>
                    Cetak Laporan
                </button>
                <button onclick="exportToPDF()" 
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">picture_as_pdf</span>
                    Export PDF
                </button>
            </div>
        </div>
    </div>

    <?php elseif ($selectedUserId && empty($report)): ?>
    <!-- No Data Found -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-orange-500/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-orange-400 text-3xl">data_usage</span>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Data Tidak Ditemukan</h3>
        <p class="text-[#949ba4] mb-6">Tidak ada data absensi untuk siswa dan periode yang dipilih.</p>
        <a href="index.php?page=attendance" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Kembali ke Absensi
        </a>
    </div>

    <?php else: ?>
    <!-- Initial State -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#5865F2]/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-[#8ea1f7] text-3xl">analytics</span>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Pilih Siswa untuk Melihat Laporan</h3>
        <p class="text-[#949ba4]">Silakan pilih siswa terlebih dahulu untuk menampilkan laporan absensi.</p>
    </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="mt-6 bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-[#5865F2]/10 rounded-lg">
                <span class="material-symbols-outlined text-[#5865F2] text-2xl">info</span>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg mb-2">Informasi Laporan</h3>
                <ul class="text-[#949ba4] space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Laporan menampilkan statistik kehadiran berdasarkan periode yang dipilih</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Jika tidak memilih periode, akan menampilkan data semua waktu</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Persentase dihitung dari total hari yang tercatat dalam sistem</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Laporan dapat dicetak atau diekspor ke PDF</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    // Simple print functionality for now
    // In a real application, you might want to use a library like jsPDF
    window.print();
}

// Print styles
document.addEventListener('DOMContentLoaded', function() {
    // Add print styles
    const style = document.createElement('style');
    style.textContent = `
        @media print {
            body * { visibility: hidden; }
            .print-content, .print-content * { visibility: visible; }
            .print-content { position: absolute; left: 0; top: 0; width: 100%; }
            .no-print { display: none !important; }
        }
    `;
    document.head.appendChild(style);
});
</script>