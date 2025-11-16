<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print <?= htmlspecialchars($tableName) ?> - Stuarz</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            @page { margin: 1cm; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .print-container {
            background: white;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1f2937;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        
        .header .meta {
            color: #6b7280;
            font-size: 14px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        
        th {
            background: #4f46e5;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #4338ca;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #e5e7eb;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        tr:hover {
            background: #f3f4f6;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-style: italic;
        }
        
        .btn-print {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .btn-print:hover {
            background: #4338ca;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.print()" class="btn-print">🖨️ Print</button>
            <a href="index.php?page=print" style="margin-left: 10px; padding: 12px 24px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; display: inline-block;">← Kembali</a>
        </div>
        
        <div class="header">
            <h1><?= htmlspecialchars($tableName) ?></h1>
            <div class="meta">
                Dicetak pada: <?= date('d F Y, H:i:s') ?> | 
                Total Data: <?= count($data) ?>
            </div>
        </div>
        
        <?php if (empty($data)): ?>
            <div class="no-data">
                <p>Tidak ada data dalam tabel ini.</p>
            </div>
        <?php else: ?>
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
                                        echo '<span style="color: #9ca3af; font-style: italic;">NULL</span>';
                                    } elseif (strlen($value) > 100) {
                                        echo htmlspecialchars(substr($value, 0, 100)) . '...';
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
        <?php endif; ?>
        
        <div class="footer">
            <p>Dicetak dari Sistem Stuarz | <?= date('d F Y, H:i:s') ?></p>
        </div>
    </div>
</body>
</html>

