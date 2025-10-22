<?php
// Ensure session is started for error messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get error message if exists
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);

// Set default values
$mode = $mode ?? 'create';
$item = $item ?? null;
$days = $days ?? ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$teachers = $teachers ?? [];

// Set form action and title based on mode
$formAction = $mode === 'create' ? 'index.php?page=schedule/store' : 'index.php?page=schedule/update/' . ($item['id'] ?? '');
$pageTitle = $mode === 'create' ? 'Tambah Jadwal' : 'Edit Jadwal';
?>

<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#5865F2] to-[#7289da] flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-white text-2xl">calendar_month</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white"><?= $pageTitle ?></h1>
                <p class="text-[#949ba4] text-sm mt-1">Kelola jadwal pelajaran</p>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if ($error): ?>
    <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-red-400">error</span>
            <span class="text-red-300"><?= htmlspecialchars($error) ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
        <form method="POST" action="<?= $formAction ?>" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kelas -->
                <div>
                    <label for="class_id" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Kelas <span class="text-red-400">*</span>
                    </label>
                    <select name="class_id" id="class_id" required 
                            class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                        <option value="">Pilih Kelas</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" 
                                <?= ($item['class_id'] ?? '') == $class['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Mata Pelajaran -->
                <div>
                    <label for="subject" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Mata Pelajaran <span class="text-red-400">*</span>
                    </label>
                    <select name="subject" id="subject" required 
                            class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
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
                    <label for="teacher_id" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Guru <span class="text-red-400">*</span>
                    </label>
                    <select name="teacher_id" id="teacher_id" required 
                            class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
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
                    <label for="day" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Hari <span class="text-red-400">*</span>
                    </label>
                    <select name="day" id="day" required 
                            class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
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
                    <label for="start_time" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Waktu Mulai <span class="text-red-400">*</span>
                    </label>
                    <input type="time" name="start_time" id="start_time" required 
                           value="<?= htmlspecialchars($item['start_time'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label for="end_time" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Waktu Selesai <span class="text-red-400">*</span>
                    </label>
                    <input type="time" name="end_time" id="end_time" required 
                           value="<?= htmlspecialchars($item['end_time'] ?? '') ?>"
                           class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                </div>

                <!-- Ruangan -->
                <div class="md:col-span-2">
                    <label for="class" class="block text-sm font-medium text-[#dbdee1] mb-2">
                        Ruangan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="class" id="class" required 
                           value="<?= htmlspecialchars($item['class'] ?? '') ?>"
                           placeholder="Contoh: Lab Komputer 1, Ruang 101, dll"
                           class="w-full px-4 py-3 bg-[#313338] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-[#3f4147]">
                <button type="submit" 
                        class="px-6 py-3 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">save</span>
                    <?= $mode === 'create' ? 'Simpan Jadwal' : 'Update Jadwal' ?>
                </button>
                
                <a href="index.php?page=schedule" 
                   class="px-6 py-3 bg-[#2b2d31] hover:bg-[#383a40] border border-[#3f4147] text-[#dbdee1] rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-xl">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="mt-6 bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-[#5865F2]/10 rounded-lg">
                <span class="material-symbols-outlined text-[#5865F2] text-2xl">info</span>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-semibold text-lg mb-2">Catatan Penting</h3>
                <ul class="text-[#949ba4] space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Sistem akan mengecek konflik jadwal secara otomatis</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Pastikan waktu selesai lebih besar dari waktu mulai</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[#5865F2] text-sm mt-0.5">check_circle</span>
                        <span>Jadwal yang sudah dibuat dapat diedit atau dihapus</span>
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
