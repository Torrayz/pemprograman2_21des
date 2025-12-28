<?php include 'config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mister Komputer - Lembaga Kursus Komputer Profesional</title>
    <meta name="description" content="Mister Komputer - Kursus komputer terpercaya dengan instruktur berpengalaman">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">
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
            <li><a href="#beranda" class="nav-link active">Beranda</a></li>
            <li><a href="pages/courses.php" class="nav-link">Kursus Kami</a></li>
            <li><a href="#keunggulan" class="nav-link">Keunggulan</a></li>
            <?php if (isset($_SESSION['id_user'])): ?>
                <li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="admin/dashboard.php" class="nav-link btn-nav">Admin Panel</a>
                    <?php else: ?>
                        <a href="pages/dashboard-user.php" class="nav-link btn-nav">Dashboard</a>
                    <?php endif; ?>
                </li>
                <li><a href="auth/logout.php" class="nav-link btn-logout">Logout (<?php echo $_SESSION['nama_lengkap']; ?>)</a></li>
            <?php else: ?>
                <li><a href="auth/login.php" class="nav-link btn-nav">Login</a></li>
                <li><a href="auth/register.php" class="nav-link btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- HERO SECTION -->
<section id="beranda" class="hero">
    <div class="hero-content">
        <h2 class="hero-title">Tingkatkan Skill Komputer Anda Bersama Kami</h2>
        <p class="hero-subtitle">Belajar dari instruktur berpengalaman dengan metode pembelajaran praktis dan efektif</p>
        <div class="hero-buttons">
            <a href="pages/courses.php" class="btn btn-primary">Lihat Semua Kursus</a>
            <?php if (!isset($_SESSION['id_user'])): ?>
                <a href="auth/register.php" class="btn btn-secondary">Daftar Sekarang</a>
            <?php else: ?>
                <a href="pages/dashboard-user.php" class="btn btn-secondary">Dashboard Saya</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<div class="stats-section">
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-number">2500+</div>
            <div class="stat-text">Peserta Puas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">6+</div>
            <div class="stat-text">Program Kursus</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">98%</div>
            <div class="stat-text">Tingkat Kepuasan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">8+</div>
            <div class="stat-text">Tahun Berpengalaman</div>
        </div>
    </div>
</div>

<!-- PROGRAM KURSUS SECTION -->
<section id="program" class="programs">
    <div class="programs-container">
        <div class="section-header">
            <h2>Program Kursus Unggulan</h2>
            <p>Pilih program kursus yang sesuai dengan kebutuhan Anda</p>
        </div>

        <div class="programs-grid">
            <?php
            $query = "SELECT * FROM courses LIMIT 6";
            $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <div class="program-card">
                <div class="program-icon">💻</div>
                <h3><?php echo htmlspecialchars($row['nama_course']); ?></h3>
                <p><?php echo substr(htmlspecialchars($row['deskripsi']), 0, 80); ?>...</p>
                <div class="program-meta">
                    <span class="meta-item">⏱ <?php echo $row['durasi_jam']; ?> Jam</span>
                    <span class="meta-item">👨‍🏫 <?php echo htmlspecialchars($row['instruktur']); ?></span>
                </div>
                <div class="program-price">
                    <span class="harga">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                </div>
                <a href="pages/course-detail.php?id=<?php echo $row['id_course']; ?>" class="btn btn-small">Lihat Detail</a>
            </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center" style="margin-top: 40px;">
            <a href="pages/courses.php" class="btn btn-primary btn-large">Lihat Semua Program Kursus</a>
        </div>
    </div>
</section>

<!-- KEUNGGULAN SECTION -->
<section id="keunggulan" class="features">
    <div class="features-container">
        <div class="section-header">
            <h2>Mengapa Memilih Kami?</h2>
            <p>Komitmen kami terhadap kesuksesan Anda</p>
        </div>

        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">👨‍🏫</div>
                <h3>Instruktur Berpengalaman</h3>
                <p>Tim pengajar profesional dengan pengalaman industri lebih dari 8 tahun</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">💡</div>
                <h3>Metode Praktis</h3>
                <p>Kombinasi teori dan praktek langsung untuk pemahaman maksimal</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">🏆</div>
                <h3>Sertifikat Resmi</h3>
                <p>Dapatkan sertifikat yang diakui secara nasional dan internasional</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">💼</div>
                <h3>Job Placement</h3>
                <p>Kami membantu peserta terbaik mendapatkan pekerjaan impian mereka</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">⏰</div>
                <h3>Jadwal Fleksibel</h3>
                <p>Pilih jadwal kursus yang sesuai dengan kesibukan Anda</p>
            </div>

            <div class="feature-item">
                <div class="feature-icon">🎓</div>
                <h3>Akses Selamanya</h3>
                <p>Akses materi pembelajaran seumur hidup untuk terus belajar</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA SECTION -->
<section class="cta-section">
    <div class="cta-container">
        <h2>Siap untuk Memulai Karir Baru?</h2>
        <p>Daftar sekarang dan bergabunglah dengan ribuan peserta yang telah sukses</p>
        <div class="cta-buttons">
            <?php if (!isset($_SESSION['id_user'])): ?>
                <a href="auth/register.php" class="btn btn-large btn-primary">Daftar Peserta Baru</a>
            <?php else: ?>
                <a href="pages/courses.php" class="btn btn-large btn-primary">Jelajahi Kursus</a>
            <?php endif; ?>
            <a href="pages/courses.php" class="btn btn-large btn-secondary">Lihat Semua Program</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer-main">
    <div class="footer-container">
        <div class="footer-content">
            <h3>MISTER KOMPUTER</h3>
            <p>Lembaga kursus komputer terpercaya yang telah melayani ribuan peserta sejak 2017</p>
            
            <div class="footer-info">
                <p><strong>Dibuat oleh:</strong> Tri Puji Antoro</p>
                <p><strong>NIM:</strong> 221011402646</p>
                <p><strong>Program:</strong> Teknik Informatika - Universitas Pamulang</p>
            </div>
        </div>
        <div class="footer-links">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="pages/courses.php">Kursus Kami</a></li>
                <li><a href="#keunggulan">Keunggulan</a></li>
                <?php if (isset($_SESSION['id_user'])): ?>
                    <li><a href="pages/dashboard-user.php">Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="footer-contact">
            <h4>Kontak Kami</h4>
            <ul>
                <li>📧 info@misterkomputer.com</li>
                <li>📞 (021) 1234-5678</li>
                <li>📍 Jakarta, Indonesia</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 Mister Komputer. All rights reserved. | Developed by Tri Puji Antoro (221011402646)</p>
    </div>
</footer>

<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    window.addEventListener('scroll', () => {
        let current = '';
        document.querySelectorAll('section').forEach(section => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 60) {
                current = section.getAttribute('id');
            }
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
</script>

</body>
</html>
