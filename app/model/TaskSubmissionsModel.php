<?php
class TaskSubmissionsModel {
    private $db;
    private $columnCache = [];

    public function __construct($db) { $this->db = $db; }

    private function hasColumn($table, $column) {
        $key = $table . '.' . $column;
        if (array_key_exists($key, $this->columnCache)) return $this->columnCache[$key];
        $t = $this->db->real_escape_string($table);
        $c = $this->db->real_escape_string($column);
        $sql = "SELECT COUNT(*) AS cnt FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = '".$t."' AND column_name = '".$c."'";
        $res = $this->db->query($sql);
        if ($res) {
            $row = $res->fetch_assoc();
            $this->columnCache[$key] = (int)($row['cnt'] ?? 0) > 0;
            return $this->columnCache[$key];
        }
        $this->columnCache[$key] = false;
        return false;
    }

    private function decodeBreakdown($raw) {
        if (empty($raw)) return [];
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function create($data) {
        return $this->createAttempt($data);
    }

    public function createAttempt($data) {
        $supportsAttempt = $this->hasColumn('task_submissions', 'attempt_no');
        $supportsReview = $this->hasColumn('task_submissions', 'review_status');
        $supportsBreakdown = $this->hasColumn('task_submissions', 'grade_breakdown');
        $supportsReviewMeta = $this->hasColumn('task_submissions', 'reviewed_by');

        $columns = ['task_id', 'user_id', 'class_id', 'file_path', 'status', 'grade', 'feedback'];
        $filePath = $data['file_path'] ?? null;
        
        if ($filePath === null) {
            $placeholders = ['?', '?', '?', 'NULL', '?', 'NULL', 'NULL'];
            $types = 'iiis';
            $values = [
                intval($data['task_id']),
                intval($data['user_id']),
                intval($data['class_id']),
                $data['status'] ?? 'submitted'
            ];
        } else {
            $placeholders = ['?', '?', '?', '?', '?', 'NULL', 'NULL'];
            $types = 'iiiss';
            $values = [
                intval($data['task_id']),
                intval($data['user_id']),
                intval($data['class_id']),
                $filePath,
                $data['status'] ?? 'submitted'
            ];
        }

        if ($supportsAttempt) {
            $columns[] = 'attempt_no'; $placeholders[] = '?'; $types .= 'i'; $values[] = intval($data['attempt_no'] ?? 1);
            $columns[] = 'is_final'; $placeholders[] = '?'; $types .= 'i'; $values[] = !empty($data['is_final']) ? 1 : 0;
        }
        if ($supportsReview) {
            $columns[] = 'review_status'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['review_status'] ?? 'pending';
        }
        if ($supportsBreakdown) {
            $columns[] = 'grade_breakdown'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['grade_breakdown'] ?? null;
        }
        if ($supportsReviewMeta) {
            $columns[] = 'reviewed_by'; $placeholders[] = '?'; $types .= 'i'; $values[] = $data['reviewed_by'] ?? null;
            $columns[] = 'reviewed_at'; $placeholders[] = '?'; $types .= 's'; $values[] = $data['reviewed_at'] ?? null;
        }

        $sql = "INSERT INTO task_submissions (" . implode(',', $columns) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param($types, ...$values);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getByTask($task_id) {
        $task_id = intval($task_id);
        $res = $this->db->query("SELECT ts.*, u.username, u.name FROM task_submissions ts LEFT JOIN users u ON ts.user_id = u.id WHERE ts.task_id = " . $task_id . " ORDER BY ts.submitted_at DESC");
        $data = [];
        if ($res) while ($row = $res->fetch_assoc()) {
            if (isset($row['grade_breakdown'])) {
                $row['grade_breakdown'] = $this->decodeBreakdown($row['grade_breakdown']);
            }
            $data[] = $row;
        }
        return $data;
    }

    public function getByUser($user_id) {
        $user_id = intval($user_id);
        $sql = "SELECT ts.*, u.username, u.name, t.title as task_title FROM task_submissions ts "
             . "LEFT JOIN users u ON ts.user_id = u.id "
             . "LEFT JOIN tasks_completed t ON ts.task_id = t.id "
             . "WHERE ts.user_id = ? ORDER BY ts.submitted_at DESC";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $rows = [];
        if ($res) while ($r = $res->fetch_assoc()) {
            if (isset($r['grade_breakdown'])) {
                $r['grade_breakdown'] = $this->decodeBreakdown($r['grade_breakdown']);
            }
            $rows[] = $r;
        }
        $stmt->close();
        return $rows;
    }

    public function getById($id) {
        $id = intval($id);
        $stmt = $this->db->prepare("SELECT * FROM task_submissions WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        if ($row && isset($row['grade_breakdown'])) {
            $row['grade_breakdown'] = $this->decodeBreakdown($row['grade_breakdown']);
        }
        return $row;
    }

    public function getByTaskAndUser($taskId, $userId) {
        $taskId = intval($taskId);
        $userId = intval($userId);
        $orderClause = $this->hasColumn('task_submissions', 'attempt_no') ? 'attempt_no DESC, submitted_at DESC' : 'submitted_at DESC';
        $stmt = $this->db->prepare("SELECT * FROM task_submissions WHERE task_id = ? AND user_id = ? ORDER BY {$orderClause} LIMIT 1");
        $stmt->bind_param('ii', $taskId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        if ($row && isset($row['grade_breakdown'])) {
            $row['grade_breakdown'] = $this->decodeBreakdown($row['grade_breakdown']);
        }
        return $row;
    }

    public function countAttempts($taskId, $userId) {
        if (!$this->hasColumn('task_submissions', 'attempt_no')) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as attempts FROM task_submissions WHERE task_id = ? AND user_id = ?");
        } else {
            $stmt = $this->db->prepare("SELECT MAX(attempt_no) as attempts FROM task_submissions WHERE task_id = ? AND user_id = ?");
        }
        $stmt->bind_param('ii', $taskId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : ['attempts' => 0];
        $stmt->close();
        return intval($row['attempts'] ?? 0);
    }

    public function getLatestByTaskIds(array $taskIds, $userId) {
        $userId = intval($userId);
        if (empty($taskIds)) return [];
        $ids = implode(',', array_map('intval', $taskIds));
        $orderClause = $this->hasColumn('task_submissions', 'attempt_no') ? 'attempt_no DESC, submitted_at DESC' : 'submitted_at DESC';
        $sql = "SELECT * FROM task_submissions WHERE user_id = {$userId} AND task_id IN ({$ids}) ORDER BY task_id ASC, {$orderClause}";
        $res = $this->db->query($sql);
        $map = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $tid = intval($row['task_id']);
                if (!isset($map[$tid])) {
                    if (isset($row['grade_breakdown'])) {
                        $row['grade_breakdown'] = $this->decodeBreakdown($row['grade_breakdown']);
                    }
                    $map[$tid] = $row;
                }
            }
        }
        return $map;
    }

    public function getStatsByTasks(array $taskIds) {
        if (empty($taskIds) || !$this->hasColumn('task_submissions', 'review_status')) return [];
        $ids = implode(',', array_map('intval', $taskIds));
        $sql = "SELECT task_id,
                       COUNT(*) as total,
                       SUM(review_status = 'pending') as pending,
                       SUM(review_status = 'in_review') as in_review,
                       SUM(review_status = 'needs_revision') as needs_revision,
                       SUM(review_status = 'approved') as approved,
                       SUM(review_status = 'graded') as graded
                FROM task_submissions
                WHERE task_id IN ({$ids})
                GROUP BY task_id";
        $res = $this->db->query($sql);
        $map = [];
        if ($res) while ($row = $res->fetch_assoc()) $map[$row['task_id']] = $row;
        return $map;
    }

    public function updateReview($id, $data) {
        $id = intval($id);
        $supportsReview = $this->hasColumn('task_submissions', 'review_status');
        $supportsBreakdown = $this->hasColumn('task_submissions', 'grade_breakdown');
        $supportsReviewMeta = $this->hasColumn('task_submissions', 'reviewed_by');
        $supportsFinalFlag = $this->hasColumn('task_submissions', 'is_final');

        $allowedKeys = ['status', 'feedback', 'grade'];
        if ($supportsReview) $allowedKeys[] = 'review_status';
        if ($supportsBreakdown) $allowedKeys[] = 'grade_breakdown';
        if ($supportsReviewMeta) {
            $allowedKeys[] = 'reviewed_by';
            $allowedKeys[] = 'reviewed_at';
        }
        if ($supportsFinalFlag) $allowedKeys[] = 'is_final';

        $setParts = [];
        $types = '';
        $values = [];

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowedKeys, true)) continue;
            if ($value === null) {
                $setParts[] = "{$key} = NULL";
                continue;
            }
            switch ($key) {
                case 'grade':
                    $setParts[] = "{$key} = ?";
                    $types .= 'd';
                    $values[] = floatval($value);
                    break;
                case 'reviewed_by':
                    $setParts[] = "{$key} = ?";
                    $types .= 'i';
                    $values[] = intval($value);
                    break;
                case 'is_final':
                    $setParts[] = "{$key} = ?";
                    $types .= 'i';
                    $values[] = !empty($value) ? 1 : 0;
                    break;
                default:
                    $setParts[] = "{$key} = ?";
                    $types .= 's';
                    $values[] = $value;
                    break;
            }
        }

        if (empty($setParts)) return false;
        $sql = "UPDATE task_submissions SET " . implode(', ', $setParts) . " WHERE id = ?";
        $types .= 'i';
        $values[] = $id;
        $stmt = $this->db->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param($types, ...$values);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete($id) {
        $id = intval($id);
        $stmt = $this->db->prepare("DELETE FROM task_submissions WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function markFinalAttempt($taskId, $userId) {
        if (!$this->hasColumn('task_submissions', 'is_final')) return true;
        $taskId = intval($taskId);
        $userId = intval($userId);
        $sql = "UPDATE task_submissions SET is_final = 0 WHERE task_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $taskId, $userId);
        $stmt->execute();
        $stmt->close();
        $sql2 = "UPDATE task_submissions SET is_final = 1 WHERE id = (
                    SELECT id FROM (
                        SELECT id FROM task_submissions WHERE task_id = ? AND user_id = ? ORDER BY submitted_at DESC LIMIT 1
                    ) as latest
                )";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param('ii', $taskId, $userId);
        $stmt2->execute();
        $stmt2->close();
        return true;
    }
}