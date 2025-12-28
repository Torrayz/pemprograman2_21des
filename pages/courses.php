<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kursus - Mister Komputer</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="nav-container">
        <div class="nav-logo">
            <span class="logo-icon">💻</span>
            <h1>MISTER KOMPUTER</h1>
        </div>
        <ul class="nav-menu">
            <li><a href="../index.php" class="nav-link">Beranda</a></li>
            <li><a href="courses.php" class="nav-link active">Kursus Kami</a></li>
            <?php if (isset($_SESSION['id_user'])): ?>
                <li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="../admin/dashboard.php" class="nav-link btn-nav">Admin Panel</a>
                    <?php else: ?>
                        <a href="dashboard-user.php" class="nav-link btn-nav">Dashboard</a>
                    <?php endif; ?>
                </li>
                <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="../auth/login.php" class="nav-link btn-nav">Login</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h1 style="text-align: center; margin-bottom: 10px;">Semua Program Kursus</h1>
    <p style="text-align: center; color: #666; margin-bottom: 50px;">Pilih program yang sesuai dengan kebutuhan Anda</p>

    <div class="courses-grid">
        <?php
        $query = "SELECT * FROM courses ORDER BY nama_course ASC";
        $result = mysqli_query($koneksi, $query);

        while ($row = mysqli_fetch_assoc($result)):
            $jumlah_peserta = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments WHERE id_course = " . $row['id_course']))['total'];
        ?>
        <div class="course-card-full">
            <div class="course-header">
                <h3><?php echo htmlspecialchars($row['nama_course']); ?></h3>
                <span class="badge-price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
            </div>
            
            <p class="course-description"><?php echo htmlspecialchars($row['deskripsi']); ?></p>

            <div class="course-info">
                <div class="info-item">
                    <span class="label">Durasi:</span>
                    <span class="value"><?php echo $row['durasi_jam']; ?> Jam</span>
                </div>
                <div class="info-item">
                    <span class="label">Instruktur:</span>
                    <span class="value"><?php echo htmlspecialchars($row['instruktur']); ?></span>
                </div>
                <div class="info-item">
                    <span class="label">Peserta:</span>
                    <span class="value"><?php echo $jumlah_peserta; ?> / <?php echo $row['kapasitas']; ?></span>
                </div>
            </div>

            <div class="course-actions">
                <a href="course-detail.php?id=<?php echo $row['id_course']; ?>" class="btn btn-primary">Lihat Detail & Daftar</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="footer-main">
    <div class="footer-container">
        <div class="footer-content">
            <h3>MISTER KOMPUTER</h3>
            <p>Lembaga kursus komputer terpercaya</p>
            <div class="footer-info">
                <p><strong>Dibuat oleh:</strong> Tri Puji Antoro (221011402646)</p>
            </div>
        </div>
        <div class="footer-links">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="../index.php">Beranda</a></li>
                <li><a href="courses.php">Kursus Kami</a></li>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <li><a href="dashboard-user.php">Dashboard</a></li>
                <?php endif; ?>
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
