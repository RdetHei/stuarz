<?php
// Ambil data jadwal dari controller
$schedules = $schedules ?? [];

// Kelompokkan berdasarkan hari
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$groupedSchedules = [];
foreach ($days as $day) {
    $groupedSchedules[$day] = [];
}
foreach ($schedules as $schedule) {
    $day = $schedule['day'] ?? '';
    if (isset($groupedSchedules[$day])) {
        $groupedSchedules[$day][] = $schedule;
    }
}

// Ambil filter dari query string
$selectedClass = $_GET['class_id'] ?? '';
$selectedTeacher = $_GET['teacher_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Pelajaran - Stuarz</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .schedule-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .schedule-card:hover {
            background: rgba(88, 101, 242, 0.1);
            border-color: rgba(88, 101, 242, 0.3);
            transform: translateX(4px);
        }
        
        .day-header {
            background: linear-gradient(135deg, rgba(88, 101, 242, 0.15), rgba(114, 137, 218, 0.15));
            border-left: 3px solid #5865F2;
        }
        
        .badge-lecture {
            background: rgba(88, 101, 242, 0.15);
            color: #8ea1f7;
            border: 1px solid rgba(88, 101, 242, 0.3);
        }
        
        .badge-lab {
            background: rgba(87, 242, 135, 0.15);
            color: #57f287;
            border: 1px solid rgba(87, 242, 135, 0.3);
        }
        
        .badge-tutorial {
            background: rgba(254, 231, 92, 0.15);
            color: #fee75c;
            border: 1px solid rgba(254, 231, 92, 0.3);
        }
        
        .filter-btn {
            background: #2b2d31;
            border: 1px solid #3f4147;
            transition: all 0.2s;
        }
        
        .filter-btn:hover {
            background: #383a40;
            border-color: #5865F2;
        }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 8px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #1e1f22;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #3f4147;
            border-radius: 4px;
        }
        
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #5865F2;
        }
    </style>
