<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once __DIR__ . '/config.php';

error_log("Request started: " . date('Y-m-d H:i:s'));

try {
    $question = trim($_POST['question'] ?? '');
    if ($question === '') throw new Exception('Question is required');

    // Search documentation
    $docs = search_documentation($question);
    
    if (empty($docs)) {
        echo json_encode([
            'answer' => 'Maaf, saya tidak menemukan dokumentasi yang relevan dengan pertanyaan Anda.',
            'used_docs' => []
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Format all documents into a comprehensive answer with complete details
    $answer = '';
    foreach ($docs as $index => $doc) {
        // Add separator between documents
        if ($index > 0) {
            $answer .= "\n\n" . str_repeat("=", 31) . "\n\n";
        }

        // Document header with title and section
        $answer .= "📑 DOKUMEN " . ($index + 1) . "\n";
        $answer .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Title with section
        $answer .= "📌 Judul: {$doc['title']}\n";
        if (!empty($doc['section'])) {
            $answer .= "📂 Bagian: {$doc['section']}\n";
        }
        if (!empty($doc['slug'])) {
            $answer .= "🔖 Slug: {$doc['slug']}\n";
        }

        // Add page URL
        $page_url = get_page_url($doc['section'], $doc['slug']);
        $answer .= "🔗 Halaman: " . $page_url . "\n";
        $answer .= "\n";

        // Description if available
        if (!empty($doc['description'])) {
            $answer .= "📝 Deskripsi:\n";
            $answer .= strip_tags($doc['description']) . "\n\n";
        }
        
        // Content if available
        if (!empty($doc['content'])) {
            $answer .= "📄 Konten:\n";
            $answer .= strip_tags($doc['content']) . "\n";
        }

        // Document metadata
        $answer .= "\n🆔 ID Dokumen: {$doc['id']}";
    }

    // Add note about search terms if multiple documents found
    if (count($docs) > 1) {
        $answer .= "\n\n" . str_repeat("=", 31) . "\n\n";
        $answer .= "ℹ️ Catatan: Ditemukan " . count($docs) . " dokumen yang sesuai dengan pencarian Anda.";
    }

    // Add navigation suggestion
    $answer .= "\n\n📍 Untuk melihat informasi lebih detail, Anda dapat mengunjungi halaman-halaman di atas dengan mengklik menu yang sesuai di navigasi atau menggunakan URL yang diberikan.";

    $used = array_map(function($d) { return $d['title']; }, $docs);
    echo json_encode([
        'answer' => $answer,
        'used_docs' => $used
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    $errorMsg = $e->getMessage();
    $trace = $e->getTraceAsString();
    error_log("Shorekeeper Error: " . $errorMsg);
    error_log("Stack trace: " . $trace);
    error_log("Request data: " . print_r($_POST, true));
    http_response_code(500);
    echo json_encode([
        'error' => true, 
        'message' => $errorMsg,
        'debug' => [
            'time' => date('Y-m-d H:i:s'),
            'trace' => $trace
        ]
    ], JSON_UNESCAPED_UNICODE);
}
