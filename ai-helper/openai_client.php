<?php

require_once __DIR__ . '/../app/config/config.php';

function askOpenAI(string $prompt, array $contextDocs = [], array $opts = []) : ?string {

    $apiKey = $GLOBALS['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY');

    if (!$apiKey) {
        error_log("OPENAI_API_KEY not configured");
        return null;
    }

    // Build prompt
    $fullPrompt = "You are Shorekeeper, AI assistant for Stuarz.\n";
    $fullPrompt .= "Answer clearly based on user question.\n\n";

    if (!empty($contextDocs)) {
        $fullPrompt .= "Relevant documentation:\n";
        foreach ($contextDocs as $doc) {
            $fullPrompt .= "- " . ($doc['title'] ?? 'Untitled') . "\n";
            $fullPrompt .= strip_tags($doc['content'] ?? '') . "\n\n";
        }
    }

    $fullPrompt .= "User question:\n$prompt";

    // Build API payload
    $body = json_encode([
        "model" => $opts["model"] ?? "gpt-4.1-mini",
        "input" => $fullPrompt,
        "temperature" => $opts["temperature"] ?? 0.2,
        "max_output_tokens" => $opts["max_tokens"] ?? 512
    ]);

    $ch = curl_init("https://api.openai.com/v1/responses");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ],
        CURLOPT_POSTFIELDS => $body
    ]);

    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err || $http >= 400) {
        error_log("OpenAI error: HTTP $http / $err / $resp");
        return null;
    }

    $json = json_decode($resp, true);

    // FIX: Ambil output_text dari struktur yang benar
    if (!empty($json['output'][0]['content'][0]['text'])) {
        return $json['output'][0]['content'][0]['text'];
    }

    return null;
}
