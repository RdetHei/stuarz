<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// FIX #1: Perbaiki path config
require_once __DIR__ . '/../app/config/config.php';




// Import OpenAI client
require_once __DIR__ . '/openai_client.php';

$question = trim($_POST['question'] ?? '');
if ($question === '') {
    echo json_encode(['error' => true, 'message' => 'Question is required']);
    exit;
}

// FIX #3: Cegah error undefined function
$docs = []
    ? search_documentation($question)
    : [];

$ai = askOpenAI($question, $docs, [
    "temperature" => 0.2,
    "max_tokens" => 512
]);

if ($ai !== null) {
    echo json_encode([
        'answer' => $ai,
        'used_docs' => array_map(fn($d) => $d['title'], $docs)
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode([
    'answer' => 'Maaf, AI tidak tersedia.',
    'used_docs' => []
]);
exit;
