<?php 
include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mister Komputer</title>
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
            <li><a href="dashboard.php" class="nav-link active btn-nav">Dashboard</a></li>
            <li><a href="manage-courses.php" class="nav-link">Kelola Kursus</a></li>
            <li><a href="manage-users.php" class="nav-link">Kelola User</a></li>
            <li><a href="manage-payments.php" class="nav-link">Pembayaran</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</p>
    </div>

    <div class="admin-stats">
        <?php
        $total_users = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE role = 'user'"))['total'];
        $total_courses = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM courses"))['total'];
        $total_enrollments = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments"))['total'];
        $total_payments = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(jumlah_pembayaran) as total FROM payments WHERE status_pembayaran = 'verified'"))['total'] ?? 0;
        ?>

        <div class="stat-card-admin">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <span class="stat-label">Total User</span>
                <span class="stat-value"><?php echo $total_users; ?></span>
            </div>
        </div>

        <div class="stat-card-admin">
            <div class="stat-icon">📚</div>
            <div class="stat-content">
                <span class="stat-label">Total Kursus</span>
                <span class="stat-value"><?php echo $total_courses; ?></span>
            </div>
        </div>

        <div class="stat-card-admin">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <span class="stat-label">Total Pendaftar</span>
                <span class="stat-value"><?php echo $total_enrollments; ?></span>
            </div>
        </div>

        <div class="stat-card-admin">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">Rp <?php echo number_format($total_payments, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>

    <div class="admin-grid">
        <div class="admin-section">
            <h2>Kursus Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama Kursus</th>
                        <th>Harga</th>
                        <th>Peserta</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $courses = mysqli_query($koneksi, "SELECT c.*, COUNT(e.id_enrollment) as jumlah_peserta 
                    FROM courses c LEFT JOIN enrollments e ON c.id_course = e.id_course 
                    GROUP BY c.id_course ORDER BY c.created_at DESC LIMIT 5");
                    
                    while ($row = mysqli_fetch_assoc($courses)):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_course']); ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['jumlah_peserta']; ?> / <?php echo $row['kapasitas']; ?></td>
                        <td><a href="manage-courses.php" class="btn btn-small">Kelola</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-section">
            <h2>Pendaftaran Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Kursus</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $enrollments = mysqli_query($koneksi, "SELECT e.*, u.nama_lengkap, c.nama_course 
                    FROM enrollments e 
                    JOIN users u ON e.id_user = u.id_user 
                    JOIN courses c ON e.id_course = c.id_course 
                    ORDER BY e.tanggal_daftar DESC LIMIT 5");
                    
                    while ($row = mysqli_fetch_assoc($enrollments)):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_course']); ?></td>
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
                        <td><?php echo date('d M Y', strtotime($row['tanggal_daftar'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 40px;">
        <a href="manage-courses.php" class="btn btn-primary">Kelola Kursus</a>
        <a href="manage-users.php" class="btn btn-secondary">Kelola User</a>
        <a href="manage-payments.php" class="btn btn-secondary">Lihat Pembayaran</a>
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
