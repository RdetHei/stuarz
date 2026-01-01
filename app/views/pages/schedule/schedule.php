
<?php
$classesWithSchedules = $classesWithSchedules ?? [];
$filterClasses = $filterClasses ?? [];
$filterTeachers = $filterTeachers ?? [];
$days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$selectedClass = $_GET['class_id'] ?? '';
$selectedTeacher = $_GET['teacher_id'] ?? '';
$selectedDay = $_GET['day'] ?? '';
?>

<style>
  .class-container {
    transition: all 0.2s ease;
  }
  .class-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  }
</style>

<div class="min-h-screen bg-gray-900 p-6">
  <div class="max-w-7xl mx-auto">
    
    
    <div class="mb-6">
      <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
        <div>
          <h1 class="text-2xl font-semibold text-gray-100 mb-1">Jadwal Pelajaran</h1>
          <p class="text-sm text-gray-400">Semester Genap 2024/2025</p>
        </div>

        <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
        <a href="index.php?page=schedule/create" 
           class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Jadwal
        </a>
        <?php endif; ?>
      </div>

      
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
        <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <input type="hidden" name="page" value="schedule">

          <div>
            <label class="block text-xs font-medium text-gray-400 mb-1.5">Kelas</label>
            <select name="class_id" class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
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
            <select name="teacher_id" class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
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
            <select name="day" class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
              <option value="">Semua Hari</option>
              <?php foreach ($days as $d): ?>
              <option value="<?= $d ?>" <?= $selectedDay == $d ? 'selected' : '' ?>><?= $d ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors">
              Filter
            </button>
            <?php if ($selectedClass || $selectedTeacher || $selectedDay): ?>
            <a href="index.php?page=schedule" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-gray-300 text-sm font-medium rounded-md transition-colors">
              Reset
            </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
      
      
      <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden hover:border-gray-600 transition-colors">
        
        
        <div class="p-4 border-b border-gray-700">
          <div class="flex items-start gap-3">
            <div class="w-12 h-12 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
              </svg>
            </div>
            
            <div class="flex-1 min-w-0">
              <h2 class="text-base font-semibold text-gray-100 truncate mb-1"><?= htmlspecialchars($className) ?></h2>
              <?php if ($classCode): ?>
              <p class="text-xs text-gray-400 truncate"><?= htmlspecialchars($classCode) ?></p>
              <?php endif; ?>
              <?php if ($classDescription): ?>
              <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars($classDescription) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        
        <div class="p-4 bg-gray-900">
          <div class="flex items-center justify-between mb-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-700 text-gray-300 rounded text-xs font-medium">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <?= $totalSchedules ?> Jadwal
            </span>
            <?php if (isset($_SESSION['level']) && $_SESSION['level'] === 'admin'): ?>
            <a href="index.php?page=schedule/create&class_id=<?= $classId ?>" 
               class="text-xs text-blue-400 hover:text-blue-300 transition-colors">
              + Tambah
            </a>
            <?php endif; ?>
          </div>
          
          <?php if ($totalSchedules > 0): ?>
          <button onclick="viewScheduleModal(<?= $classId ?>, '<?= htmlspecialchars($className, ENT_QUOTES) ?>', '<?= htmlspecialchars($classCode ?? '', ENT_QUOTES) ?>', <?= htmlspecialchars(json_encode($schedulesByDay), ENT_QUOTES) ?>)" 
                  class="w-full px-3 py-2 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 text-blue-400 text-sm font-medium rounded-md transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Lihat Jadwal
          </button>
          <?php else: ?>
          <div class="w-full px-3 py-2 bg-gray-700 text-gray-400 text-sm font-medium rounded-md cursor-not-allowed flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            Belum Ada Jadwal
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    
    <?php if (empty($classesWithSchedules)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-lg p-12 text-center">
      <div class="w-16 h-16 mx-auto mb-4 rounded-lg bg-gray-700 flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Kelas</h3>
      <p class="text-sm text-gray-400 mb-6">Belum ada kelas yang tersedia. Silakan buat kelas terlebih dahulu.</p>
      <a href="index.php?page=classes" 
         class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Kelas
      </a>
    </div>
    <?php endif; ?>

    
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <div class="w-8 h-8 bg-blue-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-sm font-semibold text-gray-100 mb-1">Informasi Penting</h3>
          <p class="text-sm text-gray-400">
            Jadwal dapat berubah sewaktu-waktu. Harap selalu cek pembaruan dari admin atau sistem notifikasi.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>


<div id="scheduleViewModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center p-4">
  <div class="bg-gray-800 border border-gray-700 rounded-lg max-w-5xl w-full max-h-[85vh] overflow-hidden flex flex-col">
    
    
    <div class="px-5 py-3 bg-gray-900 border-b border-gray-700 flex items-center justify-between flex-shrink-0">
      <div>
        <div id="modalClassName" class="text-base font-semibold text-gray-100"></div>
        <p class="text-xs text-gray-400 mt-0.5">Jadwal Lengkap Kelas</p>
      </div>
      <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-200 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
    
    
    <div class="p-5 overflow-y-auto flex-1 bg-gray-900">
      <div id="scheduleModalContent" class="space-y-3">
        
      </div>
    </div>
  </div>
</div>

<script>
const isAdmin = <?= isset($_SESSION['level']) && $_SESSION['level'] === 'admin' ? 'true' : 'false' ?>;

function viewScheduleModal(classId, className, classCode, schedulesByDay) {
  const modal = document.getElementById('scheduleViewModal');
  const modalClassName = document.getElementById('modalClassName');
  const modalContent = document.getElementById('scheduleModalContent');
  
  modalClassName.innerHTML = `
    <div>
      <div class="text-xl font-semibold text-gray-100">${escapeHtml(className)}</div>
      ${classCode ? `<div class="text-sm text-gray-400 mt-0.5">${escapeHtml(classCode)}</div>` : ''}
    </div>
  `;
  
  let content = '';
  const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  
  days.forEach(day => {
    const daySchedules = schedulesByDay[day] || [];
    if (daySchedules.length > 0) {
      content += `
        <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
          <div class="px-5 py-4 bg-gray-900 border-b border-gray-700">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-semibold text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                ${day}
              </h3>
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-500/10 text-blue-300 border border-blue-500/20 rounded-md text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                ${daySchedules.length} Jadwal
              </span>
            </div>
          </div>
          <div class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      `;
      
      daySchedules.forEach(schedule => {
        let badgeClass = 'bg-blue-500/10 text-blue-300 border-blue-500/20';
        let badgeText = 'Lecture';
        const subject = schedule.subject || '';
        if (subject.toLowerCase().includes('lab') || subject.toLowerCase().includes('praktikum')) {
          badgeClass = 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20';
          badgeText = 'Lab';
        } else if (subject.toLowerCase().includes('tutorial')) {
          badgeClass = 'bg-amber-500/10 text-amber-300 border-amber-500/20';
          badgeText = 'Tutorial';
        }
        
        content += `
          <div class="bg-gray-900 rounded-lg border border-gray-700 p-4 hover:border-blue-500/50 transition-all group">
            <div class="flex items-start justify-between gap-3">
              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                  <h4 class="text-base font-semibold text-gray-100">${escapeHtml(subject)}</h4>
                  <span class="px-2.5 py-1 rounded-md text-xs font-medium border ${badgeClass} flex-shrink-0">
                    ${badgeText}
                  </span>
                </div>
                <div class="space-y-2 text-sm">
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-200 font-medium">${schedule.start_time || ''} - ${schedule.end_time || ''}</span>
                  </div>
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-gray-200">${escapeHtml(schedule.teacher_name || 'N/A')}</span>
                  </div>
                  ${schedule.room ? `
                  <div class="flex items-center gap-2 text-gray-400">
                    <svg class="w-4 h-4 flex-shrink-0 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="text-gray-200">${escapeHtml(schedule.room)}</span>
                  </div>
                  ` : ''}
                </div>
              </div>
              ${isAdmin ? `
              <div class="flex items-center gap-1 flex-shrink-0">
                <a href="index.php?page=schedule/edit/${schedule.id}" 
                   class="p-2 text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 rounded-md transition-colors"
                   title="Edit">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <button type="button" 
                        class="delete-btn p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-md transition-colors"
                        title="Hapus"
                        data-id="${schedule.id}"
                        data-url="index.php?page=schedule/delete"
                        data-item-name="Jadwal ${escapeHtml(schedule.subject_name || 'Jadwal')}"
                        data-row-selector=".schedule-item">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </div>
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
      <div class="text-center py-16">
        <div class="w-20 h-20 mx-auto mb-4 rounded-xl bg-gray-800 flex items-center justify-center">
          <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-100 mb-2">Belum Ada Jadwal</h3>
        <p class="text-gray-400 mb-4">Kelas ini belum memiliki jadwal pelajaran</p>
        ${isAdmin ? `
        <a href="index.php?page=schedule/create&class_id=${classId}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
          </svg>
          Tambah Jadwal
        </a>
        ` : ''}
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

document.getElementById('scheduleViewModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeScheduleModal();
  }
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeScheduleModal();
  }
});
</script>