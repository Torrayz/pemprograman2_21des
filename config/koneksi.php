<?php
// Database Connection Configuration
$koneksi = mysqli_connect("localhost", "root", "", "tripujiantoro");

// Check connection
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($koneksi, "utf8");

// Session start
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
