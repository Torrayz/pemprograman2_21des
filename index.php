<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>MKOM - Data Peserta</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- HERO -->
    <div class="app-header hero">
        <h2>MISTER KOMPUTER</h2>
        <p>Sistem Manajemen Peserta Kursus</p>
    </div>

    <a href="tambah.php" class="button">+ Tambah Peserta</a>

    <?php if (isset($_GET['status'])) { ?>
        <div class="alert">
            Data berhasil <?= $_GET['status']; ?>
        </div>
    <?php } ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Program</th>
                <th>Alamat</th>
                <th>Telp</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $data = mysqli_query($koneksi, "SELECT * FROM peserta");
        if (mysqli_num_rows($data) == 0) {
            echo "<tr><td colspan='7'>Belum ada data peserta</td></tr>";
        }
        while ($d = mysqli_fetch_assoc($data)) {
        ?>
            <tr>
                <td><?= $d['id_peserta']; ?></td>
                <td><?= $d['nama_peserta']; ?></td>
                <td><?= $d['jenis_kelamin']; ?></td>
                <td><?= $d['program_kursus']; ?></td>
                <td><?= $d['alamat']; ?></td>
                <td><?= $d['no_telepon']; ?></td>
                <td>
                    <a href="edit.php?id=<?= $d['id_peserta']; ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus.php?id=<?= $d['id_peserta']; ?>"
                       onclick="return confirm('Yakin hapus data?')"
                       class="btn btn-delete">Hapus</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <!-- IDENTITAS -->
    <div class="footer">
        <p>
            Dibuat oleh <b>Tri Puji Antoro</b><br>
            NIM: <b>221011402646</b><br>
            Teknik Informatika – Universitas Pamulang
        </p>
    </div>

</div>

</body>
</html>
