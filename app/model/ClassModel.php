<?php
class ClassModel {
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    // Validasi kode unik dan field wajib
    public function validate($data, $isEdit = false, $id = null) {
        $errors = [];
        if (empty($data['name'])) $errors[] = 'Nama kelas wajib diisi.';
        if (empty($data['code'])) $errors[] = 'Kode kelas wajib diisi.';
        if (!$isEdit || ($isEdit && !empty($data['code']))) {
            $sql = 'SELECT id FROM classes WHERE code = ?' . ($isEdit ? ' AND id != ?' : '');
            $stmt = $this->db->prepare($sql);
            if ($isEdit) {
                $stmt->bind_param('si', $data['code'], $id);
            } else {
                $stmt->bind_param('s', $data['code']);
            }
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) $errors[] = 'Kode kelas sudah digunakan.';
            $stmt->close();
        }
        return $errors;
    }

    public function getAll($userId = null) {
        $result = [];
        // Base query with member count
        $sql = 'SELECT c.*, u.username as creator, 
                (SELECT COUNT(*) FROM class_members cm WHERE cm.class_id = c.id) as members_count
                FROM classes c 
                LEFT JOIN users u ON c.created_by = u.id';
        
        // If userId provided, filter to only classes user is a member of
        if ($userId !== null) {
            $sql .= ' INNER JOIN class_members cm ON c.id = cm.class_id WHERE cm.user_id = ?';
        }
        
        $sql .= ' ORDER BY c.id DESC';
        
        if ($userId !== null) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                // Get user's role in this class
                $roleStmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ?');
                $roleStmt->bind_param('ii', $row['id'], $userId);
                $roleStmt->execute();
                $roleRes = $roleStmt->get_result();
                $roleRow = $roleRes->fetch_assoc();
                $row['my_role'] = $roleRow['role'] ?? null;
                $roleStmt->close();
                $result[] = $row;
            }
            $stmt->close();
        } else {
            $res = $this->db->query($sql);
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $result[] = $row;
                }
                $res->free();
            }
        }
        return $result;
    }

    public function getById($id, $userId = null) {
        $sql = 'SELECT c.*, 
                (SELECT COUNT(*) FROM class_members cm WHERE cm.class_id = c.id) as members_count
                FROM classes c WHERE c.id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        
        // If userId provided, get user's role in this class
        if ($row && $userId !== null) {
            $roleStmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ?');
            $roleStmt->bind_param('ii', $id, $userId);
            $roleStmt->execute();
            $roleRes = $roleStmt->get_result();
            $roleRow = $roleRes->fetch_assoc();
            $row['my_role'] = $roleRow['role'] ?? null;
            $roleStmt->close();
        }
        
        return $row;
    }

    // Find class by its code
    public function findByCode($code) {
        $stmt = $this->db->prepare('SELECT * FROM classes WHERE code = ?');
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO classes (name, code, description, created_by) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('sssi', $data['name'], $data['code'], $data['description'], $data['created_by']);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare('UPDATE classes SET name=?, code=?, description=? WHERE id=?');
        $stmt->bind_param('sssi', $data['name'], $data['code'], $data['description'], $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete($id) {
        // Hapus anggota dulu
        $this->db->query('DELETE FROM class_members WHERE class_id = ' . intval($id));
        $stmt = $this->db->prepare('DELETE FROM classes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    // Get classes managed by user (admin/guru)
    public function getManagedClasses($userId) {
        $result = [];
        $sql = 'SELECT c.*, u.username as creator,
                (SELECT COUNT(*) FROM class_members cm WHERE cm.class_id = c.id) as members_count
                FROM classes c 
                LEFT JOIN users u ON c.created_by = u.id
                WHERE c.created_by = ? OR EXISTS (
                    SELECT 1 FROM class_members cm 
                    WHERE cm.class_id = c.id 
                    AND cm.user_id = ? 
                    AND cm.role IN ("teacher", "admin")
                )
                ORDER BY c.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            // Get user's role in this class
            $roleStmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ?');
            $roleStmt->bind_param('ii', $row['id'], $userId);
            $roleStmt->execute();
            $roleRes = $roleStmt->get_result();
            $roleRow = $roleRes->fetch_assoc();
            // If user is creator but not in class_members, set role as teacher
            if (!$roleRow && intval($row['created_by']) === intval($userId)) {
                $row['my_role'] = 'teacher';
            } else {
                $row['my_role'] = $roleRow['role'] ?? null;
            }
            $roleStmt->close();
            $result[] = $row;
        }
        $stmt->close();
        return $result;
    }

    // Anggota kelas
    public function getMembers($class_id) {
        $result = [];
        $sql = 'SELECT m.*, u.username, u.email FROM class_members m LEFT JOIN users u ON m.user_id = u.id WHERE m.class_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $class_id);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) $result[] = $row;
        $stmt->close();
        return $result;
    }
    public function addMember($classId, $userId, $role) {
        try {
            // Validate class exists
            $stmt = $this->db->prepare("SELECT id FROM classes WHERE id = ?");
            $stmt->bind_param("i", $classId);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $stmt->close();
                throw new \Exception("Class ID {$classId} not found");
            }
            $stmt->close();

            // Validate user exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows === 0) {
                $stmt->close();
                throw new \Exception("User ID {$userId} not found");
            }
            $stmt->close();

            // Check if member already exists
            $stmt = $this->db->prepare("SELECT id FROM class_members WHERE class_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $classId, $userId);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $stmt->close();
                throw new \Exception("User is already a member of this class");
            }
            $stmt->close();

            // Add member
            $stmt = $this->db->prepare("INSERT INTO class_members (class_id, user_id, role) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $classId, $userId, $role);
            $result = $stmt->execute();
            $stmt->close();

            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Failed to add member: " . $e->getMessage());
        }
    }
    public function removeMember($class_id, $user_id) {
        $stmt = $this->db->prepare('DELETE FROM class_members WHERE class_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $class_id, $user_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
