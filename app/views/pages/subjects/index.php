
<div class="bg-gray-900 min-h-screen">
  <div class="mx-auto max-w-7xl px-6 py-8 lg:px-8">
    
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-100">Subjects</h1>
        <p class="text-sm text-gray-400 mt-1">Manage all subjects and teachers</p>
      </div>
      <a href="index.php?page=subjects/create" 
         class="bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Add Subject
      </a>
    </div>

    
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6">
      
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-[#5865F2]/10 rounded-lg">
            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Subjects</p>
            <p class="text-xl font-semibold text-gray-100"><?= $stats['subjects'] ?></p>
          </div>
        </div>
      </div>

      
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-emerald-500/10 rounded-lg">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Students</p>
            <p class="text-xl font-semibold text-gray-100"><?= $stats['students'] ?></p>
          </div>
        </div>
      </div>

      
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-amber-500/10 rounded-lg">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Teachers</p>
            <p class="text-xl font-semibold text-gray-100"><?= $stats['teachers'] ?></p>
          </div>
        </div>
      </div>

      
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-rose-500/10 rounded-lg">
            <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Classes</p>
            <p class="text-xl font-semibold text-gray-100"><?= $stats['classes'] ?></p>
          </div>
        </div>
      </div>
    </div>

    
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-[#111827]">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Description</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Guru Pengajar</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <?php if (empty($subjects ?? [])): ?>
            <tr>
              <td colspan="4" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                  </div>
                  <p class="text-gray-400 text-sm mb-3">No subjects found</p>
                  <a href="index.php?page=subjects/create" 
                     class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create First Subject
                  </a>
                </div>
              </td>
            </tr>
            <?php else: ?>
            <?php foreach ($subjects as $s): ?>
            <tr class="hover:bg-gray-800 transition-colors">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-lg bg-[#5865F2]/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-200"><?= htmlspecialchars($s['name'] ?? '') ?></span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-400"><?= htmlspecialchars($s['description'] ?? '-') ?></span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <?php if (!empty($s['teacher_name'])): ?>
                <div class="flex items-center gap-2">
                  <div class="w-6 h-6 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] flex items-center justify-center flex-shrink-0">
                    <span class="text-xs font-semibold text-white">
                      <?= strtoupper(substr($s['teacher_name'], 0, 1)) ?>
                    </span>
                  </div>
                  <span class="text-sm text-gray-300"><?= htmlspecialchars($s['teacher_name']) ?></span>
                </div>
                <?php else: ?>
                <span class="text-sm text-gray-500 italic">Belum ditentukan</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="index.php?page=subjects/edit&id=<?= $s['id'] ?>" 
                     class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors" 
                     title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </a>
                  <form method="post" action="index.php?page=subjects/delete" class="inline" onsubmit="return confirm('Delete this subject?')">
                    <input type="hidden" name="id" value="<?= $s['id'] ?>">
                    <button type="submit" 
                            class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-md transition-colors" 
                            title="Delete">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>