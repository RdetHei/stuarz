<?php
class TasksCompletedModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    private function hasColumn($table, $column) {
        $t = $this->db->real_escape_string($table);
        $c = $this->db->real_escape_string($column);
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '".$t."' AND column_name = '".$c."'";
        $res = $this->db->query($sql);
        if ($res) {
            $r = $res->fetch_assoc();
            return (int)($r['cnt'] ?? 0) > 0;
        }
        return false;
    }
    public function getAll($filters = []) {
        $data = [];
        $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
        $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
        
        // Base query with teacher information
    // Build base select with optional joins for subject and schedule
    $selects = "t.*, c.name AS class_name, u.name AS teacher_name, u.level AS teacher_level";
    if ($hasSubject) $selects .= ", s.name AS subject_name";
    if ($hasSchedule) $selects .= ", sch.subject AS schedule_subject, sch.day AS schedule_day, sch.start_time AS schedule_start, sch.end_time AS schedule_end";

    $sql = "SELECT " . $selects . " FROM tasks_completed t 
            LEFT JOIN classes c ON t.class_id = c.id 
            LEFT JOIN users u ON t.user_id = u.id";
    if ($hasSubject) $sql .= " LEFT JOIN subjects s ON t.subject_id = s.id";
    if ($hasSchedule) $sql .= " LEFT JOIN schedule sch ON t.schedule_id = sch.id";
        
        // Add filters
        $whereConditions = [];
        $params = [];
        $paramTypes = '';
        
        if (!empty($filters['teacher_id'])) {
            $whereConditions[] = "t.user_id = ?";
            $params[] = $filters['teacher_id'];
            $paramTypes .= 'i';
        }
        
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "t.class_id = ?";
            $params[] = $filters['class_id'];
            $paramTypes .= 'i';
        }
        
        if (!empty($filters['subject_id'])) {
            $whereConditions[] = "t.subject_id = ?";
            $params[] = $filters['subject_id'];
            $paramTypes .= 'i';
        }
        if (!empty($filters['schedule_id'])) {
            $whereConditions[] = "t.schedule_id = ?";
            $params[] = $filters['schedule_id'];
            $paramTypes .= 'i';
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(" AND ", $whereConditions);
        }
        
        $sql .= " ORDER BY t.deadline DESC";
        
        if (!empty($params)) {
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($paramTypes, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($sql);
        }
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Normalize status to capitalized for view
                if (isset($row['status'])) $row['status'] = ucfirst($row['status']);
                if (!$hasSubject) $row['subject_name'] = '';
                if (!$hasSchedule) {
                    $row['schedule_subject'] = '';
                    $row['schedule_day'] = '';
                    $row['schedule_start'] = '';
                    $row['schedule_end'] = '';
                }
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    public function getByTeacherId($teacherId) {
        return $this->getAll(['teacher_id' => $teacherId]);
    }
    
    public function getByStudentClass($studentId) {
        // Get student's class first
        $stmt = $this->db->prepare("SELECT class FROM users WHERE id = ?");
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        
        if (!$student || !$student['class']) {
            return [];
        }
        
        // Get class ID from class name
        $stmt = $this->db->prepare("SELECT id FROM classes WHERE name = ?");
        $stmt->bind_param('s', $student['class']);
        $stmt->execute();
        $result = $stmt->get_result();
        $class = $result->fetch_assoc();
        
        if (!$class) {
            return [];
        }
        
        return $this->getAll(['class_id' => $class['id']]);
    }
    
    public function getById($id) {
        $id = intval($id);
        $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
        $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
        $selects = "t.*, c.name AS class_name";
        if ($hasSubject) $selects .= ", s.name AS subject_name";
        if ($hasSchedule) $selects .= ", sch.subject AS schedule_subject, sch.day AS schedule_day, sch.start_time AS schedule_start, sch.end_time AS schedule_end, t.schedule_id";

        $sql = "SELECT " . $selects . " FROM tasks_completed t LEFT JOIN classes c ON t.class_id = c.id";
        if ($hasSubject) $sql .= " LEFT JOIN subjects s ON t.subject_id = s.id";
        if ($hasSchedule) $sql .= " LEFT JOIN schedule sch ON t.schedule_id = sch.id";
        $sql .= " WHERE t.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        if ($row && isset($row['status'])) $row['status'] = ucfirst($row['status']);
        if ($row && !$hasSubject) $row['subject_name'] = '';
        if ($row && !$hasSchedule) {
            $row['schedule_subject'] = '';
            $row['schedule_day'] = '';
            $row['schedule_start'] = '';
            $row['schedule_end'] = '';
            $row['schedule_id'] = null;
        }
        return $row;
    }
    public function create($data) {
        try {
            $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
            $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
            // Build insert with optional subject_id and schedule_id
            $cols = ['user_id','title','description','status','deadline','class_id'];
            $placeholders = ['?','?','?','?','?','?'];
            $types = 'issssi';
            $values = [ $data['user_id'], $data['title'], $data['description'], $data['status'], $data['deadline'], $data['class_id'] ];

            if ($hasSubject) {
                $cols[] = 'subject_id'; $placeholders[] = '?'; $types .= 'i'; $values[] = $data['subject_id'];
            }
            if ($hasSchedule && isset($data['schedule_id']) && $data['schedule_id'] !== null) {
                $cols[] = 'schedule_id'; $placeholders[] = '?'; $types .= 'i'; $values[] = $data['schedule_id'];
            }

            $sql = "INSERT INTO tasks_completed (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$values);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Failed to create task: " . $e->getMessage());
        }
    }
    public function update($id, $data) {
        $id = intval($id);
        $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
        $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
        // Build UPDATE dynamically to include optional subject_id, schedule_id, and user_id
        $setParts = [];
        $types = '';
        $values = [];

        $setParts[] = 'title = ?'; $types .= 's'; $values[] = $data['title'];
        $setParts[] = 'description = ?'; $types .= 's'; $values[] = $data['description'];
        $setParts[] = 'status = ?'; $types .= 's'; $values[] = $data['status'];
        $setParts[] = 'deadline = ?'; $types .= 's'; $values[] = $data['deadline'];
        $setParts[] = 'class_id = ?'; $types .= 'i'; $values[] = $data['class_id'];

        if ($hasSubject) { $setParts[] = 'subject_id = ?'; $types .= 'i'; $values[] = $data['subject_id']; }
        if ($hasSchedule) { $setParts[] = 'schedule_id = ?'; $types .= 'i'; $values[] = isset($data['schedule_id']) ? $data['schedule_id'] : null; }
        if (isset($data['user_id'])) { $setParts[] = 'user_id = ?'; $types .= 'i'; $values[] = $data['user_id']; }

        $sql = "UPDATE tasks_completed SET " . implode(', ', $setParts) . " WHERE id = ?";
        $types .= 'i'; $values[] = $id;
        $stmt = $this->db->prepare($sql);
        // bind params dynamically
        $stmt->bind_param($types, ...$values);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    public function delete($id) {
        $id = intval($id);
        $stmt = $this->db->prepare("DELETE FROM tasks_completed WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
