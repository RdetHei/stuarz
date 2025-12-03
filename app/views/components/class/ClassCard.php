<?php
// Props expected: $name, $code, $members_count, $role, $link
// Optional: $creator_name, $creator_avatar, $creator_id
$name = $name ?? 'Nama Kelas';
$code = $code ?? '';
$members_count = isset($members_count) ? intval($members_count) : 0;
$role = $role ?? 'student';
$link = $link ?? '#';
$creator_name = $creator_name ?? ($creator ?? null);
$creator_avatar = $creator_avatar ?? null;
$creator_id = isset($creator_id) ? intval($creator_id) : null;
// Resolve avatar URL
$avatarUrl = '';
if (!empty($creator_avatar)) {
  if (preg_match('#^https?://#i', $creator_avatar)) {
    $avatarUrl = $creator_avatar;
  } else {
    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'); if ($baseUrl === '/') $baseUrl = '';
    $prefix = ($baseUrl ? $baseUrl . '/' : '');
    $candidate = $prefix . ltrim($creator_avatar, '/\\');
    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
    $candidateFs = $docRoot ? $docRoot . '/' . ltrim($candidate, '/\\') : '';
    if ($candidateFs && is_file($candidateFs)) {
      $avatarUrl = $candidate;
    } else {
      $altFs = $docRoot ? $docRoot . '/' . ltrim($creator_avatar, '/\\') : '';
      if ($altFs && is_file($altFs)) { $avatarUrl = ltrim($creator_avatar, '/\\'); } else { $avatarUrl = $creator_avatar; }
    }
  }
}
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 flex flex-col justify-between">
  <div>
    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 truncate"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></h3>
    <?php if ($code !== ''): ?>
      <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kode: <span class="font-mono text-sm text-gray-700 dark:text-gray-300"><?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?></span></div>
    <?php endif; ?>
    <?php if (!empty($creator_name)): ?>
      <div class="mt-2 inline-flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
        <?php if (!empty($avatarUrl)): ?>
          <img src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($creator_name, ENT_QUOTES, 'UTF-8') ?>" class="w-5 h-5 rounded-full object-cover border border-gray-300 dark:border-gray-600" />
        <?php else: ?>
          <span class="w-5 h-5 rounded-full bg-indigo-600/40 text-white flex items-center justify-center text-[10px] font-bold border border-gray-300 dark:border-gray-600"><?php echo htmlspecialchars(strtoupper(mb_substr($creator_name,0,1,'UTF-8')), ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
        <?php if ($creator_id): ?>
          <a href="index.php?page=profile&user_id=<?= $creator_id ?>" class="hover:text-gray-700 dark:hover:text-gray-300">Wali: <?= htmlspecialchars($creator_name, ENT_QUOTES, 'UTF-8') ?></a>
        <?php else: ?>
          <span>Wali: <?= htmlspecialchars($creator_name, ENT_QUOTES, 'UTF-8') ?></span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="mt-3 text-sm text-gray-600 dark:text-gray-300">Anggota: <strong><?= $members_count ?></strong></div>
  </div>

  <div class="mt-4 flex items-center justify-between">
    <a href="<?= $link ?>" class="px-3 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 text-sm">Masuk</a>
    <?php if ($role === 'admin' || $role === 'teacher' || $role === 'guru'): ?>
      <a href="<?= $link ?>?manage=1" class="px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm text-gray-800 dark:text-gray-200">Kelola</a>
    <?php endif; ?>
  </div>
</div>
