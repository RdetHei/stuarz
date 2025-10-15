<?php
class GradesModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function getAll() {
        $data = [];
        $sql = "SELECT g.*, u.username, c.name AS class_name, s.name AS subject_name, t.title AS task_title FROM grades g LEFT JOIN users u ON g.user_id = u.id LEFT JOIN classes c ON g.class_id = c.id LEFT JOIN subjects s ON g.subject_id = s.id LEFT JOIN tasks_completed t ON g.task_id = t.id ORDER BY g.id DESC";
        $result = $this->db->query($sql);
        if ($result) while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }
    public function getById($id) {
        $id = intval($id);
        $result = $this->db->query("SELECT * FROM grades WHERE id=$id");
        return $result ? $result->fetch_assoc() : null;
    }
    public function create($data) {
        $user_id = intval($data['user_id']);
        $class_id = intval($data['class_id']);
        $subject_id = intval($data['subject_id']);
        $task_id = intval($data['task_id']);
        $score = floatval($data['score']);
        return $this->db->query("INSERT INTO grades (user_id, class_id, subject_id, task_id, score) VALUES ($user_id, $class_id, $subject_id, $task_id, $score)");
    }
    public function update($id, $data) {
        $id = intval($id);
        $user_id = intval($data['user_id']);
        $class_id = intval($data['class_id']);
        $subject_id = intval($data['subject_id']);
        $task_id = intval($data['task_id']);
        $score = floatval($data['score']);
        return $this->db->query("UPDATE grades SET user_id=$user_id, class_id=$class_id, subject_id=$subject_id, task_id=$task_id, score=$score WHERE id=$id");
    }
    public function delete($id) {
        $id = intval($id);
        return $this->db->query("DELETE FROM grades WHERE id=$id");
    }
}
