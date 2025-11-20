
<?php
$edit = isset($subject);
$action = $edit ? 'index.php?page=subjects/update' : 'index.php?page=subjects/store';
$id = $edit ? intval($subject['id']) : 0;
$name = $edit ? $subject['name'] : '';
$desc = $edit ? $subject['description'] : '';
$teachers = $teachers ?? [];
?>

<div class="max-w-4xl mx-auto p-6">
  
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white"><?= $edit ? 'Edit Subject' : 'Tambah Subject' ?></h1>
          <p class="text-gray-400 text-sm mt-1">Kelola data mata pelajaran</p>
        </div>
      </div>
      
      <a href="index.php?page=subjects" 
         class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 border border-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
      </a>
    </div>
  </div>

  
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
    <form method="post" action="<?= $action ?>" class="space-y-6">
      <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?= $id ?>">
      <?php endif; ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="md:col-span-2">
          <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
            Nama Mata Pelajaran <span class="text-red-500">*</span>
          </label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
              </svg>
            </div>
            <input 
              type="text" 
              id="name"
              name="name" 
              value="<?= htmlspecialchars($name) ?>" 
              required 
              placeholder="Contoh: Matematika, Bahasa Indonesia"
              class="w-full pl-10 pr-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all placeholder:text-gray-500" 
            />
          </div>
        </div>

        
        <div>
          <label for="teacher_id" class="block text-sm font-medium text-gray-300 mb-2">
            Guru Pengajar <span class="text-red-500">*</span>
          </label>
          <select 
            name="teacher_id" 
            id="teacher_id"
            required 
            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Pilih Guru</option>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= $teacher['id'] ?>" <?= isset($subject) && $subject['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($teacher['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        
        <div>
          <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
            Status
          </label>
          <select 
            name="status" 
            id="status"
            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="active" <?= (isset($subject) && ($subject['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Aktif</option>
            <option value="inactive" <?= (isset($subject) && ($subject['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Tidak Aktif</option>
          </select>
        </div>

        
        <div class="md:col-span-2">
          <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
            Deskripsi <span class="text-red-500">*</span>
          </label>
          <textarea 
            name="description" 
            id="description"
            rows="4"
            required
            placeholder="Deskripsikan mata pelajaran ini..."
            class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all resize-none placeholder:text-gray-500"><?= htmlspecialchars($desc) ?></textarea>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex items-center gap-3 pt-6 border-t border-gray-700">
        <button 
          type="submit" 
          class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          <?= $edit ? 'Update Subject' : 'Simpan Subject' ?>
        </button>
        
        <a 
          href="index.php?page=subjects" 
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
        <h3 class="text-white font-semibold text-lg mb-3">Tips Mengelola Subject</h3>
        <ul class="space-y-2">
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Pastikan nama mata pelajaran jelas dan mudah dipahami</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Assign guru yang sesuai dengan keahliannya</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Deskripsi yang baik membantu siswa memahami mata pelajaran</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>