<?php
// Use records/classes passed from controller
$records = $records ?? [];
$classes = $classes ?? [];
$activeClass = $activeClass ?? null;
$activeClassId = intval($activeClass['id'] ?? 0);

// Stats should be provided by controller (from DB). Provide safe fallback here.
$stats = $stats ?? [
    'Hadir' => 0,
    'Absen' => 0,
    'Terlambat' => 0,
    'Izin' => 0,
    'Sakit' => 0
];

// Convert to display format
$displayStats = [
    ['title' => 'Hari Hadir', 'value' => (int)($stats['Hadir'] ?? 0), 'color' => 'success', 'icon' => 'check'],
    ['title' => 'Hari Absen',  'value' => (int)($stats['Absen'] ?? 0),  'color' => 'danger',  'icon' => 'x'],
    ['title' => 'Hari Terlambat', 'value' => (int)($stats['Terlambat'] ?? 0),  'color' => 'warning','icon' => 'clock'],
];
?>

<style>
  .toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: #2b2d31;
    border: 1px solid #3f4147;
    color: white;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  }
  .toast.show {
    opacity: 1;
    transform: translateY(0);
  }
</style>

<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">Attendance Clock</h1>
                    <p class="text-gray-400 text-sm mt-1">Check in and check out system</p>
                </div>
            </div>
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
            <a href="index.php?page=attendance_manage" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                Manage Attendance
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Clock Card -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 mb-6">
        <div class="text-center">
            <div class="mb-6">
                <p class="text-gray-400 text-sm mb-2">Current Time</p>
                <div id="clock" class="text-6xl font-bold text-white mb-2"></div>
                <p class="text-gray-500 text-sm" id="dateDisplay"></p>
            </div>
            
            <div class="mb-6">
                <?php if ($activeClass): ?>
                <div class="w-full max-w-2xl mx-auto bg-gray-900 border border-gray-700 rounded-xl p-5 text-left">
                    <p class="text-xs uppercase tracking-[0.2em] text-gray-500 mb-2">Kelas Aktif</p>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-semibold text-white"><?= htmlspecialchars($activeClass['name'] ?? 'Tanpa Nama') ?></h3>
                            <p class="text-sm text-gray-400">Kode: <span class="font-mono text-gray-200"><?= htmlspecialchars($activeClass['code'] ?? '-') ?></span></p>
                        </div>
                        <a href="index.php?page=class/detail/<?= intval($activeClassId) ?>" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                            Ganti kelas →
                        </a>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">Absensi akan dicatat otomatis untuk kelas ini berdasarkan kelas terakhir yang Anda masuki.</p>
                </div>
                <?php else: ?>
                <div class="w-full max-w-2xl mx-auto bg-gray-900 border border-dashed border-red-500/40 rounded-xl p-5 text-left">
                    <p class="text-sm font-semibold text-red-300 mb-2">Belum memilih kelas aktif</p>
                    <p class="text-sm text-gray-300">Masuk ke salah satu kelas dari halaman <a href="index.php?page=class" class="underline text-indigo-400 hover:text-indigo-300">Kelas Saya</a> untuk mengatur konteks absensi.</p>
                    <div class="mt-4">
                        <a href="index.php?page=class" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-all">
                            Pergi ke Kelas
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="flex items-center justify-center gap-4">
                <button id="checkInBtn" data-class-id="<?= $activeClassId ?>" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold transition-all flex items-center gap-2 shadow-lg <?= $activeClassId ? '' : 'opacity-40 cursor-not-allowed' ?>" <?= $activeClassId ? '' : 'disabled' ?>>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Check In
                </button>
                <button id="checkOutBtn" data-class-id="<?= $activeClassId ?>" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition-all flex items-center gap-2 shadow-lg <?= $activeClassId ? '' : 'opacity-40 cursor-not-allowed' ?>" <?= $activeClassId ? '' : 'disabled' ?>>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Check Out
                </button>
            </div>
            
            <div id="message" class="mt-6"></div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <?php foreach ($displayStats as $s): ?>
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl hover:border-gray-600 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm font-medium text-gray-400 mb-2"><?= htmlspecialchars($s['title']) ?></div>
                    <div class="text-3xl font-bold text-white"><?= number_format($s['value']) ?></div>
                    <div class="text-xs text-gray-500 mt-1">total hari</div>
                </div>
                <div class="w-14 h-14 rounded-xl flex items-center justify-center <?php
                    echo $s['color']==='success' ? 'bg-emerald-500/20' : ($s['color']==='danger' ? 'bg-red-500/20' : 'bg-orange-500/20');
                ?>">
                    <?php if ($s['icon']==='check'): ?>
                        <svg class="w-7 h-7 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    <?php elseif ($s['icon']==='x'): ?>
                        <svg class="w-7 h-7 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round"/>
                        </svg>
                    <?php else: ?>
                        <svg class="w-7 h-7 text-orange-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 6v6l4 2" stroke-linecap="round"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Filter Section -->
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter Records
        </h3>
        <form method="get" class="flex flex-wrap gap-4">
            <input type="hidden" name="page" value="attendance">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
                <input type="date" name="start" value="<?= $_GET['start'] ?? date('Y-m-d') ?>" 
                       class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
                <input type="date" name="end" value="<?= $_GET['end'] ?? date('Y-m-d') ?>" 
                       class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Records Table -->
    <?php if (!empty($records)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-xl font-bold text-white">Attendance Records</h3>
            <p class="text-sm text-gray-400 mt-1">Total: <?= count($records) ?> records</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-900 border-b border-gray-700">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Check In</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Check Out</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php foreach ($records as $record): ?>
                    <tr class="hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                                    <?= strtoupper(substr($record['username'], 0, 1)) ?>
                                </div>
                                <span class="text-white font-medium"><?= htmlspecialchars($record['username']) ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            <?= date('d/m/Y', strtotime($record['date'])) ?>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <?= $record['check_in'] ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            <?= $record['check_out'] ?? '-' ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php
                            $statusClass = $record['status'] === 'present' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 
                                          ($record['status'] === 'late' ? 'bg-orange-500/20 text-orange-300 border-orange-500/30' : 
                                           'bg-red-500/20 text-red-300 border-red-500/30');
                            ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?= $statusClass ?>">
                                <?= ucfirst($record['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
            <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">No Records Found</h3>
        <p class="text-gray-400">No attendance records available for the selected date range.</p>
    </div>
    <?php endif; ?>
</div>

<div id="attToast" class="toast">Saved</div>

<script>
// Real-time clock
function updateClock() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const dateStr = now.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    document.getElementById('clock').textContent = timeStr;
    document.getElementById('dateDisplay').textContent = dateStr;
}
setInterval(updateClock, 1000);
updateClock();

// Toast function
function showToast(message, isSuccess = true) {
    const toast = document.getElementById('attToast');
    toast.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2000);
}

// Handle check in/out
const ACTIVE_CLASS_ID = <?= json_encode($activeClassId) ?>;

async function handleAttendance(type) {
    try {
        if (!ACTIVE_CLASS_ID) {
            showMessage('Masuk ke salah satu kelas terlebih dahulu.', false);
            return;
        }

        const response = await fetch(`index.php?page=attendance_${type}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `class_id=${ACTIVE_CLASS_ID}`
        });
        
        const data = await response.json();
        
        showMessage(data.message, data.success);
        showToast(data.message, data.success);
        
        if (data.success) {
            setTimeout(() => window.location.reload(), 1500);
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', false);
    }
}

function showMessage(message, isSuccess) {
    const messageEl = document.getElementById('message');
    messageEl.innerHTML = `
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg ${isSuccess ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/20 text-red-300 border border-red-500/30'}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${isSuccess ? 
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' : 
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>'}
            </svg>
            <span>${message}</span>
        </div>
    `;
}

document.getElementById('checkInBtn').onclick = () => handleAttendance('checkin');
document.getElementById('checkOutBtn').onclick = () => handleAttendance('checkout');
</script>