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
        $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return $rows;
        mysqli_stmt_bind_param($stmt, "ii", $userId, $limit);
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

    public function setReadStatus(int $id, bool $read = true): bool
    {
        if (!$this->columnExists('is_read')) {
            return false;
        }
        $val = $read ? 1 : 0;
        $stmt = mysqli_prepare($this->conn, "UPDATE notifications SET is_read = ? WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "ii", $val, $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function markAllRead(?int $userId = null): bool
    {
        if (!$this->columnExists('is_read')) {
            return false;
        }
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
        if (!$this->columnExists('is_read')) {
            return 0;
        }
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



}
