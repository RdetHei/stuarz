<?php


$notifications = $notifications ?? [];
?>
<div class="max-w-4xl mx-auto p-6">
  <div class="mb-6 flex items-center justify-between">
    <h2 class="text-2xl font-semibold text-white">Notifikasi Saya</h2>
    <div class="text-sm text-gray-400">Terbaru</div>
  </div>

  <div class="space-y-3">
    <?php if (empty($notifications)): ?>
      <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 text-gray-400">Belum ada notifikasi.</div>
    <?php else: foreach ($notifications as $n): ?>
      <div class="bg-gray-800 border <?= empty($n['read_at']) ? 'border-indigo-600' : 'border-gray-700' ?> rounded-lg p-4 flex items-start justify-between gap-4">
        <div>
          <div class="text-sm text-gray-300 font-medium"><?= htmlspecialchars($n['title'] ?? $n['type']) ?></div>
          <div class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($n['body'] ?? '') ?></div>
        </div>
        <div class="text-right text-xs text-gray-400">
          <div><?= htmlspecialchars($n['created_at'] ?? '') ?></div>
          <?php if (empty($n['read_at'])): ?>
            <div class="mt-2"><a href="index.php?page=student/notifications_mark&id=<?= intval($n['id']) ?>" class="text-indigo-400">Tandai sudah dibaca</a></div>
          <?php else: ?>
            <div class="mt-2 text-green-400">Terbaca</div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
