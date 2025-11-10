<?php
// Simple helper to insert notifications
function notify_event($config, $type, $entity, $entity_id = null, $user_id = null, $message = '', $url = null) {
    $sql = "INSERT INTO notifications (type, entity, entity_id, user_id, message, url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($config, $sql);
    if (!$stmt) return false;

    // normalize values and types for bind_param
    $eid = $entity_id === null ? 0 : intval($entity_id);
    $uid = $user_id === null ? 0 : intval($user_id);
    $msg = $message ?? '';
    $uurl = $url ?? '';

    // types: s (type), s (entity), i (entity_id), i (user_id), s (message), s (url)
    mysqli_stmt_bind_param($stmt, 'ssiiss', $type, $entity, $eid, $uid, $msg, $uurl);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return (bool)$ok;
}
