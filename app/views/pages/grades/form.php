<?php
$edit = isset($grade);
$action = $edit ? 'index.php?page=grades/update' : 'index.php?page=grades/store';
$id = $edit ? intval($grade['id']) : 0;
$user_id = $edit ? $grade['user_id'] : '';
$class_id = $edit ? $grade['class_id'] : '';
$subject_id = $edit ? $grade['subject_id'] : '';
$task_id = $edit ? $grade['task_id'] : '';
$score = $edit ? $grade['score'] : '';
?>
<div class="max-w-lg mx-auto p-6">
  <div class="rounded-2xl shadow-md bg-gray-800 p-8">
    <h2 class="text-xl font-bold text-white mb-6"><?= $edit ? 'Edit Nilai' : 'Tambah Nilai' ?></h2>
    <form method="post" action="<?= $action ?>" class="space-y-5">
      <?php if ($edit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
      <div>
        <label class="block text-gray-300 mb-1">Siswa (user_id)</label>
        <input type="number" name="user_id" value="<?= htmlspecialchars($user_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Kelas (class_id)</label>
        <input type="number" name="class_id" value="<?= htmlspecialchars($class_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Subject (subject_id)</label>
        <input type="number" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Tugas (task_id)</label>
        <input type="number" name="task_id" value="<?= htmlspecialchars($task_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Skor</label>
        <input type="number" name="score" min="0" max="100" value="<?= htmlspecialchars($score) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div class="flex gap-2 mt-6">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium transition">Simpan</button>
        <a href="index.php?page=grades" class="bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg px-4 py-2 font-medium transition">Batal</a>
      </div>
    </form>
  </div>
</div>
