<?php
include 'config/koneksi.php';
$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM peserta WHERE id_peserta='$id'");
header("location:index.php?status=dihapus");
?>
