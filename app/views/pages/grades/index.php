<div class="max-w-7xl mx-auto p-6">
  <!-- Header Section -->
  <div class="mb-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-indigo-500 flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-semibold text-gray-100">Grades</h1>
          <p class="text-gray-400 text-sm mt-0.5">Kelola nilai dan penilaian siswa</p>
        </div>
      </div>
      
      <?php 
      $userLevel = $_SESSION['level'] ?? 'user';
      if ($userLevel === 'admin' || $userLevel === 'guru'): 
      ?>
      <a href="index.php?page=grades/grading" 
         class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
        Grading Tugas
      </a>
      <?php endif; ?>
    </div>

    <!-- Filter Section -->
    <div class="mt-4 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="get" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="grades">
        
        <div class="flex-1 min-w-[200px]">
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Mata Pelajaran</label>
          <select name="subject_id" class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors">
            <option value="">Semua Mata Pelajaran</option>
            <?php foreach (($subjects ?? []) as $s): ?>
              <option value="<?= $s['id'] ?>" <?= (($_GET['subject_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-xs font-medium text-gray-400 mb-1.5">Kelas</label>
          <select name="class_id" class="w-full px-3 py-2 bg-gray-900 border border-gray-700 rounded-md text-sm text-gray-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none transition-colors">
            <option value="">Semua Kelas</option>
            <?php foreach (($classes ?? []) as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (($_GET['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium rounded-md transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
          </button>

          <?php if (!empty($_GET['subject_id']) || !empty($_GET['class_id'])): ?>
          <a href="index.php?page=grades" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-gray-200 text-sm font-medium rounded-md transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Reset
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <?php
    // Include the graded view which handles fetching and rendering the graded submissions table
    require_once __DIR__ . '/graded.php';
  ?>

  <!-- Grading System Info -->
  <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-5">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-gray-100 font-semibold text-base mb-3">Sistem Penilaian</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
          <div class="flex items-start gap-2.5 text-sm bg-gray-900 border border-gray-700 rounded-md p-3">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-emerald-500/20 text-emerald-400 text-xs font-bold flex-shrink-0">A</span>
            <div>
              <span class="text-gray-200 font-medium">Grade A</span>
              <span class="text-gray-400"> • 90-100 (Sangat Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2.5 text-sm bg-gray-900 border border-gray-700 rounded-md p-3">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-blue-500/20 text-blue-400 text-xs font-bold flex-shrink-0">B</span>
            <div>
              <span class="text-gray-200 font-medium">Grade B</span>
              <span class="text-gray-400"> • 75-89 (Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2.5 text-sm bg-gray-900 border border-gray-700 rounded-md p-3">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-yellow-500/20 text-yellow-400 text-xs font-bold flex-shrink-0">C</span>
            <div>
              <span class="text-gray-200 font-medium">Grade C</span>
              <span class="text-gray-400"> • 60-74 (Cukup)</span>
            </div>
          </div>
          <div class="flex items-start gap-2.5 text-sm bg-gray-900 border border-gray-700 rounded-md p-3">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded bg-red-500/20 text-red-400 text-xs font-bold flex-shrink-0">D</span>
            <div>
              <span class="text-gray-200 font-medium">Grade D</span>
              <span class="text-gray-400"> • <60 (Kurang)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>