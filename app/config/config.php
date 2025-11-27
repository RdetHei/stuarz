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
