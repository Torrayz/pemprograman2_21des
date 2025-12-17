<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Peserta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">
    <div class="app-header">
        <h2>Tambah Peserta</h2>
        <p>Masukkan data peserta kursus</p>
    </div>

<form method="POST">
    <h4>Informasi Peserta</h4>
    <input type="text" name="nama" placeholder="Nama Peserta" required>
    <select name="jk">
        <option>Laki-laki</option>
        <option>Perempuan</option>
    </select>
    <input type="text" name="program" placeholder="Program Kursus">
    <textarea name="alamat" placeholder="Alamat"></textarea>
    <input type="text" name="telp" placeholder="Nomor Telepon">
    <button type="submit" name="simpan">Simpan</button>
</form>

<?php
if (isset($_POST['simpan'])) {
    mysqli_query($koneksi, "INSERT INTO peserta VALUES (
        '', '$_POST[nama]', '$_POST[jk]',
        '$_POST[program]', '$_POST[alamat]', '$_POST[telp]'
    )");
    header("location:index.php?status=ditambahkan");
}
?>
</div>
</body>
</html>
