<?php include '../config/koneksi.php'; 

if (!isset($_SESSION['id_user']) || $_SESSION['role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!valid_csrf()) { http_response_code(403); exit('Token keamanan tidak valid.'); }
    $id = filter_var($_POST['user_id'] ?? null, FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';
    if (!$id || !in_array($action,['toggle','delete'],true)) { http_response_code(422); exit('Permintaan tidak valid.'); }
    if ($action === 'toggle') {
        $stmt = $koneksi->prepare("UPDATE users SET status=IF(status='aktif','nonaktif','aktif') WHERE id_user=? AND role='user'");
        $stmt->bind_param('i',$id);
        $stmt->execute();
    } else {
        $stmt = $koneksi->prepare("DELETE FROM users WHERE id_user=? AND role='user'");
        $stmt->bind_param('i',$id);
        $stmt->execute();
    }
    header('Location: manage-users.php');
    exit;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Admin</title>
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
            <li><a href="manage-users.php" class="nav-link active btn-nav">Kelola User</a></li>
            <li><a href="manage-payments.php" class="nav-link">Pembayaran</a></li>
            <li><a href="../auth/logout.php" class="nav-link btn-logout">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container" style="margin-top: 100px; margin-bottom: 50px;">
    <h1>Kelola User</h1>

    <div class="admin-list-section">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Kursus Terdaftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = mysqli_query($koneksi, "SELECT u.*, COUNT(e.id_enrollment) as jumlah_kursus 
                FROM users u LEFT JOIN enrollments e ON u.id_user = e.id_user 
                WHERE u.role = 'user' 
                GROUP BY u.id_user ORDER BY u.created_at DESC");

                while ($row = mysqli_fetch_assoc($users)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['no_telepon']); ?></td>
                    <td><?php echo $row['jumlah_kursus']; ?></td>
                    <td>
                        <?php 
                        if ($row['status'] == 'aktif') {
                            echo '<span class="badge badge-success">Aktif</span>';
                        } else {
                            echo '<span class="badge badge-danger">Nonaktif</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <form method="POST" style="display:inline-flex;gap:6px">
                            <?php echo csrf_input(); ?>
                            <input type="hidden" name="user_id" value="<?php echo (int)$row['id_user']; ?>">
                            <button type="submit" name="action" value="toggle" class="btn btn-small"><?php echo $row['status']==='aktif' ? 'Nonaktifkan' : 'Aktifkan'; ?></button>
                            <button type="submit" name="action" value="delete" class="btn btn-small btn-danger" onclick="return confirm('Hapus pengguna dan data terkait secara permanen?')">Hapus</button>
                        </form>
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
