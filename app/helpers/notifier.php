<?php

function notify_event($config, $eventType, $entity, $entity_id = null, $user_id = null, $message = '', $url = null) {
    static $schemaVariant = null;

    if (!$config) {
        return false;
    }

    if ($schemaVariant === null) {
        $check = mysqli_query($config, "SHOW COLUMNS FROM notifications LIKE 'entity'");
        if ($check instanceof \mysqli_result && $check->num_rows > 0) {
            $schemaVariant = 'modern';
        } else {
            $schemaVariant = 'legacy';
        }
        if ($check instanceof \mysqli_result) {
            mysqli_free_result($check);
        }
    }

    $eid = $entity_id === null ? 0 : intval($entity_id);
    $uid = $user_id === null ? 0 : intval($user_id);
    $msg = $message ?? '';
    $eventType = $eventType ?: 'info';
    $entity = $entity ?: 'general';

    if ($schemaVariant === 'modern') {
        $sql = "INSERT INTO notifications (type, entity, entity_id, user_id, message, url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($config, $sql);
        if (!$stmt) {
            return false;
        }
        $uurl = $url ?? '';
        mysqli_stmt_bind_param($stmt, 'ssiiss', $eventType, $entity, $eid, $uid, $msg, $uurl);
    } else {

        $legacyType = $entity; // store entity name (e.g., announcement, task)
        if ($legacyType === 'general') {
            $legacyType = $eventType;
        }
        if ($msg === '') {
            $msg = ucfirst($entity) . ' ' . strtolower($eventType);
        }
        $sql = "INSERT INTO notifications (user_id, type, reference_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($config, $sql);
        if (!$stmt) {
            return false;
        }
        mysqli_stmt_bind_param($stmt, 'isis', $uid, $legacyType, $eid, $msg);
    }

    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return (bool)$ok;
}
