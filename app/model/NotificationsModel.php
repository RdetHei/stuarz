<?php
class NotificationsModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO notifications (type, entity, entity_id, user_id, message, url) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;
        $entityId = isset($data['entity_id']) ? $data['entity_id'] : null;
        $userId = isset($data['user_id']) ? $data['user_id'] : null;
        mysqli_stmt_bind_param($stmt, "ssiiis",
            $data['type'],
            $data['entity'],
            $entityId,
            $userId,
            $data['message'],
            $data['url']
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function getRecent($limit = 20)
    {
        $limit = (int)$limit;
        $rows = [];
        $sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return $rows;
        mysqli_stmt_bind_param($stmt, "i", $limit);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function getAll($limit = 100)
    {
        return $this->getRecent($limit);
    }
}
