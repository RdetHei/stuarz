<!-- Tasks List UI - Discord/GitHub dark style -->
<div class="max-w-7xl mx-auto p-6">
  <!-- Header -->
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Tasks</h1>
          <p class="text-gray-400 text-sm mt-1">Kelola tugas dan assignment siswa</p>
        </div>
      </div>

      <a href="index.php?page=tasks/create" 
         class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2 shadow-lg">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Task
      </a>
    </div>
  </div>

  <!-- Tasks Table -->
  <?php if (!empty($tasks ?? [])): ?>
  <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-900 border-b border-gray-700">
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Title</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Subject</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Class</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Deadline</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php foreach (($tasks ?? []) as $t): ?>
          <tr class="hover:bg-gray-700/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-white font-medium"><?= htmlspecialchars($t['title'] ?? '') ?></span>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-400"><?= htmlspecialchars($t['subject_name'] ?? '') ?></td>
            <td class="px-6 py-4 text-gray-400"><?= htmlspecialchars($t['class_name'] ?? '') ?></td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <?= htmlspecialchars($t['deadline'] ?? '') ?>
              </div>
            </td>
            <td class="px-6 py-4">
              <?php if (($t['status'] ?? '') === 'Completed'): ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                  </svg>
                  Completed
                </span>
              <?php else: ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  Pending
                </span>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="index.php?page=tasks/edit&id=<?= $t['id'] ?>" 
                   class="p-2 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-200" 
                   title="Edit">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="index.php?page=tasks/delete" class="inline" onsubmit="return confirm('Delete this task?')">
                  <input type="hidden" name="id" value="<?= $t['id'] ?>">
                  <button type="submit" 
                          class="p-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 rounded-lg transition-all duration-200" 
                          title="Delete">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Table Footer -->
    <div class="px-6 py-4 bg-gray-900 border-t border-gray-700">
      <div class="flex items-center justify-between text-sm text-gray-400">
        <span>Total: <?= count($tasks ?? []) ?> tasks</span>
        <div class="flex items-center gap-2">
          <div class="w-2 h-2 bg-green-500 rounded-full"></div>
          <span>Last updated: <?= date('d M Y, H:i') ?></span>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>
  <!-- Empty State -->
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
      <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
    </div>
    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Tasks</h3>
    <p class="text-gray-400 mb-6 max-w-md mx-auto">Belum ada tugas yang tersedia. Mulai tambahkan tugas pertama untuk siswa.</p>
    <a href="index.php?page=tasks/create" 
       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 shadow-lg">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Add First Task
    </a>
  </div>
  <?php endif; ?>

  <!-- Info Card -->
  <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-white font-semibold text-lg mb-3">Tentang Tasks</h3>
        <ul class="space-y-2">
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Tasks dapat diberikan kepada kelas atau siswa tertentu</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Siswa dapat mengumpulkan tugas sebelum deadline</span>
          </li>
          <li class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold flex-shrink-0 mt-0.5">✓</span>
            <span class="text-gray-400">Guru dapat memberikan feedback dan nilai untuk setiap submission</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>