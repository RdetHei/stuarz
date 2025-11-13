<?php if (!isset($newsItem)) { die('News not found'); } ?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($newsItem['title']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* Minimal, print-friendly styles */
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #111; background: #fff; margin: 0; padding: 24px; }
    .container { max-width: 800px; margin: 0 auto; }
    h1 { font-size: 28px; margin-bottom: 8px; }
    .meta { color: #444; font-size: 13px; margin-bottom: 18px; }
    img { max-width: 100%; height: auto; display: block; margin: 12px 0; }
    .content { font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
    @media print { 
      /* remove any remaining chrome if accidentally included */
      a[href]:after { content: " (" attr(href) ")"; color: #000; }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1><?= htmlspecialchars($newsItem['title']) ?></h1>
      <div class="meta">By <?= htmlspecialchars($newsItem['author'] ?? '-') ?> | <?= htmlspecialchars(date('d F Y', strtotime($newsItem['created_at'] ?? '')) ) ?></div>
    </header>

    <?php if (!empty($newsItem['thumbnail'])): ?>
      <img src="<?= htmlspecialchars(ltrim($newsItem['thumbnail'], '/')) ?>" alt="<?= htmlspecialchars($newsItem['title']) ?>">
    <?php endif; ?>

    <article class="content">
      <?= nl2br(htmlspecialchars($newsItem['content'])) ?>
    </article>
  </div>
</body>
</html>
