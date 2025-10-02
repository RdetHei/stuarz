<?php
class users
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $rows = [];
        $sql = "SELECT id, username, name, email, `level`, avatar, banner, join_date, phone, address, bio FROM users ORDER BY id DESC";
        $res = mysqli_query($this->conn, $sql);
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
            mysqli_free_result($res);
        }
        return $rows;
    }

    public function getUserById($id)
    {
        $sql = "SELECT id, username, name, email, `level`, avatar, banner, join_date, phone, address, bio FROM users WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return null;
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        if (function_exists('mysqli_stmt_get_result')) {
            $res = mysqli_stmt_get_result($stmt);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);
            return $row;
        }

        $meta = mysqli_stmt_result_metadata($stmt);
        if (!$meta) { mysqli_stmt_close($stmt); return null; }

        $fields = [];
        $row = [];
        $bindVars = [];
        while ($f = mysqli_fetch_field($meta)) {
            $fields[] = $f->name;
            $bindVars[] = &$row[$f->name];
        }
        mysqli_free_result($meta);

        call_user_func_array('mysqli_stmt_bind_result', array_merge([$stmt], $bindVars));
        if (mysqli_stmt_fetch($stmt)) {
            $result = [];
            foreach ($row as $k => $v) $result[$k] = $v;
            mysqli_stmt_close($stmt);
            return $result;
        }

        mysqli_stmt_close($stmt);
        return null;
    }

    public function createUser(array $data)
    {
        $sql = "INSERT INTO users (username, name, email, password, `level`, avatar, banner, join_date, phone, address, bio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "sssssssssss",
            $data['username'],
            $data['name'],
            $data['email'],
            $data['password'],
            $data['level'],
            $data['avatar'],
            $data['banner'],
            $data['join_date'],
            $data['phone'],
            $data['address'],
            $data['bio']
        );
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function updateUser($id, array $data)
    {
        // build dynamic update (password optional)
        $fields = ['username = ?', 'name = ?', 'email = ?', 'level = ?', 'avatar = ?', 'banner = ?', 'phone = ?', 'address = ?', 'bio = ?'];
        $types = "sssssssss";
        $params = [
            $data['username'],
            $data['name'],
            $data['email'],
            $data['level'],
            $data['avatar'],
            $data['banner'],
            $data['phone'],
            $data['address'],
            $data['bio']
        ];

        if (!empty($data['password'])) {
            array_unshift($fields, 'password = ?');
            $types = 's' . $types;
            array_unshift($params, $data['password']);
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = mysqli_prepare($this->conn, $sql);
        if (!$stmt) return false;

        // bind params + id
        $typesFinal = $types . "i";
        $params[] = $id;
        $refs = [];
        foreach ($params as $k => $v) $refs[$k] = &$params[$k];
        array_unshift($refs, $typesFinal);
        call_user_func_array('mysqli_stmt_bind_param', array_merge([$stmt], $refs));

        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }

    public function deleteUser($id)
    {
        $stmt = mysqli_prepare($this->conn, "DELETE FROM users WHERE id = ?");
        if (!$stmt) return false;
        mysqli_stmt_bind_param($stmt, "i", $id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return (bool)$ok;
    }
}
