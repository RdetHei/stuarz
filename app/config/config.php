<?php 
$tz = 'Asia/Jakarta';
if (!ini_get('date.timezone')) {
	date_default_timezone_set($tz);
}
$GLOBALS['app_timezone'] = $tz;
$OPENAI_API_KEY = "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";

$GLOBALS['OPENAI_API_KEY'] = $OPENAI_API_KEY;

$config = mysqli_connect("localhost","root","","stuarz");

if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

function base_url($path = '') {
    return "http://localhost/stuarz/public/" . ltrim($path, '/');
}

if (file_exists(__DIR__ . '/../helpers/level_helper.php')) {
	require_once __DIR__ . '/../helpers/level_helper.php';
}
