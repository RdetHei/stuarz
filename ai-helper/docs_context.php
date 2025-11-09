<?php
// ai-helper/docs_context.php
// Returns up to 3 relevant documentation rows for a given question
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config.php';

try {
    $question = trim($_POST['question'] ?? ($_GET['question'] ?? ''));
    if ($question === '') {
        echo json_encode(['docs' => []]);
        exit;
    }

    $rows = search_documentation($question);

    echo json_encode(['docs' => $rows], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}
