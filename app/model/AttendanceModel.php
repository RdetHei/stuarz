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
}