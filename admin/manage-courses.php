<?php include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

// Handle delete course
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($koneksi, "DELETE FROM enrollments WHERE id_course = $id");
    mysqli_query($koneksi, "DELETE FROM courses WHERE id_course = $id");
    header('Location: manage-courses.php');
    exit();
}

// Handle add/edit course
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_course = mysqli_real_escape_string($koneksi, $_POST['nama_course']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga = floatval($_POST['harga']);
    $durasi_jam = intval($_POST['durasi_jam']);
    $instruktur = mysqli_real_escape_string($koneksi, $_POST['instruktur']);
    $kapasitas = intval($_POST['kapasitas']);

    if (isset($_POST['id_course']) && $_POST['id_course'] != '') {
        // Update
        $id = intval($_POST['id_course']);
        $update = mysqli_query($koneksi, "UPDATE courses SET nama_course = '$nama_course', deskripsi = '$deskripsi', 
        harga = $harga, durasi_jam = $durasi_jam, instruktur = '$instruktur', kapasitas = $kapasitas 
        WHERE id_course = $id");
        $message = $update ? 'Kursus berhasil diupdate!' : 'Gagal mengupdate kursus!';
    } else {
        // Insert
        $insert = mysqli_query($koneksi, "INSERT INTO courses (nama_course, deskripsi, harga, durasi_jam, instruktur, kapasitas) 
        VALUES ('$nama_course', '$deskripsi', $harga, $durasi_jam, '$instruktur', $kapasitas)");
        $message = $insert ? 'Kursus berhasil ditambahkan!' : 'Gagal menambahkan kursus!';
    }
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM courses WHERE id_course = $id"));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kursus - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <span class="logo-icon">💻</span>
            <h1>MISTER KOMPUTER - ADMIN</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="dashboard.php" class="nav-link">Dashboard</a></li>
            <li><a href="manage-courses.php" class="nav-link active btn-nav">Kelola Kursus</a></li>
            <li><a href="manage-users.php" class="nav-link">Kelola User</a></li>
            <li><a href="manage-payments.php" class="nav-link">Pembayaran</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h1>Kelola Kursus</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="admin-form-section">
        <h2><?php echo $edit_data ? 'Edit Kursus' : 'Tambah Kursus Baru'; ?></h2>
        
        <form method="POST" class="form-admin">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id_course" value="<?php echo $edit_data['id_course']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Nama Kursus</label>
                <input type="text" name="nama_course" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['nama_course']) : ''; ?>">
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" required><?php echo $edit_data ? htmlspecialchars($edit_data['deskripsi']) : ''; ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" required step="1000" value="<?php echo $edit_data ? $edit_data['harga'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Durasi (Jam)</label>
                    <input type="number" name="durasi_jam" required value="<?php echo $edit_data ? $edit_data['durasi_jam'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="number" name="kapasitas" required value="<?php echo $edit_data ? $edit_data['kapasitas'] : 30; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Instruktur</label>
                <input type="text" name="instruktur" required value="<?php echo $edit_data ? htmlspecialchars($edit_data['instruktur']) : 'Tri Puji Antoro'; ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?php echo $edit_data ? 'Update Kursus' : 'Tambah Kursus'; ?></button>
                <?php if ($edit_data): ?>
                    <a href="manage-courses.php" class="btn btn-secondary">Batal</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="admin-list-section">
        <h2>Daftar Kursus</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Kursus</th>
                    <th>Harga</th>
                    <th>Durasi</th>
                    <th>Peserta</th>
                    <th>Instruktur</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $courses = mysqli_query($koneksi, "SELECT c.*, COUNT(e.id_enrollment) as jumlah_peserta 
                FROM courses c LEFT JOIN enrollments e ON c.id_course = e.id_course 
                GROUP BY c.id_course ORDER BY c.created_at DESC");

                while ($row = mysqli_fetch_assoc($courses)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_course']); ?></td>
                    <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['durasi_jam']; ?> jam</td>
                    <td><?php echo $row['jumlah_peserta']; ?> / <?php echo $row['kapasitas']; ?></td>
                    <td><?php echo htmlspecialchars($row['instruktur']); ?></td>
                    <td>
                        <a href="manage-courses.php?edit=<?php echo $row['id_course']; ?>" class="btn btn-small">Edit</a>
                        <a href="manage-courses.php?delete=<?php echo $row['id_course']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Yakin hapus kursus ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 40px;">
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>

<footer class="footer-main">
    <div class="footer-container">
        <div class="footer-content">
            <h3>MISTER KOMPUTER</h3>
            <p>Admin Panel</p>
            <div class="footer-info">
                <p><strong>Dibuat oleh:</strong> Tri Puji Antoro (221011402646)</p>
            </div>
        </div>
        <div class="footer-links">
            <h4>Menu</h4>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-courses.php">Kelola Kursus</a></li>
                <li><a href="manage-users.php">Kelola User</a></li>
                <li><a href="manage-payments.php">Pembayaran</a></li>
            </ul>
        </div>
        <div class="footer-contact">
            <h4>Kontak</h4>
            <ul>
                <li>📧 info@misterkomputer.com</li>
                <li>📞 (021) 1234-5678</li>
                <li>📍 Jakarta, Indonesia</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Mister Komputer. Developed by Tri Puji Antoro (221011402646)</p>
    </div>
</footer>

</body>
</html>
