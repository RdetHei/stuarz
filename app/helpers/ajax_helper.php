<?php
function sendJsonResponse($data, $statusCode = 200) {
    if (ob_get_level()) {
        ob_clean();
    }
    http_response_code($statusCode);
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    echo json_encode($data);
    exit;
}

function sendJsonError($message, $statusCode = 400) {
    sendJsonResponse(['ok' => false, 'message' => $message], $statusCode);
}

function sendJsonSuccess($message = 'OK', $data = []) {
    $payload = array_merge(['ok' => true, 'message' => $message], $data);
    sendJsonResponse($payload, 200);
}
