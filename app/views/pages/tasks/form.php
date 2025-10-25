<?php
$edit = isset($task);
$action = $edit ? 'index.php?page=tasks/update' : 'index.php?page=tasks/store';
$id = $edit ? intval($task['id']) : 0;
$title = $edit ? $task['title'] : '';
$desc = $edit ? $task['description'] : '';
$status = $edit ? $task['status'] : '';
$deadline = $edit ? $task['deadline'] : '';
$class_id = $edit ? $task['class_id'] : '';
$subject_id = $edit ? $task['subject_id'] : '';
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$schedules = $schedules ?? [];
?>

<div class="max-w-4xl mx-auto p-6">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white"><?= $edit ? 'Edit Task' : 'Tambah Task' ?></h1>
          <p class="text-gray-400 text-sm mt-1">Kelola tugas untuk siswa</p>
        </div>
      </div>
      
      <a href="index.php?page=tasks" 
         class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 border border-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>

  <!-- Flash Messages -->
  <?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- Form Card -->
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data" class="space-y-6" id="taskForm">
      <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Title -->
        <div class="md:col-span-2">
          <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
            Judul Task <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <input type="text" 
                   id="title"
                   name="title" 
                   value="<?= htmlspecialchars($title) ?>" 
                   required 
                   placeholder="Contoh: Tugas Matematika Bab 5"
                   class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500" />
          </div>
        </div>

        <!-- Class -->
        <div>
          <label for="class_id" class="block text-sm font-medium text-gray-300 mb-2">
            Kelas <span class="text-red-500">*</span>
          </label>
          <select name="class_id" 
                  id="class_id" 
                  required 
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Pilih Kelas</option>
            <?php foreach ($classes as $class): ?>
            <option value="<?= $class['id'] ?>" <?= $class_id == $class['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($class['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Teacher (assignment) -->
        <div>
          <label for="teacher_id" class="block text-sm font-medium text-gray-300 mb-2">Guru (penanggung jawab)</label>
          <?php $currentLevel = $_SESSION['level'] ?? 'user'; ?>
          <?php if ($currentLevel === 'admin'): ?>
            <select name="teacher_id" id="teacher_id" class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
              <option value="">Pilih Guru</option>
              <?php foreach ($teachers ?? [] as $t): ?>
                <option value="<?= $t['id'] ?>" <?= (isset($task['user_id']) && $task['user_id']==$t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['name']) ?></option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <input type="hidden" name="teacher_id" value="<?= intval($_SESSION['user_id'] ?? 0) ?>">
            <div class="px-3 py-2 text-sm text-gray-300 bg-gray-900 border border-gray-700 rounded-lg">Anda: <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Saya') ?></div>
          <?php endif; ?>
        </div>

        <!-- Subject -->
        <div>
          <label for="subject_id" class="block text-sm font-medium text-gray-300 mb-2">
            Mata Pelajaran <span class="text-red-500">*</span>
          </label>
          <select name="subject_id" 
                  id="subject_id" 
                  required 
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Pilih Mata Pelajaran</option>
            <?php foreach ($subjects as $subject): ?>
            <option value="<?= $subject['id'] ?>" <?= $subject_id == $subject['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($subject['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Schedule (optional link) -->
        <div>
          <label for="schedule_id" class="block text-sm font-medium text-gray-300 mb-2">
            Hubungkan ke Jadwal (opsional)
          </label>
          <select name="schedule_id" id="schedule_id" class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Tidak terkait jadwal</option>
            <?php foreach ($schedules as $sch): ?>
              <option value="<?= $sch['id'] ?>" <?= (isset($task['schedule_id']) && $task['schedule_id'] == $sch['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($sch['class']) ?> - <?= htmlspecialchars($sch['subject']) ?> (<?= htmlspecialchars($sch['day']) ?> <?= htmlspecialchars($sch['start_time']) ?>-<?= htmlspecialchars($sch['end_time']) ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <p class="text-xs text-gray-500 mt-2">Opsional: jika tugas terkait sesi jadwal tertentu, pilih di sini.</p>
        </div>

        <!-- Deadline -->
        <div>
          <label for="deadline" class="block text-sm font-medium text-gray-300 mb-2">
            Deadline <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
            </div>
            <input type="date" 
                   id="deadline"
                   name="deadline" 
                   value="<?= htmlspecialchars($deadline) ?>" 
                   required 
                   class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all" />
          </div>
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
            Status <span class="text-red-500">*</span>
          </label>
          <select name="status" 
                  id="status" 
                  required 
                  class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Completed" <?= $status === 'Completed' ? 'selected' : '' ?>>Completed</option>
          </select>
        </div>

        <!-- Description -->
        <div class="md:col-span-2">
          <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
            Deskripsi <span class="text-red-500">*</span>
          </label>
          <textarea name="description" 
                    id="description"
                    rows="4"
                    required
                    placeholder="Deskripsikan tugas yang harus dikerjakan siswa..."
                    class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all resize-none placeholder:text-gray-500"><?= htmlspecialchars($desc) ?></textarea>
        </div>

        <!-- File Attachment -->
        <div class="md:col-span-2">
          <label for="file" class="block text-sm font-medium text-gray-300 mb-2">
            File Lampiran
          </label>
          <div class="flex items-center gap-4">
            <label class="flex-1 flex items-center justify-center px-4 py-3 bg-gray-900 border-2 border-dashed border-gray-700 rounded-lg cursor-pointer hover:border-indigo-600 transition-all">
              <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <span class="text-sm">Choose file or drag here</span>
              </div>
              <input type="file" 
                     id="file"
                     name="file" 
                     class="hidden" 
                     accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" />
            </label>
          </div>
          <p class="text-xs text-gray-500 mt-2">Supported: PDF, DOC, DOCX, TXT, JPG, PNG (Max 5MB)</p>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex items-center gap-3 pt-6 border-t border-gray-700">
        <button type="submit" 
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          <?= $edit ? 'Update Task' : 'Simpan Task' ?>
        </button>
        
        <a href="index.php?page=tasks" 
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
        <h3 class="text-white font-semibold text-lg mb-3">Tips Membuat Task</h3>
        <ul class="space-y-2">
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Pastikan judul task jelas dan deskriptif</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Berikan deadline yang realistis untuk siswa menyelesaikan tugas</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Lampirkan file referensi jika diperlukan untuk membantu siswa</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taskForm');
    const fileInput = document.getElementById('file');
    const label = fileInput?.closest('label');
    
    // File input preview
    if (fileInput && label) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('File terlalu besar. Maksimal 5MB.');
                    fileInput.value = '';
                    return;
                }
                
                label.innerHTML = `
                    <div class="flex items-center gap-2 text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm">${fileName} (${fileSize}MB)</span>
                    </div>
                `;
            }
        });
    }
    
    // Form validation
    if (form) {
        form.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const classId = document.getElementById('class_id').value;
            const subjectId = document.getElementById('subject_id').value;
            const deadline = document.getElementById('deadline').value;
            
            let errors = [];
            
            if (!title) {
                errors.push('Judul task wajib diisi');
            }
            
            if (!description) {
                errors.push('Deskripsi task wajib diisi');
            }
            
            if (!classId) {
                errors.push('Kelas wajib dipilih');
            }
            
            if (!subjectId) {
                errors.push('Mata pelajaran wajib dipilih');
            }
            
            if (!deadline) {
                errors.push('Deadline wajib diisi');
            } else if (deadline < new Date().toISOString().split('T')[0]) {
                errors.push('Deadline tidak boleh lebih awal dari hari ini');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Error:\n' + errors.join('\n'));
                return false;
            }
        });
    }
    
    // Set minimum date to today
    const deadlineInput = document.getElementById('deadline');
    if (deadlineInput) {
        deadlineInput.min = new Date().toISOString().split('T')[0];
    }
});
</script>