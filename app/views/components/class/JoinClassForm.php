<?php
// JoinClassForm component
// Expects: $action (default index.php?page=join_class), optional $error
$action = $action ?? 'index.php?page=join_class';
$error = $error ?? null;
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4">
  <form id="joinClassForm" method="POST" action="<?= $action ?>" novalidate>
    <?php if (function_exists('csrf_field')) csrf_field(); ?>
    <?php if ($error): ?>
      <div class="mb-3 text-sm text-red-500"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <label class="block text-sm text-gray-600 dark:text-gray-300 mb-2">Kode Kelas</label>
    <div class="flex gap-2">
      <input name="class_code" id="joinCode" type="text" placeholder="Masukkan Kode Kelas" required class="flex-1 px-3 py-2 rounded bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm text-gray-300" />
      <button id="joinSubmit" type="submit" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Gabung Kelas</button>
    </div>
    <p id="joinError" class="mt-2 text-sm text-red-500 hidden"></p>
  </form>
</div>
