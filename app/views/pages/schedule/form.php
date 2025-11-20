
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
$mode = $mode ?? 'create';
$item = $item ?? null;
$days = $days ?? ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachers = $teachers ?? [];
$formAction = $mode === 'create' ? 'index.php?page=schedule/store' : 'index.php?page=schedule/update/' . ($item['id'] ?? '');
$pageTitle = $mode === 'create' ? 'Tambah Jadwal' : 'Edit Jadwal';
?>

<div class="max-w-4xl mx-auto p-6">
    
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white"><?= $pageTitle ?></h1>
                    <p class="text-gray-400 text-sm mt-1">Kelola jadwal pelajaran</p>
                </div>
            </div>
            
            <a href="index.php?page=schedule" 
               class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 border border-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    
    <?php if ($error): ?>
    <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-red-300"><?= htmlspecialchars($error) ?></span>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select name="class_id" id="class_id" required 
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                        <option value="">Pilih Kelas</option>
                        <?php 
                        $preselectedClassId = $_GET['class_id'] ?? ($item['class_id'] ?? '');
                        foreach ($classes as $class): 
                        ?>
                        <option value="<?= $class['id'] ?>" 
                                <?= $preselectedClassId == $class['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select name="subject" id="subject" required 
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                        <option value="">Pilih Mata Pelajaran</option>
                        <?php foreach ($subjects as $subject): ?>
                        <option value="<?= htmlspecialchars($subject['name']) ?>" 
                                <?= ($item['subject'] ?? '') == $subject['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Guru -->
                <div>
                    <label for="teacher_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Guru <span class="text-red-500">*</span>
                    </label>
                    <select name="teacher_id" id="teacher_id" required 
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                        <option value="">Pilih Guru</option>
                        <?php foreach ($teachers as $teacher): ?>
                        <option value="<?= $teacher['id'] ?>" 
                                <?= ($item['teacher_id'] ?? '') == $teacher['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($teacher['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Hari -->
                <div>
                    <label for="day" class="block text-sm font-medium text-gray-300 mb-2">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="day" id="day" required 
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                        <option value="">Pilih Hari</option>
                        <?php foreach ($days as $day): ?>
                        <option value="<?= $day ?>" 
                                <?= ($item['day'] ?? '') == $day ? 'selected' : '' ?>>
                            <?= $day ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-300 mb-2">
                        Waktu Mulai <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="time" name="start_time" id="start_time" required 
                               value="<?= htmlspecialchars($item['start_time'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-300 mb-2">
                        Waktu Selesai <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input type="time" name="end_time" id="end_time" required 
                               value="<?= htmlspecialchars($item['end_time'] ?? '') ?>"
                               class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
                    </div>
                </div>

                <!-- Ruangan -->
                <div class="md:col-span-2">
                    <label for="class" class="block text-sm font-medium text-gray-300 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <input type="text" name="class" id="class" required 
                               value="<?= htmlspecialchars($item['class'] ?? '') ?>"
                               placeholder="Contoh: Lab Komputer 1, Ruang 101, dll"
                               class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-700">
                <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $mode === 'create' ? 'Simpan Jadwal' : 'Update Jadwal' ?>
                </button>
                
                <a href="index.php?page=schedule" 
                   class="px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg mb-3">Catatan Penting</h3>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span class="text-gray-400">Sistem akan mengecek konflik jadwal secara otomatis</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span class="text-gray-400">Pastikan waktu selesai lebih besar dari waktu mulai</span>
                    </li>
                    <li class="flex items-start gap-2 text-sm">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
                        <span class="text-gray-400">Jadwal yang sudah dibuat dapat diedit atau dihapus kapan saja</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
// Time validation
document.addEventListener('DOMContentLoaded', function() {
    const startTime = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    
    function validateTime() {
        if (startTime.value && endTime.value) {
            if (startTime.value >= endTime.value) {
                endTime.setCustomValidity('Waktu selesai harus lebih besar dari waktu mulai');
            } else {
                endTime.setCustomValidity('');
            }
        }
    }
    
    startTime.addEventListener('change', validateTime);
    endTime.addEventListener('change', validateTime);
});
</script>