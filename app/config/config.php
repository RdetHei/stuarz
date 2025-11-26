<?php 
$tz = 'Asia/Jakarta';
if (!ini_get('date.timezone')) {
	date_default_timezone_set($tz);
}
$GLOBALS['app_timezone'] = $tz;

$config = mysqli_connect("localhost","root","","stuarz");
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

function base_url($path = '') {
    return "http://localhost/stuarz/public/" . ltrim($path, '/');
}
// Load helpers
if (is_file(__DIR__ . '/../helpers/csrf.php')) require_once __DIR__ . '/../helpers/csrf.php';
if (is_file(__DIR__ . '/../helpers/media_helper.php')) require_once __DIR__ . '/../helpers/media_helper.php';
