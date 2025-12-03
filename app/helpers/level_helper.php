<?php
/**
 * Level helper utilities to centralize mapping between legacy and normalized level values.
 */
function normalize_level(string $raw = null): ?string {
    if ($raw === null) return null;
    $r = trim(strtolower($raw));
    if ($r === 'teacher') return 'guru';
    if ($r === 'student') return 'user';
    if ($r === 'guru' || $r === 'user' || $r === 'admin') return $r;
    return $r; // unknown value, return as-is
}

function is_teacher_level($raw): bool {
    $n = normalize_level((string)$raw);
    return $n === 'guru' || $n === 'admin';
}

function is_student_level($raw): bool {
    $n = normalize_level((string)$raw);
    return $n === 'user';
}

function display_level_label($raw): string {
    $n = normalize_level((string)$raw);
    if ($n === 'admin') return 'Admin';
    if ($n === 'guru') return 'Teacher';
    if ($n === 'user') return 'Student';
    return ucfirst($raw);
}

?>
