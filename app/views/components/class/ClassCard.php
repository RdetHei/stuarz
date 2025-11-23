<?php
// Props expected: $name, $code, $members_count, $role, $link
$name = $name ?? 'Nama Kelas';
$code = $code ?? '';
$members_count = isset($members_count) ? intval($members_count) : 0;
$role = $role ?? 'student';
$link = $link ?? '#';
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 flex flex-col justify-between">
  <div>
    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 truncate"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h3>
    <?php if ($code !== ''): ?>
      <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kode: <span class="font-mono text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?></span></div>
    <?php endif; ?>
    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">Anggota: <strong><?= $members_count ?></strong></div>
  </div>

  <div class="mt-4 flex items-center justify-between">
    <a href="<?= $link ?>" class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 text-sm">Masuk</a>
    <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
      <a href="<?= $link ?>?manage=1" class="px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm text-gray-800 dark:text-gray-200">Kelola</a>
    <?php endif; ?>
  </div>
</div>
