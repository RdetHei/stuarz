<?php
class documentation
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getById(int $id): ?array
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM documentation WHERE id = ? LIMIT 1");
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM documentation WHERE slug = ? LIMIT 1");
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, "s", $slug);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
        return $row ?: null;
    }

    public function search(?string $q): array
    {
        $rows = [];
        if ($q && $q !== '') {
            $stmt = mysqli_prepare($this->conn, "SELECT * FROM documentation WHERE title LIKE CONCAT('%', ?, '%') OR description LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') ORDER BY section, title");
            mysqli_stmt_bind_param($stmt, "sss", $q, $q, $q);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
        } else {
            $res = mysqli_query($this->conn, "SELECT * FROM documentation ORDER BY section, title");
        }
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            if (is_object($res)) mysqli_free_result($res);
        }
        return $rows;
    }

    public function create(array $data): bool
    {
        $stmt = mysqli_prepare($this->conn, "INSERT INTO documentation (section, title, slug, description, content) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "sssss", $data['section'], $data['title'], $data['slug'], $data['description'], $data['content']);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = mysqli_prepare($this->conn, "UPDATE documentation SET section = ?, title = ?, slug = ?, description = ?, content = ? WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "sssssi", $data['section'], $data['title'], $data['slug'], $data['description'], $data['content'], $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function delete(int $id): bool
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM documentation WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "i", $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}
