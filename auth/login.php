<?php 
include '../config/koneksi.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? mysqli_real_escape_string($koneksi, trim($_POST['email'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validasi input
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } else {
        // Query untuk cek user
        $query = "SELECT id_user, nama_lengkap, email, password, role, status FROM users WHERE email = '$email'";
        $result = mysqli_query($koneksi, $query);

        if (!$result) {
            $error = 'Database error: ' . mysqli_error($koneksi);
        } else {
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                if ($row['status'] == 'nonaktif') {
                    $error = 'Akun Anda tidak aktif. Hubungi admin!';
                } elseif ($password === $row['password']) {
                    $_SESSION['id_user'] = $row['id_user'];
                    $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['role'] = $row['role'];

                    if ($row['role'] == 'admin') {
                        header('Location: ../admin/dashboard.php');
                    } else {
                        header('Location: ../pages/dashboard-user.php');
                    }
                    exit();
                } else {
                    $error = 'Email atau password salah!';
                }
            } else {
                $error = 'Email tidak terdaftar!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mister Komputer</title>
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
            <li><a href="register.php" class="nav-link btn-nav">Register</a></li>
        </ul>
    </div>
</nav>

<div class="container-auth">
    <div class="auth-box">
        <h2>Login Akun</h2>
        <p class="auth-subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        <?php
        if ($error) {
            echo '<div class="alert alert-error">' . htmlspecialchars($error) . '</div>';
        }
        if ($success) {
            echo '<div class="alert alert-success">' . htmlspecialchars($success) . '</div>';
        }
        ?>

        <form method="POST" class="form-auth">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Masukkan email Anda">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password Anda">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>

        <p class="auth-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>

        <!-- Demo credentials untuk testing -->
        <div class="demo-credentials" style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #ddd;">
            <p style="font-size: 12px; color: #666;"><strong>Demo Akun untuk Testing:</strong></p>
            <p style="font-size: 12px; color: #666;"><strong>Admin:</strong> admin@misterkomputer.com / admin123</p>
            <p style="font-size: 12px; color: #666;"><strong>User:</strong> budi@email.com / user123</p>
        </div>
    </div>
</div>

<footer class="footer-auth">
    <p>&copy; 2025 Mister Komputer. Developed by Tri Puji Antoro (221011402646)</p>
</footer>

</body>
</html>
