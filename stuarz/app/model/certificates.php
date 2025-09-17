<?php
class certificates
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $rows = [];
        $sql = "SELECT c.*, u.username, u.avatar 
                FROM certificates c 
                LEFT JOIN users u ON c.user_id = u.id 
                ORDER BY c.created_at DESC";
        $res = mysqli_query($this->conn, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            mysqli_free_result($res);
        }
        return $rows;
    }

    public function getByUserId($userId)
    {
        $rows = [];
        $sql = "SELECT c.*, u.username, u.avatar
                FROM certificates c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return [];
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            mysqli_free_result($res);
        }
        mysqli_stmt_close($stmt);
        return $rows;
    }

    public function getById($id)
    {
        $sql = "SELECT c.*, u.username, u.avatar 
                FROM certificates c 
                LEFT JOIN users u ON c.user_id = u.id 
                WHERE c.id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        return $row;
    }

    public function create($data)
    {
        $sql = "INSERT INTO certificates (user_id, title, description, file_path, issued_by, issued_at, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param(
            $stmt,
            "isssss",
            $data['user_id'],
            $data['title'],
            $data['description'],
            $data['file_path'],
            $data['issued_by'],
            $data['issued_at']
        );

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function update($id, $data)
    {
        $sql = "UPDATE certificates SET 
                title = ?, 
                description = ?, 
                file_path = ?, 
                issued_by = ?, 
                issued_at = ?, 
                updated_at = NOW() 
                WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param(
            $stmt,
            "sssssi",
            $data['title'],
            $data['description'],
            $data['file_path'],
            $data['issued_by'],
            $data['issued_at'],
            $id
        );

        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM certificates WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;

        mysqli_stmt_bind_param($stmt, "i", $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function getCountByUserId($userId)
    {
        $sql = "SELECT COUNT(*) as count FROM certificates WHERE user_id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return 0;

        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : ['count' => 0];
        mysqli_stmt_close($stmt);
        return (int)$row['count'];
    }
}
