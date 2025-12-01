<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print <?= htmlspecialchars($tableName) ?> - Stuarz</title>

    <style>
        /* Screen: dark UI matching `print/index.php` */
        body { font-family: Inter, Calibri, Arial, sans-serif; background:#0f172a; color:#e6eef8; margin:0; }
        .container { max-width:1200px; margin:24px auto; padding:24px; }
        .card { background:#111827; border:1px solid #111827; border-radius:8px; overflow:hidden; }
        .card-header { padding:16px 20px; background:#0b1220; border-bottom:1px solid #111827; }
        .card-body { padding:20px; }
        .title { font-size:22px; font-weight:700; color:#f8fafc; }
        .meta { color:#94a3b8; font-size:13px; margin-top:6px; }
        .actions { display:flex; gap:10px; align-items:center; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:8px; font-weight:600; cursor:pointer; text-decoration:none; }
        .btn-primary { background:#5865F2; color:#fff; border:1px solid rgba(88,101,242,0.12); }
        .btn-muted { background:#111827; color:#d1d5db; border:1px solid #2b3340; }

        table { width:100%; border-collapse:collapse; font-size:13px; }
        th, td { padding:8px 10px; border:1px solid rgba(148,163,184,0.08); }
        th { background:rgba(88,101,242,0.06); color:#e6eef8; text-align:left; font-weight:700; }
        tr:nth-child(even) td { background:rgba(255,255,255,0.01); }
        .no-data { padding:36px; text-align:center; color:#9ca3af; }

        /* Print: minimal, white paper */
        @media print {
            :root { color-scheme: light; }
            body { background: #fff !important; color: #000 !important; margin:0; padding:0; }
            /* hide non-essential UI */
            .no-print, .actions, .card-header .search, .card-header .controls { display:none !important; }
            /* make content full width and white */
            .container { max-width: 100%; margin: 0; padding: 0; }
            .card, .card-body { background: #fff !important; border: none !important; box-shadow: none !important; }
            table { page-break-inside: avoid; font-size:12px; }
            th, td { border:1px solid #BBBBBB !important; color:#000 !important; }
            /* page settings */
            @page { size: A4; margin: 2cm; }
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div class="title"><?= htmlspecialchars($tableName) ?></div>
                    <div class="meta">Dicetak pada: <?= date('d F Y, H:i:s') ?> • Total Data: <?= count($data) ?></div>
                </div>

                <div class="no-print" style="display:flex;align-items:center;gap:10px;">
                    <a href="index.php?page=print" class="btn btn-muted">Kembali</a>
                    <button onclick="window.print()" class="btn btn-primary">Print Dokumen</button>
                </div>
            </div>

            <div class="card-body">
                <?php if (empty($data)): ?>
                    <div class="no-data">Tidak ada data dalam tabel ini.</div>
                <?php else: ?>
                    <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <?php foreach ($columns as $col): ?>
                                    <th><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $col))) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <?php foreach ($columns as $col): ?>
                                        <td>
                                            <?php
                                                $value = $row[$col] ?? '';
                                                if (is_null($value)) {
                                                    echo '<span style="color:#6b7280;font-style:italic;">NULL</span>';
                                                } elseif (is_string($value) && strlen($value) > 250) {
                                                    echo htmlspecialchars(substr($value, 0, 250)) . '...';
                                                } else {
                                                    echo htmlspecialchars($value);
                                                }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>
</html>














