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
        $sql = "SELECT 
            a.*,
            c.name as class_name,
            u.username as creator
        FROM announcements a
        LEFT JOIN classes c ON a.class_id = c.id
        LEFT JOIN users u ON a.created_by = u.id
        ORDER BY a.created_at DESC";

        $result = [];
        $query = $this->db->query($sql);
        
        if ($query) {
            while ($row = $query->fetch_assoc()) {
                $result[] = $row;
            }
            $query->free();
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
        $sql = "SELECT 
            a.*,
            c.name as class_name,
            u.username as creator
        FROM announcements a
        LEFT JOIN classes c ON a.class_id = c.id
        LEFT JOIN users u ON a.created_by = u.id
        WHERE a.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        $stmt->close();
        return $row;
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO announcements 
                    (created_by, title, content, class_id, photo, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "issss", 
                $data['created_by'],
                $data['title'],
                $data['content'],
                $data['class_id'],
                $data['photo']
            );
            
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Failed to create announcement: " . $e->getMessage());
        }
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
