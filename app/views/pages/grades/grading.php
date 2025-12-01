<?php
$submissions = $submissions ?? [];
$tasks = $tasks ?? [];
$classes = $classes ?? [];
$subjects = $subjects ?? [];
$filterTask = $_GET['task_id'] ?? null;
$filterStatus = $_GET['status'] ?? 'pending';
$filterClass = $_GET['class_id'] ?? null;
?>

<div class="max-w-7xl mx-auto p-6">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Grading Tugas</h1>
          <p class="text-gray-400 text-sm mt-1">Berikan nilai pada tugas yang sudah dikumpulkan siswa</p>
        </div>
      </div>
        <script src="public/js/grades.js"></script>
    </div>

    <!-- Filters -->
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="get" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="grades/grading">
        
        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Tugas</label>
          <select name="task_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Tugas</option>
            <?php foreach ($tasks as $t): ?>
              <option value="<?= $t['id'] ?>" <?= ($filterTask == $t['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($t['title'] ?? '') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
          <select name="status" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="in_review" <?= $filterStatus === 'in_review' ? 'selected' : '' ?>>In Review</option>
            <option value="needs_revision" <?= $filterStatus === 'needs_revision' ? 'selected' : '' ?>>Needs Revision</option>
            <option value="graded" <?= $filterStatus === 'graded' ? 'selected' : '' ?>>Graded</option>
            <option value="all" <?= $filterStatus === 'all' ? 'selected' : '' ?>>Semua</option>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Kelas</label>
          <select name="class_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Kelas</option>
            <?php foreach ($classes as $c): ?>
              <option value="<?= $c['id'] ?>" <?= ($filterClass == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name'] ?? '') ?>
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
          <a href="index.php?page=grades/grading" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  <?php if (!empty($submissions)): ?>
  <div class="space-y-4">
    <?php foreach ($submissions as $sub): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-6">
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-semibold">
              <?= strtoupper(substr($sub['student_name'] ?? $sub['username'] ?? 'S', 0, 1)) ?>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white"><?= htmlspecialchars($sub['student_name'] ?? $sub['username'] ?? '') ?></h3>
              <p class="text-sm text-gray-400"><?= htmlspecialchars($sub['username'] ?? '') ?></p>
            </div>
          </div>
          <div class="ml-13 space-y-1">
            <p class="text-white"><span class="text-gray-400">Tugas:</span> <?= htmlspecialchars($sub['task_title'] ?? '') ?></p>
            <p class="text-gray-400 text-sm"><span class="text-gray-500">Kelas:</span> <?= htmlspecialchars($sub['class_name'] ?? '') ?> • <span class="text-gray-500">Mata Pelajaran:</span> <?= htmlspecialchars($sub['subject_name'] ?? '') ?></p>
            <p class="text-gray-400 text-sm"><span class="text-gray-500">Dikumpulkan:</span> <?= date('d M Y, H:i', strtotime($sub['submitted_at'] ?? '')) ?></p>
            <?php if (!empty($sub['attempt_no'])): ?>
            <p class="text-gray-400 text-sm">Percobaan #<?= intval($sub['attempt_no']) ?><?= !empty($sub['is_final']) ? ' • Final' : '' ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div>
          <?php
          $statusColors = [
            'pending' => 'bg-yellow-500/20 text-yellow-200 border-yellow-500/30',
            'in_review' => 'bg-blue-500/20 text-blue-200 border-blue-500/30',
            'needs_revision' => 'bg-red-500/20 text-red-200 border-red-500/30',
            'approved' => 'bg-emerald-500/20 text-emerald-200 border-emerald-500/30',
            'graded' => 'bg-indigo-500/20 text-indigo-200 border-indigo-500/30'
          ];
          $status = $sub['review_status'] ?? 'pending';
          $color = $statusColors[$status] ?? $statusColors['pending'];
          ?>
          <span class="px-3 py-1 rounded-full text-xs font-semibold border <?= $color ?>">
            <?= ucfirst(str_replace('_', ' ', $status)) ?>
          </span>
        </div>
      </div>

      <?php if (!empty($sub['feedback'])): ?>
      <div class="mb-4 p-3 bg-gray-900/50 rounded-lg border border-gray-700">
        <p class="text-sm text-gray-300"><?= nl2br(htmlspecialchars($sub['feedback'])) ?></p>
      </div>
      <?php endif; ?>

      <?php if (!empty($sub['file_path']) && strpos($sub['file_path'], 'manual_completion_') !== 0): ?>
      <div class="mb-4">
        <a href="<?= htmlspecialchars($sub['file_path']) ?>" target="_blank" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600/20 hover:bg-blue-600/30 border border-blue-600/30 text-blue-400 rounded-lg transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          Download File
        </a>
      </div>
      <?php elseif (str_starts_with($sub['file_path'] ?? '', 'manual_completion_')): ?>
      <div class="mb-4">
        <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-600/20 border border-green-600/30 text-green-400 rounded-lg">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          Tugas Selesai (Manual)
        </span>
      </div>
      <?php endif; ?>

      <!-- Grading Form -->
      <form method="post" action="index.php?page=grades/grade-submission" class="mt-4 pt-4 border-t border-gray-700 grade-form" data-submission-id="<?= intval($sub['id']) ?>">
        <input type="hidden" name="submission_id" value="<?= intval($sub['id']) ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status Review</label>
            <select name="review_status" class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600">
              <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="in_review" <?= $status === 'in_review' ? 'selected' : '' ?>>In Review</option>
              <option value="needs_revision" <?= $status === 'needs_revision' ? 'selected' : '' ?>>Needs Revision</option>
              <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
              <option value="graded" <?= $status === 'graded' ? 'selected' : '' ?>>Graded</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Nilai (0-100)</label>
            <input type="number" name="score" min="0" max="100" step="0.01" 
                   value="<?= $sub['grade'] !== null ? htmlspecialchars($sub['grade']) : '' ?>" 
                   class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600">
          </div>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-300 mb-2">Feedback</label>
          <textarea name="feedback" rows="3" 
                    class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 resize-none"
                    placeholder="Berikan feedback untuk siswa..."><?= htmlspecialchars($sub['feedback'] ?? '') ?></textarea>
        </div>

        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2">
            <button type="button" data-score="100" class="quick-grade px-3 py-1 bg-emerald-600 text-white rounded-md text-sm">100</button>
            <button type="button" data-score="90" class="quick-grade px-3 py-1 bg-blue-600 text-white rounded-md text-sm">90</button>
            <button type="button" data-score="75" class="quick-grade px-3 py-1 bg-amber-600 text-white rounded-md text-sm">75</button>
            <button type="button" data-score="50" class="quick-grade px-3 py-1 bg-red-600 text-white rounded-md text-sm">50</button>
          </div>
          <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Nilai
          </button>
          <?php if ($sub['grade'] !== null): ?>
          <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-lg text-sm font-semibold">
            Nilai: <?= number_format(floatval($sub['grade']), 2) ?>
          </span>
          <?php endif; ?>
        </div>
      </form>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
    <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-gray-700/50 flex items-center justify-center">
      <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
    </div>
    <h3 class="text-xl font-bold text-white mb-2">Tidak Ada Submission</h3>
    <p class="text-gray-400">Belum ada tugas yang dikumpulkan untuk dinilai.</p>
  </div>
  <?php endif; ?>
</div>