</head>
<body class="bg-[#1e1f22] text-[#dbdee1] min-h-screen">
    
    <div class="max-w-[1400px] mx-auto p-6 lg:p-8">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#5865F2] to-[#7289da] flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-white text-2xl">calendar_month</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Jadwal Pelajaran</h1>
                        <p class="text-[#949ba4] text-sm mt-1">Semester Genap 2024/2025</p>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
                <a href="index.php?page=schedule/create" class="px-5 py-2.5 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                    <span class="material-symbols-outlined text-xl">add</span>
                    Tambah Jadwal
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Filter Section -->
            <div class="mt-6 flex flex-wrap gap-3">
                <form method="GET" action="index.php" class="flex flex-wrap gap-3 w-full">
                    <input type="hidden" name="page" value="schedule">
                    
                    <div class="flex-1 min-w-[200px]">
                        <select name="class_id" class="w-full px-4 py-2.5 bg-[#2b2d31] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                            <option value="">Semua Kelas</option>
                            <?php
                            $classQuery = $config->query("SELECT id, name FROM classes ORDER BY name");
                            while ($class = $classQuery->fetch_assoc()):
                            ?>
                            <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[200px]">
                        <select name="teacher_id" class="w-full px-4 py-2.5 bg-[#2b2d31] border border-[#3f4147] rounded-lg text-[#dbdee1] focus:border-[#5865F2] focus:outline-none transition-all">
                            <option value="">Semua Guru</option>
                            <?php
                            $teacherQuery = $config->query("SELECT id, name FROM users WHERE role='teacher' ORDER BY name");
                            while ($teacher = $teacherQuery->fetch_assoc()):
                            ?>
                            <option value="<?= $teacher['id'] ?>" <?= $selectedTeacher == $teacher['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($teacher['name']) ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="px-6 py-2.5 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">filter_alt</span>
                        Filter
                    </button>
                    
                    <?php if ($selectedClass || $selectedTeacher): ?>
                    <a href="index.php?page=schedule" class="px-6 py-2.5 bg-[#2b2d31] hover:bg-[#383a40] border border-[#3f4147] text-[#dbdee1] rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-xl">close</span>
                        Reset
                    </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Schedule Grid -->
        <div class="space-y-4">
            <?php foreach ($days as $day): ?>
            <?php if (!empty($groupedSchedules[$day])): ?>
            <div class="bg-[#2b2d31] rounded-xl overflow-hidden border border-[#3f4147] shadow-lg">
                <!-- Day Header -->
                <div class="day-header px-6 py-4">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#5865F2] text-2xl">today</span>
                        <h2 class="text-xl font-bold text-white"><?= $day ?></h2>
                        <span class="ml-auto bg-[#5865F2]/20 text-[#8ea1f7] px-3 py-1 rounded-full text-sm font-medium">
                            <?= count($groupedSchedules[$day]) ?> Jadwal
                        </span>
                    </div>
                </div>
                
                <!-- Schedule Items -->
                <div class="p-4 space-y-3">
                    <?php foreach ($groupedSchedules[$day] as $schedule): ?>
                    <?php
                    // Tentukan badge type berdasarkan subject atau bisa dari database
                    $badgeClass = 'badge-lecture';
                    $badgeText = 'lecture';
                    if (stripos($schedule['subject'], 'lab') !== false || stripos($schedule['subject'], 'praktikum') !== false) {
                        $badgeClass = 'badge-lab';
                        $badgeText = 'lab';
                    } elseif (stripos($schedule['subject'], 'tutorial') !== false) {
                        $badgeClass = 'badge-tutorial';
                        $badgeText = 'tutorial';
                    }
                    ?>
                    <div class="schedule-card p-4 bg-[#313338] rounded-lg border border-[#3f4147] hover:border-[#5865F2]/30">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div class="flex-1 min-w-[250px]">
                                <div class="flex items-center gap-2 flex-wrap mb-3">
                                    <h3 class="text-lg font-semibold text-white"><?= htmlspecialchars($schedule['subject']) ?></h3>
                                    <span class="<?= $badgeClass ?> px-3 py-1 rounded-full text-xs font-medium uppercase tracking-wide">
                                        <?= $badgeText ?>
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
                                    <div class="flex items-center gap-2 text-[#949ba4]">
                                        <span class="material-symbols-outlined text-[#5865F2] text-lg">schedule</span>
                                        <span><?= htmlspecialchars($schedule['start_time']) ?> - <?= htmlspecialchars($schedule['end_time']) ?></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-[#949ba4]">
                                        <span class="material-symbols-outlined text-[#5865F2] text-lg">person</span>
                                        <span>
                                            <?php
                                            $teacherQuery = $config->query("SELECT name FROM users WHERE id=" . intval($schedule['teacher_id']));
                                            $teacher = $teacherQuery->fetch_assoc();
                                            echo htmlspecialchars($teacher['name'] ?? 'N/A');
                                            ?>
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 text-[#949ba4]">
                                        <span class="material-symbols-outlined text-[#5865F2] text-lg">meeting_room</span>
                                        <span><?= htmlspecialchars($schedule['class']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
                            <div class="flex items-center gap-2">
                                <a href="index.php?page=schedule/edit/<?= $schedule['id'] ?>" 
                                   class="p-2 bg-[#5865F2]/10 hover:bg-[#5865F2]/20 border border-[#5865F2]/30 text-[#8ea1f7] rounded-lg transition-all duration-200">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form method="POST" action="index.php?page=schedule/delete/<?= $schedule['id'] ?>" 
                                      onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
                                    <button type="submit" 
                                            class="p-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 text-red-400 rounded-lg transition-all duration-200">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Info Footer -->
        <div class="mt-8 bg-[#2b2d31] border border-[#3f4147] rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-[#5865F2]/10 rounded-lg">
                    <span class="material-symbols-outlined text-[#5865F2] text-2xl">info</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-white font-semibold text-lg mb-2">Informasi Penting</h3>
                    <p class="text-[#949ba4] leading-relaxed">
                        Jadwal dapat berubah sewaktu-waktu. Harap selalu cek pembaruan dari admin atau sistem notifikasi.
                        Untuk pertanyaan lebih lanjut, silakan hubungi bagian akademik.
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>