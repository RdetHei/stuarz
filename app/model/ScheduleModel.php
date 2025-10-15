
<?php
class ScheduleModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function getAll($filters = []) {
        $where = [];
        if (!empty($filters['class_id'])) $where[] = "class_id=" . intval($filters['class_id']);
        if (!empty($filters['teacher_id'])) $where[] = "teacher_id=" . intval($filters['teacher_id']);
        $sql = "SELECT * FROM schedule" . (count($where) ? " WHERE " . implode(' AND ', $where) : "") . " ORDER BY class_id, FIELD(day,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'), start_time";
        $res = $this->db->query($sql);
        $data = [];
        if ($res) while ($r = $res->fetch_assoc()) $data[] = $r;
        return $data;
    }

    public function getById($id) {
        $id = intval($id);
        $res = $this->db->query("SELECT * FROM schedule WHERE id=$id");
        return $res ? $res->fetch_assoc() : null;
    }

    public function create($data) {
        if ($this->checkOverlap($data['class_id'], $data['day'], $data['start_time'], $data['end_time'])) {
            return ['error' => 'Bentrokan jadwal pada kelas yang sama'];
        }
        $class = $this->db->real_escape_string($data['class']);
        $subject = $this->db->real_escape_string($data['subject']);
        $teacher_id = intval($data['teacher_id']);
        $class_id = intval($data['class_id']);
        $day = $this->db->real_escape_string($data['day']);
        $start = $this->db->real_escape_string($data['start_time']);
        $end = $this->db->real_escape_string($data['end_time']);
        $sql = "INSERT INTO schedule (`class`,`subject`,`teacher_id`,`class_id`,`day`,`start_time`,`end_time`)
                VALUES ('$class','$subject',$teacher_id,$class_id,'$day','$start','$end')";
        return $this->db->query($sql);
    }

    public function update($id, $data) {
        $id = intval($id);
        if ($this->checkOverlap($data['class_id'], $data['day'], $data['start_time'], $data['end_time'], $id)) {
            return ['error' => 'Bentrokan jadwal pada kelas yang sama'];
        }
        $class = $this->db->real_escape_string($data['class']);
        $subject = $this->db->real_escape_string($data['subject']);
        $teacher_id = intval($data['teacher_id']);
        $class_id = intval($data['class_id']);
        $day = $this->db->real_escape_string($data['day']);
        $start = $this->db->real_escape_string($data['start_time']);
        $end = $this->db->real_escape_string($data['end_time']);
        $sql = "UPDATE schedule SET `class`='$class', `subject`='$subject', teacher_id=$teacher_id, class_id=$class_id, `day`='$day', start_time='$start', end_time='$end' WHERE id=$id";
        return $this->db->query($sql);
    }

    public function delete($id) {
        $id = intval($id);
        return $this->db->query("DELETE FROM schedule WHERE id=$id");
    }

    // Cek overlap pada kelas yang sama. exclude_id untuk update.
    public function checkOverlap($class_id, $day, $start, $end, $exclude_id = null) {
        $class_id = intval($class_id);
        $day = $this->db->real_escape_string($day);
        $start = $this->db->real_escape_string($start);
        $end = $this->db->real_escape_string($end);
        $ex = $exclude_id ? " AND id!=".$this->db->real_escape_string(intval($exclude_id)) : "";
        // Overlap jika NOT (existing.end_time <= new.start_time OR existing.start_time >= new.end_time)
        $sql = "SELECT COUNT(*) as cnt FROM schedule WHERE class_id=$class_id AND day='$day' AND NOT (end_time <= '$start' OR start_time >= '$end') $ex";
        $res = $this->db->query($sql);
        $row = $res ? $res->fetch_assoc() : null;
        return $row && intval($row['cnt']) > 0;
    }
}