<?php
class NotificationsModel
{
    private $conn;
    private static $schemaVariant = null;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    private function detectSchema(): void
    {
        if (self::$schemaVariant !== null || !$this->conn) {
            return;
        }

        $query = mysqli_query($this->conn, "SHOW COLUMNS FROM notifications LIKE 'entity'");
        if ($query instanceof \mysqli_result && $query->num_rows > 0) {
            self::$schemaVariant = 'modern';
        } else {
            self::$schemaVariant = 'legacy';
        }
        if ($query instanceof \mysqli_result) {
            mysqli_free_result($query);
        }
    }

    public function create(array $data)
    {
        $this->detectSchema();
        if (!self::$schemaVariant) {
            return false;
        }

        $entityId = isset($data['entity_id']) ? (int)$data['entity_id'] : 0;
        $userId = isset($data['user_id']) ? (int)$data['user_id'] : 0;
        $message = $data['message'] ?? '';

        if (self::$schemaVariant === 'modern') {
            $sql = "INSERT INTO notifications (type, entity, entity_id, user_id, message, url) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) return false;
            $url = $data['url'] ?? '';
            mysqli_stmt_bind_param(
                $stmt,
                "ssiiss",
                $data['type'],
                $data['entity'],
                $entityId,
                $userId,
                $message,
                $url
            );
        } else {
            $entity = $data['entity'] ?? 'general';
            $eventType = $data['type'] ?? $entity;
            if ($message === '') {
                $message = ucfirst($entity) . ' ' . strtolower($eventType);
            }
            $sql = "INSERT INTO notifications (user_id, type, reference_id, message, created_at) VALUES (?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) return false;
            mysqli_stmt_bind_param(
                $stmt,
                "isis",
                $userId,
                $entity,
                $entityId,
                $message
            );
        }

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    private function normalizeRow(array $row): array
    {
        if (self::$schemaVariant === 'modern') {
            $row['entity'] = $row['entity'] ?? ($row['type'] ?? 'general');
            $row['entity_id'] = $row['entity_id'] ?? ($row['reference_id'] ?? null);
            return $row;
        }

        // legacy columns: user_id, type (entity), reference_id, message, is_read, created_at
        $row['entity'] = $row['type'] ?? 'general';
        $row['entity_id'] = $row['reference_id'] ?? null;

        // reuse message/created_at as is. Provide synthetic event type
        $row['event_type'] = $row['event_type'] ?? 'info';
        if (!isset($row['type']) || in_array($row['type'], ['announcement', 'task', 'message'], true)) {
            $row['type'] = 'info';
        }

        return $row;
    }

    public function getRecent($limit = 20)
    {
        $this->detectSchema();
        $limit = (int)$limit;
        $rows = [];
        $sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return $rows;
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $rows[] = $this->normalizeRow($r);
            }
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    /**
     * Get notifications for a specific user (most recent first).
     */
    public function getForUser(int $userId, int $limit = 50)
    {
        $this->detectSchema();
        $rows = [];
        $limit = (int)$limit;

        // Basic fetch
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return $rows;
        mysqli_stmt_bind_param($stmt, "ii", $userId, $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $ids = [];
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $r = $this->normalizeRow($r);
                $rows[] = $r;
                $ids[] = intval($r['id'] ?? 0);
            }
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);

        // If notifications table doesn't have is_read, consult notification_reads
        if (!$this->columnExists('is_read') && !empty($ids)) {
            $this->ensureReadTrackingTable();
            $ids = array_filter(array_map('intval', $ids));
            if (!empty($ids)) {
                $in = implode(',', $ids);
                $sql2 = "SELECT notification_id FROM notification_reads WHERE user_id = ? AND notification_id IN ($in)";
                $stmt2 = mysqli_prepare($this->conn, $sql2);
                if ($stmt2) {
                    mysqli_stmt_bind_param($stmt2, 'i', $userId);
                    mysqli_stmt_execute($stmt2);
                    $res2 = mysqli_stmt_get_result($stmt2);
                    $readMap = [];
                    if ($res2) {
                        while ($rr = mysqli_fetch_assoc($res2)) {
                            $readMap[intval($rr['notification_id'])] = true;
                        }
                        mysqli_free_result($res2);
                    }
                    mysqli_stmt_close($stmt2);
                    // annotate rows
                    foreach ($rows as &$r) {
                        $nid = intval($r['id'] ?? 0);
                        $r['is_read'] = !empty($readMap[$nid]) ? 1 : 0;
                    }
                    unset($r);
                }
            }
        }

        return $rows;
    }

    public function getAll($limit = 100)
    {
        return $this->getRecent($limit);
    }

    private function columnExists(string $column): bool
    {
        $res = mysqli_query($this->conn, "SHOW COLUMNS FROM notifications LIKE '" . mysqli_real_escape_string($this->conn, $column) . "'");
        if ($res instanceof \mysqli_result) {
            $exists = $res->num_rows > 0;
            mysqli_free_result($res);
            return $exists;
        }
        return false;
    }

