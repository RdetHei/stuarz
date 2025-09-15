<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$me = $_SESSION['user'] ?? null;
$canAdmin = isset($me['level']) && $me['level'] === 'admin';

// Prefer controller-provided $users array. Jika tidak ada, fallback ke query DB (safe check).
$rows = [];
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
?>

<div class="p-6 space-y-6 min-h-screen text-gray-100">
  <div class="p-6 rounded-2xl shadow-lg bg-transparent">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold flex items-center gap-2">Daftar Akun</h1>
      <a href="index.php?page=create_user" class="px-3 py-2 bg-indigo-600 rounded text-sm hover:bg-indigo-500">Buat Akun</a>
    </div>

    <?php if (!empty($errorMsg)): ?>
      <div class="mb-4 text-sm text-red-400"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <div class="overflow-hidden rounded-xl">
      <table class="min-w-full divide-y divide-gray-700">
        <thead>
          <tr class="bg-blue-900 text-white text-sm uppercase tracking-wide">
            <th class="px-6 py-3 text-left">#</th>
            <th class="px-6 py-3 text-left">Avatar</th>
            <th class="px-6 py-3 text-left">Username</th>
            <th class="px-6 py-3 text-left">Email</th>
            <th class="px-6 py-3 text-left">Level</th>
            <th class="px-6 py-3 text-left">Bergabung</th>
            <th class="px-6 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-gray-800">
          <?php if (count($rows) > 0): ?>
            <?php foreach ($rows as $i => $row): ?>
              <?php $rowLevel = htmlspecialchars(strtolower($row['level'] ?? '')); ?>
              <tr class="<?= ($i % 2 == 0) ? 'bg-gray-800' : 'bg-gray-900'; ?> hover:bg-gray-700 transition" data-level="<?= $rowLevel; ?>">
                <td class="px-6 py-4 text-sm"><?= $i + 1 ?></td>
                <td class="px-6 py-4">
                  <?php
                  // compute base URL (folder tempat index.php berada), e.g. "/stuarz/public" or ""
                  if (!isset($baseUrl)) {
                    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
                    if ($baseUrl === '/') $baseUrl = '';
                  }
                  ?>
                  <img src="<?= htmlspecialchars(($baseUrl ? $baseUrl . '/' : '') . ltrim($row['avatar'] ?: 'assets/default-avatar.png', '/')); ?>"
                    class="w-10 h-10 rounded-full object-cover border-2 border-white/20" alt="avatar">
                </td>
                <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($row['username']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['email']); ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($row['level']); ?></td>
                <td class="px-6 py-4"><?=htmlspecialchars($row['join_date'] ?? 'malah kosong njir'); ?></td>
                <td class="px-6 py-4 flex justify-center gap-2">
                  <a href="index.php?page=edit_user&id=<?= (int)$row['id']; ?>"
                    class="px-3 py-1 bg-blue-500 text-white text-xs rounded-lg hover:bg-blue-400">Edit</a>
                  <a href="index.php?page=delete_user&id=<?= (int)$row['id']; ?>"
                    onclick="return confirm('Yakin ingin menghapus akun ini?')"
                    class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-500">Hapus</a>
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

<?php if (count($rows) > 0): ?>
  <script src="<?= ($baseUrl !== '' ? $baseUrl . '/' : '/') ?>js/account.js" defer></script>
<?php endif; ?>