<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Peserta - MISTER KOMPUTER</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="app-header">
        <h2>MISTER KOMPUTER</h2>
        <p>Sistem Manajemen Data Peserta Kursus</p>
    </div>

    <!-- NAV BUTTONS -->
    <div class="nav-buttons">
        <a href="../index.php" class="button button-secondary">← Kembali ke Beranda</a>
        <a href="tambah.php" class="button button-primary">+ Tambah Peserta Baru</a>
    </div>

    <!-- ALERT -->
    <?php if (isset($_GET['status'])) { 
        $status = htmlspecialchars($_GET['status']);
        $statusText = match($status) {
            'ditambahkan' => 'Data peserta berhasil ditambahkan',
            'diubah' => 'Data peserta berhasil diperbarui',
            'dihapus' => 'Data peserta berhasil dihapus',
            default => 'Operasi berhasil'
        };
    ?>
        <div class="alert alert-success">
            <strong>Berhasil!</strong> <?= $statusText; ?>
        </div>
    <?php } ?>

    <!-- TABLE WRAPPER -->
    <div class="table-wrapper">
        <!-- TABLE -->
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peserta</th>
                    <th>Jenis Kelamin</th>
                    <th>Program Kursus</th>
                    <th>Alamat</th>
                    <th>No. Telepon</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM peserta ORDER BY id_peserta DESC";
            $data = mysqli_query($koneksi, $query);
            $no = 1;
            
            if (mysqli_num_rows($data) == 0) {
                echo "<tr><td colspan='7' class='text-center'>Belum ada data peserta. <a href='tambah.php' class='link'>Tambah data?</a></td></tr>";
            }
            
            while ($d = mysqli_fetch_assoc($data)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($d['nama_peserta']); ?></td>
                    <td><?= htmlspecialchars($d['jenis_kelamin']); ?></td>
                    <td><?= htmlspecialchars($d['program_kursus']); ?></td>
                    <td><?= htmlspecialchars($d['alamat']); ?></td>
                    <td><?= htmlspecialchars($d['no_telepon']); ?></td>
                    <td class="action-buttons">
                        <a href="edit.php?id=<?= $d['id_peserta']; ?>" class="btn btn-edit">Edit</a>
                        <a href="hapus.php?id=<?= $d['id_peserta']; ?>"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                           class="btn btn-delete">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>
            Dibuat oleh <b>Tri Puji Antoro</b><br>
            NIM: <b>221011402646</b><br>
            Program Studi Teknik Informatika – Universitas Pamulang
        </p>
    </div>

</div>

</body>
</html>
