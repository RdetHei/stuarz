<?php 
$config = mysqli_connect("localhost","root","","stuarz");
 
// Check connection
if (mysqli_connect_errno()){
	echo "Koneksi database gagal : " . mysqli_connect_error();
}

function base_url($path = '') {
    return "http://localhost/stuarz/" . ltrim($path, '/');
}
