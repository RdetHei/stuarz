
<?php
class ScheduleModel {
    private $db;
    public function __construct($db) {
        $this->db = $db;
        if (!is_object($this->db) || !method_exists($this->db, 'prepare')) {
            error_log("ScheduleModel::__construct - invalid DB object passed; prepare() missing or db is not an object");
        }
    }

    public function getAll($filters = []) {
        $sql = "SELECT * FROM schedule";
        $conds = [];
        $types = '';
        $params = [];
        if (!empty($filters['class_id'])) { $conds[] = "class_id = ?"; $types .= 'i'; $params[] = intval($filters['class_id']); }
        if (!empty($filters['teacher_id'])) { $conds[] = "teacher_id = ?"; $types .= 'i'; $params[] = intval($filters['teacher_id']); }
        if (!empty($filters['day'])) { $conds[] = "day = ?"; $types .= 's'; $params[] = $filters['day']; }
        if ($conds) $sql .= " WHERE " . implode(' AND ', $conds);
        $sql .= " ORDER BY class_id, FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), start_time";

        $stmt = $this->db->prepare($sql);
        if ($conds) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($res && ($r = $res->fetch_assoc())) $data[] = $r;
        $stmt->close();
        return $data;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM schedule WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function create($data) {
        if ($this->checkOverlap($data['class_id'], $data['day'], $data['start_time'], $data['end_time'])) {
            return ['error' => 'Bentrokan jadwal pada kelas yang sama'];
        }
        $sql = "INSERT INTO schedule (`class`,`subject`,`teacher_id`,`class_id`,`day`,`start_time`,`end_time`) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);


        $stmt->bind_param('ssiisss', $data['class'], $data['subject'], $data['teacher_id'], $data['class_id'], $data['day'], $data['start_time'], $data['end_time']);
        $ok = $stmt->execute();
        if (!$ok) {
            error_log("ScheduleModel::create failed: " . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }

    public function update($id, $data) {
        if ($this->checkOverlap($data['class_id'], $data['day'], $data['start_time'], $data['end_time'], $id)) {
            return ['error' => 'Bentrokan jadwal pada kelas yang sama'];
        }
        $sql = "UPDATE schedule SET `class`=?, `subject`=?, teacher_id=?, class_id=?, `day`=?, start_time=?, end_time=? WHERE id=?";
        $stmt = $this->db->prepare($sql);


        $stmt->bind_param('ssiisssi', $data['class'], $data['subject'], $data['teacher_id'], $data['class_id'], $data['day'], $data['start_time'], $data['end_time'], $id);
        $ok = $stmt->execute();
        if (!$ok) {
            error_log("ScheduleModel::update failed: " . $stmt->error);
        }
        $stmt->close();
        return $ok;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function checkOverlap($class_id, $day, $start, $end, $exclude_id = null) {
        $sql = "SELECT COUNT(*) as cnt FROM schedule WHERE class_id = ? AND day = ? AND NOT (end_time <= ? OR start_time >= ?)";
        $types = 'isss';
        $params = [$class_id, $day, $start, $end];
        if ($exclude_id) { $sql .= " AND id != ?"; $types .= 'i'; $params[] = $exclude_id; }
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row && intval($row['cnt']) > 0;
    }

    public function getAllWithRelations($filters = []) {
        $sql = "SELECT s.*, 
                       c.name as class_name, 
                       c.code as class_code,
                       u.name as teacher_name
                FROM schedule s
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN users u ON s.teacher_id = u.id";
        $conds = [];
        $types = '';
        $params = [];
        if (!empty($filters['class_id'])) { 
            $conds[] = "s.class_id = ?"; 
            $types .= 'i'; 
            $params[] = intval($filters['class_id']); 
        }
        if (!empty($filters['teacher_id'])) { 
            $conds[] = "s.teacher_id = ?"; 
            $types .= 'i'; 
            $params[] = intval($filters['teacher_id']); 
        }
        if (!empty($filters['day'])) { 
            $conds[] = "s.day = ?"; 
            $types .= 's'; 
            $params[] = $filters['day']; 
        }
        if ($conds) $sql .= " WHERE " . implode(' AND ', $conds);
        $sql .= " ORDER BY s.class_id, FIELD(s.day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), s.start_time";

        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            error_log("ScheduleModel::getAllWithRelations - Prepare failed: " . $this->db->error);
            return [];
        }
        
        if ($conds) {
            if (!$stmt->bind_param($types, ...$params)) {
                error_log("ScheduleModel::getAllWithRelations - Bind param failed: " . $stmt->error);
                $stmt->close();
                return [];
            }
        }
        
        if (!$stmt->execute()) {
            error_log("ScheduleModel::getAllWithRelations - Execute failed: " . $stmt->error);
            $stmt->close();
            return [];
        }
        
        $res = $stmt->get_result();
        $data = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $data[] = $r;
            }
        }
        $stmt->close();
        return $data;
    }

    public function getSchedulesByClass($filters = []) {
        $schedules = $this->getAllWithRelations($filters);
        $grouped = [];
        foreach ($schedules as $schedule) {
            $classId = $schedule['class_id'];
            if (!isset($grouped[$classId])) {
                $grouped[$classId] = [
                    'class_id' => $classId,
                    'class_name' => $schedule['class_name'] ?? 'Unknown',
                    'class_code' => $schedule['class_code'] ?? '',
                    'schedules' => []
                ];
            }
            $grouped[$classId]['schedules'][] = $schedule;
        }
        return $grouped;
    }
}