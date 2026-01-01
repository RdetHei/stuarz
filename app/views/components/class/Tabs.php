<?php


$tabs = $tabs ?? [];
$active = $active ?? ($tabs[0]['id'] ?? null);
?>
<div>
  <nav class="flex gap-4 border-b border-gray-200 dark:border-gray-700 mb-4">
    <?php foreach ($tabs as $t): $tid = $t['id']; $tt = $t['title']; ?>
      <button data-tab="<?= htmlspecialchars($tid) ?>" class="tab-btn text-sm pb-3 <?= $tid === $active ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 dark:text-gray-300' ?> focus:outline-none">
        <?= htmlspecialchars($tt) ?>
      </button>
    <?php endforeach; ?>
  </nav>
  <div class="tab-contents">
    <?php foreach ($tabs as $t): $tid = $t['id']; ?>
      <div data-tab-content="<?= htmlspecialchars($tid) ?>" class="<?= $tid === $active ? '' : 'hidden' ?>">
        
      </div>
    <?php endforeach; ?>
  </div>
</div>
