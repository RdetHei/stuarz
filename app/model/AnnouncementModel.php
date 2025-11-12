<?php
class AnnouncementModel
{
    private $db;

    public function __construct($db)
    {
        // Expecting mysqli connection
        $this->db = $db;
    }

    public function getAll()
    {
        $result = [];
        $sql = "
            SELECT
                a.*,
                u.username AS creator,
                u.username AS username,
                c.name AS class_name
            FROM announcements AS a
            LEFT JOIN users AS u ON a.created_by = u.id
            LEFT JOIN classes AS c ON a.class_id = c.id
            ORDER BY a.created_at DESC
        ";
        $res = $this->db->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $result[] = $row;
            }
            $res->free();
        }
        return $result;
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT a.*, u.username, u.avatar, c.name AS class_name
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                LEFT JOIN classes AS c ON a.class_id = c.id
                WHERE a.created_by = ?
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function getByClassId($classId)
    {
        // kept for compatibility but if class_id doesn't exist this should return empty
        // check first that column exists would be better; here we attempt safe query
        $sql = "SELECT a.*, u.username, u.avatar, c.name AS class_name
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                LEFT JOIN classes AS c ON a.class_id = c.id
                WHERE a.class_id = ?
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        if (! $stmt) return []; // if column missing, prepared statement will fail
        $stmt->bind_param('i', $classId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }

    public function getById($id)
    {
        $sql = "SELECT 
            a.*,
            u.username as creator,
            u.username as username,
            c.name AS class_name
        FROM announcements a
        LEFT JOIN users u ON a.created_by = u.id
        LEFT JOIN classes c ON a.class_id = c.id
        WHERE a.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;

        $stmt->close();
        return $row;
    }

    public function create($data)
    {
        $sql = "INSERT INTO announcements (created_by, class_id, title, content, photo, created_at)
            VALUES (?, NULLIF(?, 0), ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);

        $created_by = intval($data['created_by'] ?? 0);
        $class_id = intval($data['class_id'] ?? 0);
        $title = $data['title'] ?? '';
        $content = $data['content'] ?? '';
        $photo = $data['photo'] ?? '';

        $stmt->bind_param('iisss', $created_by, $class_id, $title, $content, $photo);

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE announcements SET 
                    title = ?,
                    content = ?,
                    photo = ?,
                    class_id = NULLIF(?, 0)
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $title = $data['title'] ?? '';
        $content = $data['content'] ?? '';
        $photo = $data['photo'] ?? '';
        $class_id = intval($data['class_id'] ?? 0);
        $stmt->bind_param('sssii', $title, $content, $photo, $class_id, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM announcements WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getCountByUserId($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM announcements WHERE created_by = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['count' => 0];
        $stmt->close();
        return (int)$row['count'];
    }

    public function getCommentsByAnnouncementId(int $announcementId): array
    {
        $sql = "SELECT ac.*, u.username
                FROM announcement_comments ac
                LEFT JOIN users u ON ac.user_id = u.id
                WHERE ac.announcement_id = ?
                ORDER BY ac.created_at ASC";
        $stmt = $this->db->prepare($sql);
        if (! $stmt) {
            return [];
        }
        $stmt->bind_param('i', $announcementId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();
        return $rows;
    }
}
