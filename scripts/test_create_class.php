<?php
// Quick test harness to simulate class creation via controller->store()
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/controller/ClassController.php';

// Simulate session user
session_start();
$_SESSION['user'] = ['id' => 1, 'level' => 'admin', 'username' => 'testadmin'];

// Simulate POST payload
$_POST['name'] = 'Test Class ' . rand(1000,9999);
$_POST['code'] = '';
$_POST['description'] = 'Created by automated test.';

try {
    $ctrl = new ClassController();
    // Capture output and exceptions
    ob_start();
    $ctrl->store();
    $out = ob_get_clean();
    echo "Controller output:\n" . $out . "\n";
} catch (Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
