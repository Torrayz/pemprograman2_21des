<?php
$host = "database";   // ganti jadi localhost
$user = "root";
$pass = "092004";    // hapus pw sesuai pw kita
$db   = "tripujiantoro"; //

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal konek: " . mysqli_connect_error());
}
?>