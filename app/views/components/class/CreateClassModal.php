<?php
// CreateClassModal
// Expects: $action (post endpoint), $generated_code (optional)
$action = $action ?? 'index.php?page=class_store';
$generated_code = $generated_code ?? '';
?>
<div id="createClassModal" class="hidden fixed inset-0 z-70 flex items-center justify-center bg-black/50 px-4 py-6">
  <div class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-lg shadow-xl border border-gray-200 dark:border-gray-700">
    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Buat Kelas Baru</h3>
      <button id="closeCreateClass" class="text-gray-500 hover:text-gray-900">&times;</button>
    </div>
    <div class="p-4">
      <form id="createClassForm" method="POST" action="<?= $action ?>">
        <div class="mb-3">
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Nama Kelas</label>
          <input name="name" type="text" required class="w-full px-3 py-2 rounded border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm" />
        </div>
        <div class="mb-3">
          <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Kode Kelas</label>
          <input name="code" type="text" readonly value="<?= htmlspecialchars($generated_code, ENT_QUOTES, 'UTF-8') ?>" class="w-full px-3 py-2 rounded border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm" />
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" id="cancelCreate" class="px-3 py-2 rounded bg-gray-100 hover:bg-gray-200">Batal</button>
          <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
