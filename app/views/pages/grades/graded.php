<?php
// Standalone graded submissions view
// Safe, minimal, uses mysqli connection from app config
// Ensure DB config is loaded (allow inclusion from index.php)
if (!isset($config) || !$config) {
    require_once __DIR__ . '/../../../config/config.php';
}

// Build base query and allow optional filters via GET
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

// Optional filters from the existing filters UI
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

// Prepare and execute safely
$stmt = mysqli_prepare($config, $sql);
if ($stmt) {
    if (!empty($params)) {
        // Bind parameters dynamically
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
?>

<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">Daftar Nilai Tugas (Sudah Dinilai)</h2>

    <?php if (empty($grades)): ?>
        <div class="p-6 bg-gray-50 rounded border border-gray-200 text-gray-600">Belum ada nilai tugas yang sudah dinilai.</div>
    <?php else: ?>
    <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-sm">
                <th class="p-3 border">Tugas</th>
                <th class="p-3 border">Mata Pelajaran</th>
                <th class="p-3 border">Kelas</th>
                <th class="p-3 border">Guru</th>
                <th class="p-3 border">Nilai</th>
                <th class="p-3 border">Tanggal Dinilai</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($grades as $g): ?>
            <tr class="hover:bg-gray-50">
                <td class="p-3 border"><?= htmlspecialchars($g['task_title'] ?? '-') ?></td>
                <td class="p-3 border"><?= htmlspecialchars($g['subject_name'] ?? '-') ?></td>
                <td class="p-3 border"><?= htmlspecialchars($g['class_name'] ?? '-') ?></td>
                <td class="p-3 border"><?= htmlspecialchars($g['teacher_name'] ?? '-') ?></td>
                <td class="p-3 border font-semibold"><?= htmlspecialchars($g['score'] ?? '-') ?></td>
                <td class="p-3 border"><?= htmlspecialchars($g['graded_at'] ? date('Y-m-d H:i', strtotime($g['graded_at'])) : '-') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</div>
