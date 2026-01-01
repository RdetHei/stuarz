<?php



if (!isset($config) || !$config) {
    require_once __DIR__ . '/../../../config/config.php';
}

$grades = [];
$baseSql = "SELECT 
    ts.id AS submission_id,
    u.name AS student_name,
    s.name AS subject_name,
    tc.title AS task_title,
    ts.grade AS score,
    ts.submitted_at AS graded_at,
    c.name AS class_name,
    t.name AS teacher_name
FROM task_submissions ts
JOIN users u ON u.id = ts.user_id
JOIN classes c ON c.id = ts.class_id
LEFT JOIN tasks_completed tc ON tc.id = ts.task_id
LEFT JOIN subjects s ON s.id = tc.subject_id
LEFT JOIN teachers t ON t.id = s.teacher_id";

$where = " WHERE ts.status = 'graded'";
$params = [];
$types = '';

if (!empty($_GET['subject_id'])) {
    $where .= " AND tc.subject_id = ?";
    $params[] = (int) $_GET['subject_id'];
    $types .= 'i';
}
if (!empty($_GET['class_id'])) {
    $where .= " AND c.id = ?";
    $params[] = (int) $_GET['class_id'];
    $types .= 'i';
}

$order = " ORDER BY ts.submitted_at DESC";

$sql = $baseSql . $where . $order;

$stmt = mysqli_prepare($config, $sql);
if ($stmt) {
    if (!empty($params)) {

        $bind_names[] = $types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array(array($stmt, 'bind_param'), $bind_names);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $grades[] = $row;
        }
        mysqli_free_result($res);
    }
    mysqli_stmt_close($stmt);
} else {
    error_log('Grades prepare failed: ' . mysqli_error($config));
}

function getGradeBadge($score) {
    if ($score >= 90) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">A</span>';
    } elseif ($score >= 75) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-500/20 text-blue-400 border border-blue-500/30">B</span>';
    } elseif ($score >= 60) {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">C</span>';
    } else {
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-500/20 text-red-400 border border-red-500/30">D</span>';
    }
}
?>

<div class="bg-gray-800 border border-gray-700 rounded-lg overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-700">
        <h2 class="text-lg font-semibold text-gray-100">Daftar Nilai Tugas (Sudah Dinilai)</h2>
        <p class="text-sm text-gray-400 mt-1">Total: <?= count($grades) ?> tugas telah dinilai</p>
    </div>

    <?php if (empty($grades)): ?>
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-700 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-gray-400 text-sm">Belum ada nilai tugas yang sudah dinilai.</p>
        </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-900 text-xs font-medium text-gray-400 uppercase tracking-wider">
                    <th class="px-4 py-3 text-left border-b border-gray-700">Tugas</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Mata Pelajaran</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Kelas</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Guru</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Nilai</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Grade</th>
                    <th class="px-4 py-3 text-left border-b border-gray-700">Tanggal Dinilai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
            <?php foreach($grades as $g): 
                $score = $g['score'] ?? 0;
            ?>
                <tr class="hover:bg-gray-700/50 transition-colors">
                    <td class="px-4 py-3 text-sm">
                        <div class="text-gray-200 font-medium"><?= htmlspecialchars($g['task_title'] ?? '-') ?></div>
                        <div class="text-gray-500 text-xs mt-0.5"><?= htmlspecialchars($g['student_name'] ?? '-') ?></div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-300">
                        <?= htmlspecialchars($g['subject_name'] ?? '-') ?>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                            <?= htmlspecialchars($g['class_name'] ?? '-') ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">
                        <?= htmlspecialchars($g['teacher_name'] ?? '-') ?>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <span class="text-gray-100 font-semibold text-base"><?= htmlspecialchars($score) ?></span>
                        <span class="text-gray-500 text-xs">/100</span>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <?= getGradeBadge($score) ?>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">
                        <?php if ($g['graded_at']): ?>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span><?= date('d M Y', strtotime($g['graded_at'])) ?></span>
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5 ml-5">
                                <?= date('H:i', strtotime($g['graded_at'])) ?>
                            </div>
                        <?php else: ?>
                            <span class="text-gray-500">-</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>