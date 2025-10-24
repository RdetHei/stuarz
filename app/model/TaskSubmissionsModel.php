<?php
class TaskSubmissionsModel {
    private $db;
    public function __construct($db) { $this->db = $db; }

    public function create($data) {
        // $data: task_id, user_id, class_id, file_path, status (optional)
        $sql = "INSERT INTO task_submissions (task_id, user_id, class_id, file_path, status, grade, feedback) VALUES (?, ?, ?, ?, ?, NULL, NULL)";
        $stmt = $this->db->prepare($sql);
        $status = $data['status'] ?? 'submitted';
        $stmt->bind_param('iiiss', $data['task_id'], $data['user_id'], $data['class_id'], $data['file_path'], $status);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getByTask($task_id) {
        $task_id = intval($task_id);
        $res = $this->db->query("SELECT ts.*, u.username, u.name FROM task_submissions ts LEFT JOIN users u ON ts.user_id = u.id WHERE ts.task_id = " . $task_id . " ORDER BY ts.submitted_at DESC");
        $data = [];
        if ($res) while ($row = $res->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getById($id) {
        $id = intval($id);
        $stmt = $this->db->prepare("SELECT * FROM task_submissions WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        return $row;
    }

    public function delete($id) {
        $id = intval($id);
        $stmt = $this->db->prepare("DELETE FROM task_submissions WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
