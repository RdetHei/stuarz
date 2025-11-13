<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Grades</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #111; background: #fff; margin: 0; padding: 18px; }
    .container { max-width: 1000px; margin: 0 auto; }
    h1 { font-size: 22px; margin-bottom: 6px; }
    table { width: 100%; border-collapse: collapse; margin-top: 12px; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 13px; }
    th { background: #f5f5f5; }
    .meta { color: #555; font-size: 12px; }
    @media print { body { padding: 6mm; } }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>Grades</h1>
      <div class="meta">Generated: <?= date('d F Y H:i') ?></div>
    </header>

    <?php if (!empty($grades ?? [])): ?>
      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Subject</th>
            <th>Task</th>
            <th>Score</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($grades as $g): ?>
            <tr>
              <td><?= htmlspecialchars($g['username'] ?? '') ?></td>
              <td><?= htmlspecialchars($g['subject_name'] ?? '') ?></td>
              <td><?= htmlspecialchars($g['task_title'] ?? '') ?></td>
              <td><?= htmlspecialchars($g['score'] ?? '') ?></td>
              <td><?= htmlspecialchars($g['created_at'] ?? '') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No grades available.</p>
    <?php endif; ?>
  </div>
</body>
</html>
