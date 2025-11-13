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
                u.avatar AS creator_avatar
            FROM announcements AS a
            LEFT JOIN users AS u ON a.created_by = u.id
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
    $sql = "SELECT a.*, u.username, u.avatar
        FROM announcements a
        LEFT JOIN users u ON a.created_by = u.id
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
        // The announcements table no longer contains a class_id column in this schema.
        // Keep the method for compatibility but return an empty result to avoid SQL errors.
        return [];
    }

    public function getById($id)
    {
        $sql = "SELECT 
            a.*,
            u.username as creator,
            u.avatar as creator_avatar
        FROM announcements a
        LEFT JOIN users u ON a.created_by = u.id
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
        $sql = "INSERT INTO announcements (created_by, title, content, photo, created_at)
            VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->db->prepare($sql);

        $created_by = intval($data['created_by'] ?? 0);
        $title = $data['title'] ?? '';
        $content = $data['content'] ?? '';
        $photo = $data['photo'] ?? '';

        $stmt->bind_param('isss', $created_by, $title, $content, $photo);

        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE announcements SET 
                    title = ?,
                    content = ?,
                    photo = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $title = $data['title'] ?? '';
        $content = $data['content'] ?? '';
        $photo = $data['photo'] ?? '';
        $stmt->bind_param('sssi', $title, $content, $photo, $id);
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
