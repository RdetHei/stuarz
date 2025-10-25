<!-- Subjects List UI - Discord/GitHub dark style -->
<div class="max-w-7xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-white">Subjects</h1>
    <a href="index.php?page=subjects/create" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg px-4 py-2 font-medium transition">Add Subject</a>
  </div>
  <div class="rounded-2xl shadow-md bg-gray-800 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-700">
      <thead class="bg-gray-900">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</th>
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru Pengajar</th>
          <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-700">
        <?php foreach (($subjects ?? []) as $s): ?>
        <tr class="hover:bg-gray-800 group transition">
          <td class="px-6 py-4 whitespace-nowrap text-gray-300"> <?= htmlspecialchars($s['name'] ?? '') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-400"> <?= htmlspecialchars($s['description'] ?? '') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-400"> <?= htmlspecialchars($s['teacher_name'] ?? 'Belum ditentukan') ?> </td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
            <a href="index.php?page=subjects/edit&id=<?= $s['id'] ?>" class="inline-flex items-center text-indigo-400 hover:text-indigo-200 mr-2" title="Edit"><svg xmlns='http://www.w3.org/2000/svg' class='lucide lucide-pencil' width='18' height='18' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M15.232 5.232l-1.464-1.464a2 2 0 0 0-2.828 0l-6.536 6.536a2 2 0 0 0-.586 1.414V15h3.182a2 2 0 0 0 1.414-.586l6.536-6.536a2 2 0 0 0 0-2.828z'/><path d='M13.5 6.5l-7 7'/></svg></a>
            <form method="post" action="index.php?page=subjects/delete" class="inline" onsubmit="return confirm('Delete this subject?')">
              <input type="hidden" name="id" value="<?= $s['id'] ?>">
              <button type="submit" class="inline-flex items-center text-red-400 hover:text-red-200" title="Delete"><svg xmlns='http://www.w3.org/2000/svg' class='lucide lucide-trash' width='18' height='18' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='3 6 5 6 21 6'/><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2'/></svg></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
