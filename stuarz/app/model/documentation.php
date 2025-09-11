<?php
class documentation {
     private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUserById($id)
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM documentation WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
}