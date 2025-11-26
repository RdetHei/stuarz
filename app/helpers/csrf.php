<?php
// Simple CSRF helper
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_get_token(): string {
    if (empty($_SESSION['_csrf_token']) || empty($_SESSION['_csrf_token_time']) || ($_SESSION['_csrf_token_time'] + 3600) < time()) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['_csrf_token_time'] = time();
    }
    return $_SESSION['_csrf_token'];
}

function csrf_field(): void {
    $t = csrf_get_token();
    echo '<input type="hidden" name="_csrf" value="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '" />';
}

function csrf_meta(): void {
    $t = csrf_get_token();
    echo '<meta name="csrf-token" content="' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_check(string $token = null): bool {
    if ($token === null) {
        // Check POST field first
        $token = $_POST['_csrf'] ?? null;
        if (!$token) {
            // Accept token from header too
            $headers = getallheaders();
            $token = $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? null;
        }
    }
    if (empty($token) || empty($_SESSION['_csrf_token'])) return false;
    return hash_equals($_SESSION['_csrf_token'], $token);
}

function csrf_require(): void {
    $isAjax = (!empty($_GET['ajax']) && $_GET['ajax'] == '1') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
    if (!csrf_check()) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Invalid CSRF token']);
            exit;
        }
        // Non-AJAX: set flash and redirect back
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['error'] = 'Token CSRF tidak valid. Silakan muat ulang halaman.';
        $back = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header('Location: ' . $back);
        exit;
    }
}
