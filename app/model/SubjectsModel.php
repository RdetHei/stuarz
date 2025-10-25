<?php
class SubjectsModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function getAll() {
        $data = [];
        $result = $this->db->query("SELECT s.*, u.name as teacher_name 
                                   FROM subjects s 
                                   LEFT JOIN users u ON s.teacher_id = u.id 
                                   ORDER BY s.name ASC");
        if ($result) while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }
    public function getById($id) {
        $id = intval($id);
        $result = $this->db->query("SELECT * FROM subjects WHERE id=$id");
        return $result ? $result->fetch_assoc() : null;
    }
    public function create($data) {
        $name = $this->db->real_escape_string($data['name']);
        $desc = $this->db->real_escape_string($data['description']);
        $teacherId = intval($data['teacher_id']);
        return $this->db->query("INSERT INTO subjects (name, description, teacher_id) VALUES ('$name', '$desc', $teacherId)");
    }
    public function update($id, $data) {
        $id = intval($id);
        $name = $this->db->real_escape_string($data['name']);
        $desc = $this->db->real_escape_string($data['description']);
        $teacherId = intval($data['teacher_id']);
        return $this->db->query("UPDATE subjects SET name='$name', description='$desc', teacher_id=$teacherId WHERE id=$id");
    }
    public function delete($id) {
        $id = intval($id);
        return $this->db->query("DELETE FROM subjects WHERE id=$id");
    }
}
