<?php
if (!isset($_SESSION['user'])) {
  header("Location: index.php?page=login");
  exit;
}

// Ambil semua data user
$sql = "SELECT id, username, email, level, avatar, join_date FROM users ORDER BY join_date DESC";
$result = mysqli_query($config, $sql);
?>

<div class="p-6 space-y-6 bg-gray-900 min-h-screen text-gray-900">
  <div class="bg-white p-6 rounded-2xl shadow">
    <h1 class="text-2xl font-bold mb-4">👥 Daftar Akun</h1>
    
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avatar</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bergabung</th>
            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td class="px-6 py-4 text-sm text-gray-700"><?= $no++ ?></td>
                <td class="px-6 py-4">
                  <img src="<?= htmlspecialchars($row['avatar'] ?: 'assets/default-avatar.png'); ?>" 
                       class="w-10 h-10 rounded-full object-cover" alt="avatar">
                </td>
                <td class="px-6 py-4 text-sm font-medium"><?= htmlspecialchars($row['username']); ?></td>
                <td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['email']); ?></td>
                <td class="px-6 py-4 text-sm"><?= htmlspecialchars($row['level']); ?></td>
                <td class="px-6 py-4 text-sm"><?= htmlspecialchars(date("d M Y", strtotime($row['join_date']))); ?></td>
                <td class="px-6 py-4 flex justify-center space-x-2">
                  <a href="index.php?page=edit_user&id=<?= $row['id']; ?>" 
                     class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700">Edit</a>
                  <a href="index.php?page=delete_user&id=<?= $row['id']; ?>" 
                     onclick="return confirm('Yakin ingin menghapus akun ini?')" 
                     class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada akun terdaftar.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
