<?php include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

$id_enrollment = isset($_GET['id_enrollment']) ? intval($_GET['id_enrollment']) : 0;
$id_user = $_SESSION['id_user'];

// Get enrollment data
$enrollment = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT e.*, c.nama_course, c.harga FROM enrollments e 
                                                          JOIN courses c ON e.id_course = c.id_course 
                                                          WHERE e.id_enrollment = $id_enrollment AND e.id_user = $id_user"));

if (!$enrollment) {
    header('Location: dashboard-user.php');
    exit();
}

$payment_success = false;
$payment_error = '';

// This is a demo payment request; it is NEVER proof of a completed payment.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = (string)($_POST['metode_pembayaran'] ?? '');
    $allowed = ['bank_transfer', 'ewallet', 'credit_card'];
    if (!valid_csrf() || !in_array($method, $allowed, true)) {
        $payment_error = 'Permintaan tidak valid.';
    } else {
        try {
            $stmt = $koneksi->prepare("INSERT INTO payments (id_enrollment,id_user,id_course,jumlah_pembayaran,metode_pembayaran,status_pembayaran) VALUES (?,?,?,?,?,'pending')");
            $courseId = (int)$enrollment['id_course'];
            $amount = (float)$enrollment['harga'];
            $stmt->bind_param('iiids', $id_enrollment, $id_user, $courseId, $amount, $method);
            $stmt->execute();
            $payment_success = true;
        } catch (mysqli_sql_exception $error) {
            error_log($error->getMessage());
            $payment_error = 'Pengajuan pembayaran gagal.';
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Mister Komputer</title>
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
            <li><a href="dashboard-user.php" class="nav-link btn-nav">Dashboard</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h1 style="text-align: center; margin-bottom: 30px;">Pembayaran Kursus</h1>

    <?php if ($payment_success): ?>
    <div class="alert alert-success" style="text-align: center; margin-bottom: 30px;">
        <h3>Permintaan Pembayaran Dicatat</h3>
        <p>Status masih pending. Belum ada transaksi pembayaran yang diverifikasi; akses kursus tidak berubah.</p>
    </div>
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="dashboard-user.php" class="btn btn-primary">Kembali ke Dashboard</a>
    </div>
    <?php else: ?>

    <div class="payment-container">
        <div class="payment-left">
            <div class="payment-summary">
                <h3>Ringkasan Pembayaran</h3>
                <div class="summary-item">
                    <span class="label">Kursus</span>
                    <span class="value"><?php echo htmlspecialchars($enrollment['nama_course']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="label">Harga Kursus</span>
                    <span class="value">Rp <?php echo number_format($enrollment['harga'], 0, ',', '.'); ?></span>
                </div>
                <div class="summary-item">
                    <span class="label">Pajak (0%)</span>
                    <span class="value">Rp 0</span>
                </div>
                <div class="summary-item total">
                    <span class="label">Total Pembayaran</span>
                    <span class="value">Rp <?php echo number_format($enrollment['harga'], 0, ',', '.'); ?></span>
                </div>
            </div>
        </div>

        <div class="payment-right">
            <div class="payment-form">
                <h3>Pilih Metode Pembayaran</h3>
                
                <?php if ($payment_error): ?>
                    <div class="alert alert-error"><?php echo $payment_error; ?></div>
                <?php endif; ?>

                <form method="POST" class="form-payment">
                    <?php echo csrf_input(); ?>
                    <div class="payment-methods">
                        <div class="payment-option">
                            <input type="radio" id="bank_transfer" name="metode_pembayaran" value="bank_transfer" checked>
                            <label for="bank_transfer">
                                <span class="method-icon">🏦</span>
                                <span class="method-name">Transfer Bank</span>
                                <span class="method-desc">Transfer langsung ke rekening kami</span>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="ewallet" name="metode_pembayaran" value="ewallet">
                            <label for="ewallet">
                                <span class="method-icon">📱</span>
                                <span class="method-name">E-Wallet</span>
                                <span class="method-desc">GCash, Grab Pay, atau OVO</span>
                            </label>
                        </div>

                        <div class="payment-option">
                            <input type="radio" id="credit_card" name="metode_pembayaran" value="credit_card">
                            <label for="credit_card">
                                <span class="method-icon">💳</span>
                                <span class="method-name">Kartu Kredit</span>
                                <span class="method-desc">Visa, Mastercard, atau AMEX</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 20px;">Simpan Permintaan Pembayaran</button>
                </form>

                <p style="text-align: center; font-size: 12px; color: #666; margin-top: 20px;">
                    Mode simulasi: sistem ini tidak memproses pembayaran nyata.
                </p>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="dashboard-user.php" class="btn btn-secondary">Batalkan</a>
    </div>

    <?php endif; ?>
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
