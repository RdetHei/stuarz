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

<div class="p-6 space-y-6 min-h-screen text-gray-100">
  <div class="p-0 rounded-2xl shadow-lg bg-transparent">
  <div class="flex items-center justify-between px-6 py-4 border border-gray-700 rounded-2xl mb-4 bg-gray-800/60">
    <h1 class="text-xl font-semibold flex items-center gap-2">Daftar Akun</h1>
    <a href="index.php?page=create_user" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500 border border-indigo-500/30">Buat Akun</a>
  </div>

  <?php if (!empty($errorMsg)): ?>
    <div class="mb-4 text-sm text-red-400"><?= htmlspecialchars($errorMsg) ?></div>
  <?php endif; ?>

  <div class="overflow-hidden rounded-xl border border-gray-700">
    <table class="min-w-full">
    <thead class="bg-[#1f2937] text-gray-200 text-xs uppercase tracking-wide">
      <tr>
      <th class="px-6 py-3 text-left">#</th>
      <th class="px-6 py-3 text-left">Avatar</th>
      <th class="px-6 py-3 text-left">Username</th>
      <th class="px-6 py-3 text-left">Email</th>
      <th class="px-6 py-3 text-left">Level</th>
      <th class="px-6 py-3 text-left">Bergabung</th>
      <th class="px-6 py-3 text-left">Aksi</th>
      </tr>
    </thead>
    <tbody class="bg-[#111827] divide-y divide-gray-800">
      <?php if (count($rows) > 0): ?>
      <?php foreach ($rows as $i => $row): ?>
        <tr class="hover:bg-[#1f2937] transition">
        <td class="px-6 py-3 text-sm text-gray-300"><?= $i + 1 ?></td>
        <td class="px-6 py-3">
          <?php
          $avatar = $row['avatar'] ?? '';
          $avatarSrc = ($baseUrl ? $baseUrl . '/' : '') . ltrim($avatar ?: 'assets/default-avatar.png', '/');
          ?>
          <img src="<?= htmlspecialchars($avatarSrc) ?>"
             class="w-10 h-10 rounded-full object-cover border-2 border-white/20" alt="avatar">
        </td>
        <td class="px-6 py-3 font-medium text-gray-100"><?= htmlspecialchars($row['username'] ?? '') ?></td>
        <td class="px-6 py-3 text-gray-300"><?= htmlspecialchars($row['email'] ?? '') ?></td>
        <td class="px-6 py-3">
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs border border-gray-600 bg-gray-800 text-gray-200">
          <span class="material-symbols-outlined text-[14px]">verified_user</span>
          <?= htmlspecialchars($row['level'] ?? '') ?>
          </span>
        </td>
        <td class="px-6 py-4"><?= htmlspecialchars($row['join_date'] ?? '') ?></td>
        <td class="px-6 py-3">
          <div class="flex justify gap-6">
          <a href="index.php?page=edit_user&id=<?= (int)($row['id'] ?? 0) ?>" class="px-3 py-1 bg-blue-600/80 text-white text-xs rounded-md hover:bg-blue-500 border border-blue-500/30">Edit</a>
          <form method="post" action="index.php?page=delete_user" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
            <input type="hidden" name="id" value="<?= (int)($row['id'] ?? 0) ?>">
            <button type="submit" class="px-3 py-1 bg-red-600/80 text-white text-xs rounded-md hover:bg-red-500 border border-red-500/30">Hapus</button>
          </form>
          </div>
        </td>
        </tr>
      <?php endforeach; ?>
      <?php else: ?>
      <tr class="bg-blue-900">
        <td colspan="7" class="px-6 py-4 text-center text-gray-300">Tidak ada akun terdaftar.</td>
      </tr>
      <?php endif; ?>
    </tbody>
    </table>
  </div>
  </div>
</div>