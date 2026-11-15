<?php include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!valid_csrf()) { http_response_code(403); exit('Token keamanan tidak valid.'); }
    if (($_POST['action'] ?? '') === 'delete') {
        $id = filter_var($_POST['id_course'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) { http_response_code(422); exit('ID kursus tidak valid.'); }
        $stmt = $koneksi->prepare('DELETE FROM courses WHERE id_course=?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
        header('Location: manage-courses.php');
        exit;
    }
    $name = trim((string)($_POST['nama_course'] ?? ''));
    $description = trim((string)($_POST['deskripsi'] ?? ''));
    $price = (float)($_POST['harga'] ?? 0);
    $hours = (int)($_POST['durasi_jam'] ?? 0);
    $instructor = trim((string)($_POST['instruktur'] ?? ''));
    $capacity = (int)($_POST['kapasitas'] ?? 0);
    if ($name === '' || $description === '' || $instructor === '' || $price < 0 || $hours < 1 || $capacity < 1) {
        $message = 'Data kursus tidak valid.';
    } else {
        if (!empty($_POST['id_course'])) {
            $id = (int)$_POST['id_course'];
            $stmt = $koneksi->prepare('UPDATE courses SET nama_course=?,deskripsi=?,harga=?,durasi_jam=?,instruktur=?,kapasitas=? WHERE id_course=?');
            $stmt->bind_param('ssdisii',$name,$description,$price,$hours,$instructor,$capacity,$id);
        } else {
            $stmt = $koneksi->prepare('INSERT INTO courses(nama_course,deskripsi,harga,durasi_jam,instruktur,kapasitas) VALUES(?,?,?,?,?,?)');
            $stmt->bind_param('ssdisi',$name,$description,$price,$hours,$instructor,$capacity);
        }
        $stmt->execute();
        header('Location: manage-courses.php');
        exit;
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
        
        <form method="POST" class="form-admin"><?php echo csrf_input(); ?>
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
                        <form method="POST" style="display:inline"><?php echo csrf_input(); ?><input type="hidden" name="id_course" value="<?php echo (int)$row['id_course']; ?>"><button type="submit" name="action" value="delete" class="btn btn-small btn-danger" onclick="return confirm('Hapus kursus dan pendaftaran terkait?')">Hapus</button></form>
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
