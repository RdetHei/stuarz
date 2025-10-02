<?php
$edit = isset($subject);
$action = $edit ? 'index.php?page=subjects/update' : 'index.php?page=subjects/store';
$id = $edit ? intval($subject['id']) : 0;
$name = $edit ? $subject['name'] : '';
$desc = $edit ? $subject['description'] : '';
?>
<div class="max-w-lg mx-auto p-6">
  <div class="rounded-2xl shadow-md bg-gray-800 p-8">
    <h2 class="text-xl font-bold text-white mb-6"><?= $edit ? 'Edit Subject' : 'Tambah Subject' ?></h2>
    <form method="post" action="<?= $action ?>" class="space-y-5">
      <?php if ($edit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
      <div>
        <label class="block text-gray-300 mb-1">Nama</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Deskripsi</label>
        <input type="text" name="description" value="<?= htmlspecialchars($desc) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div class="flex gap-2 mt-6">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium transition">Simpan</button>
        <a href="index.php?page=subjects" class="bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg px-4 py-2 font-medium transition">Batal</a>
      </div>
    </form>
  </div>
</div>
