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
        // Returns only classes user has joined (INNER JOIN)
        $sql = 'SELECT c.*, u.username as creator, (SELECT COUNT(*) FROM class_members cm2 WHERE cm2.class_id = c.id) as members_count, cm.role as member_role FROM classes c LEFT JOIN users u ON c.created_by = u.id INNER JOIN class_members cm ON c.id = cm.class_id';

        if ($userId !== null) {
            $sql .= ' AND cm.user_id = ?';
        }

        $sql .= ' ORDER BY c.id DESC';

        if ($userId !== null) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $row['is_joined'] = 1; // INNER JOIN ensures membership
                $row['my_role'] = $row['member_role'];
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

    /**
     * Get all classes with user's membership status (all classes + is_joined flag)
     * CRITICAL: Uses LEFT JOIN with AND cm.user_id = :userId to avoid false positives
     * 
     * @param int $userId User ID
     * @return array All classes with is_joined (0/1) and member_role (or null)
     */
    public function getAllClassesWithUserStatus($userId) {
        $result = [];
        $sql = "SELECT c.id, c.name, c.code, c.description, c.created_by, c.created_at, u.username as creator, u.avatar AS creator_avatar, (SELECT COUNT(*) FROM class_members cm2 WHERE cm2.class_id = c.id) as members_count, CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined, cm.role AS member_role, cm.joined_at FROM classes c LEFT JOIN users u ON c.created_by = u.id LEFT JOIN class_members cm ON cm.class_id = c.id AND cm.user_id = ? ORDER BY c.id DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        
        while ($row = $res->fetch_assoc()) {
            // Ensure proper types
            $row['is_joined'] = (int)$row['is_joined'];
            $row['member_role'] = $row['member_role'] ?? null;
            $row['my_role'] = $row['member_role']; // For backwards compatibility
            $result[] = $row;
        }
        $stmt->close();
        return $result;
    }

    public function getById($id, $userId = null) {
        $sql = 'SELECT c.*, (SELECT COUNT(*) FROM class_members cm WHERE cm.class_id = c.id) as members_count';
        if ($userId !== null) {
            $sql .= ', cm.role as member_role, CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END as is_joined';
        }
        $sql .= ', u.username AS creator, u.avatar AS creator_avatar, u.id AS creator_id, u.level AS creator_level';
        $sql .= ' FROM classes c LEFT JOIN users u ON u.id = c.created_by';

        if ($userId !== null) {
            $sql .= ' LEFT JOIN class_members cm ON cm.class_id = c.id AND cm.user_id = ?';
            $sql .= ' WHERE c.id = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ii', $userId, $id);
        } else {
            $sql .= ' WHERE c.id = ? LIMIT 1';
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $id);
        }

        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if ($row && $userId !== null) {
            $row['member_role'] = $row['member_role'] ?? null;
            $row['is_joined'] = (int)($row['is_joined'] ?? 0);
            $row['my_role'] = $row['member_role'];
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
        $sql = 'SELECT c.*, u.username as creator, (SELECT COUNT(*) FROM class_members cm WHERE cm.class_id = c.id) as members_count FROM classes c LEFT JOIN users u ON c.created_by = u.id WHERE c.created_by = ? OR EXISTS (SELECT 1 FROM class_members cm WHERE cm.class_id = c.id AND cm.user_id = ? AND cm.role IN ("guru","teacher", "admin")) ORDER BY c.id DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            // To avoid an extra per-row query, obtain membership via a quick
            // lookup using class_members where possible. Preserve backward
            // compatible key `my_role` while preferring `member_role`.
            $memberRole = null;
            $checkStmt = $this->db->prepare('SELECT role FROM class_members WHERE class_id = ? AND user_id = ?');
            $checkStmt->bind_param('ii', $row['id'], $userId);
            $checkStmt->execute();
            $checkRes = $checkStmt->get_result();
            $rRow = $checkRes->fetch_assoc();
            if ($rRow) {
                $memberRole = $rRow['role'];
            }
            $checkStmt->close();

            if (!$memberRole && intval($row['created_by']) === intval($userId)) {
                $row['member_role'] = 'teacher';
                $row['is_joined'] = 1;
                $row['my_role'] = 'teacher';
            } else {
                $row['member_role'] = $memberRole;
                $row['is_joined'] = $memberRole ? 1 : 0;
                $row['my_role'] = $memberRole ?? null;
            }
            $result[] = $row;
        }
        $stmt->close();
        return $result;
    }

    // Anggota kelas
    public function getMembers($class_id) {
        $result = [];
        $sql = 'SELECT m.*, u.username, u.email, u.avatar, u.level AS level FROM class_members m LEFT JOIN users u ON m.user_id = u.id WHERE m.class_id = ?';
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
