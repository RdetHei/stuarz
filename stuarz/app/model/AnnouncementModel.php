<?php
class AnnouncementModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; // PDO connection
    }

    public function getAll()
    {
        $sql = "SELECT a.*, u.username, u.avatar 
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                ORDER BY a.created_at DESC";
        $result = $this->db->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT a.*, u.username, u.avatar
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                WHERE a.created_by = ?
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByClassId($classId)
    {
        $sql = "SELECT a.*, u.username, u.avatar
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                WHERE a.class_id = ?
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT a.*, u.username, u.avatar
                FROM announcements a
                LEFT JOIN users u ON a.created_by = u.id
                WHERE a.id = ?
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO announcements (created_by, title, content, class_id, photo, created_at) 
                VALUES (:created_by, :title, :content, :class_id, :photo, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':created_by' => $data['user_id'],
            ':title'      => $data['title'],
            ':content'    => $data['content'],
            ':class_id'   => $data['class_id'],
            ':photo'      => $data['photo']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE announcements SET 
                    title = :title,
                    content = :content,
                    class_id = :class_id,
                    photo = :photo,
                    updated_at = NOW()
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title'    => $data['title'],
            ':content'  => $data['content'],
            ':class_id' => $data['class_id'],
            ':photo'    => $data['photo'],
            ':id'       => $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM announcements WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getCountByUserId($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM announcements WHERE created_by = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['count'];
    }
}
