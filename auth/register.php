<?php include '../config/koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mister Komputer</title>
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
            <li><a href="login.php" class="nav-link btn-nav">Login</a></li>
        </ul>
    </div>
</nav>

<div class="container-auth">
    <div class="auth-box">
        <h2>Daftar Akun Baru</h2>
        <p class="auth-subtitle">Buat akun untuk mengakses program kursus kami</p>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string)($_POST['nama_lengkap'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $confirm = (string)($_POST['confirm_password'] ?? '');
            $phone = trim((string)($_POST['no_telepon'] ?? ''));
            $address = trim((string)($_POST['alamat'] ?? ''));
            $error = '';
            if (!valid_csrf()) {
                $error = 'Permintaan tidak valid, muat ulang formulir.';
            } elseif (mb_strlen($name) < 2 || mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Nama atau email tidak valid.';
            } elseif (strlen($password) < 8 || $password !== $confirm) {
                $error = 'Gunakan password minimal 8 karakter dan pastikan konfirmasi sama.';
            } elseif ($phone === '' || strlen($phone) > 15) {
                $error = 'Nomor telepon tidak valid.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                try {
                    $stmt = $koneksi->prepare("INSERT INTO users (nama_lengkap, email, password, no_telepon, alamat, role) VALUES (?, ?, ?, ?, ?, 'user')");
                    $stmt->bind_param('sssss', $name, $email, $hash, $phone, $address);
                    $stmt->execute();
                    echo '<div class="alert alert-success">Registrasi berhasil. Silakan <a href="login.php">masuk</a>.</div>';
                } catch (mysqli_sql_exception $exception) {
                    $error = ((int)$exception->getCode() === 1062) ? 'Email sudah terdaftar.' : 'Registrasi gagal.';
                }
            }
            if ($error !== '') {
                echo '<div class="alert alert-error">' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</div>';
            }
        }

        ?>

        <form method="POST" class="form-auth">
            <?php echo csrf_input(); ?>
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" required placeholder="Masukkan nama lengkap Anda">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Masukkan email Anda">
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="tel" name="no_telepon" required placeholder="Masukkan no. telepon Anda">
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" placeholder="Masukkan alamat Anda"></textarea>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" required placeholder="Ketik ulang password">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Daftar</button>
        </form>

        <p class="auth-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>

<footer class="footer-auth">
    <p>&copy; 2025 Mister Komputer. Developed by Tri Puji Antoro (221011402646)</p>
</footer>

</body>
</html>
