<?php
/**
 * Script untuk generate password hash yang benar
 * Jalankan file ini untuk mendapatkan hash password yang valid
 * Kemudian gunakan hash tersebut di database
 */

// Password yang ingin di-hash
$password_admin = 'admin123';
$password_user = 'user123';

// Generate hash dengan PASSWORD_BCRYPT
$hash_admin = password_hash($password_admin, PASSWORD_BCRYPT, ['cost' => 10]);
$hash_user = password_hash($password_user, PASSWORD_BCRYPT, ['cost' => 10]);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator - Mister Komputer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .hash-item {
            margin-bottom: 25px;
            padding: 20px;
            background: #f3f4f6;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        .hash-item h3 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .hash-item p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .hash-value {
            background: white;
            padding: 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
            color: #1f2937;
            border: 1px solid #e5e7eb;
            margin-top: 10px;
        }
        .copy-btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .copy-btn:hover {
            background: #4338ca;
        }
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            border-radius: 6px;
            margin-top: 25px;
            font-size: 13px;
            color: #0c4a6e;
        }
        .instructions h4 {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .instructions ol {
            margin-left: 20px;
        }
        .instructions li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Password Hash Generator</h1>
    <p class="subtitle">Gunakan hash di bawah untuk database Anda</p>

    <div class="hash-item">
        <h3>Admin User</h3>
        <p><strong>Email:</strong> admin@misterkomputer.com</p>
        <p><strong>Password:</strong> admin123</p>
        <div class="hash-value" id="admin-hash"><?php echo $hash_admin; ?></div>
        <button class="copy-btn" onclick="copyToClipboard('admin-hash')">Copy Hash</button>
    </div>

    <div class="hash-item">
        <h3>Regular User</h3>
        <p><strong>Email:</strong> budi@email.com</p>
        <p><strong>Password:</strong> user123</p>
        <div class="hash-value" id="user-hash"><?php echo $hash_user; ?></div>
        <button class="copy-btn" onclick="copyToClipboard('user-hash')">Copy Hash</button>
    </div>

    <div class="instructions">
        <h4>Cara Menggunakan:</h4>
        <ol>
            <li>Copy hash di atas</li>
            <li>Login ke phpMyAdmin</li>
            <li>Pilih database "mkom"</li>
            <li>Buka tabel "users"</li>
            <li>Update password field dengan hash yang sudah di-copy</li>
            <li>Selesai! Sekarang Anda bisa login dengan password baru</li>
        </ol>
    </div>
</div>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    
    navigator.clipboard.writeText(text).then(function() {
        alert('Hash berhasil di-copy!');
    }, function(err) {
        console.error('Gagal copy:', err);
    });
}
</script>

</body>
</html>
