<?php if (!isset($announcement)) { die('Announcement not found'); } ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($announcement['title']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #111; background: #fff; margin: 0; padding: 24px; }
    .container { max-width: 800px; margin: 0 auto; }
    h1 { font-size: 24px; margin-bottom: 8px; }
    .meta { color: #444; font-size: 13px; margin-bottom: 18px; }
    img { max-width: 100%; height: auto; display: block; margin: 12px 0; }
    .content { font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1><?= htmlspecialchars($announcement['title']) ?></h1>
      <div class="meta">By <?= htmlspecialchars($announcement['creator'] ?? '-') ?> | <?= htmlspecialchars($announcement['created_at'] ?? '') ?></div>
    </header>

    <?php if (!empty($announcement['photo'])): ?>
      <img src="<?= htmlspecialchars(ltrim($announcement['photo'], '/')) ?>" alt="<?= htmlspecialchars($announcement['title']) ?>">
    <?php endif; ?>

    <article class="content">
      <?= nl2br(htmlspecialchars($announcement['content'])) ?>
    </article>
  </div>
</body>
</html>
