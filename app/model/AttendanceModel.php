<?php
class AttendanceModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll($filters = []) {
        $where = [];
        if (!empty($filters['class_id'])) $where[] = "a.class_id=" . intval($filters['class_id']);
        if (!empty($filters['date'])) $where[] = "a.date='" . $this->db->real_escape_string($filters['date']) . "'";
        $sql = "SELECT a.*, u.name as student_name FROM attendance a LEFT JOIN users u ON u.id=a.user_id" . (count($where) ? " WHERE " . implode(' AND ', $where) : "") . " ORDER BY a.date DESC, u.name";
        $res = $this->db->query($sql);
        $data = [];
        if ($res) while ($r = $res->fetch_assoc()) $data[] = $r;
        return $data;
    }

    public function getByUserDateClass($user_id, $date, $class_id) {
        $user_id = intval($user_id);
        $class_id = intval($class_id);
        $date = $this->db->real_escape_string($date);
        $res = $this->db->query("SELECT * FROM attendance WHERE user_id=$user_id AND date='$date' AND class_id=$class_id");
        return $res ? $res->fetch_assoc() : null;
    }

    public function store($data) {
        // unique constraint business logic enforced here
        $exists = $this->getByUserDateClass($data['user_id'], $data['date'], $data['class_id']);
        if ($exists) {
            // update existing
            $status = $this->db->real_escape_string($data['status']);
            return $this->db->query("UPDATE attendance SET status='$status' WHERE id=" . intval($exists['id']));
        } else {
            $user_id = intval($data['user_id']);
            $date = $this->db->real_escape_string($data['date']);
            $status = $this->db->real_escape_string($data['status']);
            $class_id = intval($data['class_id']);
            return $this->db->query("INSERT INTO attendance (user_id, date, status, class_id) VALUES ($user_id, '$date', '$status', $class_id)");
        }
    }

    public function reportByUser($user_id, $from = null, $to = null) {
        $user_id = intval($user_id);
        $where = "user_id=$user_id";
        if ($from) $where .= " AND date >= '" . $this->db->real_escape_string($from) . "'";
        if ($to) $where .= " AND date <= '" . $this->db->real_escape_string($to) . "'";
        $sql = "SELECT status, COUNT(*) as cnt FROM attendance WHERE $where GROUP BY status";
        $res = $this->db->query($sql);
        $data = ['Hadir'=>0,'Absen'=>0,'Terlambat'=>0];
        if ($res) while ($r = $res->fetch_assoc()) {
            $data[$r['status']] = intval($r['cnt']);
        }
        return $data;
    }
}