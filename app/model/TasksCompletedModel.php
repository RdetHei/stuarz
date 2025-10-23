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
        try {
            $sql = "INSERT INTO tasks_completed 
                    (user_id, class_id, task_name, description, file_path) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "iisss",
                $data['user_id'],
                $data['class_id'],
                $data['task_name'],
                $data['description'],
                $data['file_path']
            );
            
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (\Exception $e) {
            throw new \Exception("Failed to create task submission: " . $e->getMessage());
        }
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
