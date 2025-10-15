<?php
class TasksCompletedModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function getAll() {
        $data = [];
        // Hapus LEFT JOIN jika kolom tidak ada
        $result = $this->db->query("SELECT * FROM tasks_completed ORDER BY deadline DESC");
        if ($result) while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }
    public function getById($id) {
        $id = intval($id);
        $result = $this->db->query("SELECT * FROM tasks_completed WHERE id=$id");
        return $result ? $result->fetch_assoc() : null;
    }
    public function create($data) {
        $user_id = intval($data['user_id']);
        $title = $this->db->real_escape_string($data['title']);
        $desc = $this->db->real_escape_string($data['description']);
        $status = $this->db->real_escape_string($data['status']);
        $deadline = $this->db->real_escape_string($data['deadline']);
        $class_id = intval($data['class_id']);
        $subject_id = intval($data['subject_id']);
        return $this->db->query("INSERT INTO tasks_completed (user_id, title, description, status, deadline, class_id, subject_id) VALUES ($user_id, '$title', '$desc', '$status', '$deadline', $class_id, $subject_id)");
    }
    public function update($id, $data) {
        $id = intval($id);
        $title = $this->db->real_escape_string($data['title']);
        $desc = $this->db->real_escape_string($data['description']);
        $status = $this->db->real_escape_string($data['status']);
        $deadline = $this->db->real_escape_string($data['deadline']);
        $class_id = intval($data['class_id']);
        $subject_id = intval($data['subject_id']);
        return $this->db->query("UPDATE tasks_completed SET title='$title', description='$desc', status='$status', deadline='$deadline', class_id=$class_id, subject_id=$subject_id WHERE id=$id");
    }
    public function delete($id) {
        $id = intval($id);
        return $this->db->query("DELETE FROM tasks_completed WHERE id=$id");
    }
}