    /**
     * Ensure a per-user read tracking table exists for modern schema where
     * notifications table does not include an `is_read` column.
     */
    private function ensureReadTrackingTable(): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS notification_reads (
            id INT NOT NULL AUTO_INCREMENT,
            notification_id INT NOT NULL,
            user_id INT NOT NULL,
            read_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uniq_notification_user (notification_id, user_id),
            KEY idx_user (user_id),
            KEY idx_notification (notification_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        @mysqli_query($this->conn, $sql);
    }

    public function setReadStatus(int $id, bool $read = true): bool
    {
        // If notifications table supports is_read, update it directly
        if ($this->columnExists('is_read')) {
            $val = $read ? 1 : 0;
            $stmt = mysqli_prepare($this->conn, "UPDATE notifications SET is_read = ? WHERE id = ?");
            if (!$stmt) return false;
            mysqli_stmt_bind_param($stmt, "ii", $val, $id);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool)$ok;
        }

        // Fallback: use per-user read tracking table
        $this->ensureReadTrackingTable();
        // Need current user to record per-user reads
        $userId = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
        if ($userId === null) return false;

        if ($read) {
            // insert if not exists
            $stmt = mysqli_prepare($this->conn, "INSERT INTO notification_reads (notification_id, user_id, read_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE read_at = NOW()");
            if (!$stmt) return false;
            mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool)$ok;
        } else {
            // delete record
            $stmt = mysqli_prepare($this->conn, "DELETE FROM notification_reads WHERE notification_id = ? AND user_id = ?");
            if (!$stmt) return false;
            mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool)$ok;
        }
    }

    public function markAllRead(?int $userId = null): bool
    {
        // If table has is_read, update directly
        if ($this->columnExists('is_read')) {
            if ($userId !== null) {
                $stmt = mysqli_prepare($this->conn, "UPDATE notifications SET is_read = 1 WHERE user_id = ?");
                if (!$stmt) return false;
                mysqli_stmt_bind_param($stmt, "i", $userId);
            } else {
                $stmt = mysqli_prepare($this->conn, "UPDATE notifications SET is_read = 1");
                if (!$stmt) return false;
            }
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            return (bool)$ok;
        }

        // Fallback: insert entries into notification_reads for all notifications for this user
        if ($userId === null) return false;
        $this->ensureReadTrackingTable();
        // fetch notification ids for this user
        $ids = [];
        $sql = "SELECT id FROM notifications WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $ids[] = intval($r['id']);
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
        if (empty($ids)) return true;
        // batch insert using prepared statement
        $okAll = true;
        $ins = mysqli_prepare($this->conn, "INSERT INTO notification_reads (notification_id, user_id, read_at) VALUES (?, ?, NOW()) ON DUPLICATE KEY UPDATE read_at = NOW()");
        if (!$ins) return false;
        foreach ($ids as $nid) {
            mysqli_stmt_bind_param($ins, 'ii', $nid, $userId);
            $okAll = $okAll && mysqli_stmt_execute($ins);
        }
        mysqli_stmt_close($ins);
        return (bool)$okAll;
    }

    public function deleteById(int $id): bool
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM notifications WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "i", $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function countUnread(?int $userId = null): int
    {
        // If notifications table supports is_read, use it
        if ($this->columnExists('is_read')) {
            if ($userId !== null) {
                $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS c FROM notifications WHERE is_read = 0 AND user_id = ?");
                if (!$stmt) return 0;
                mysqli_stmt_bind_param($stmt, "i", $userId);
            } else {
                $stmt = mysqli_prepare($this->conn, "SELECT COUNT(*) AS c FROM notifications WHERE is_read = 0");
                if (!$stmt) return 0;
            }
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $count = 0;
            if ($res) {
                $row = mysqli_fetch_assoc($res);
                $count = (int)($row['c'] ?? 0);
                mysqli_free_result($res);
            }
            mysqli_stmt_close($stmt);
            return $count;
        }

        // Fallback: count notifications for user minus those present in notification_reads
        if ($userId === null) {
            // count all notifications not tracked as read by any user is ambiguous; return total notifications
            $res = mysqli_query($this->conn, "SELECT COUNT(*) AS c FROM notifications");
            if ($res && $res instanceof mysqli_result) {
                $row = mysqli_fetch_assoc($res);
                mysqli_free_result($res);
                return (int)($row['c'] ?? 0);
            }
            return 0;
        }
        // count notifications for user
        $stmt = mysqli_prepare($this->conn, "SELECT id FROM notifications WHERE user_id = ?");
        if (!$stmt) return 0;
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $ids = [];
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $ids[] = intval($r['id']);
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
        if (empty($ids)) return 0;
        $this->ensureReadTrackingTable();
        $in = implode(',', array_map('intval', $ids));
        $sql2 = "SELECT COUNT(*) AS c FROM notifications n LEFT JOIN notification_reads nr ON nr.notification_id = n.id AND nr.user_id = ? WHERE n.user_id = ? AND (nr.notification_id IS NULL)";
        $stmt2 = mysqli_prepare($this->conn, $sql2);
        if (!$stmt2) return 0;
        mysqli_stmt_bind_param($stmt2, 'ii', $userId, $userId);
        mysqli_stmt_execute($stmt2);
        $res2 = mysqli_stmt_get_result($stmt2);
        $count = 0;
        if ($res2) {
            $row = mysqli_fetch_assoc($res2);
            $count = (int)($row['c'] ?? 0);
            mysqli_free_result($res2);
        }
        mysqli_stmt_close($stmt2);
        return $count;
    }

