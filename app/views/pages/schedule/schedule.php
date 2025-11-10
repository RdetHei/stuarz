<?php
// Get data from controller
$classesWithSchedules = $classesWithSchedules ?? [];
$filterClasses = $filterClasses ?? [];
$filterTeachers = $filterTeachers ?? [];

// Days for grouping
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

// Get filters from query string
$selectedClass = $_GET['class_id'] ?? '';
$selectedTeacher = $_GET['teacher_id'] ?? '';
$selectedDay = $_GET['day'] ?? '';
?>

<style>
  .schedule-card { 
    transition: all 0.2s ease; 
  }
  .schedule-card:hover { 
    background: #1f2937;
    border-color: #5865F2;
    transform: translateY(-1px);
  }
  .class-container {
    transition: all 0.2s ease;
  }
  .class-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
  }
</style>

<div class="min-h-screen bg-gray-900">
  <div class="max-w-[1400px] mx-auto p-6">
    <!-- Header Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-100 mb-1">Jadwal Pelajaran</h1>
          <p class="text-gray-400 text-sm">Semester Genap 2024/2025</p>
        </div>

        <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
        <a href="index.php?page=schedule/create" 
           class="px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md font-medium transition-colors flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Jadwal
        </a>
        <?php endif; ?>
      </div>

      <!-- Filter Section -->
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <input type="hidden" name="page" value="schedule">

          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5">Kelas</label>
            <select name="class_id" class="w-full px-3 py-2 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
              <option value="">Semua Kelas</option>
              <?php foreach ($filterClasses as $class): ?>
              <option value="<?= $class['id'] ?>" <?= $selectedClass == $class['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($class['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5">Guru</label>
            <select name="teacher_id" class="w-full px-3 py-2 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
              <option value="">Semua Guru</option>
              <?php foreach ($filterTeachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>" <?= $selectedTeacher == $teacher['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($teacher['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5">Hari</label>
            <select name="day" class="w-full px-3 py-2 bg-[#111827] border border-gray-700 rounded-md text-sm text-gray-200 focus:border-[#5865F2] focus:ring-1 focus:ring-[#5865F2] focus:outline-none transition-colors">
              <option value="">Semua Hari</option>
              <?php foreach ($days as $d): ?>
              <option value="<?= $d ?>" <?= $selectedDay == $d ? 'selected' : '' ?>><?= $d ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
              Filter
            </button>
            <?php if ($selectedClass || $selectedTeacher || $selectedDay): ?>
            <a href="index.php?page=schedule" class="px-4 py-2 bg-[#111827] hover:bg-gray-700 border border-gray-700 text-gray-300 rounded-md text-sm font-medium transition-colors">
              Reset
            </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Classes Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
      <?php foreach ($classesWithSchedules as $classData): ?>
      <?php
      $classId = $classData['id'];
      $className = $classData['name'] ?? 'Unknown';
      $classCode = $classData['code'] ?? '';
      $classDescription = $classData['description'] ?? '';
      $schedules = $classData['schedules'] ?? [];
      
      if (!is_array($schedules)) {
          $schedules = [];
      }
      
      $schedulesByDay = [];
      foreach ($days as $day) {
          $schedulesByDay[$day] = [];
      }
      
      if (is_array($schedules) && !empty($schedules)) {
          foreach ($schedules as $schedule) {
              if (!is_array($schedule)) continue;
              $day = trim($schedule['day'] ?? '');
              
              if (empty($day)) {
                  if (!isset($schedulesByDay['Hari Tidak Diketahui'])) {
                      $schedulesByDay['Hari Tidak Diketahui'] = [];
                  }
                  $schedulesByDay['Hari Tidak Diketahui'][] = $schedule;
                  continue;
              }
              
              $matchedDay = null;
              foreach ($days as $validDay) {
                  if (strcasecmp($day, $validDay) === 0) {
                      $matchedDay = $validDay;
                      break;
                  }
              }
              if ($matchedDay === null) {
                  $matchedDay = $day;
                  if (!isset($schedulesByDay[$matchedDay])) {
                      $schedulesByDay[$matchedDay] = [];
                  }
              }
              $schedulesByDay[$matchedDay][] = $schedule;
          }
      }
      
      $totalSchedules = is_array($schedules) ? count($schedules) : 0;
      ?>
      
      <div class="class-container bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
        <!-- Class Header -->
        <div class="px-4 py-3 bg-[#111827] border-b border-gray-700">
          <div class="flex items-center justify-between gap-3">
            <div class="flex-1 min-w-0">
              <h2 class="text-base font-semibold text-gray-100 truncate"><?= htmlspecialchars($className) ?></h2>
              <?php if ($classCode): ?>
              <p class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($classCode) ?></p>
              <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
              <span class="bg-gray-700 text-gray-300 px-2.5 py-1 rounded text-xs font-medium">
                <?= $totalSchedules ?> Jadwal
              </span>
              <?php if ($totalSchedules > 0): ?>
              <button onclick="viewScheduleModal(<?= $classId ?>, '<?= htmlspecialchars($className, ENT_QUOTES) ?>', <?= htmlspecialchars(json_encode($schedulesByDay), ENT_QUOTES) ?>)" 
                      class="px-2.5 py-1 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded text-xs font-medium transition-colors">
                Lihat
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Schedules Content -->
        <div class="p-3">
          <?php if ($totalSchedules > 0): ?>
          <div class="space-y-3">
            <?php 
            $daysToShow = array_merge($days, array_keys($schedulesByDay));
            $daysToShow = array_unique($daysToShow);
            foreach ($daysToShow as $day): 
            ?>
            <?php if (!empty($schedulesByDay[$day])): ?>
            <div class="bg-[#111827] rounded-md border border-gray-700 overflow-hidden">
              <!-- Day Header -->
              <div class="px-3 py-2 bg-gray-800 border-b border-gray-700 flex items-center justify-between">
                <h3 class="text-xs font-semibold text-gray-300"><?= $day ?></h3>
                <span class="text-xs text-gray-500"><?= count($schedulesByDay[$day]) ?></span>
              </div>
              
              <!-- Day Schedules -->
              <div class="p-2 space-y-2">
                <?php foreach ($schedulesByDay[$day] as $schedule): ?>
                <?php
                $badgeClass = 'bg-[#5865F2]/10 text-[#5865F2] border-[#5865F2]/20';
                $badgeText = 'Lecture';
                if (stripos($schedule['subject'], 'lab') !== false || stripos($schedule['subject'], 'praktikum') !== false) {
                    $badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                    $badgeText = 'Lab';
                } elseif (stripos($schedule['subject'], 'tutorial') !== false) {
                    $badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                    $badgeText = 'Tutorial';
                }
                ?>
                <div class="schedule-card p-2.5 bg-[#1f2937] rounded border border-gray-700">
                  <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                      <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <h4 class="text-sm font-medium text-gray-100 truncate"><?= htmlspecialchars($schedule['subject']) ?></h4>
                        <span class="px-2 py-0.5 rounded text-[10px] font-medium border <?= $badgeClass ?> flex-shrink-0">
                          <?= $badgeText ?>
                        </span>
                      </div>
                      
                      <div class="space-y-1 text-xs text-gray-400">
                        <div class="flex items-center gap-1.5">
                          <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                          </svg>
                          <span><?= htmlspecialchars($schedule['start_time']) ?> - <?= htmlspecialchars($schedule['end_time']) ?></span>
                        </div>
                        
                        <div class="flex items-center gap-1.5">
                          <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                          </svg>
                          <span class="truncate"><?= htmlspecialchars($schedule['teacher_name'] ?? 'N/A') ?></span>
                        </div>
                        
                        <?php if (!empty($schedule['class'])): ?>
                        <div class="flex items-center gap-1.5">
                          <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                          </svg>
                          <span class="truncate"><?= htmlspecialchars($schedule['class']) ?></span>
                        </div>
                        <?php endif; ?>
                      </div>
                    </div>

                    <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
                    <div class="flex items-center gap-1 flex-shrink-0">
                      <a href="index.php?page=schedule/edit/<?= $schedule['id'] ?>" 
                         class="p-1.5 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded transition-colors"
                         title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </a>
                      <form method="POST" action="index.php?page=schedule/delete/<?= $schedule['id'] ?>" 
                            onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
                        <button type="submit" 
                                class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-colors"
                                title="Hapus">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
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
          <?php else: ?>
          <!-- Empty State for Class -->
          <div class="text-center py-8">
            <div class="w-12 h-12 mx-auto mb-3 rounded-lg bg-gray-800 flex items-center justify-center">
              <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <p class="text-gray-500 text-sm mb-3">Belum ada jadwal</p>
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
            <a href="index.php?page=schedule/create&class_id=<?= $classId ?>" 
               class="inline-flex items-center gap-2 px-3 py-1.5 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              Tambah
            </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <?php if (empty($classesWithSchedules)): ?>
    <!-- Empty State -->
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-12 text-center">
      <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Kelas</h3>
      <p class="text-gray-400 mb-6">Belum ada kelas yang tersedia. Silakan buat kelas terlebih dahulu.</p>
      <a href="index.php?page=classes" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Kelas
      </a>
    </div>
    <?php endif; ?>

    <!-- Info Footer -->
    <div class="mt-6 bg-[#1f2937] border border-gray-700 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 bg-[#5865F2]/10 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="flex-1">
          <h3 class="text-gray-100 font-semibold text-sm mb-1">Informasi Penting</h3>
          <p class="text-gray-400 text-sm leading-relaxed">
            Jadwal dapat berubah sewaktu-waktu. Harap selalu cek pembaruan dari admin atau sistem notifikasi.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Schedule View Modal -->
<div id="scheduleViewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
  <div class="bg-[#1f2937] border border-gray-700 rounded-lg max-w-5xl w-full max-h-[90vh] overflow-hidden shadow-2xl">
    <!-- Modal Header -->
    <div class="px-6 py-4 bg-[#111827] border-b border-gray-700 flex items-center justify-between">
      <div>
        <h2 id="modalClassName" class="text-xl font-semibold text-gray-100"></h2>
        <p class="text-gray-400 text-sm mt-0.5">Jadwal Lengkap</p>
      </div>
      <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-200 transition-colors p-2 hover:bg-gray-800 rounded">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    
    <!-- Modal Content -->
    <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
      <div id="scheduleModalContent" class="space-y-4">
        <!-- Content will be populated by JavaScript -->
      </div>
    </div>
  </div>
</div>

<script>
const isAdmin = <?= isset($_SESSION['level']) && $_SESSION['level'] === 'admin' ? 'true' : 'false' ?>;

function viewScheduleModal(classId, className, schedulesByDay) {
  const modal = document.getElementById('scheduleViewModal');
  const modalClassName = document.getElementById('modalClassName');
  const modalContent = document.getElementById('scheduleModalContent');
  
  modalClassName.textContent = className;
  
  let content = '';
  const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  
  days.forEach(day => {
    const daySchedules = schedulesByDay[day] || [];
    if (daySchedules.length > 0) {
      content += `
        <div class="bg-[#111827] rounded-lg border border-gray-700 overflow-hidden">
          <div class="px-4 py-3 bg-gray-800 border-b border-gray-700">
            <div class="flex items-center justify-between">
              <h3 class="text-base font-semibold text-gray-100">${day}</h3>
              <span class="bg-gray-700 text-gray-300 px-2.5 py-1 rounded text-xs font-medium">
                ${daySchedules.length} Jadwal
              </span>
            </div>
          </div>
          <div class="p-4">
            <div class="space-y-3">
      `;
      
      daySchedules.forEach(schedule => {
        let badgeClass = 'bg-[#5865F2]/10 text-[#5865F2] border-[#5865F2]/20';
        let badgeText = 'Lecture';
        const subject = schedule.subject || '';
        if (subject.toLowerCase().includes('lab') || subject.toLowerCase().includes('praktikum')) {
          badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
          badgeText = 'Lab';
        } else if (subject.toLowerCase().includes('tutorial')) {
          badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
          badgeText = 'Tutorial';
        }
        
        content += `
          <div class="bg-[#1f2937] rounded-lg border border-gray-700 p-4 hover:border-[#5865F2] transition-colors">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                  <h4 class="text-base font-medium text-gray-100">${escapeHtml(subject)}</h4>
                  <span class="px-2.5 py-0.5 rounded text-xs font-medium border ${badgeClass}">
                    ${badgeText}
                  </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-200">${schedule.start_time || ''} - ${schedule.end_time || ''}</span>
                  </div>
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-gray-200">${escapeHtml(schedule.teacher_name || 'N/A')}</span>
                  </div>
                  ${schedule.class ? `
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-gray-200">${escapeHtml(schedule.class)}</span>
                  </div>
                  ` : ''}
                </div>
              </div>
              ${isAdmin ? `
              <a href="index.php?page=schedule/edit/${schedule.id}" 
                 class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded transition-colors"
                 title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
              </a>
              ` : ''}
            </div>
          </div>
        `;
      });
      
      content += `
            </div>
          </div>
        </div>
      `;
    }
  });
  
  if (!content) {
    content = `
      <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
          <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Jadwal</h3>
        <p class="text-gray-400">Kelas ini belum memiliki jadwal</p>
      </div>
    `;
  }
  
  modalContent.innerHTML = content;
  modal.classList.remove('hidden');
  document.body.style.overflow = 'hidden';
}

function closeScheduleModal() {
  const modal = document.getElementById('scheduleViewModal');
  modal.classList.add('hidden');
  document.body.style.overflow = 'auto';
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Close modal when clicking outside
document.getElementById('scheduleViewModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeScheduleModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeScheduleModal();
  }
});
</script>********************************************