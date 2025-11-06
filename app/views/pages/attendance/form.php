<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get data from controller
$classes = $classes ?? [];
$students = $students ?? [];
$date = $date ?? date('Y-m-d');
$selectedClassId = $_GET['class_id'] ?? '';
?>

<div class="container">
    <h2 class="text-3xl font-bold text-white mb-6">Ambil Absen</h2>

    <!-- Class and Date Selection -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
        <form method="GET" action="index.php" class="mb-4">
            <input type="hidden" name="page" value="attendance/mark">
            <div class="row">
                <div class="col-md-4">
                    <select name="class_id" class="form-control" required>
                        <option value="">Pilih Kelas</option>
                        <?php foreach($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= isset($_GET['class_id']) && $_GET['class_id'] == $class['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="date" name="date" class="form-control" 
                           value="<?= htmlspecialchars($date) ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Tampilkan Siswa</button>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($students)): ?>
        <!-- Attendance Form -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
            <div class="p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white mb-1">Daftar Siswa</h2>
                        <p class="text-gray-400 text-sm flex items-center gap-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Kelas: <span class="font-semibold text-white"><?= htmlspecialchars($classes[array_search($selectedClassId, array_column($classes, 'id'))]['name'] ?? 'N/A') ?></span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Tanggal: <span class="font-semibold text-white"><?= date('d/m/Y', strtotime($date)) ?></span>
                            </span>
                        </p>
                    </div>
                    <div class="text-sm text-gray-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Total: <span class="font-semibold text-white"><?= count($students) ?></span> siswa
                    </div>
                </div>
            </div>

            <form method="post" action="index.php?page=attendance/store">
                <input type="hidden" name="class_id" value="<?= htmlspecialchars($_GET['class_id']) ?>">
                <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['username']) ?></td>
                                    <td>
                                        <input type="hidden" name="user_id[]" value="<?= $student['id'] ?>">
                                        <select name="status[]" class="form-control">
                                            <option value="hadir" <?= ($student['status'] ?? '') == 'hadir' ? 'selected' : '' ?>>Hadir</option>
                                            <option value="alfa" <?= ($student['status'] ?? '') == 'alfa' ? 'selected' : '' ?>>Alfa</option>
                                            <option value="izin" <?= ($student['status'] ?? '') == 'izin' ? 'selected' : '' ?>>Izin</option>
                                            <option value="sakit" <?= ($student['status'] ?? '') == 'sakit' ? 'selected' : '' ?>>Sakit</option>
                                            <option value="terlambat" <?= ($student['status'] ?? '') == 'terlambat' ? 'selected' : '' ?>>Terlambat</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="notes[]" class="form-control" 
                                               value="<?= htmlspecialchars($student['notes'] ?? '') ?>">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Form Actions -->
                <div class="p-6 bg-gray-900 border-t border-gray-700">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Pastikan status kehadiran sudah benar sebelum menyimpan
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <a href="index.php?page=attendance" 
                               class="px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Batal
                            </a>
                            
                            <button type="submit" 
                                    class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Absensi
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    <?php elseif(isset($_GET['class_id'])): ?>
        <!-- No Students Found -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-orange-500/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tidak Ada Siswa</h3>
            <p class="text-gray-400 mb-6 max-w-md mx-auto">Kelas yang dipilih belum memiliki siswa terdaftar. Silakan hubungi administrator.</p>
            <a href="index.php?page=attendance" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Absensi
            </a>
        </div>

    <?php else: ?>
        <!-- Initial State -->
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
                <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Pilih Kelas dan Tanggal</h3>
            <p class="text-gray-400">Silakan pilih kelas dan tanggal terlebih dahulu untuk menampilkan daftar siswa.</p>
        </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg mb-3">Petunjuk Input Absensi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <div>
                            <span class="text-white font-medium">Hadir:</span>
                            <span class="text-gray-400"> Siswa hadir tepat waktu</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500/20 text-orange-400 text-xs font-bold flex-shrink-0 mt-0.5">⏰</span>
                        <div>
                            <span class="text-white font-medium">Terlambat:</span>
                            <span class="text-gray-400"> Siswa hadir tapi terlambat</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-500/20 text-red-400 text-xs font-bold flex-shrink-0 mt-0.5">✗</span>
                        <div>
                            <span class="text-white font-medium">Absen:</span>
                            <span class="text-gray-400"> Tidak hadir tanpa keterangan</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold flex-shrink-0 mt-0.5">📋</span>
                        <div>
                            <span class="text-white font-medium">Izin:</span>
                            <span class="text-gray-400"> Tidak hadir dengan izin</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-500/20 text-purple-400 text-xs font-bold flex-shrink-0 mt-0.5">🏥</span>
                        <div>
                            <span class="text-white font-medium">Sakit:</span>
                            <span class="text-gray-400"> Tidak hadir karena sakit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>