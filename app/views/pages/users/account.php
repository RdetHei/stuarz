<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$me = $_SESSION['user'] ?? null;
$canAdmin = isset($me['level']) && $me['level'] === 'admin';

$rows = [];
$errorMsg = '';

// Prefer controller-provided $users array. Jika tidak ada, fallback ke query DB (safe check).
if (isset($users) && is_array($users)) {
  $rows = $users;
} else {
  // coba gunakan $config jika tersedia
  if (isset($config) && $config instanceof mysqli) {
    $sql = "SELECT id, username, email, level, avatar, join_date FROM users ORDER BY join_date DESC";
    $res = mysqli_query($config, $sql);
    if ($res) {
      while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
      mysqli_free_result($res);
    }
  } else {
    // tidak ada koneksi DB tersedia
    $errorMsg = "Database connection not available. Pastikan controller mengirim \$users atau global \$config tersedia.";
  }
}

// compute base URL (folder tempat index.php berada), e.g. "/stuarz/public" or ""
if (!isset($baseUrl)) {
  $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
  if ($baseUrl === '/') $baseUrl = '';
}
?>

<div class="bg-gray-900 min-h-screen">
  <div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-100">Daftar Akun</h1>
        <p class="text-sm text-gray-400 mt-1">Manage user accounts and permissions</p>
      </div>
      <a href="index.php?page=create_user" 
         class="bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Akun
      </a>
    </div>

    <?php if (!empty($errorMsg)): ?>
    <div class="mb-4 bg-red-500/10 border border-red-500/30 rounded-lg p-4">
      <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-red-400"><?= htmlspecialchars($errorMsg) ?></p>
      </div>
    </div>
    <?php endif; ?>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-[#5865F2]/10 rounded-lg">
            <svg class="w-5 h-5 text-[#5865F2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Total Users</p>
            <p class="text-xl font-semibold text-gray-100"><?= count($rows) ?></p>
          </div>
        </div>
      </div>

      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-emerald-500/10 rounded-lg">
            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Admins</p>
            <p class="text-xl font-semibold text-gray-100">
              <?= count(array_filter($rows, fn($u) => ($u['level'] ?? '') === 'admin')) ?>
            </p>
          </div>
        </div>
      </div>

      <div class="bg-[#1f2937] border border-gray-700 rounded-lg p-4">
        <div class="flex items-center gap-3">
          <div class="p-2 bg-amber-500/10 rounded-lg">
            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-400">Regular Users</p>
            <p class="text-xl font-semibold text-gray-100">
              <?= count(array_filter($rows, fn($u) => ($u['level'] ?? '') !== 'admin')) ?>
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-[#1f2937] border border-gray-700 rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
          <thead class="bg-[#111827]">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Level</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Joined</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $row): ?>
            <tr class="hover:bg-gray-800 transition-colors">
              <!-- User Info -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-3">
                  <?php
                  $avatar = $row['avatar'] ?? '';
                  $avatarSrc = ($baseUrl ? $baseUrl . '/' : '') . ltrim($avatar ?: 'assets/default-avatar.png', '/');
                  $username = $row['username'] ?? '';
                  $initial = strtoupper(substr($username, 0, 1));
                  ?>
                  <div class="relative flex-shrink-0">
                    <img src="<?= htmlspecialchars($avatarSrc) ?>"
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-700" 
                         alt="<?= htmlspecialchars($username) ?>"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5865F2] to-[#4752C4] flex items-center justify-center hidden">
                      <span class="text-sm font-semibold text-white"><?= $initial ?></span>
                    </div>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-200"><?= htmlspecialchars($username) ?></p>
                    <p class="text-xs text-gray-500">ID: <?= htmlspecialchars($row['id'] ?? '') ?></p>
                  </div>
                </div>
              </td>

              <!-- Email -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                  </svg>
                  <span class="text-sm text-gray-400"><?= htmlspecialchars($row['email'] ?? '') ?></span>
                </div>
              </td>

              <!-- Level -->
              <td class="px-6 py-4 whitespace-nowrap">
                <?php
                $level = $row['level'] ?? '';
                $badgeClass = 'bg-gray-700 text-gray-300 border-gray-600';
                $icon = 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z';
                
                if ($level === 'admin') {
                  $badgeClass = 'bg-[#5865F2]/10 text-[#5865F2] border-[#5865F2]/20';
                  $icon = 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z';
                } elseif ($level === 'teacher') {
                  $badgeClass = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                  $icon = 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222';
                } elseif ($level === 'student') {
                  $badgeClass = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                  $icon = 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
                }
                ?>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border <?= $badgeClass ?>">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $icon ?>"/>
                  </svg>
                  <?= ucfirst(htmlspecialchars($level)) ?>
                </span>
              </td>

              <!-- Join Date -->
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span class="text-sm text-gray-400"><?= htmlspecialchars($row['join_date'] ?? '-') ?></span>
                </div>
              </td>

              <!-- Actions -->
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <div class="flex items-center justify-end gap-2">
                  <a href="index.php?page=edit_user&id=<?= (int)($row['id'] ?? 0) ?>" 
                     class="p-2 text-gray-400 hover:text-[#5865F2] hover:bg-[#5865F2]/10 rounded-md transition-colors"
                     title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                  </a>
                  <form method="post" action="index.php?page=delete_user" class="inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                    <input type="hidden" name="id" value="<?= (int)($row['id'] ?? 0) ?>">
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
            <?php else: ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                  </div>
                  <p class="text-gray-400 text-sm mb-3">Tidak ada akun terdaftar</p>
                  <a href="index.php?page=create_user" 
                     class="inline-flex items-center gap-2 px-4 py-2 bg-[#5865F2] hover:bg-[#4752C4] text-white rounded-md text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create First User
                  </a>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>