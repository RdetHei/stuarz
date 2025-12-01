<?php
// JoinClassForm component
// Expects: $action (default index.php?page=join_class), optional $error
$action = $action ?? 'index.php?page=join_class';
$error = $error ?? null;
?>
<div class="bg-gray-900 border border-gray-800 rounded-xl shadow-sm p-4">
  <form id="joinClassForm" method="POST" action="<?= $action ?>" novalidate>
    <?php if ($error): ?>
      <div class="mb-3 text-sm text-red-500"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <label class="block text-sm text-gray-200 mb-2">Kode Kelas</label>
    <div class="flex gap-2">
      <input name="class_code" id="joinCode" type="text" placeholder="Masukkan Kode Kelas" required class="flex-1 px-3 py-2 rounded-md bg-gray-800 border border-gray-700 text-sm text-gray-200" />
      <button id="joinSubmit" type="submit" class="px-3 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-white font-medium">Gabung Kelas</button>
    </div>
    <p id="joinError" class="mt-2 text-sm text-red-500 hidden"></p>
  </form>
</div>
