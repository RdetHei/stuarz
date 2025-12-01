<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Semua Tabel - Stuarz</title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none !important; }
            .table-section { page-break-after: always; }
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
            font-size: 32px;
        }
        
        .header .meta {
            color: #6b7280;
            font-size: 14px;
        }
        
        .table-section {
            margin-bottom: 50px;
            page-break-inside: avoid;
        }
        
        .table-header {
            background: #4f46e5;
            color: white;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        
        .table-header h2 {
            margin: 0;
            font-size: 20px;
        }
        
        .table-header .meta {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }
        
        th {
            background: #4f46e5;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #4338ca;
        }
        
        td {
            padding: 8px;
            border: 1px solid #e5e7eb;
        }
        
        tr:nth-child(even) {
            background: #f9fafb;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
            background: #f9fafb;
            border-radius: 6px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
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
        
        .toc {
            background: #f9fafb;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            border-left: 4px solid #4f46e5;
        }
        
        .toc h3 {
            margin-top: 0;
            color: #1f2937;
        }
        
        .toc ul {
            list-style: none;
            padding: 0;
        }
        
        .toc li {
            padding: 5px 0;
        }
        
        .toc a {
            color: #4f46e5;
            text-decoration: none;
        }
        
        .toc a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.print()" class="btn-print">🖨️ Print Semua</button>
            <a href="index.php?page=print" style="margin-left: 10px; padding: 12px 24px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; display: inline-block;">← Kembali</a>
        </div>
        
        <div class="header">
            <h1>Laporan Semua Data</h1>
            <div class="meta">
                Dicetak pada: <?= date('d F Y, H:i:s') ?> | 
                Total Tabel: <?= count($allData) ?>
            </div>
        </div>
        
        <div class="toc no-print">
            <h3>Daftar Isi</h3>
            <ul>
                <?php foreach (array_keys($allData) as $idx => $table): ?>
                    <li>
                        <a href="#table-<?= $idx ?>">
                            <?= ucfirst(str_replace('_', ' ', $table)) ?> 
                            (<?= count($allData[$table]['data']) ?> data)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <?php foreach ($allData as $idx => $tableData): ?>
            <?php 
            $table = array_keys($allData)[$idx];
            $tableName = ucfirst(str_replace('_', ' ', $table));
            $columns = $tableData['columns'];
            $data = $tableData['data'];
            ?>
            <div id="table-<?= $idx ?>" class="table-section">
                <div class="table-header">
                    <h2><?= htmlspecialchars($tableName) ?></h2>
                    <div class="meta">Tabel: <?= htmlspecialchars($table) ?> | Total: <?= count($data) ?> data</div>
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
                                            } elseif (strlen($value) > 80) {
                                                echo htmlspecialchars(substr($value, 0, 80)) . '...';
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
            </div>
        <?php endforeach; ?>
        
        <div class="footer">
            <p>Dicetak dari Sistem Stuarz | <?= date('d F Y, H:i:s') ?></p>
            <p>Total Tabel: <?= count($allData) ?> | Total Halaman: <span id="page-count"></span></p>
        </div>
    </div>
    
    <script>
        // Count pages after print
        window.addEventListener('afterprint', function() {
            // This is approximate, actual page count depends on printer settings
        });
    </script>
</body>
</html>
















