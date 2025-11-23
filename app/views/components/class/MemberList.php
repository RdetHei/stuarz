<?php
// MemberList component
// Expects: $members (array of ['id','username','email','role','avatar'])
$members = $members ?? [];
$sessionUser = $_SESSION['user'] ?? [];
?>
<div class="space-y-3">
  <?php if (empty($members)): ?>
    <div class="text-sm text-gray-500">Tidak ada anggota.</div>
  <?php else: ?>
    <?php foreach ($members as $m): ?>
      <?php
        // Get display name - prefer username, fallback to email or user_id
        $displayName = $m['username'] ?? $m['name'] ?? $m['email'] ?? 'User #' . ($m['user_id'] ?? '');
        $avatar = $m['avatar'] ?? '';
        $role = $m['role'] ?? 'student';
        $userId = $m['user_id'] ?? $m['id'] ?? 0;
        $initial = strtoupper(mb_substr($displayName, 0, 1, 'UTF-8'));
      ?>
      <div class="flex items-center gap-3 bg-white dark:bg-gray-800 rounded p-3 shadow-sm">
        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden flex items-center justify-center profile-avatar">
          <?php if (!empty($avatar)): ?>
            <img src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?>" class="w-full h-full object-cover" />
          <?php else: ?>
            <span class="text-gray-600 dark:text-gray-300 font-semibold"><?= htmlspecialchars($initial, ENT_QUOTES, 'UTF-8') ?></span>
          <?php endif; ?>
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?= htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8') ?></div>
          <div class="text-xs text-gray-500">
            <?php if (!empty($m['email'])): ?>
              <?= htmlspecialchars($m['email'], ENT_QUOTES, 'UTF-8') ?> • 
            <?php endif; ?>
            <?= htmlspecialchars(ucfirst($role), ENT_QUOTES, 'UTF-8') ?>
          </div>
        </div>
        <?php if (($sessionUser['level'] ?? '') === 'admin' || ($sessionUser['level'] ?? '') === 'guru' || ($sessionUser['level'] ?? '') === 'teacher'): ?>
          <form method="POST" action="index.php?page=class_remove_member" onsubmit="return confirm('Hapus anggota dari kelas?');">
            <input type="hidden" name="class_id" value="<?= intval($class_id ?? $_GET['id'] ?? 0) ?>" />
            <input type="hidden" name="user_id" value="<?= intval($userId) ?>" />
            <button type="submit" class="px-3 py-1 rounded bg-red-100 hover:bg-red-200 text-red-700 text-sm transition-colors">Hapus</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
