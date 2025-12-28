<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peserta - MISTER KOMPUTER</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="container">
    <!-- HEADER -->
    <div class="app-header">
        <h2>Tambah Peserta Baru</h2>
        <p>Masukkan data peserta kursus dengan lengkap dan akurat</p>
    </div>

    <!-- BACK BUTTON -->
    <a href="data-peserta.php" class="button button-secondary">← Kembali ke Data Peserta</a>

    <!-- FORM -->
    <form method="POST" class="form-container">
        <div class="form-group">
            <h4>Informasi Peserta</h4>
            
            <div class="form-field">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="form-field">
                <label>Jenis Kelamin *</label>
                <select name="jk" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            <div class="form-field">
                <label>Program Kursus *</label>
                <select name="program" required>
                    <option value="">-- Pilih Program --</option>
                    <option value="Microsoft Office">Microsoft Office</option>
                    <option value="Database & SQL">Database & SQL</option>
                    <option value="Web Design">Web Design</option>
                    <option value="Graphic Design">Graphic Design</option>
                    <option value="Data Analytics">Data Analytics</option>
                    <option value="Sistem & Networking">Sistem & Networking</option>
                </select>
            </div>

            <div class="form-field">
                <label>Alamat *</label>
                <textarea name="alamat" placeholder="Jalan, No., Kelurahan, Kecamatan, Kota/Kabupaten" required></textarea>
            </div>

            <div class="form-field">
                <label>Nomor Telepon *</label>
                <input type="tel" name="telp" placeholder="Contoh: 085123456789" required>
            </div>

            <button type="submit" name="simpan" class="button button-primary">Simpan Data Peserta</button>
        </div>
    </form>

    <!-- PROCESS FORM -->
    <?php
    if (isset($_POST['simpan'])) {
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $jk = mysqli_real_escape_string($koneksi, $_POST['jk']);
        $program = mysqli_real_escape_string($koneksi, $_POST['program']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
        
        $query = "INSERT INTO peserta (nama_peserta, jenis_kelamin, program_kursus, alamat, no_telepon) 
                  VALUES ('$nama', '$jk', '$program', '$alamat', '$telp')";
        
        if (mysqli_query($koneksi, $query)) {
            header("location:data-peserta.php?status=ditambahkan");
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
