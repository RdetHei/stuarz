<?php


$task = $task ?? null;
$submission = $submission ?? null;
?>
<div class="max-w-3xl mx-auto p-6">
  <div class="mb-4 flex items-center justify-between">
    <h2 class="text-2xl font-semibold text-white"><?= htmlspecialchars($task['title'] ?? 'Detail Tugas') ?></h2>
    <div class="text-sm text-gray-400">Deadline: <?= htmlspecialchars($task['deadline'] ?? '-') ?></div>
  </div>

  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <div class="mb-4 text-gray-300"><?= nl2br(htmlspecialchars($task['description'] ?? '-')) ?></div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
      <div class="text-sm text-gray-400">
        <div><strong>Kelas:</strong> <?= htmlspecialchars($task['class_name'] ?? '-') ?></div>
        <div class="mt-1"><strong>Mata Pelajaran:</strong> <?= htmlspecialchars($task['subject_name'] ?? '-') ?></div>
      </div>
      <div class="text-sm text-gray-400">
        <div><strong>Status tugas:</strong> <span class="text-white"><?= htmlspecialchars($task['status'] ?? '-') ?></span></div>
        <div class="mt-1"><strong>Pengumpulan terlambat:</strong> <?= empty($task['allow_late']) ? 'Tidak' : 'Iya' ?></div>
      </div>
    </div>

    <div class="mb-4">
      <h3 class="text-white font-semibold mb-2">Pengumpulan Saya</h3>
      <?php if ($submission): ?>
        <div class="text-sm text-gray-300">Status: <span class="font-medium text-white"><?= htmlspecialchars($submission['status']) ?></span></div>
        <div class="text-sm text-gray-400">Tanggal submit: <?= htmlspecialchars($submission['submitted_at']) ?></div>
        <?php if (!empty($submission['file_path'])): ?>
          <div class="mt-2"><a href="<?= htmlspecialchars($submission['file_path']) ?>" target="_blank" class="text-indigo-400">Lihat file yang dikumpulkan</a></div>
        <?php endif; ?>
        <?php if (isset($submission['grade'])): ?>
          <div class="mt-3 p-3 bg-gray-900 border border-gray-700 rounded">Nilai: <span class="text-indigo-300 font-semibold"><?= htmlspecialchars($submission['grade']) ?></span></div>
        <?php endif; ?>
      <?php else: ?>
        <div class="text-sm text-gray-400">Anda belum mengumpulkan tugas ini.</div>
        <div class="mt-3">
          <a href="index.php?page=student/submit&task_id=<?= intval($task['id'] ?? $_GET['id'] ?? 0) ?>" class="px-4 py-2 bg-indigo-600 text-white rounded">Submit Tugas</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
