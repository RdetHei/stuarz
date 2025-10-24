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
        
        // Base query with teacher information
        if ($hasSubject) {
            $sql = "SELECT t.*, c.name AS class_name, s.name AS subject_name, u.name AS teacher_name, u.level AS teacher_level 
                    FROM tasks_completed t 
                    LEFT JOIN classes c ON t.class_id = c.id 
                    LEFT JOIN subjects s ON t.subject_id = s.id 
                    LEFT JOIN users u ON t.user_id = u.id";
        } else {
            $sql = "SELECT t.*, c.name AS class_name, u.name AS teacher_name, u.level AS teacher_level 
                    FROM tasks_completed t 
                    LEFT JOIN classes c ON t.class_id = c.id 
                    LEFT JOIN users u ON t.user_id = u.id";
        }
        
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
        if ($hasSubject) {
            $stmt = $this->db->prepare("SELECT t.*, c.name AS class_name, s.name AS subject_name FROM tasks_completed t LEFT JOIN classes c ON t.class_id = c.id LEFT JOIN subjects s ON t.subject_id = s.id WHERE t.id = ? LIMIT 1");
        } else {
            $stmt = $this->db->prepare("SELECT t.*, c.name AS class_name FROM tasks_completed t LEFT JOIN classes c ON t.class_id = c.id WHERE t.id = ? LIMIT 1");
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        if ($row && isset($row['status'])) $row['status'] = ucfirst($row['status']);
        if ($row && !$hasSubject) $row['subject_name'] = '';
        return $row;
    }
    public function create($data) {
        try {
            $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
            if ($hasSubject) {
                // Tasks table uses columns: user_id, title, description, status, deadline, class_id, subject_id
                $sql = "INSERT INTO tasks_completed (user_id, title, description, status, deadline, class_id, subject_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param(
                    'issssii',
                    $data['user_id'],
                    $data['title'],
                    $data['description'],
                    $data['status'],
                    $data['deadline'],
                    $data['class_id'],
                    $data['subject_id']
                );
            } else {
                // Without subject_id column
                $sql = "INSERT INTO tasks_completed (user_id, title, description, status, deadline, class_id) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param(
                    'issssi',
                    $data['user_id'],
                    $data['title'],
                    $data['description'],
                    $data['status'],
                    $data['deadline'],
                    $data['class_id']
                );
            }
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
        if ($hasSubject) {
            $stmt = $this->db->prepare("UPDATE tasks_completed SET title = ?, description = ?, status = ?, deadline = ?, class_id = ?, subject_id = ? WHERE id = ?");
            $stmt->bind_param('ssssiii', $data['title'], $data['description'], $data['status'], $data['deadline'], $data['class_id'], $data['subject_id'], $id);
        } else {
            $stmt = $this->db->prepare("UPDATE tasks_completed SET title = ?, description = ?, status = ?, deadline = ?, class_id = ? WHERE id = ?");
            $stmt->bind_param('ssssii', $data['title'], $data['description'], $data['status'], $data['deadline'], $data['class_id'], $id);
        }
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
