<?php


class NewsModel {
    private $db;

    public function __construct($config) {
        $this->db = ($config instanceof mysqli) ? $config : null;
    }

    private function refValues(array $arr) {
        $refs = [];
        foreach ($arr as $k => $v) $refs[$k] = &$arr[$k];
        return $refs;
    }

    public function categories() {
        if (!$this->db) return [];
        $cats = [];
        $sql = "SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC";
        if ($res = $this->db->query($sql)) {
            while ($row = $res->fetch_assoc()) $cats[] = $row['category'];
            $res->free();
        }
        return $cats;
    }

    public function filterPaginated($q, $cat, $page, $perPage) {
        if (!$this->db) return ['rows' => [], 'total' => 0];

        list($whereSql, $types, $values) = $this->buildFilters($q, $cat);

        $countSql = "SELECT COUNT(*) AS c FROM news" . ($whereSql ? " WHERE $whereSql" : "");
        $total = 0;
        if ($stmt = $this->db->prepare($countSql)) {
            if ($types !== "") {
                $params = array_merge([$types], $values);
                call_user_func_array([$stmt, 'bind_param'], $this->refValues($params));
            }
            if ($stmt->execute() && ($res = method_exists($stmt, 'get_result') ? $stmt->get_result() : null)) {
                if ($res) {
                    $row = $res->fetch_assoc();
                    $total = (int)($row['c'] ?? 0);
                    $res->free();
                }
            } else {
                if ($stmt->bind_result($c) && $stmt->execute()) {
                    $stmt->fetch();
                    $total = (int)$c;
                }
            }
            $stmt->close();
        }

        $offset = max(0, ((int)$page - 1) * max(1, (int)$perPage));
        $limit = max(1, (int)$perPage);

        $listSql = "SELECT n.id, n.title, n.content, n.category, n.thumbnail, n.author, n.created_at
                    FROM news n"
            . ($whereSql ? " WHERE $whereSql" : "")
            . " ORDER BY n.created_at DESC, n.id DESC LIMIT ?, ?";

        $rows = [];
        if ($stmt = $this->db->prepare($listSql)) {
            $listTypes = $types . "ii";
            $listValues = $values;
            $listValues[] = $offset;
            $listValues[] = $limit;
            $params = array_merge([$listTypes], $listValues);
            call_user_func_array([$stmt, 'bind_param'], $this->refValues($params));

            if ($stmt->execute()) {
                if (method_exists($stmt, 'get_result')) {
                    $res = $stmt->get_result();
                    while ($r = $res->fetch_assoc()) $rows[] = $r;
                    if ($res) $res->free();
                } else {
                    $meta = $stmt->result_metadata();
                    if ($meta) {
                        $fields = [];
                        $row = [];
                        while ($field = $meta->fetch_field()) {
                            $fields[] = &$row[$field->name];
                        }
                        call_user_func_array([$stmt, 'bind_result'], $fields);
                        while ($stmt->fetch()) {
                            $rec = [];
                            foreach ($row as $k => $v) $rec[$k] = $v;
                            $rows[] = $rec;
                        }
                        $meta->free();
                    }
                }
            }
            $stmt->close();
        }

        return ['rows' => $rows, 'total' => $total];
    }

    public function find($id) {
        if (!$this->db) return null;
        $sql = "SELECT n.id, n.title, n.content, n.category, n.thumbnail, n.author, n.created_at
                FROM news n
                WHERE n.id = ? LIMIT 1";
        if ($stmt = $this->db->prepare($sql)) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                if (method_exists($stmt, 'get_result')) {
                    $res = $stmt->get_result();
                    $row = $res ? $res->fetch_assoc() : null;
                    if ($res) $res->free();
                    $stmt->close();
                    return $row ?: null;
                } else {
                    $stmt->bind_result($id, $title, $content, $category, $thumbnail, $author, $created_at);
                    if ($stmt->fetch()) {
                        $stmt->close();
                        return compact('id','title','content','category','thumbnail','author','created_at');
                    }
                }
            }
            $stmt->close();
        }
        return null;
    }

    public function delete($id) {
        if (!$this->db) return false;
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
    }

    private function buildFilters($q, $cat) {
        $where = [];
        $types = "";
        $values = [];
        $q = trim((string)$q);
        $cat = trim((string)$cat);
        if ($q !== '') {
            $where[] = "(title LIKE ? OR content LIKE ?)";
            $like = '%' . $q . '%';
            $types .= "ss";
            $values[] = $like;
            $values[] = $like;
        }
        if ($cat !== '') {
            $where[] = "category = ?";
            $types .= "s";
            $values[] = $cat;
        }
        $whereSql = implode(' AND ', $where);
        return [$whereSql, $types, $values];
    }
}
?>
