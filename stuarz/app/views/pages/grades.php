<?php
// Variables expected: $totalGrades, $avgGrade, $highGrade, $thisWeek, $subjects, $recent
// Defensive defaults
$totalGrades = $totalGrades ?? 0;
$avgGrade = $avgGrade ?? 0;
$highGrade = $highGrade ?? 0;
$thisWeek = $thisWeek ?? 0;
$subjects = $subjects ?? [];
$recent = $recent ?? [];

function gradeColor($score) {
    if ($score >= 90) return 'bg-green-500';
    if ($score >= 75) return 'bg-yellow-500';
    return 'bg-red-500';
}
?>

<div class="flex gap-6">
  <!-- Sidebar subjects -->
  <aside class="w-64 bg-[#0f172a] text-gray-200 rounded-xl border border-gray-800 h-max">
    <div class="px-4 py-3 border-b border-gray-800">
      <h2 class="text-sm font-semibold tracking-wide">Subjects</h2>
    </div>
    <nav class="p-2 space-y-1">
      <?php foreach ($subjects as $s): ?>
        <a href="#" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-800 transition-colors">
          <span class="text-sm"><?= htmlspecialchars($s['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
          <span class="text-xs text-gray-400"><?= isset($s['avg_score']) && $s['avg_score'] !== null ? htmlspecialchars($s['avg_score']) . '%' : '-' ?></span>
        </a>
      <?php endforeach; ?>
      <?php if (empty($subjects)): ?>
        <div class="px-3 py-2 text-xs text-gray-500">Belum ada mata pelajaran.</div>
      <?php endif; ?>
    </nav>
  </aside>

  <!-- Main content -->
  <section class="flex-1">
    <header class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
      <div class="bg-[#0f172a] border border-gray-800 rounded-xl p-4">
        <div class="text-sm text-gray-400">Total Grades</div>
        <div class="mt-2 text-3xl font-bold text-white"><?= (int)$totalGrades ?></div>
      </div>
      <div class="bg-[#0f172a] border border-gray-800 rounded-xl p-4">
        <div class="text-sm text-gray-400">Average Grade</div>
        <div class="mt-2 text-3xl font-bold text-white"><?= number_format((float)$avgGrade, 1) ?>%</div>
      </div>
      <div class="bg-[#0f172a] border border-gray-800 rounded-xl p-4">
        <div class="text-sm text-gray-400">Highest Grade</div>
        <div class="mt-2 text-3xl font-bold text-white"><?= number_format((float)$highGrade, 0) ?>%</div>
      </div>
      <div class="bg-[#0f172a] border border-gray-800 rounded-xl p-4">
        <div class="text-sm text-gray-400">This Week</div>
        <div class="mt-2 text-3xl font-bold text-white"><?= (int)$thisWeek ?></div>
      </div>
    </header>

    <div class="mb-3 text-white text-lg font-semibold">Recent Grades</div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <?php foreach ($recent as $g): ?>
        <?php
          $score = (float)($g['score'] ?? 0);
          $bar = max(0, min(100, $score));
          $date = htmlspecialchars(date('Y-m-d', strtotime($g['created_at'] ?? 'now')));
        ?>
        <div class="bg-[#0f172a] border border-gray-800 rounded-xl p-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="text-indigo-300 text-sm font-medium"><?= htmlspecialchars($g['subject'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
              <div class="text-xs text-gray-500"><?= htmlspecialchars($g['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="text-xs bg-gray-800 text-gray-200 px-2 py-1 rounded-md"><?= number_format($score, 0) ?>/100</div>
          </div>
          <div class="mt-3 text-xs text-gray-400"><?= $date ?></div>
          <div class="mt-2 h-2 w-full bg-gray-800 rounded-full overflow-hidden">
            <div class="h-full <?= gradeColor($bar) ?>" style="width: <?= (int)$bar ?>%"></div>
          </div>
        </div>
      <?php endforeach; ?>

      <?php if (empty($recent)): ?>
        <div class="text-sm text-gray-400">Belum ada nilai.</div>
      <?php endif; ?>
    </div>
  </section>
</div>


