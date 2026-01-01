<?php


$filter = $_GET['filter'] ?? 'all';
?>
<div class="max-w-4xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-semibold text-white">Tugas Saya</h2>
    <div class="flex items-center gap-2">
      <label class="text-sm text-gray-300">Filter:</label>
      <select id="taskFilter" class="bg-gray-900 border border-gray-700 text-white px-3 py-2 rounded">
        <option value="all" <?= $filter==='all' ? 'selected' : '' ?>>Semua</option>
        <option value="pending" <?= $filter==='pending' ? 'selected' : '' ?>>Pending</option>
        <option value="completed" <?= $filter==='completed' ? 'selected' : '' ?>>Selesai</option>
      </select>
    </div>
  </div>

  <div class="space-y-4">
    <?php if (empty($tasks)): ?>
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-gray-400">Belum ada tugas.</div>
    <?php else: ?>
      <?php foreach ($tasks as $t): 

        if ($filter==='pending' && strtolower($t['status'])==='completed') continue;
        if ($filter==='completed' && strtolower($t['status'])!=='completed') continue;
      ?>
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 flex items-start justify-between gap-4">
          <div class="flex-1">
            <div class="flex items-center justify-between gap-4">
              <h3 class="text-white font-semibold text-lg"><?= htmlspecialchars($t['title']) ?></h3>
              <div class="text-sm text-gray-400">Deadline: <?= htmlspecialchars($t['deadline'] ?? '-') ?></div>
            </div>
            <p class="text-gray-300 text-sm mt-2"><?= htmlspecialchars(substr($t['description'] ?? '',0,140)) ?><?= strlen($t['description'] ?? '')>140 ? '...' : '' ?></p>
            <div class="mt-3 flex items-center gap-3 text-sm">
              <span class="px-2 py-1 rounded text-xs <?= strtolower($t['status'])==='completed' ? 'bg-green-600 text-white' : 'bg-yellow-600 text-black' ?>"><?= htmlspecialchars($t['status']) ?></span>
              <span class="text-gray-400">Mata pelajaran: <?= htmlspecialchars($t['subject_name'] ?? '-') ?></span>
            </div>
          </div>
          <div class="flex-shrink-0 flex flex-col items-end gap-2">
            <a href="index.php?page=student/task_detail&id=<?= intval($t['task_id'] ?? $t['id']) ?>" class="px-4 py-2 bg-indigo-600 text-white rounded">Lihat Detail</a>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<script>
document.getElementById('taskFilter')?.addEventListener('change', function(){
  const v = this.value;
  const url = new URL(window.location.href);
  url.searchParams.set('filter', v);
  window.location.href = url.toString();
});
</script>
