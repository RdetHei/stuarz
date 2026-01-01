<?php
$task = $task ?? [];
$taskId = intval($_GET['task_id'] ?? $task['id'] ?? 0);
?>
<div class="max-w-2xl mx-auto p-6">
  <div class="mb-6">
    <h2 class="text-2xl font-semibold text-white">Submit Tugas</h2>
    <div class="text-sm text-gray-400"><?= htmlspecialchars($task['title'] ?? 'Tugas') ?> — Deadline: <span class="text-white"><?= htmlspecialchars($task['deadline'] ?? '-') ?></span></div>
  </div>

  <form id="submitForm" action="index.php?page=student/submit_action" method="post" enctype="multipart/form-data" class="bg-gray-800 border border-gray-700 rounded-lg p-6">
    <input type="hidden" name="task_id" value="<?= $taskId ?>">

    <div class="mt-4">
      <label for="submission_file" class="block text-sm font-medium text-gray-300">Unggah File (Opsional)</label>
      <input type="file" 
             name="submission_file" 
             id="submission_file" 
             class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
      <p class="text-xs text-gray-500 mt-1">Tipe file yang diizinkan: PDF, DOCX, PNG, JPG. Maks: 5MB.</p>
    </div>

    <div class="mt-6 flex items-center gap-3">
      <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Submit</button>
      <a href="index.php?page=student/task_detail&id=<?= $taskId ?>" class="text-gray-300">Kembali</a>
    </div>
  </form>
</div>

<script src="js/tasks.js"></script>