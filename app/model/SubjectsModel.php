<?php
class SubjectsModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function getAll() {
        $data = [];
        $result = $this->db->query("SELECT * FROM subjects ORDER BY name ASC");
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
        return $this->db->query("INSERT INTO subjects (name, description) VALUES ('$name', '$desc')");
    }
    public function update($id, $data) {
        $id = intval($id);
        $name = $this->db->real_escape_string($data['name']);
        $desc = $this->db->real_escape_string($data['description']);
        return $this->db->query("UPDATE subjects SET name='$name', description='$desc' WHERE id=$id");
    }
    public function delete($id) {
        $id = intval($id);
        return $this->db->query("DELETE FROM subjects WHERE id=$id");
    }
}
