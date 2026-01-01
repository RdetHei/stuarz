<?php


$user = $user ?? ($_SESSION['user'] ?? []);
?>
<div class="max-w-3xl mx-auto p-6">
  <div class="bg-gray-800 border border-gray-700 rounded-lg p-6 flex gap-6 items-center">
    <div class="w-24 h-24 rounded-full bg-gray-700 overflow-hidden flex items-center justify-center">
      <?php if (!empty($user['avatar'])): ?>
        <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="w-full h-full object-cover">
      <?php else: ?>
        <span class="text-gray-400">IMG</span>
      <?php endif; ?>
    </div>
    <div class="flex-1">
      <div class="text-lg font-semibold text-white"><?= htmlspecialchars($user['name'] ?? '-') ?></div>
      <div class="text-sm text-gray-400">Kelas: <?= htmlspecialchars($user['class_name'] ?? '-') ?></div>
      <div class="text-sm text-gray-400 mt-2">Telepon: <?= htmlspecialchars($user['phone'] ?? '-') ?></div>
      <div class="mt-3">
        <a href="index.php?page=student/edit_profile" class="px-3 py-2 bg-indigo-600 text-white rounded">Edit Profil</a>
      </div>
    </div>
  </div>

  <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-6">
    <h3 class="text-white font-semibold mb-2">Bio</h3>
    <div class="text-gray-300"><?= nl2br(htmlspecialchars($user['bio'] ?? 'Belum ada bio.')) ?></div>
  </div>
</div>
