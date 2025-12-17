<?php
include 'config/koneksi.php';
$id = $_GET['id'];
$d = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM peserta WHERE id_peserta='$id'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Peserta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="app-header">
        <h2>Edit Peserta</h2>
        <p>Perbarui data peserta</p>
    </div>

<form method="POST">
    <h4>Informasi Peserta</h4>
    <input type="text" name="nama" value="<?= $d['nama_peserta']; ?>">
    <input type="text" name="program" value="<?= $d['program_kursus']; ?>">
    <textarea name="alamat"><?= $d['alamat']; ?></textarea>
    <input type="text" name="telp" value="<?= $d['no_telepon']; ?>">
    <button type="submit" name="update">Update</button>
</form>

<?php
if (isset($_POST['update'])) {
    mysqli_query($koneksi, "UPDATE peserta SET
        nama_peserta='$_POST[nama]',
        program_kursus='$_POST[program]',
        alamat='$_POST[alamat]',
        no_telepon='$_POST[telp]'
        WHERE id_peserta='$id'
    ");
    header("location:index.php?status=diupdate");
}
?>
</div>
</body>
</html>
