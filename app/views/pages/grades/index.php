<div class="max-w-7xl mx-auto p-6">
  <div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-indigo-600 flex items-center justify-center shadow-lg">
          <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
          </svg>
        </div>
        <div>
          <h1 class="text-3xl font-bold text-white">Grades</h1>
          <p class="text-gray-400 text-sm mt-1">Kelola nilai dan penilaian siswa</p>
        </div>
      </div>
    </div>
    <div class="mt-6 bg-gray-800 border border-gray-700 rounded-lg p-4">
      <form method="get" action="index.php" class="flex flex-wrap gap-3">
        <input type="hidden" name="page" value="grades">
        
        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Mata Pelajaran</label>
          <select name="subject_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Mata Pelajaran</option>
            <?php foreach (($subjects ?? []) as $s): ?>
              <option value="<?= $s['id'] ?>" <?= (($_GET['subject_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex-1 min-w-[200px]">
          <label class="block text-sm font-medium text-gray-400 mb-2">Kelas</label>
          <select name="class_id" class="w-full px-4 py-2.5 bg-gray-900 border border-gray-700 rounded-lg text-white focus:border-indigo-600 focus:outline-none transition-all">
            <option value="">Semua Kelas</option>
            <?php foreach (($classes ?? []) as $c): ?>
              <option value="<?= $c['id'] ?>" <?= (($_GET['class_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="flex items-end gap-2">
          <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter
          </button>

          <?php if (!empty($_GET['subject_id']) || !empty($_GET['class_id'])): ?>
          <a href="index.php?page=grades" class="px-6 py-2.5 bg-gray-700 hover:bg-gray-600 border border-gray-600 text-white rounded-lg font-medium transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Reset
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <?php if (!empty($grades ?? [])): ?>
  <div class="bg-gray-800 border border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="bg-gray-900 border-b border-gray-700">
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Student</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Subject</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Task</th>
            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Score</th>
            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php foreach (($grades ?? []) as $g): ?>
          <tr class="hover:bg-gray-700/50 transition-colors">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 flex items-center justify-center text-white text-sm font-semibold">
                  <?= strtoupper(substr($g['username'] ?? 'U', 0, 1)) ?>
                </div>
                <span class="text-white font-medium"><?= htmlspecialchars($g['username'] ?? '') ?></span>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-400"><?= htmlspecialchars($g['subject_name'] ?? '') ?></td>
            <td class="px-6 py-4">
              <div class="flex items-center gap-2 text-gray-400">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <?= htmlspecialchars($g['task_title'] ?? '') ?>
              </div>
            </td>
            <td class="px-6 py-4">
              <?php $score = intval($g['score'] ?? 0); ?>
              <?php if ($score >= 90): ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                  A (<?= $score ?>)
                </span>
              <?php elseif ($score >= 75): ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                  </svg>
                  B (<?= $score ?>)
                </span>
              <?php elseif ($score >= 60): ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                  </svg>
                  C (<?= $score ?>)
                </span>
              <?php else: ?>
                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-red-500/20 text-red-300 border border-red-500/30">
                  <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                  D (<?= $score ?>)
                </span>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4">
              <div class="flex items-center justify-end gap-2">
                <a href="index.php?page=grades/edit&id=<?= $g['id'] ?>" 
                   class="p-2 bg-indigo-600/20 hover:bg-indigo-600/30 border border-indigo-600/30 text-indigo-400 rounded-lg transition-all duration-200" 
                   title="Edit">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form method="post" action="index.php?page=grades/delete" class="inline" onsubmit="return confirm('Delete this grade?')">
                  <input type="hidden" name="id" value="<?= $g['id'] ?>">
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

    <div class="px-6 py-4 bg-gray-900 border-t border-gray-700">
      <div class="flex items-center justify-between text-sm text-gray-400">
        <span>Total: <?= count($grades ?? []) ?> nilai</span>
        <div class="flex items-center gap-4">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            <span>A (90-100)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
            <span>B (75-89)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
            <span>C (60-74)</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500"></div>
            <span>D (<60)</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php else: ?>
  <div class="bg-gray-800 border border-gray-700 rounded-xl p-12 text-center">
    <div class="w-20 h-20 mx-auto mb-4 rounded-2xl bg-indigo-600/20 flex items-center justify-center">
      <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
      </svg>
    </div>
    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Nilai</h3>
    <p class="text-gray-400 mb-6 max-w-md mx-auto">Belum ada nilai yang tersedia. Gunakan filter untuk mencari nilai atau tambahkan nilai baru.</p>
  </div>
  <?php endif; ?>

  <div class="mt-6 bg-gray-800 border border-gray-700 rounded-xl p-6">
    <div class="flex items-start gap-4">
      <div class="flex-shrink-0 w-12 h-12 bg-indigo-600/20 rounded-xl flex items-center justify-center">
        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1">
        <h3 class="text-white font-semibold text-lg mb-3">Sistem Penilaian</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold flex-shrink-0 mt-0.5">A</span>
            <div>
              <span class="text-white font-medium">Grade A:</span>
              <span class="text-gray-400"> Nilai 90-100 (Sangat Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-500/20 text-blue-400 text-xs font-bold flex-shrink-0 mt-0.5">B</span>
            <div>
              <span class="text-white font-medium">Grade B:</span>
              <span class="text-gray-400"> Nilai 75-89 (Baik)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-500/20 text-yellow-400 text-xs font-bold flex-shrink-0 mt-0.5">C</span>
            <div>
              <span class="text-white font-medium">Grade C:</span>
              <span class="text-gray-400"> Nilai 60-74 (Cukup)</span>
            </div>
          </div>
          <div class="flex items-start gap-2 text-sm">
            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-500/20 text-red-400 text-xs font-bold flex-shrink-0 mt-0.5">D</span>
            <div>
              <span class="text-white font-medium">Grade D:</span>
              <span class="text-gray-400"> Nilai <60 (Kurang)</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>