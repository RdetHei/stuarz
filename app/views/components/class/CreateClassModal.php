<?php

$formAction = 'index.php?page=class_store';
if (isset($action) && !empty($action) && strpos($action, 'class_store') !== false) {
    
    $formAction = $action;
}
$generated_code = $generated_code ?? '';
?>
<div id="createClassModal" class="hidden fixed inset-0 z-70 flex items-center justify-center bg-black/50 px-4 py-6">
  <div class="bg-gray-800 rounded-lg w-full max-w-lg shadow-xl border border-gray-700">
    <div class="px-4 py-3 border-b border-gray-700 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-white">Buat Kelas Baru</h3>
      <button id="closeCreateClass" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
    </div>
    <div class="p-4">
      <form id="createClassForm" method="POST" action="<?= $formAction ?>">
        <div class="mb-3">
          <label class="block text-sm text-gray-300 mb-1">Nama Kelas</label>
          <input name="name" type="text" required class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-700 text-white placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none" />
        </div>
        <div class="mb-3">
          <label class="block text-sm text-gray-300 mb-1">Kode Kelas</label>
          <input name="code" type="text" readonly value="<?= htmlspecialchars($generated_code, ENT_QUOTES, 'UTF-8') ?>" class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-700 text-white text-sm" />
        </div>
        <div class="mb-3">
          <label class="block text-sm text-gray-300 mb-1">Deskripsi (Opsional)</label>
          <textarea name="description" rows="3" class="w-full px-3 py-2 rounded border border-gray-600 bg-gray-700 text-white placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none resize-none" placeholder="Deskripsi singkat tentang kelas ini..."></textarea>
        </div>
        <div class="flex justify-end gap-2">
          <button type="button" id="cancelCreate" class="px-3 py-2 rounded bg-gray-700 hover:bg-gray-600 text-white">Batal</button>
          <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Create</button>
        </div>
      </form>
    </div>
  </div>
</div>
