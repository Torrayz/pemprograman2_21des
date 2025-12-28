<?php 
include '../config/koneksi.php'; 

// Get and validate ID
if (!isset($_GET['id'])) {
    header("location:data-peserta.php");
    exit();
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Delete query
$query = "DELETE FROM peserta WHERE id_peserta='$id'";

if (mysqli_query($koneksi, $query)) {
    header("location:data-peserta.php?status=dihapus");
} else {
    header("location:data-peserta.php?error=gagal_hapus");
}
exit();
?>
