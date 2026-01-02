<?php 
include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Mister Komputer</title>
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
            <li><a href="courses.php" class="nav-link">Kursus Kami</a></li>
            <li><a href="dashboard-user.php" class="nav-link active btn-nav">Dashboard</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="dashboard-header">
        <h1>Dashboard Saya</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Profil Saya</h3>
            <div class="profile-info">
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <p><strong>Role:</strong> <span class="badge">User</span></p>
            </div>
        </div>

        <?php
        $id_user = $_SESSION['id_user'];

        // Get user data
        $user_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user = $id_user"));

        // Count stats
        $total_enrolled = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments WHERE id_user = $id_user"))['total'];
        $paid_count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments WHERE id_user = $id_user AND status_pembayaran = 'sudah_bayar'"))['total'];
        $total_spent = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah_pembayaran) as total FROM payments WHERE id_user = $id_user AND status_pembayaran = 'verified'"))['total'] ?? 0;
        ?>

        <div class="dashboard-card">
            <h3>Statistik</h3>
            <div class="stats-mini">
                <div class="stat-mini-item">
                    <span class="stat-number"><?php echo $total_enrolled; ?></span>
                    <span class="stat-label">Kursus Terdaftar</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-number"><?php echo $paid_count; ?></span>
                    <span class="stat-label">Sudah Dibayar</span>
                </div>
                <div class="stat-mini-item">
                    <span class="stat-number">Rp <?php echo number_format($total_spent, 0, ',', '.'); ?></span>
                    <span class="stat-label">Total Pengeluaran</span>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Kursus Saya</h2>
        
        <?php
        $enrollments = mysqli_query($koneksi, "SELECT e.*, c.nama_course, c.harga, c.durasi_jam FROM enrollments e 
                                              JOIN courses c ON e.id_course = c.id_course 
                                              WHERE e.id_user = $id_user
                                              ORDER BY e.tanggal_daftar DESC");

        if (mysqli_num_rows($enrollments) == 0) {
            echo '<div class="alert alert-info">Anda belum terdaftar pada kursus apapun. <a href="courses.php">Lihat semua kursus</a></div>';
        } else {
        ?>
        <div class="enrollments-table">
            <table>
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Tanggal Daftar</th>
                        <th>Harga</th>
                        <th>Status Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($enrollments)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_course']); ?></td>
                        <td><?php echo date('d M Y', strtotime($row['tanggal_daftar'])); ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td>
                            <?php 
                            if ($row['status_pembayaran'] == 'belum_bayar') {
                                echo '<span class="badge badge-danger">Belum Bayar</span>';
                            } elseif ($row['status_pembayaran'] == 'verifikasi') {
                                echo '<span class="badge badge-warning">Verifikasi</span>';
                            } else {
                                echo '<span class="badge badge-success">Sudah Bayar</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <?php if ($row['status_pembayaran'] == 'belum_bayar'): ?>
                                <a href="payment.php?id_enrollment=<?php echo $row['id_enrollment']; ?>" class="btn btn-small">Bayar</a>
                            <?php else: ?>
                                <a href="course-detail.php?id=<?php echo $row['id_course']; ?>" class="btn btn-small">Lihat</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>

    <div style="margin-top: 40px;">
        <a href="courses.php" class="btn btn-primary">Lihat Kursus Lainnya</a>
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
                <li><a href="dashboard-user.php">Dashboard</a></li>
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