public function clearAll(): bool
{
    $sql = "TRUNCATE TABLE notifications";
    return (bool) mysqli_query($this->conn, $sql);
}

public function clearByUser(int $userId): bool
{
    $stmt = mysqli_prepare($this->conn, "DELETE FROM notifications WHERE user_id = ?");
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "i", $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return (bool)$ok;
}



    // ------------------------
    // Automatic source scanning
    // ------------------------
    private function tableHasColumn($table, $column)
    {
        $t = mysqli_real_escape_string($this->conn, $table);
        $c = mysqli_real_escape_string($this->conn, $column);
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '" . $t . "' AND column_name = '" . $c . "'";
        $res = mysqli_query($this->conn, $sql);
        if ($res && $res instanceof mysqli_result) {
            $row = mysqli_fetch_assoc($res);
            mysqli_free_result($res);
            return (int)($row['cnt'] ?? 0) > 0;
        }
        return false;
    }

    private function notificationExists($entity, $entityId)
    {
        $this->detectSchema();
        if (self::$schemaVariant === 'modern') {
            $stmt = mysqli_prepare($this->conn, "SELECT id FROM notifications WHERE entity = ? AND entity_id = ? LIMIT 1");
            if (!$stmt) return false;
            mysqli_stmt_bind_param($stmt, 'si', $entity, $entityId);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $exists = $res && mysqli_fetch_assoc($res) ? true : false;
            if ($res && $res instanceof mysqli_result) mysqli_free_result($res);
            mysqli_stmt_close($stmt);
            return $exists;
        }
        // legacy
        $stmt = mysqli_prepare($this->conn, "SELECT id FROM notifications WHERE type = ? AND reference_id = ? LIMIT 1");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, 'si', $entity, $entityId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $exists = $res && mysqli_fetch_assoc($res) ? true : false;
        if ($res && $res instanceof mysqli_result) mysqli_free_result($res);
        mysqli_stmt_close($stmt);
        return $exists;
    }

    /**
     * Scan common source tables for new/updated rows and create notifications.
     * This is a best-effort generator used by controllers (can be run on page load or cron).
     * @param int $minutes lookback window in minutes
     * @return int number of notifications created
     */
    public function generateFromSources($minutes = 5)
    {
        $created = 0;
        $since = date('Y-m-d H:i:s', strtotime("-" . intval($minutes) . " minutes"));

        $tables = ['announcements', 'task_submissions', 'tasks_completed', 'attendance', 'schedule', 'news'];

        foreach ($tables as $table) {
            // Find an appropriate timestamp column
            $candidates = ['updated_at', 'modified_at', 'created_at', 'submitted_at', 'date', 'deadline'];
            $tsCol = null;
            foreach ($candidates as $cand) {
                if ($this->tableHasColumn($table, $cand)) { $tsCol = $cand; break; }
            }
            if ($tsCol === null) continue;

            // Build query safely
            $t = mysqli_real_escape_string($this->conn, $table);
            $sql = "SELECT * FROM `" . $t . "` WHERE `" . $tsCol . "` >= ? ORDER BY `" . $tsCol . "` DESC LIMIT 50";
            $stmt = mysqli_prepare($this->conn, $sql);
            if (!$stmt) continue;
            mysqli_stmt_bind_param($stmt, 's', $since);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            if (!$res) { mysqli_stmt_close($stmt); continue; }

            while ($row = mysqli_fetch_assoc($res)) {
                $id = isset($row['id']) ? intval($row['id']) : 0;
                if ($id <= 0) continue;
                if ($this->notificationExists($table, $id)) continue;

                // Determine target user if possible
                $targetUser = 0;
                if (isset($row['user_id']) && intval($row['user_id']) > 0) $targetUser = intval($row['user_id']);
                if (isset($row['created_by']) && intval($row['created_by']) > 0) $targetUser = intval($row['created_by']);

                // Construct a friendly message where possible
                $title = $row['title'] ?? $row['name'] ?? null;
                if ($title) {
                    $message = ucfirst($table) . ': ' . $title;
                } else {
                    $message = ucfirst($table) . ' #' . $id . ' updated';
                }

                $data = [
                    'type' => $table,
                    'entity' => $table,
                    'entity_id' => $id,
                    'user_id' => $targetUser,
                    'message' => $message,
                    'url' => ''
                ];

                if ($this->create($data)) {
                    $created++;
                }
            }
            mysqli_free_result($res);
            mysqli_stmt_close($stmt);
        }

        if ($created > 0) {
            error_log("NotificationsModel::generateFromSources created {$created} notifications (lookback {$minutes}m)");
        }
        return $created;
    }

}


