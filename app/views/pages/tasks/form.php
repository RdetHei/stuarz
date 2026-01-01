<?php
$edit = isset($task);
$action = $edit ? 'index.php?page=tasks/update' : 'index.php?page=tasks/store';
$id = $edit ? intval($task['id']) : 0;
$title = $edit ? $task['title'] : '';
$desc = $edit ? $task['description'] : '';
$status = $edit ? $task['status'] : '';
$deadline = $edit ? $task['deadline'] : '';
$class_id = $edit ? $task['class_id'] : ($_GET['class_id'] ?? '');
$subject_id = $edit ? $task['subject_id'] : '';
$workflow_state = $edit ? ($task['workflow_state'] ?? 'published') : 'published';
$approval_required = $edit ? intval($task['approval_required'] ?? 0) : 0;
$max_attempts = $edit ? intval($task['max_attempts'] ?? 1) : 1;
$reminder_at = $edit && !empty($task['reminder_at']) ? date('Y-m-d\TH:i', strtotime($task['reminder_at'])) : '';
$allow_late = $edit ? intval($task['allow_late'] ?? 0) : 0;
$late_deadline = $edit && !empty($task['late_deadline']) ? date('Y-m-d', strtotime($task['late_deadline'])) : '';
$rubricValue = $edit && !empty($task['grading_rubric']) ? json_encode($task['grading_rubric'], JSON_PRETTY_PRINT) : '';
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$schedules = $schedules ?? [];
$existing_file_name = $edit && !empty($task['file_path']) ? pathinfo($task['file_path'], PATHINFO_BASENAME) : '';
?>

