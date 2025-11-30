<?php
// MemberList component
// Expects: $members (array of ['id','username','email','role','avatar'])
$members = $members ?? [];
$sessionUser = $_SESSION['user'] ?? [];

// Resolve base prefix
$baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
if ($baseUrl === '/') $baseUrl = '';
$prefix = ($baseUrl ? $baseUrl . '/' : '');

// Role colors
$roleColors = [
  'admin' => ['bg' => 'bg-red-500/10', 'border' => 'border-red-500/20', 'text' => 'text-red-400'],
  'teacher' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'guru' => ['bg' => 'bg-purple-500/10', 'border' => 'border-purple-500/20', 'text' => 'text-purple-400'],
  'student' => ['bg' => 'bg-blue-500/10', 'border' => 'border-blue-500/20', 'text' => 'text-blue-400']
];
?>

<?php if (empty($members)): ?>
  <!-- Empty State -->
  <div class="text-center py-12">
    <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-700/50 flex items-center justify-center">
      <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
    </div>
    <h3 class="text-lg font-bold text-white mb-2">Belum Ada Anggota</h3>
    <p class="text-sm text-gray-400">Tidak ada anggota di kelas ini.</p>
  </div>
<?php else: ?>
  
  <!-- Member Stats -->
  <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <?php
      $totalMembers = count($members);

      // Hitung jumlah berdasarkan level; prefer 'level' dari tabel users.
      $levelCounts = [ 'admin' => 0, 'guru' => 0, 'user' => 0, 'other' => 0 ];

      // Kumpulkan user IDs yang tidak memiliki 'level' pada entry members
      $missingIds = [];
      foreach ($members as $mm) {
        $uid = intval($mm['user_id'] ?? $mm['id'] ?? 0);
        $hasLevel = isset($mm['level']) && $mm['level'] !== '';
        if (!$hasLevel && $uid) $missingIds[$uid] = $uid;
      }

      // Jika ada missing, query tabel users untuk mengambil level mereka (batch)
      $levelsMap = [];
      if (!empty($missingIds)) {
        try {
          global $config;
          if ($config instanceof mysqli) {
            $ids = array_map('intval', array_values($missingIds));
            $in = implode(',', $ids);
            $sql = "SELECT id, level FROM users WHERE id IN ($in)";
            $res = $config->query($sql);
            if ($res) {
              while ($r = $res->fetch_assoc()) {
                $levelsMap[intval($r['id'])] = $r['level'] ?? '';
              }
              $res->free();
            }
          }
        } catch (Throwable $e) {
          // ignore DB errors and fallback to existing member data
        }
      }

      foreach ($members as $mm) {
        $uid = intval($mm['user_id'] ?? $mm['id'] ?? 0);
        $raw = '';
        if (isset($mm['level']) && $mm['level'] !== '') {
          $raw = (string)$mm['level'];
        } elseif ($uid && isset($levelsMap[$uid])) {
          $raw = (string)$levelsMap[$uid];
        } else {
          $raw = (string)($mm['role'] ?? '');
        }
        $raw = strtolower(trim($raw));
        if ($raw === 'teacher') $raw = 'guru';
        if ($raw === 'student') $raw = 'user';
        if ($raw === '') $raw = 'other';
        if (!isset($levelCounts[$raw])) $levelCounts[$raw] = 0;
        $levelCounts[$raw]++;
      }

      $teachers = ($levelCounts['guru'] ?? 0);
      $students = ($levelCounts['user'] ?? 0);
      $admins = ($levelCounts['admin'] ?? 0);
    ?>

    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Total</div>
      <div class="text-2xl font-bold text-white"><?= $totalMembers ?></div>
    </div>

    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Pengajar</div>
      <div class="text-2xl font-bold text-purple-400"><?= $teachers ?></div>
    </div>

    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Siswa</div>
      <div class="text-2xl font-bold text-blue-400"><?= $students ?></div>
    </div>

    <div class="bg-gray-750 border border-gray-700 rounded-lg p-4">
      <div class="text-sm text-gray-400 mb-1">Admin</div>
      <div class="text-2xl font-bold text-amber-400"><?= $admins ?></div>
    </div>
  </div>

  <!-- Member List -->
  <div class="space-y-3">
    <?php foreach ($members as $m): ?>
      <?php
        // Get display name
        $displayName = $m['username'] ?? $m['name'] ?? $m['email'] ?? 'User #' . ($m['user_id'] ?? '');
        $role = $m['role'] ?? 'student';
        $userId = $m['user_id'] ?? $m['id'] ?? 0;
        $initial = strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'));
        $colors = $roleColors[$role] ?? $roleColors['student'];
        
        // Resolve avatar URL
        $avatar = $m['avatar'] ?? '';
        $avatarUrl = '';
        if (!empty($avatar)) {
          if (preg_match('#^https?://#i', $avatar)) {
            $avatarUrl = $avatar;
          } else {
            $candidate = $prefix . ltrim($avatar, '/\\');
            $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
            $candidateFs = $docRoot ? $docRoot . '/' . ltrim($candidate, '/\\') : '';
            if ($candidateFs && is_file($candidateFs)) {
              $avatarUrl = $candidate;
            } else {
              $altFs = $docRoot ? $docRoot . '/' . ltrim($avatar, '/\\') : '';
              if ($altFs && is_file($altFs)) {
                $avatarUrl = ltrim($avatar, '/\\');
              } else {
                $avatarUrl = $avatar;
              }
            }
          }
        }
        
        // Generate avatar colors based on initial
        $avatarColors = [
          'A' => ['from-red-500', 'to-red-600'],
          'B' => ['from-orange-500', 'to-orange-600'],
          'C' => ['from-amber-500', 'to-amber-600'],
          'D' => ['from-yellow-500', 'to-yellow-600'],
          'E' => ['from-lime-500', 'to-lime-600'],
          'F' => ['from-green-500', 'to-green-600'],
          'G' => ['from-emerald-500', 'to-emerald-600'],
          'H' => ['from-teal-500', 'to-teal-600'],
          'I' => ['from-cyan-500', 'to-cyan-600'],
          'J' => ['from-sky-500', 'to-sky-600'],
          'K' => ['from-blue-500', 'to-blue-600'],
          'L' => ['from-indigo-500', 'to-indigo-600'],
          'M' => ['from-violet-500', 'to-violet-600'],
          'N' => ['from-purple-500', 'to-purple-600'],
          'O' => ['from-fuchsia-500', 'to-fuchsia-600'],
          'P' => ['from-pink-500', 'to-pink-600'],
          'Q' => ['from-rose-500', 'to-rose-600'],
        ];
        $defaultColor = ['from-blue-500', 'to-blue-600'];
        $avatarColor = $avatarColors[$initial] ?? $defaultColor;
      ?>
      
      <div class="bg-gray-750 border border-gray-700 hover:border-gray-600 rounded-lg transition-all group">
        <div class="flex items-center gap-4 p-4">
          
          <!-- Avatar -->
          <div class="flex-shrink-0">
            <?php if (!empty($avatarUrl)): ?>
              <img src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>" 
                   alt="<?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>" 
                   class="w-12 h-12 rounded-full object-cover border-2 border-gray-600 group-hover:border-gray-500 transition-colors" />
            <?php else: ?>
              <div class="w-12 h-12 rounded-full bg-gradient-to-br <?= $avatarColor[0] ?> <?= $avatarColor[1] ?> flex items-center justify-center border-2 border-gray-600 group-hover:border-gray-500 transition-colors shadow-lg">
                <span class="text-lg font-bold text-white"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
              <h4 class="text-sm font-semibold text-white truncate"><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></h4>
              <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?= $colors['bg'] ?> <?= $colors['border'] ?> <?= $colors['text'] ?> border flex-shrink-0">
                <?= htmlspecialchars(ucfirst($role), ENT_QUOTES, 'UTF-8') ?>
              </span>
            </div>
            <?php if (!empty($m['email'])): ?>
              <div class="flex items-center gap-1.5 text-xs text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="truncate"><?= htmlspecialchars($m['email'], ENT_QUOTES, 'UTF-8') ?></span>
              </div>
            <?php endif; ?>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2 flex-shrink-0">
            <!-- View Profile Button -->
            <a href="index.php?page=profile&id=<?= intval($userId) ?>" 
               class="p-2 text-gray-400 hover:text-blue-400 hover:bg-blue-500/10 border border-transparent hover:border-blue-500/20 rounded-lg transition-all"
               title="Lihat Profile">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </a>
            
            <!-- Remove Button (Admin/Teacher only) -->
            <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru' || ($sessionUser['level'] ?? '') === 'teacher'): ?>
              <form method="POST" action="index.php?page=class_remove_member" class="inline" onsubmit="return confirm('Yakin ingin menghapus <?= htmlspecialchars($displayName) ?> dari kelas?');">
                <input type="hidden" name="class_id" value="<?= intval($class_id ?? $_GET['id'] ?? 0) ?>" />
                <input type="hidden" name="user_id" value="<?= intval($userId) ?>" />
                <button type="submit" 
                        class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/20 rounded-lg transition-all"
                        title="Hapus dari Kelas">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                  </svg>
                </button>
              </form>
            <?php endif; ?>
          </div>

        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>