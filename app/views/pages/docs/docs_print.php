<?php if (!$currentDoc) { echo '<p>Dokumentasi tidak ditemukan.</p>'; return; } ?>
<div>
  <h1 class="print-title"><?= htmlspecialchars($currentDoc['title']) ?></h1>
  <div class="print-meta">
    <?= !empty($currentDoc['author']) ? 'By ' . htmlspecialchars($currentDoc['author']) . ' • ' : '' ?>
    <?= !empty($currentDoc['last_updated']) ? 'Updated ' . htmlspecialchars($currentDoc['last_updated']) : '' ?>
  </div>
  <div class="print-content"><?= nl2br(htmlspecialchars($currentDoc['content'])) ?></div>
</div>