<div class="min-h-screen bg-gray-900 p-6">
<div class="max-w-4xl mx-auto">
  
  <div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-semibold text-gray-100"><?= $edit ? 'Edit Task' : 'Tambah Task' ?></h1>
          <p class="text-sm text-gray-400 mt-0.5">Kelola tugas untuk siswa</p>
        </div>
      </div>
      
      <a href="index.php?page=tasks" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-200 text-sm font-medium rounded-md border border-gray-700 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg flex items-center gap-3">
      <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      <span class="text-sm"><?= htmlspecialchars($_SESSION['error']) ?></span>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data" id="taskForm">
      <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
      <?php endif; ?>

      <div id="formErrors" class="hidden mx-6 mt-6 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg text-sm"></div>

      <div class="p-6 space-y-6">
        
        <div>
          <div class="flex items-center gap-2 mb-4">
            <h3 class="text-base font-semibold text-gray-100">Informasi Dasar</h3>
          </div>
          <div class="space-y-4">
            
            <div>
              <label for="title" class="block text-xs font-medium text-gray-400 mb-2">
                Judul Task <span class="text-red-400">*</span>
              </label>
              <input type="text" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required 
                     placeholder="Contoh: Tugas Matematika Bab 5"
                     class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="class_id" class="block text-xs font-medium text-gray-400 mb-2">
                  Kelas <span class="text-red-400">*</span>
                </label>
                <select name="class_id" id="class_id" required 
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                  <option value="">Pilih Kelas</option>
                  <?php foreach ($classes as $class): ?>
                  <option value="<?= $class['id'] ?>" <?= $class_id == $class['id'] ? 'selected' : '' ?> >
                    <?= htmlspecialchars($class['name']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div>
                <label for="subject_id" class="block text-xs font-medium text-gray-400 mb-2">
                  Mata Pelajaran <span class="text-red-400">*</span>
                </label>
                <select name="subject_id" id="subject_id" required 
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                  <option value="">Pilih Mata Pelajaran</option>
                  <?php foreach ($subjects as $subject): ?>
                  <option value="<?= $subject['id'] ?>" <?= $subject_id == $subject['id'] ? 'selected' : '' ?> >
                    <?= htmlspecialchars($subject['name']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="deadline" class="block text-xs font-medium text-gray-400 mb-2">
                  Deadline <span class="text-red-400">*</span>
                </label>
                <input type="date" id="deadline" name="deadline" value="<?= htmlspecialchars($deadline) ?>" required 
                       class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" />
              </div>

              <div>
                <label for="status" class="block text-xs font-medium text-gray-400 mb-2">
                  Status <span class="text-red-400">*</span>
                </label>
                <select name="status" id="status" required 
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                  <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="Completed" <?= $status === 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
              </div>
            </div>

            <div>
              <label for="description" class="block text-xs font-medium text-gray-400 mb-2">
                Deskripsi <span class="text-red-400">*</span>
              </label>
              <textarea name="description" id="description" rows="4" required
                        placeholder="Deskripsikan tugas yang harus dikerjakan siswa..."
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none resize-none"><?= htmlspecialchars($desc) ?></textarea>
            </div>

          </div>
        </div>

        <div class="border-t border-gray-700"></div>

        <div>
          <div class="flex items-center gap-2 mb-4">
            <h3 class="text-base font-semibold text-gray-100">Pengaturan Lanjutan</h3>
          </div>
          <div class="space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label for="workflow_state" class="block text-xs font-medium text-gray-400 mb-2">Workflow State</label>
                <select name="workflow_state" id="workflow_state" 
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                  <?php $states = ['draft' => 'Draft', 'published' => 'Published', 'in_review' => 'In Review', 'closed' => 'Closed']; ?>
                  <?php foreach ($states as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $workflow_state === $value ? 'selected' : '' ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Draft tidak dapat diakses siswa</p>
              </div>

              <div>
                <label for="max_attempts" class="block text-xs font-medium text-gray-400 mb-2">Maksimal Percobaan</label>
                <input type="number" name="max_attempts" id="max_attempts" min="1" value="<?= htmlspecialchars($max_attempts) ?>" 
                       class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Berapa kali siswa boleh mengumpulkan</p>
              </div>
            </div>

            <div class="space-y-3">
              <label class="flex items-start gap-3 p-3 bg-gray-900 border border-gray-700 rounded-md cursor-pointer hover:border-gray-600 transition-colors">
                <input type="checkbox" name="approval_required" value="1" <?= $approval_required ? 'checked' : '' ?> 
                       class="mt-0.5 w-4 h-4 rounded bg-gray-800 border-gray-600 text-blue-500 focus:ring-1 focus:ring-blue-500 focus:ring-offset-0">
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-200">Wajib Approval</div>
                  <div class="text-xs text-gray-400 mt-0.5">Aktifkan jika guru perlu review sebelum dinilai</div>
                </div>
              </label>

              <label class="flex items-start gap-3 p-3 bg-gray-900 border border-gray-700 rounded-md cursor-pointer hover:border-gray-600 transition-colors">
                <input type="checkbox" name="allow_late" value="1" <?= $allow_late ? 'checked' : '' ?> id="allowLateCheck"
                       class="mt-0.5 w-4 h-4 rounded bg-gray-800 border-gray-600 text-blue-500 focus:ring-1 focus:ring-blue-500 focus:ring-offset-0">
                <div class="flex-1">
                  <div class="text-sm font-medium text-gray-200">Izinkan Pengumpulan Terlambat</div>
                  <div class="text-xs text-gray-400 mt-0.5">Beri waktu tambahan setelah deadline</div>
                </div>
              </label>
            </div>

            <div id="lateDeadlineWrapper" class="<?= !$allow_late ? 'hidden' : '' ?> pl-7">
              <label for="lateDeadlineInput" class="block text-xs font-medium text-gray-400 mb-2">Deadline Terlambat</label>
              <input type="date" name="late_deadline" id="lateDeadlineInput" value="<?= $late_deadline ?>" 
                     class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none">
            </div>

          </div>
        </div>

        <div class="border-t border-gray-700"></div>

        <div>
          <div class="flex items-center gap-2 mb-4">
            <h3 class="text-base font-semibold text-gray-100">File & Penilaian</h3>
          </div>
          <div class="space-y-4">
            
            <div>
              <label class="block text-xs font-medium text-gray-400 mb-2">File Lampiran</label>
              <label class="block p-4 bg-gray-900 border-2 border-dashed border-gray-700 rounded-md cursor-pointer hover:border-blue-500 transition-colors">
                <div class="flex items-center gap-3">
                  <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                  </svg>
                  <div class="flex-1 min-w-0">
                    <span id="fileLabelText" class="text-sm text-gray-300 block truncate">
                      <?= $existing_file_name ? htmlspecialchars($existing_file_name) : 'Pilih file atau drag & drop' ?>
                    </span>
                    <p class="text-xs text-gray-500 mt-0.5">PDF, DOC, DOCX, TXT, JPG, PNG (Max 5MB)</p>
                  </div>
                  <button type="button" id="removeFileBtn" 
                          class="<?= !$existing_file_name ? 'hidden' : '' ?> px-3 py-1 text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/30 rounded hover:bg-red-500/20 transition-colors">
                    Hapus
                  </button>
                </div>
                <input type="file" id="file" name="file" class="hidden" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" />
              </label>
              
              <?php if ($edit && !empty($task['file_path'])): ?>
                <div class="mt-2 flex items-center gap-3 text-xs">
                  <a href="<?= htmlspecialchars($task['file_path']) ?>" target="_blank" 
                     class="text-blue-400 hover:text-blue-300 transition-colors">
                    Lihat lampiran saat ini →
                  </a>
                  <label class="flex items-center gap-2 text-gray-400">
                    <input type="checkbox" name="remove_file" value="1" 
                           class="w-3.5 h-3.5 rounded bg-gray-800 border-gray-600 text-red-500">
                    <span>Hapus saat menyimpan</span>
                  </label>
                </div>
              <?php endif; ?>
            </div>

            <div>
              <label for="gradingRubric" class="block text-xs font-medium text-gray-400 mb-2">Rubrik Penilaian</label>
              <textarea name="grading_rubric" id="gradingRubric" rows="4" 
                        placeholder="Pemahaman Konsep:40&#10;Kerapihan:30&#10;Kreativitas:30"
                        class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 placeholder-gray-500 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none resize-none"><?= htmlspecialchars($rubricValue) ?></textarea>
              
              <div id="rubricPreview" class="mt-3 p-3 bg-gray-900 border border-gray-700 rounded-md">
                <div class="text-xs font-medium text-gray-300 mb-2">Preview Rubrik:</div>
                <div id="rubricItems" class="text-xs text-gray-400">Tidak ada rubrik</div>
              </div>
            </div>

          </div>
        </div>

      </div>

      <div class="px-6 py-3 bg-gray-900 border-t border-gray-700 flex items-center justify-between">
        <div class="text-xs text-gray-500">
          <span class="text-red-400">*</span> Field wajib diisi
        </div>
        <div class="flex items-center gap-2">
          <a href="index.php?page=tasks" 
             class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 text-sm font-medium rounded-md transition-colors">
            Batal
          </a>
          <button type="submit" 
                  class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <?= $edit ? 'Update' : 'Simpan' ?>
          </button>
        </div>
      </div>

    </form>
  </div>

</div>
</div>

<script src="js/tasks.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('file');
  const fileLabelText = document.getElementById('fileLabelText');
  const removeFileBtn = document.getElementById('removeFileBtn');
  const allowLateCheck = document.getElementById('allowLateCheck');
  const lateDeadlineWrapper = document.getElementById('lateDeadlineWrapper');
  const lateDeadlineInput = document.getElementById('lateDeadlineInput');
  const deadlineInput = document.getElementById('deadline');
  const rubricTextarea = document.getElementById('gradingRubric');
  const rubricItems = document.getElementById('rubricItems');

  if (fileInput && fileLabelText && removeFileBtn) {
    fileInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        if (file.size > 5 * 1024 * 1024) {
          alert('File terlalu besar. Maksimal 5MB.');
          fileInput.value = '';
          return;
        }
        const size = (file.size / 1024 / 1024).toFixed(2);
        fileLabelText.textContent = `${file.name} (${size}MB)`;
        removeFileBtn.classList.remove('hidden');
      }
    });

    removeFileBtn.addEventListener('click', function() {
      fileInput.value = '';
      fileLabelText.textContent = 'Pilih file atau drag & drop di sini';
      removeFileBtn.classList.add('hidden');
    });
  }

  if (allowLateCheck && lateDeadlineWrapper && lateDeadlineInput) {
    allowLateCheck.addEventListener('change', function() {
      if (this.checked) {
        lateDeadlineWrapper.classList.remove('hidden');
        if (deadlineInput.value) {
          lateDeadlineInput.min = deadlineInput.value;
        }
      } else {
        lateDeadlineWrapper.classList.add('hidden');
        lateDeadlineInput.value = '';
      }
    });
  }

  if (deadlineInput) {
    deadlineInput.min = new Date().toISOString().split('T')[0];
  }

  function renderRubric() {
    if (!rubricTextarea || !rubricItems) return;
    const text = rubricTextarea.value.trim();
    if (!text) {
      rubricItems.innerHTML = '<div class="text-gray-500">Tidak ada rubrik</div>';
      return;
    }

    const lines = text.split('\n').filter(l => l.trim());
    let items = [];
    
    lines.forEach(line => {
      const parts = line.split(':');
      if (parts.length >= 2) {
        const criteria = parts[0].trim();
        const score = parts.slice(1).join(':').trim();
        items.push({ criteria, score });
      }
    });

    if (items.length === 0) {
      rubricItems.innerHTML = '<div class="text-gray-500">Format: Kriteria:Skor</div>';
      return;
    }

    rubricItems.innerHTML = items.map(item => `
      <div class="flex items-center justify-between py-1.5 px-2 bg-gray-800 rounded">
        <span class="text-gray-200">${item.criteria}</span>
        <span class="text-blue-400 font-medium">${item.score}</span>
      </div>
    `).join('');
  }

  if (rubricTextarea) {
    rubricTextarea.addEventListener('input', renderRubric);
    renderRubric();
  }
});
</script>