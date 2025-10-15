<?php
require_once dirname(__DIR__) . '/config/config.php';

class GradeController
{
    private $db;

    public function __construct()
    {
        global $config; // Use the global database connection
        $this->db = $config;
    }
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        global $config; // mysqli connection
        $userId = (int)($_SESSION['user']['id'] ?? 0);
        if ($userId <= 0) {
            echo 'User tidak valid';
            return;
        }

        // Queries
        // Total Grades
        $totalGrades = 0;
        $stmt = mysqli_prepare($config, 'SELECT COUNT(*) AS total FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $totalGrades = (int)($row['total'] ?? 0); }

        // Average Grade
        $avgGrade = 0.0;
        $stmt = mysqli_prepare($config, 'SELECT ROUND(AVG(score), 1) AS avg_score FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $avgGrade = (float)($row['avg_score'] ?? 0); }

        // Highest Grade
        $highGrade = 0.0;
        $stmt = mysqli_prepare($config, 'SELECT MAX(score) AS max_score FROM grades WHERE user_id = ?');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $highGrade = (float)($row['max_score'] ?? 0); }

        // This Week (last 7 days)
        $thisWeekCount = 0;
        $stmt = mysqli_prepare($config, 'SELECT COUNT(*) AS cnt FROM grades WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) { $row = mysqli_fetch_assoc($res); $thisWeekCount = (int)($row['cnt'] ?? 0); }

        // Sidebar Subjects + average per subject for the user
        $subjects = [];
        $sql = 'SELECT s.id, s.name, COUNT(g.id) AS total, ROUND(AVG(g.score),1) AS avg_score
                FROM subjects s
                LEFT JOIN grades g ON g.subject_id = s.id AND g.user_id = ?
                GROUP BY s.id, s.name
                ORDER BY s.name ASC';
        $stmt = mysqli_prepare($config, $sql);
        mysqli_stmt_bind_param($stmt, 'i', $userId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) { $subjects[] = $row; }
        }

        // Recent Grades (limit 8) with subject name
        $recent = [];
        $stmt = mysqli_prepare($this->db, "
            SELECT 
                g.id,
                s.name AS subject_name,
                t.title AS task_title,
                g.score,
                g.created_at
            FROM grades g
            JOIN subjects s ON g.subject_id = s.id
            LEFT JOIN tasks_completed t ON g.task_id = t.id
            WHERE g.user_id = ?
            ORDER BY g.created_at DESC
            LIMIT 8
        ");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $recent = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Pass to view
        $data = [
            'totalGrades' => $totalGrades,
            'avgGrade' => $avgGrade,
            'highGrade' => $highGrade,
            'thisWeek' => $thisWeekCount,
            'subjects' => $subjects,
            'recent' => $recent,
        ];

        // Render
    // Ambil semua grades untuk list utama (bukan hanya recent)
    $grades = [];
    $sql = 'SELECT g.*, u.username, s.name AS subject_name, t.title AS task_title, c.name AS class_name
        FROM grades g
        LEFT JOIN users u ON g.user_id = u.id
        LEFT JOIN subjects s ON g.subject_id = s.id
        LEFT JOIN tasks_completed t ON g.task_id = t.id
        LEFT JOIN classes c ON g.class_id = c.id';
    $result = $this->db->query($sql);
    if ($result) while ($row = $result->fetch_assoc()) $grades[] = $row;

    // Untuk filter dropdown (subjects/classes)
    $subjectsList = $this->db->query('SELECT * FROM subjects ORDER BY name ASC');
    $subjects = $subjectsList ? $subjectsList->fetch_all(MYSQLI_ASSOC) : [];
    $classesList = $this->db->query('SELECT * FROM classes ORDER BY name ASC');
    $classes = $classesList ? $classesList->fetch_all(MYSQLI_ASSOC) : [];

    // Render view baru
    $content = dirname(__DIR__) . '/views/pages/grades/index.php';
    include dirname(__DIR__) . '/views/layouts/dLayout.php';
    }
}
