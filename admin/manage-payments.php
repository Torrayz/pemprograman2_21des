<?php include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!valid_csrf()) {
        http_response_code(403);
        exit('Token keamanan tidak valid.');
    }
    $id = filter_var($_POST['payment_id'] ?? null, FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';
    if (!$id || !in_array($action, ['verify','reject'], true)) {
        http_response_code(422);
        exit('Permintaan tidak valid.');
    }
    $koneksi->begin_transaction();
    try {
        $stmt = $koneksi->prepare("SELECT id_enrollment FROM payments WHERE id_payment=? AND status_pembayaran='pending' FOR UPDATE");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) throw new RuntimeException('Pembayaran pending tidak ditemukan.');
        if ($action === 'verify') {
            $update = $koneksi->prepare("UPDATE payments SET status_pembayaran='verified' WHERE id_payment=?");
            $update->bind_param('i', $id);
            $update->execute();
            $enroll = $koneksi->prepare("UPDATE enrollments SET status_pembayaran='sudah_bayar' WHERE id_enrollment=?");
            $enroll->bind_param('i', $row['id_enrollment']);
            $enroll->execute();
        } else {
            $update = $koneksi->prepare("UPDATE payments SET status_pembayaran='failed' WHERE id_payment=?");
            $update->bind_param('i', $id);
            $update->execute();
        }
        $koneksi->commit();
        header('Location: manage-payments.php');
        exit;
    } catch (Throwable $error) {
        $koneksi->rollback();
        error_log($error->getMessage());
        $message = 'Gagal memperbarui status pembayaran.';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pembayaran - Admin</title>
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
            <li><a href="manage-courses.php" class="nav-link">Kelola Kursus</a></li>
            <li><a href="manage-users.php" class="nav-link">Kelola User</a></li>
            <li><a href="manage-payments.php" class="nav-link active btn-nav">Pembayaran</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h1>Kelola Pembayaran</h1><p>Verifikasi bersifat manual dan hanya boleh dilakukan setelah pembayaran benar-benar diterima.</p><?php if (!empty($message)): ?><p class="alert alert-error"><?= htmlspecialchars($message) ?></p><?php endif; ?>

    <div class="admin-list-section">
        <h2>Daftar Pembayaran</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Kursus</th>
                    <th>Jumlah</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $payments = mysqli_query($koneksi, "SELECT p.*, u.nama_lengkap, c.nama_course 
                FROM payments p 
                JOIN users u ON p.id_user = u.id_user 
                JOIN courses c ON p.id_course = c.id_course 
                ORDER BY p.created_at DESC");

                while ($row = mysqli_fetch_assoc($payments)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_course']); ?></td>
                    <td>Rp <?php echo number_format($row['jumlah_pembayaran'], 0, ',', '.'); ?></td>
                    <td><?php echo ucfirst(str_replace('_', ' ', $row['metode_pembayaran'])); ?></td>
                    <td>
                        <?php 
                        if ($row['status_pembayaran'] == 'pending') {
                            echo '<span class="badge badge-warning">Pending</span>';
                        } elseif ($row['status_pembayaran'] == 'verified') {
                            echo '<span class="badge badge-success">Verified</span>';
                        } else {
                            echo '<span class="badge badge-danger">Failed</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo date('d M Y H:i', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['status_pembayaran'] == 'pending'): ?>
                            <form method="POST" style="display:inline-flex;gap:6px">
                                <?php echo csrf_input(); ?>
                                <input type="hidden" name="payment_id" value="<?php echo (int)$row['id_payment']; ?>">
                                <button class="btn btn-small" type="submit" name="action" value="verify" onclick="return confirm('Pastikan pembayaran benar-benar diterima sebelum verifikasi. Lanjutkan?')">Verifikasi manual</button>
                                <button class="btn btn-small btn-danger" type="submit" name="action" value="reject">Tolak</button>
                            </form>
                        <?php else: ?>
                            <span style="color: #666;">-</span>
                        <?php endif; ?>
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
