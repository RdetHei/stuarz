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

<div class="max-w-6xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#5865F2] to-[#7289da] flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-2xl">how_to_reg</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white">Input Absensi</h1>
                <p class="text-[#949ba4] text-sm mt-1">Catat kehadiran siswa per kelas</p>
            </div>
        </div>
    </div>

    <!-- Class and Date Selection -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6 mb-6">
        <h2 class="text-xl font-semibold text-white mb-4">Pilih Kelas dan Tanggal</h2>
        
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="page" value="attendance/mark">
            
            <div>
                <label for="class_id" class="block text-sm font-medium text-[#dbdee1] mb-2">
                    Kelas <span class="text-red-400">*</span>
                </label>
                <select name="class_id" id="class_id" required 
                        class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                    <option value="">Pilih Kelas</option>
                    <?php foreach ($classes as $class): ?>
                    <option value="<?= $class['id'] ?>" 
                            <?= $selectedClassId == $class['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($class['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <label for="date" class="block text-sm font-medium text-[#dbdee1] mb-2">
                    Tanggal <span class="text-red-400">*</span>
                </label>
                <input type="date" name="date" id="date" required 
                       value="<?= htmlspecialchars($date) ?>"
                       class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-xl">search</span>
                    Tampilkan Siswa
                </button>
            </div>
        </form>
    </div>

    <?php if (!empty($students) && $selectedClassId): ?>
    <!-- Attendance Form -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-semibold text-white">Daftar Siswa</h2>
                <p class="text-[#949ba4] text-sm mt-1">
                    Kelas: <?= htmlspecialchars($classes[array_search($selectedClassId, array_column($classes, 'id'))]['name'] ?? 'N/A') ?> | 
                    Tanggal: <?= date('d/m/Y', strtotime($date)) ?>
                </p>
            </div>
            <div class="text-sm text-[#949ba4]">
                Total: <?= count($students) ?> siswa
            </div>
        </div>

        <form method="POST" action="index.php?page=attendance/store" class="space-y-4">
            <input type="hidden" name="class_id" value="<?= htmlspecialchars($selectedClassId) ?>">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#3f4147]">
                            <th class="text-left py-3 px-4 text-[#dbdee1] font-medium">No</th>
                            <th class="text-left py-3 px-4 text-[#dbdee1] font-medium">Nama Siswa</th>
                            <th class="text-center py-3 px-4 text-[#dbdee1] font-medium">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $index => $student): ?>
                        <tr class="border-b border-[#3f4147]/50 hover:bg-[#313338]/50 transition-colors">
                            <td class="py-3 px-4 text-[#dbdee1]"><?= $index + 1 ?></td>
                            <td class="py-3 px-4 text-white font-medium">
                                <?= htmlspecialchars($student['name']) ?>
                            </td>
                            <td class="py-3 px-4">
                                <input type="hidden" name="user_id[]" value="<?= $student['id'] ?>">
                                <select name="status[]" 
                                        class="w-full px-3 py-2 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                                    <option value="Hadir" selected>Hadir</option>
                                    <option value="Absen">Absen</option>
                                    <option value="Terlambat">Terlambat</option>
                                    <option value="Izin">Izin</option>
                                    <option value="Sakit">Sakit</option>
                                </select>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-[#3f4147]">
                <div class="text-sm text-[#949ba4]">
                    <span class="material-symbols-outlined text-[#5865F2] text-sm mr-1">info</span>
                    Pastikan status kehadiran sudah benar sebelum menyimpan
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="index.php?page=attendance" 
                       class="px-6 py-3 bg-[#2b2d31] hover:bg-[#383a40] border border-[#3f4147] text-[#dbdee1] rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">close</span>
                        Batal
                    </a>
                    
                    <button type="submit" 
                            class="px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">save</span>
                        Simpan Absensi
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php elseif ($selectedClassId && empty($students)): ?>
    <!-- No Students Found -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-8 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-orange-500/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-orange-400 text-3xl">group_remove</span>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Tidak Ada Siswa</h3>
        <p class="text-[#949ba4] mb-6">Kelas yang dipilih belum memiliki siswa terdaftar.</p>
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
            <span class="material-symbols-outlined text-[#8ea1f7] text-3xl">how_to_reg</span>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">Pilih Kelas dan Tanggal</h3>
        <p class="text-[#949ba4]">Silakan pilih kelas dan tanggal terlebih dahulu untuk menampilkan daftar siswa.</p>
    </div>
    <?php endif; ?>

    <!-- Info Card -->
    <div class="mt-6 bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-[#5865F2]/10 rounded-lg">
                <span class="material-symbols-outlined text-[#5865F2] text-2xl">info</span>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg mb-2">Petunjuk Input Absensi</h3>
                <ul class="text-[#949ba4] space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span><strong>Hadir:</strong> Siswa hadir tepat waktu</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span><strong>Terlambat:</strong> Siswa hadir tapi terlambat</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span><strong>Absen:</strong> Siswa tidak hadir tanpa keterangan</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span><strong>Izin:</strong> Siswa tidak hadir dengan izin</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span><strong>Sakit:</strong> Siswa tidak hadir karena sakit</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>