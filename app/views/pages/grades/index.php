<div class="max-w-7xl mx-auto p-6">
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
          </div>
          <div>
            <h1 class="text-3xl font-bold text-white">Grades</h1>
            <p class="text-gray-400 text-sm mt-1">Kelola nilai dan penilaian siswa</p>
          </div>
        </div>
        <?php 
        $userLevel = $_SESSION['level'] ?? 'user';
        if ($userLevel === 'admin' || $userLevel === 'guru'): 
        ?>
        <a href="index.php?page=grades/grading" 
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
          Grading Tugas
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="get" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="grades">
        
        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Mata Pelajaran</label>
          <select name="subject_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Mata Pelajaran</option>
            <?php foreach (($subjects ?? []) as $s): ?>
              <option value="<?= $s['id'] ?>" <?= (($_GET['subject_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Kelas</label>
          <select name="class_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Kelas</option>
            <?php foreach (($classes ?? []) as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (($_GET['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
          </button>

          <?php if (!empty($_GET['subject_id']) || !empty($_GET['class_id'])): ?>
          <a href="index.php?page=grades" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

  <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-white font-semibold text-lg mb-3">Sistem Penilaian</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold flex-shrink-0 mt-0.5">A</span>
            <div>
              <span class="text-white font-medium">Grade A:</span>
              <span class="text-gray-400"> Nilai 90-100 (Sangat Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold flex-shrink-0 mt-0.5">B</span>
            <div>
              <span class="text-white font-medium">Grade B:</span>
              <span class="text-gray-400"> Nilai 75-89 (Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-bold flex-shrink-0 mt-0.5">C</span>
            <div>
              <span class="text-white font-medium">Grade C:</span>
              <span class="text-gray-400"> Nilai 60-74 (Cukup)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-500/20 text-red-400 text-xs font-bold flex-shrink-0 mt-0.5">D</span>
            <div>
              <span class="text-white font-medium">Grade D:</span>
              <span class="text-gray-400"> Nilai <60 (Kurang)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>