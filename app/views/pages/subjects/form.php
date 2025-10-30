<?php
$edit = isset($subject);
$action = $edit ? 'index.php?page=subjects/update' : 'index.php?page=subjects/store';
$id = $edit ? intval($subject['id']) : 0;
$name = $edit ? $subject['name'] : '';
$desc = $edit ? $subject['description'] : '';
?>
<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-2xl px-6 py-12 lg:px-8">
    <div class="bg-gray-800/60 border border-gray-700 rounded-2xl p-8 ring-1 ring-white/5">
      <h2 class="text-3xl font-bold text-white mb-8"><?= $edit ? 'Edit Subject' : 'Tambah Subject' ?></h2>
      <form method="post" action="<?= $action ?>" class="space-y-6">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
        <div>
          <label class="block text-gray-300 mb-2">Nama</label>
          <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full bg-gray-800/70 text-white border border-gray-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
      </div>
      <div>
        <label class="block text-gray-300 mb-2">Deskripsi</label>
        <input type="text" name="description" value="<?= htmlspecialchars($desc) ?>" required class="w-full bg-gray-800/70 text-white border border-gray-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
      </div>
      <div>
        <label class="block text-gray-300 mb-2">Guru Pengajar</label>
        <select name="teacher_id" required class="w-full bg-gray-800/70 text-white border border-gray-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
          <option value="">Pilih Guru</option>
          <?php foreach ($teachers as $teacher): ?>
            <option value="<?= $teacher['id'] ?>" <?= isset($subject) && $subject['teacher_id'] == $teacher['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($teacher['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="flex gap-3 mt-8">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl px-4 py-2 font-semibold transition">Simpan</button>
        <a href="index.php?page=subjects" class="bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl px-4 py-2 font-semibold transition border border-gray-700">Batal</a>
      </div>
    </form>
  </div>
</div>
