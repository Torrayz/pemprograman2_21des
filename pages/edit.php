<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Peserta - MISTER KOMPUTER</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="app-header">
        <h2>Edit Data Peserta</h2>
        <p>Perbarui informasi peserta kursus</p>
    </div>

    <!-- BACK BUTTON -->
    <a href="data-peserta.php" class="button button-secondary">← Kembali ke Data Peserta</a>

    <!-- FORM -->
    <?php
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = "SELECT * FROM peserta WHERE id_peserta='$id'";
    $result = mysqli_query($koneksi, $query);
    $d = mysqli_fetch_assoc($result);

    if (!$d) {
        echo "<p class='error'>Data peserta tidak ditemukan.</p>";
        exit();
    }
    ?>

    <form method="POST" class="form-container">
        <div class="form-group">
            <h4>Informasi Peserta</h4>
            
            <div class="form-field">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($d['nama_peserta']); ?>" required>
            </div>

            <div class="form-field">
                <label>Jenis Kelamin *</label>
                <select name="jk" required>
                    <option value="Laki-laki" <?= ($d['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?= ($d['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <div class="form-field">
                <label>Program Kursus *</label>
                <select name="program" required>
                    <option value="Microsoft Office" <?= ($d['program_kursus'] == 'Microsoft Office') ? 'selected' : ''; ?>>Microsoft Office</option>
                    <option value="Database & SQL" <?= ($d['program_kursus'] == 'Database & SQL') ? 'selected' : ''; ?>>Database & SQL</option>
                    <option value="Web Design" <?= ($d['program_kursus'] == 'Web Design') ? 'selected' : ''; ?>>Web Design</option>
                    <option value="Graphic Design" <?= ($d['program_kursus'] == 'Graphic Design') ? 'selected' : ''; ?>>Graphic Design</option>
                    <option value="Data Analytics" <?= ($d['program_kursus'] == 'Data Analytics') ? 'selected' : ''; ?>>Data Analytics</option>
                    <option value="Sistem & Networking" <?= ($d['program_kursus'] == 'Sistem & Networking') ? 'selected' : ''; ?>>Sistem & Networking</option>
                </select>
            </div>

            <div class="form-field">
                <label>Alamat *</label>
                <textarea name="alamat" required><?= htmlspecialchars($d['alamat']); ?></textarea>
            </div>

            <div class="form-field">
                <label>Nomor Telepon *</label>
                <input type="tel" name="telp" value="<?= htmlspecialchars($d['no_telepon']); ?>" required>
            </div>

            <button type="submit" name="update" class="button button-primary">Perbarui Data Peserta</button>
        </div>
    </form>

    <!-- PROCESS FORM -->
    <?php
    if (isset($_POST['update'])) {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jk = mysqli_real_escape_string($koneksi, $_POST['jk']);
        $program = mysqli_real_escape_string($koneksi, $_POST['program']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
        
        $query = "UPDATE peserta SET 
                  nama_peserta='$nama', 
                  jenis_kelamin='$jk', 
                  program_kursus='$program', 
                  alamat='$alamat', 
                  no_telepon='$telp' 
                  WHERE id_peserta='$id'";
        
        if (mysqli_query($koneksi, $query)) {
            header("location:data-peserta.php?status=diubah");
            exit();
        } else {
            echo "<p class='error'>Error: " . mysqli_error($koneksi) . "</p>";
        }
    }
    ?>

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
