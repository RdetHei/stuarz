<?php
// Media URL resolver helper
// Usage: resolve_media_url($path) -> returns a web-accessible path (may be prefixed)

function resolve_media_url(string $path): string {
    $path = trim($path);
    if ($path === '') return '';
    // If absolute URL, return as-is
    if (preg_match('#^https?://#i', $path)) return $path;

    // Compute prefix similar to profile.php
    $baseUrl = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    if ($baseUrl === '/') $baseUrl = '';
    $prefix = ($baseUrl ? $baseUrl . '/' : '');

    // Candidate with prefix
    $candidate = $prefix . ltrim($path, '/\\');

    $docRoot = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/\\');
    if ($docRoot) {
        $fsCandidate = $docRoot . '/' . ltrim($candidate, '/\\');
        if (is_file($fsCandidate)) return $candidate;
        $fsAlt = $docRoot . '/' . ltrim($path, '/\\');
        if (is_file($fsAlt)) return ltrim($path, '/\\');
    }

    // Fallback: return path prefixed (might still work for setups)
    return $candidate;
}
