<?php
// EmptyState component
// Expects: $title, $message, $cta (array with 'text' and 'href')
$title = $title ?? 'Belum ada kelas';
$message = $message ?? 'Anda belum mengikuti atau mengelola kelas apapun.';
$cta = $cta ?? null;
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 text-center">
  <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
  <p class="text-sm text-gray-500 mt-2"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
  <?php if ($cta): ?>
    <div class="mt-4">
      <a href="<?= htmlspecialchars($cta['href'], ENT_QUOTES, 'UTF-8') ?>" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"><?= htmlspecialchars($cta['text'], ENT_QUOTES, 'UTF-8') ?></a>
    </div>
  <?php endif; ?>
</div>
