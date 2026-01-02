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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_lengkap = mysqli_real_escape_string($koneksi, trim($_POST['nama_lengkap']));
            $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
            $password = mysqli_real_escape_string($koneksi, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);
            $no_telepon = mysqli_real_escape_string($koneksi, trim($_POST['no_telepon']));
            $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));

            $error = '';

            // Validasi
            if (empty($nama_lengkap) || empty($email) || empty($password) || empty($no_telepon)) {
                $error = 'Semua field harus diisi!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Format email tidak valid!';
            } elseif ($password !== $confirm_password) {
                $error = 'Password tidak sesuai!';
            } elseif (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter!';
            } else {
                // Check email sudah ada
                $check_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
                if (mysqli_num_rows($check_email) > 0) {
                    $error = 'Email sudah terdaftar!';
                } else {
                    $insert = mysqli_query($koneksi, "INSERT INTO users (nama_lengkap, email, password, no_telepon, alamat, role) 
                    VALUES ('$nama_lengkap', '$email', '$password', '$no_telepon', '$alamat', 'user')");

                    if ($insert) {
                        echo '<div class="alert alert-success">Registrasi berhasil! Silakan <a href="login.php">login di sini</a></div>';
                    } else {
                        $error = 'Terjadi kesalahan: ' . mysqli_error($koneksi);
                    }
                }
            }

            if ($error) {
                echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
            }
        }
        ?>

        <form method="POST" class="form-auth">
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
                <input type="password" name="password" required placeholder="Minimal 6 karakter">
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
