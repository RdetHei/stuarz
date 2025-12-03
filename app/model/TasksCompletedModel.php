<?php
class TasksCompletedModel {
    private $db;
    private $columnCache = [];

    public function __construct($db) { $this->db = $db; }

    private function hasColumn($table, $column) {
        $key = $table . '.' . $column;
        if (array_key_exists($key, $this->columnCache)) {
            return $this->columnCache[$key];
        }

        $t = $this->db->real_escape_string($table);
        $c = $this->db->real_escape_string($column);
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '".$t."' AND column_name = '".$c."'";
        $res = $this->db->query($sql);
        if ($res) {
            $r = $res->fetch_assoc();
            $this->columnCache[$key] = (int)($r['cnt'] ?? 0) > 0;
            return $this->columnCache[$key];
        }
        $this->columnCache[$key] = false;
        return false;
    }

    private function decodeRubric($raw) {
        if (empty($raw)) return [];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }
    public function getAll($filters = []) {
        $data = [];
        $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
        $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
        $hasApproval = $this->hasColumn('tasks_completed', 'approval_required');
        $hasRubric = $this->hasColumn('tasks_completed', 'grading_rubric');
        $hasMaxAttempts = $this->hasColumn('tasks_completed', 'max_attempts');
        $hasReminder = $this->hasColumn('tasks_completed', 'reminder_at');
        $hasLatePolicy = $this->hasColumn('tasks_completed', 'allow_late');
        $hasWorkflow = $this->hasColumn('tasks_completed', 'workflow_state');
        
        // Base query with teacher information
    // Build base select with optional joins for subject and schedule
    $selects = "t.*, c.name AS class_name, u.name AS teacher_name, u.level AS teacher_level";
    if ($hasSubject) $selects .= ", s.name AS subject_name";
    if ($hasSchedule) $selects .= ", sch.subject AS schedule_subject, sch.day AS schedule_day, sch.start_time AS schedule_start, sch.end_time AS schedule_end";
    if ($hasApproval) $selects .= ", t.approval_required";
    if ($hasRubric) $selects .= ", t.grading_rubric";
    if ($hasMaxAttempts) $selects .= ", t.max_attempts";
    if ($hasReminder) $selects .= ", t.reminder_at, t.reminder_sent_at";
    if ($hasLatePolicy) $selects .= ", t.allow_late, t.late_deadline";
    if ($hasWorkflow) $selects .= ", t.workflow_state";

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
                if ($hasRubric) $row['grading_rubric'] = $this->decodeRubric($row['grading_rubric'] ?? null);
                if ($hasMaxAttempts && empty($row['max_attempts'])) $row['max_attempts'] = 1;
                if ($hasApproval) $row['approval_required'] = (int)($row['approval_required'] ?? 0);
                if ($hasLatePolicy) {
                    $row['allow_late'] = (int)($row['allow_late'] ?? 0);
                    $row['late_deadline'] = $row['late_deadline'] ?? null;
                }
                if ($hasReminder) {
                    $row['reminder_at'] = $row['reminder_at'] ?? null;
                    $row['reminder_sent_at'] = $row['reminder_sent_at'] ?? null;
                }
                if ($hasWorkflow && empty($row['workflow_state'])) {
                    $row['workflow_state'] = 'published';
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
        // Get all classes the student is a member of from class_members table
        $stmt = $this->db->prepare("SELECT class_id FROM class_members WHERE user_id = ? AND role = 'user'");
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $classIds = [];
        while ($row = $result->fetch_assoc()) {
            $classIds[] = intval($row['class_id']);
        }
        $stmt->close();
        
        // If student is not a member of any class, try fallback method for backward compatibility
        if (empty($classIds)) {
            // Fallback: try old method (users.class column) for backward compatibility
            $stmt = $this->db->prepare("SELECT class FROM users WHERE id = ?");
            $stmt->bind_param('i', $studentId);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();
            $stmt->close();
            
            if ($student && !empty($student['class'])) {
                $stmt = $this->db->prepare("SELECT id FROM classes WHERE name = ?");
                $stmt->bind_param('s', $student['class']);
                $stmt->execute();
                $result = $stmt->get_result();
                $class = $result->fetch_assoc();
                $stmt->close();
                
                if ($class) {
                    $classIds = [intval($class['id'])];
                }
            }
            
            if (empty($classIds)) {
                return [];
            }
        }
        
        // Use getAll method with multiple class_ids filter
        // Build filter with multiple class_ids
        $allTasks = [];
        foreach ($classIds as $classId) {
            $tasks = $this->getAll(['class_id' => $classId]);
            $allTasks = array_merge($allTasks, $tasks);
        }
        
        // Remove duplicates (in case there are any)
        $uniqueTasks = [];
        $seenIds = [];
        foreach ($allTasks as $task) {
            $taskId = intval($task['id'] ?? 0);
            if (!isset($seenIds[$taskId])) {
                $seenIds[$taskId] = true;
                $uniqueTasks[] = $task;
            }
        }
        
        // Sort by deadline DESC
        usort($uniqueTasks, function($a, $b) {
            $deadlineA = $a['deadline'] ?? '';
            $deadlineB = $b['deadline'] ?? '';
            if ($deadlineA === $deadlineB) return 0;
            if ($deadlineA === '') return 1;
            if ($deadlineB === '') return -1;
            return strcmp($deadlineB, $deadlineA);
        });
        
        return $uniqueTasks;
    }
    
    public function getById($id) {
        $id = intval($id);
        $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
        $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
        $hasApproval = $this->hasColumn('tasks_completed', 'approval_required');
        $hasRubric = $this->hasColumn('tasks_completed', 'grading_rubric');
        $hasMaxAttempts = $this->hasColumn('tasks_completed', 'max_attempts');
        $hasReminder = $this->hasColumn('tasks_completed', 'reminder_at');
        $hasLatePolicy = $this->hasColumn('tasks_completed', 'allow_late');
        $hasWorkflow = $this->hasColumn('tasks_completed', 'workflow_state');
        $selects = "t.*, c.name AS class_name";
        if ($hasSubject) $selects .= ", s.name AS subject_name";
        if ($hasSchedule) $selects .= ", sch.subject AS schedule_subject, sch.day AS schedule_day, sch.start_time AS schedule_start, sch.end_time AS schedule_end, t.schedule_id";
        if ($hasApproval) $selects .= ", t.approval_required";
        if ($hasRubric) $selects .= ", t.grading_rubric";
        if ($hasMaxAttempts) $selects .= ", t.max_attempts";
        if ($hasReminder) $selects .= ", t.reminder_at, t.reminder_sent_at";
        if ($hasLatePolicy) $selects .= ", t.allow_late, t.late_deadline";
        if ($hasWorkflow) $selects .= ", t.workflow_state";

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
        if ($row && $hasRubric) $row['grading_rubric'] = $this->decodeRubric($row['grading_rubric'] ?? null);
        if ($row && $hasMaxAttempts && empty($row['max_attempts'])) $row['max_attempts'] = 1;
        if ($row && $hasApproval) $row['approval_required'] = (int)($row['approval_required'] ?? 0);
        if ($row && $hasWorkflow && empty($row['workflow_state'])) $row['workflow_state'] = 'published';
        return $row;
    }
    public function create($data) {
        try {
            $hasSubject = $this->hasColumn('tasks_completed', 'subject_id');
            $hasSchedule = $this->hasColumn('tasks_completed', 'schedule_id');
            $hasApproval = $this->hasColumn('tasks_completed', 'approval_required');
            $hasRubric = $this->hasColumn('tasks_completed', 'grading_rubric');
            $hasMaxAttempts = $this->hasColumn('tasks_completed', 'max_attempts');
            $hasReminder = $this->hasColumn('tasks_completed', 'reminder_at');
            $hasLatePolicy = $this->hasColumn('tasks_completed', 'allow_late');
            $hasWorkflow = $this->hasColumn('tasks_completed', 'workflow_state');
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
            if ($hasApproval) { $cols[] = 'approval_required'; $placeholders[] = '?'; $types .= 'i'; $values[] = !empty($data['approval_required']) ? 1 : 0; }
            if ($hasRubric) { $cols[] = 'grading_rubric'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['grading_rubric'] ?? null; }
            if ($hasMaxAttempts) { $cols[] = 'max_attempts'; $placeholders[] = '?'; $types .= 'i'; $values[] = max(1, intval($data['max_attempts'] ?? 1)); }
            if ($hasReminder) {
                $cols[] = 'reminder_at'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['reminder_at'] ?? null;
                $cols[] = 'reminder_sent_at'; $placeholders[] = '?'; $types .= 's'; $values[] = null;
            }
            if ($hasLatePolicy) {
                $cols[] = 'allow_late'; $placeholders[] = '?'; $types .= 'i'; $values[] = !empty($data['allow_late']) ? 1 : 0;
                $cols[] = 'late_deadline'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['late_deadline'] ?? null;
            }
            if ($hasWorkflow) { $cols[] = 'workflow_state'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['workflow_state'] ?? 'published'; }

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
        $hasApproval = $this->hasColumn('tasks_completed', 'approval_required');
        $hasRubric = $this->hasColumn('tasks_completed', 'grading_rubric');
        $hasMaxAttempts = $this->hasColumn('tasks_completed', 'max_attempts');
        $hasReminder = $this->hasColumn('tasks_completed', 'reminder_at');
        $hasLatePolicy = $this->hasColumn('tasks_completed', 'allow_late');
        $hasWorkflow = $this->hasColumn('tasks_completed', 'workflow_state');
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
        if ($hasApproval) { $setParts[] = 'approval_required = ?'; $types .= 'i'; $values[] = !empty($data['approval_required']) ? 1 : 0; }
        if ($hasRubric) { $setParts[] = 'grading_rubric = ?'; $types .= 's'; $values[] = $data['grading_rubric'] ?? null; }
        if ($hasMaxAttempts) { $setParts[] = 'max_attempts = ?'; $types .= 'i'; $values[] = max(1, intval($data['max_attempts'] ?? 1)); }
        if ($hasReminder) {
            $setParts[] = 'reminder_at = ?'; $types .= 's'; $values[] = $data['reminder_at'] ?? null;
            if (array_key_exists('reminder_sent_at', $data)) {
                $setParts[] = 'reminder_sent_at = ?'; $types .= 's'; $values[] = $data['reminder_sent_at'];
            }
        }
        if ($hasLatePolicy) {
            $setParts[] = 'allow_late = ?'; $types .= 'i'; $values[] = !empty($data['allow_late']) ? 1 : 0;
            $setParts[] = 'late_deadline = ?'; $types .= 's'; $values[] = $data['late_deadline'] ?? null;
        }
        if ($hasWorkflow && isset($data['workflow_state'])) { $setParts[] = 'workflow_state = ?'; $types .= 's'; $values[] = $data['workflow_state']; }
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

    public function getTasksRequiringReminder() {
        if (!$this->hasColumn('tasks_completed', 'reminder_at')) {
            return [];
        }
        $sql = "SELECT * FROM tasks_completed WHERE reminder_at IS NOT NULL AND reminder_at <= NOW() AND (reminder_sent_at IS NULL OR reminder_sent_at < reminder_at)";
        $result = $this->db->query($sql);
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    public function markReminderSent($taskId) {
        if (!$this->hasColumn('tasks_completed', 'reminder_sent_at')) return false;
        $stmt = $this->db->prepare("UPDATE tasks_completed SET reminder_sent_at = NOW() WHERE id = ?");
        $stmt->bind_param('i', $taskId);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getPendingApprovalTasks($teacherId) {
        if (!$this->hasColumn('tasks_completed', 'approval_required')) {
            return [];
        }
        $teacherId = intval($teacherId);
        if ($teacherId <= 0) return [];
        $sql = "SELECT id, title, class_id, deadline FROM tasks_completed WHERE user_id = ? AND approval_required = 1 AND workflow_state IN ('published','in_review')";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $teacherId);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $stmt->close();
        return $rows;
    }
}
