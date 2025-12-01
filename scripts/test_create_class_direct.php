<?php
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/model/ClassModel.php';

session_start();
$db = $config;
$model = new ClassModel($db);
$data = [
    'name' => 'Direct Test ' . rand(1000,9999),
    'code' => '',
    'description' => 'Direct test'
];
if (empty($data['code'])) {
    do {
        $data['code'] = substr(strtoupper(bin2hex(random_bytes(3))),0,6);
        $exists = $model->findByCode($data['code']);
    } while ($exists);
}
$data['created_by'] = 1;
try {
    $ok = $model->create($data);
    echo "create returned: " . ($ok ? 'true' : 'false') . "\n";
    $newId = $db->insert_id;
    echo "new class id: $newId\n";
    // try addMember
    try {
        $res = $model->addMember($newId, intval($data['created_by']), 'teacher');
        echo "addMember returned: " . ($res ? 'true' : 'false') . "\n";
    } catch (Exception $e) {
        echo "addMember Exception: " . $e->getMessage() . "\n";
    }
    // insert a schedule
    $stmt = $db->prepare("INSERT INTO schedule (`class`,`subject`,`teacher_id`,`class_id`,`day`,`start_time`,`end_time`) VALUES (?,?,?,?,?,?,?)");
    $room = '';
    $subject = 'TBD';
    $teacherId = intval($data['created_by']);
    $day = 'Senin';
    $start = '07:00:00';
    $end = '08:00:00';
    $stmt->bind_param('ssiiiss', $room, $subject, $teacherId, $newId, $day, $start, $end);
    $stmt->execute();
    echo "schedule insert ok: " . ($stmt->affected_rows > 0 ? 'yes' : 'no') . "\n";
    $stmt->close();
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
