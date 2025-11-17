<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print <?= htmlspecialchars($tableName) ?> - Stuarz</title>

    <style>
        /* Gaya Print: Mirip Word */
        @media print {
            body { 
                margin: 0; 
                padding: 0;
                background: white;
            }
            .no-print { display: none !important; }
            @page {
                size: A4;
                margin: 2cm;
            }
        }

        /* Umum: tampilan seperti Word */
        body {
            font-family: Calibri, Arial, sans-serif;
            background: #ffffff;
            color: #000;
            padding: 30px;
            line-height: 1.4;
        }

        title{
            display: none;
        }

        .print-container {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header Word-style */
        .header {
            border-bottom: 2px solid #222;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 26px;
            margin: 0;
            font-weight: bold;
        }

        .header .meta {
            font-size: 12px;
            color: #555;
            margin-top: 5px;
        }

        /* Tombol untuk browser */
        .btn-print {
            background: #2563eb;
            color: white;
            padding: 10px 22px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-print:hover {
            background: #1d4ed8;
        }

        /* Tabel seperti Excel */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #c2c2c2;
            padding: 8px 6px;
            vertical-align: top;
        }

        th {
            background: #e5f0ff; /* biru muda ala Excel */
            color: #000;
            font-weight: bold;
            text-align: left;
        }

        tr:nth-child(even) {
            background: #f8f8f8; /* zebra sangat lembut */
        }

        .no-data {
            text-align: center;
            padding: 40px;
            font-style: italic;
            color: #777;
        }

        /* Footer ala dokumen Word */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            font-size: 11px;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="print-container">

        <!-- Tombol aksi -->
        <div class="no-print" style="margin-bottom: 20px;">
            <button onclick="window.print()" class="btn-print">Print Dokumen</button>
            <a href="index.php?page=print" style="background:#6b7280; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">Kembali</a>
        </div>

        <!-- Header -->
        <div class="header">
            <h1><?= htmlspecialchars($tableName) ?></h1>
            <div class="meta">
                Dicetak pada: <?= date('d F Y, H:i:s') ?> • Total Data: <?= count($data) ?>
            </div>
        </div>

        <!-- Isi -->
        <?php if (empty($data)): ?>
            <div class="no-data">Tidak ada data dalam tabel ini.</div>
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
                                            echo '<span style="color:#777;font-style:italic;">NULL</span>';
                                        } elseif (strlen($value) > 150) {
                                            echo htmlspecialchars(substr($value, 0, 150)) . '...';
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

        <!-- Footer -->
        <div class="footer">
            Dicetak dari Sistem Stuarz • <?= date('d F Y, H:i:s') ?>
        </div>

    </div>
</body>
</html>