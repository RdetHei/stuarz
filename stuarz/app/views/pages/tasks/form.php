<?php
$edit = isset($task);
$action = $edit ? 'index.php?page=tasks/update' : 'index.php?page=tasks/store';
$id = $edit ? intval($task['id']) : 0;
$title = $edit ? $task['title'] : '';
$desc = $edit ? $task['description'] : '';
$status = $edit ? $task['status'] : '';
$deadline = $edit ? $task['deadline'] : '';
$class_id = $edit ? $task['class_id'] : '';
$subject_id = $edit ? $task['subject_id'] : '';
?>
<div class="max-w-lg mx-auto p-6">
  <div class="rounded-2xl shadow-md bg-gray-800 p-8">
    <h2 class="text-xl font-bold text-white mb-6"><?= $edit ? 'Edit Tugas' : 'Tambah Tugas' ?></h2>
    <form method="post" action="<?= $action ?>" class="space-y-5">
      <?php if ($edit): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>
      <div>
        <label class="block text-gray-300 mb-1">Judul</label>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Deskripsi</label>
        <input type="text" name="description" value="<?= htmlspecialchars($desc) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Status</label>
        <input type="text" name="status" value="<?= htmlspecialchars($status) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Deadline</label>
        <input type="date" name="deadline" value="<?= htmlspecialchars($deadline) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Kelas (class_id)</label>
        <input type="number" name="class_id" value="<?= htmlspecialchars($class_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div>
        <label class="block text-gray-300 mb-1">Subject (subject_id)</label>
        <input type="number" name="subject_id" value="<?= htmlspecialchars($subject_id) ?>" required class="w-full bg-gray-700 text-white border border-gray-600 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>
      <div class="flex gap-2 mt-6">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium transition">Simpan</button>
        <a href="index.php?page=tasks" class="bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-lg px-4 py-2 font-medium transition">Batal</a>
      </div>
    </form>
  </div>
</div>
