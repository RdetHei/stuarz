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

    public function getAll() {
        $result = [];
        $sql = 'SELECT c.*, u.username as creator FROM classes c LEFT JOIN users u ON c.created_by = u.id ORDER BY c.id DESC';
        $res = $this->db->query($sql);
        if ($res) {
            while ($row = $res->fetch_assoc()) $result[] = $row;
            $res->free();
        }
        return $result;
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM classes WHERE id = ?');
        $stmt->bind_param('i', $id);
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
    public function addMember($class_id, $user_id, $role) {
        $stmt = $this->db->prepare('INSERT INTO class_members (class_id, user_id, role) VALUES (?, ?, ?)');
        $stmt->bind_param('iis', $class_id, $user_id, $role);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    public function removeMember($class_id, $user_id) {
        $stmt = $this->db->prepare('DELETE FROM class_members WHERE class_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $class_id, $user_id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
