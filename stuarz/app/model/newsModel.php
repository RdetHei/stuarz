<?php
class NewsModel {
	private $db;

	public function __construct($config) {
		// In this app, $config is a mysqli connection (see app/config/config.php)
		$this->db = ($config instanceof mysqli) ? $config : null;
	}

	public function categories() {
		if (!$this->db) return [];
		$cats = [];
		$sql = "SELECT DISTINCT category FROM news WHERE category IS NOT NULL AND category <> '' ORDER BY category ASC";
		if ($res = $this->db->query($sql)) {
			while ($row = $res->fetch_assoc()) {
				$cats[] = $row['category'];
			}
			$res->free();
		}
		return $cats;
	}

	public function filterPaginated($q, $cat, $page, $perPage) {
		if (!$this->db) return ['rows' => [], 'total' => 0];

		list($whereSql, $types, $values) = $this->buildFilters($q, $cat);

		// Total count
		$countSql = "SELECT COUNT(*) AS c FROM news" . ($whereSql ? " WHERE $whereSql" : "");
		$total = 0;
		if ($stmt = $this->db->prepare($countSql)) {
			if ($types) { $stmt->bind_param($types, ...$values); }
			if ($stmt->execute() && ($res = $stmt->get_result())) {
				$row = $res->fetch_assoc();
				$total = (int)$row['c'];
				$res->free();
			}
			$stmt->close();
		}

		$offset = max(0, ((int)$page - 1) * max(1, (int)$perPage));
		$limit = max(1, (int)$perPage);

		// Rows query
		$listSql = "SELECT id, title, content, category, thumbnail, author, created_at FROM news"
			. ($whereSql ? " WHERE $whereSql" : "")
			. " ORDER BY created_at DESC, id DESC LIMIT ?, ?";

		$rows = [];
		if ($stmt = $this->db->prepare($listSql)) {
			$listTypes = $types . "ii";
			$listValues = $values;
			$listValues[] = $offset;
			$listValues[] = $limit;
			$stmt->bind_param($listTypes, ...$listValues);
			if ($stmt->execute() && ($res = $stmt->get_result())) {
				while ($r = $res->fetch_assoc()) {
					$rows[] = $r;
				}
				$res->free();
			}
			$stmt->close();
		}

		return ['rows' => $rows, 'total' => $total];
	}

	public function find($id) {
		if (!$this->db) return null;
		$sql = "SELECT id, title, content, category, thumbnail, author, created_at FROM news WHERE id = ? LIMIT 1";
		if ($stmt = $this->db->prepare($sql)) {
			$stmt->bind_param("i", $id);
			if ($stmt->execute() && ($res = $stmt->get_result())) {
				$row = $res->fetch_assoc();
				$res->free();
				$stmt->close();
				return $row ?: null;
			}
			$stmt->close();
		}
		return null;
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
