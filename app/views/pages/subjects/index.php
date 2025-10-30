<!-- Subjects List -->
<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold text-white">Subjects</h1>
      <a href="index.php?page=subjects/create" class="bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl px-4 py-2 font-semibold transition">Add Subject</a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      <!-- Subjects Stats -->
      <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6 ring-1 ring-white/5">
        <div class="flex items-center gap-4">
          <div class="p-2 bg-indigo-500/10 rounded-lg">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-400">Total Subjects</p>
            <p class="text-2xl font-semibold text-white"><?= $stats['subjects'] ?></p>
          </div>
        </div>
      </div>

      <!-- Students Stats -->
      <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6 ring-1 ring-white/5">
        <div class="flex items-center gap-4">
          <div class="p-2 bg-emerald-500/10 rounded-lg">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-400">Total Students</p>
            <p class="text-2xl font-semibold text-white"><?= $stats['students'] ?></p>
          </div>
        </div>
      </div>

      <!-- Teachers Stats -->
      <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6 ring-1 ring-white/5">
        <div class="flex items-center gap-4">
          <div class="p-2 bg-yellow-500/10 rounded-lg">
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-400">Total Teachers</p>
            <p class="text-2xl font-semibold text-white"><?= $stats['teachers'] ?></p>
          </div>
        </div>
      </div>

      <!-- Classes Stats -->
      <div class="bg-gray-800/60 border border-gray-700 rounded-xl p-6 ring-1 ring-white/5">
        <div class="flex items-center gap-4">
          <div class="p-2 bg-rose-500/10 rounded-lg">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-400">Total Classes</p>
            <p class="text-2xl font-semibold text-white"><?= $stats['classes'] ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-gray-800/60 border border-gray-700 rounded-2xl overflow-hidden ring-1 ring-white/5">
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
