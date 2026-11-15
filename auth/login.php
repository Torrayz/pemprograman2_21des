<?php 
include '../config/koneksi.php'; 

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Masukkan email dan password yang valid.';
    } else {
        $stmt = $koneksi->prepare('SELECT id_user, nama_lengkap, email, password, role, status FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row || !password_verify($password, $row['password'])) {
            $error = 'Email atau password tidak cocok. Akun lama dengan password plaintext harus direset.';
        } elseif ($row['status'] !== 'aktif') {
            $error = 'Akun tidak aktif.';
        } else {
            session_regenerate_id(true);
            $_SESSION['id_user'] = (int)$row['id_user'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: ' . ($row['role'] === 'admin' ? '../admin/dashboard.php' : '../pages/dashboard-user.php'));
            exit;
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

        <p class="auth-link">Akun demo lama tidak lagi berlaku. Password wajib di-hash.</p>
    </div>
</div>

<footer class="footer-auth">
    <p>&copy; 2025 Mister Komputer. Developed by Tri Puji Antoro (221011402646)</p>
</footer>

</body>
</html>
