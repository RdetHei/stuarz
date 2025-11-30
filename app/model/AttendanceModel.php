<?php

class AttendanceModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getClasses() {
        $sql = "SELECT id, name FROM classes ORDER BY name";
        $res = $this->db->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAttendanceById($id) {
        $stmt = $this->db->prepare("SELECT * FROM attendance WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateAttendance($id, $data) {
        $allowedFields = ['class_id', 'date', 'check_in', 'check_out', 'status'];
        $updates = [];
        $types = '';
        $values = [];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "{$field} = ?";
                $types .= 's';
                $values[] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return false;
        }

        $values[] = $id;
        $types .= 'i';
        
        $sql = "UPDATE attendance SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...array_values($values));
        return $stmt->execute();
    }

    public function deleteAttendance($id) {
        $stmt = $this->db->prepare("DELETE FROM attendance WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getTodayAttendance($userId, $date, $classId = null) {
        $sql = "SELECT * FROM attendance WHERE user_id = ? AND date = ?";
        if ($classId !== null) $sql .= " AND class_id = ?";
        $stmt = $this->db->prepare($sql);
        if ($classId !== null) {
            $stmt->bind_param('isi', $userId, $date, $classId);
        } else {
            $stmt->bind_param('is', $userId, $date);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc() ?: null;
    }

    public function insertAttendance($userId, $classId, $date, $time, $status) {
        $stmt = $this->db->prepare("
            INSERT INTO attendance (user_id, class_id, date, check_in, status)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE check_in = VALUES(check_in), status = VALUES(status)
        ");
        $stmt->bind_param('iisss', $userId, $classId, $date, $time, $status);
        return $stmt->execute();
    }

    public function updateCheckOut($userId, $date, $classId, $time) {
        $stmt = $this->db->prepare("
            UPDATE attendance 
            SET check_out = ?
            WHERE user_id = ? AND date = ? AND class_id = ?
        ");
        $stmt->bind_param('sisi', $time, $userId, $date, $classId);
        return $stmt->execute();
    }

    public function getFilteredAttendance($startDate, $endDate, $classId = null) {
        $sql = "SELECT a.*, u.username FROM attendance a JOIN users u ON a.user_id = u.id
                WHERE a.date BETWEEN ? AND ?";
        if ($classId !== null) $sql .= " AND a.class_id = ?";
        $sql .= " ORDER BY a.date DESC, a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        if ($classId !== null) {
            $stmt->bind_param('ssi', $startDate, $endDate, $classId);
        } else {
            $stmt->bind_param('ss', $startDate, $endDate);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get attendance rows for a specific user (recent first)
     */
    public function getByUser(int $userId, $limit = 200)
    {
        $sql = "SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $userId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}