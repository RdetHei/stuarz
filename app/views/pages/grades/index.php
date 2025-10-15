<!-- Grades List UI - Discord/GitHub dark style -->
<div class="max-w-7xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-white">Grades</h1>
    <form method="get" class="flex gap-2">
      <select name="subject_id" class="bg-gray-700 text-white border-gray-600 rounded-lg px-3 py-2 focus:ring-indigo-500">
        <option value="">All Subjects</option>
        <?php foreach (($subjects ?? []) as $s): ?>
          <option value="<?= $s['id'] ?>" <?= (($_GET['subject_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="class_id" class="bg-gray-700 text-white border-gray-600 rounded-lg px-3 py-2 focus:ring-indigo-500">
        <option value="">All Classes</option>
        <?php foreach (($classes ?? []) as $c): ?>
          <option value="<?= $c['id'] ?>" <?= (($_GET['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium transition">Filter</button>
    </form>
  </div>
  <div class="rounded-2xl shadow-md bg-gray-800 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-700">
      <thead class="bg-gray-900">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Student</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Subject</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Task</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Score</th>
          <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php foreach (($grades ?? []) as $g): ?>
        <tr class="hover:bg-gray-800 group transition">
          <td class="px-6 py-4 whitespace-nowrap text-gray-300"> <?= htmlspecialchars($g['username'] ?? '') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-400"> <?= htmlspecialchars($g['subject_name'] ?? '') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-400"> <?= htmlspecialchars($g['task_title'] ?? '') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap">
            <?php $score = intval($g['score'] ?? 0); ?>
            <?php if ($score >= 90): ?>
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-600 text-white">A (<?= $score ?>)</span>
            <?php elseif ($score >= 75): ?>
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-blue-600 text-white">B (<?= $score ?>)</span>
            <?php elseif ($score >= 60): ?>
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-yellow-500 text-gray-900">C (<?= $score ?>)</span>
            <?php else: ?>
              <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-red-600 text-white">D (<?= $score ?>)</span>
            <?php endif; ?>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
            <a href="index.php?page=grades/edit&id=<?= $g['id'] ?>" class="inline-flex items-center text-indigo-400 hover:text-indigo-200 mr-2" title="Edit"><svg xmlns='http://www.w3.org/2000/svg' class='lucide lucide-pencil' width='18' height='18' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M15.232 5.232l-1.464-1.464a2 2 0 0 0-2.828 0l-6.536 6.536a2 2 0 0 0-.586 1.414V15h3.182a2 2 0 0 0 1.414-.586l6.536-6.536a2 2 0 0 0 0-2.828z'/><path d='M13.5 6.5l-7 7'/></svg></a>
            <form method="post" action="index.php?page=grades/delete" class="inline" onsubmit="return confirm('Delete this grade?')">
              <input type="hidden" name="id" value="<?= $g['id'] ?>">
              <button type="submit" class="inline-flex items-center text-red-400 hover:text-red-200" title="Delete"><svg xmlns='http://www.w3.org/2000/svg' class='lucide lucide-trash' width='18' height='18' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'/><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2'/></svg></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
