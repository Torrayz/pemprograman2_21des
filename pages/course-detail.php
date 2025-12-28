<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kursus - Mister Komputer</title>
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
    <?php
    $id_course = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $query = mysqli_query($koneksi, "SELECT * FROM courses WHERE id_course = $id_course");

    if (mysqli_num_rows($query) == 0) {
        echo '<div class="alert alert-error">Kursus tidak ditemukan!</div>';
        echo '<a href="courses.php" class="btn btn-primary">Kembali ke Daftar Kursus</a>';
    } else {
        $course = mysqli_fetch_assoc($query);
        $jumlah_peserta = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM enrollments WHERE id_course = $id_course"))['total'];

        // Check if user sudah daftar course ini
        $already_enrolled = false;
        if (isset($_SESSION['id_user'])) {
            $check = mysqli_query($koneksi, "SELECT * FROM enrollments WHERE id_user = " . $_SESSION['id_user'] . " AND id_course = $id_course");
            if (mysqli_num_rows($check) > 0) {
                $already_enrolled = true;
                // Fetch full enrollment data instead of just id_enrollment
                $enrollment = mysqli_fetch_assoc($check);
            }
        }

        // Handle enrollment
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
            if (!isset($_SESSION['id_user'])) {
                echo '<div class="alert alert-error">Anda harus login terlebih dahulu!</div>';
            } elseif ($already_enrolled) {
                echo '<div class="alert alert-error">Anda sudah mendaftar kursus ini!</div>';
            } elseif ($jumlah_peserta >= $course['kapasitas']) {
                echo '<div class="alert alert-error">Kapasitas kursus sudah penuh!</div>';
            } else {
                $insert = mysqli_query($koneksi, "INSERT INTO enrollments (id_user, id_course) VALUES (" . $_SESSION['id_user'] . ", $id_course)");
                if ($insert) {
                    echo '<div class="alert alert-success">Pendaftaran berhasil! Silakan lakukan pembayaran.</div>';
                    $already_enrolled = true;
                    $jumlah_peserta++;
                    // Fetch full enrollment data after insert
                    $check = mysqli_query($koneksi, "SELECT * FROM enrollments WHERE id_user = " . $_SESSION['id_user'] . " AND id_course = $id_course");
                    $enrollment = mysqli_fetch_assoc($check);
                }
            }
        }
    ?>

    <div class="course-detail-container">
        <div class="course-detail-header">
            <h1><?php echo htmlspecialchars($course['nama_course']); ?></h1>
            <div class="course-detail-meta">
                <span class="meta-badge">👨‍🏫 <?php echo htmlspecialchars($course['instruktur']); ?></span>
                <span class="meta-badge">⏱ <?php echo $course['durasi_jam']; ?> Jam</span>
                <span class="meta-badge">👥 <?php echo $jumlah_peserta; ?> / <?php echo $course['kapasitas']; ?> Peserta</span>
            </div>
        </div>

        <div class="course-detail-content">
            <div class="detail-left">
                <div class="detail-section">
                    <h3>Deskripsi Kursus</h3>
                    <p><?php echo nl2br(htmlspecialchars($course['deskripsi'])); ?></p>
                </div>

                <div class="detail-section">
                    <h3>Yang Akan Anda Pelajari</h3>
                    <ul class="learning-list">
                        <li>Konsep dasar dan praktek langsung</li>
                        <li>Project-project real dari industri</li>
                        <li>Best practices dan tips dari profesional</li>
                        <li>Sertifikat yang diakui industri</li>
                        <li>Akses materi pembelajaran seumur hidup</li>
                    </ul>
                </div>
            </div>

            <div class="detail-right">
                <div class="enrollment-card">
                    <div class="price-section">
                        <span class="label">Harga Kursus</span>
                        <span class="harga-besar">Rp <?php echo number_format($course['harga'], 0, ',', '.'); ?></span>
                    </div>

                    <div class="capacity-section">
                        <span class="label">Kapasitas Tersisa</span>
                        <div class="capacity-bar">
                            <div class="capacity-fill" style="width: <?php echo ($jumlah_peserta / $course['kapasitas'] * 100); ?>%"></div>
                        </div>
                        <span class="capacity-text"><?php echo ($course['kapasitas'] - $jumlah_peserta); ?> kursi tersisa</span>
                    </div>

                    <?php if (!isset($_SESSION['id_user'])): ?>
                        <a href="../auth/login.php" class="btn btn-primary btn-block">Login untuk Daftar</a>
                        <p style="text-align: center; font-size: 12px; margin-top: 10px;">Atau <a href="../auth/register.php">daftar akun baru</a></p>
                    <?php elseif ($already_enrolled): ?>
                        <div class="alert alert-success">Anda sudah terdaftar kursus ini</div>
                        <!-- Check enrollment status safely -->
                        <?php if (isset($enrollment) && $enrollment['status_pembayaran'] == 'belum_bayar'): ?>
                            <a href="payment.php?id_enrollment=<?php echo $enrollment['id_enrollment']; ?>" class="btn btn-primary btn-block">Lanjut ke Pembayaran</a>
                        <?php elseif (isset($enrollment) && $enrollment['status_pembayaran'] == 'verifikasi'): ?>
                            <div class="alert alert-info">Pembayaran Anda sedang diverifikasi</div>
                        <?php elseif (isset($enrollment) && $enrollment['status_pembayaran'] == 'sudah_bayar'): ?>
                            <div class="alert alert-success">Pembayaran Anda sudah terkonfirmasi</div>
                        <?php endif; ?>
                    <?php elseif ($jumlah_peserta >= $course['kapasitas']): ?>
                        <button class="btn btn-disabled btn-block" disabled>Kapasitas Penuh</button>
                    <?php else: ?>
                        <form method="POST">
                            <button type="submit" name="enroll" class="btn btn-primary btn-block">Daftar Sekarang</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 40px; text-align: center;">
        <a href="courses.php" class="btn btn-secondary">Kembali ke Daftar Kursus</a>
    </div>

    <?php } ?>
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
