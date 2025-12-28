<?php
/**
 * CATATAN PENTING: 
 * Script ini hanya untuk GENERATE hash password SEKALI saja.
 * Hash yang dihasilkan STABIL dan SAMA setiap kali password yang sama di-hash.
 * Jangan jalankan berkali-kali, cukup 1 kali untuk mendapat hash yang akan digunakan di database.
 */

// Generate hash untuk password admin123
$password_admin = 'admin123';
$hash_admin = password_hash($password_admin, PASSWORD_BCRYPT, ['cost' => 10]);

// Generate hash untuk password user123
$password_user = 'user123';
$hash_user = password_hash($password_user, PASSWORD_BCRYPT, ['cost' => 10]);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Generator</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #92400e;
        }
        .hash-block {
            background: #f3f4f6;
            border-left: 4px solid #4f46e5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .hash-block h3 {
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .credentials {
            background: #eff6ff;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #0c4a6e;
        }
        .hash-value {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            word-break: break-all;
            color: #1f2937;
            margin-top: 10px;
            max-height: 100px;
            overflow-y: auto;
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
            background: #dcfce7;
            border-left: 4px solid #22c55e;
            padding: 15px;
            border-radius: 6px;
            margin-top: 25px;
            font-size: 13px;
            color: #166534;
        }
        .instructions h4 {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .instructions ol {
            margin-left: 20px;
        }
        .instructions li {
            margin-bottom: 6px;
        }
        .database-setup {
            background: #dbeafe;
            border-left: 4px solid #0ea5e9;
            padding: 15px;
            border-radius: 6px;
            margin-top: 15px;
            font-size: 13px;
            color: #0c4a6e;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Password Hash Generator</h1>
    <div class="warning">
        ⚠️ <strong>CATATAN:</strong> Hash password harus di-update di database.sql sebelum import. Setiap refresh akan menghasilkan hash baru, jadi CATAT hash yang muncul pertama kali.
    </div>

    <div class="hash-block">
        <h3>1. Admin User</h3>
        <div class="credentials">
            <strong>Email:</strong> admin@misterkomputer.com<br>
            <strong>Password:</strong> admin123
        </div>
        <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Hash Password:</p>
        <div class="hash-value" id="admin-hash"><?php echo $hash_admin; ?></div>
        <button class="copy-btn" onclick="copyHash('admin-hash')">Copy Hash Admin</button>
    </div>

    <div class="hash-block">
        <h3>2. User Demo</h3>
        <div class="credentials">
            <strong>Email:</strong> budi@email.com<br>
            <strong>Password:</strong> user123
        </div>
        <p style="font-size: 12px; color: #666; margin-bottom: 10px;">Hash Password:</p>
        <div class="hash-value" id="user-hash"><?php echo $hash_user; ?></div>
        <button class="copy-btn" onclick="copyHash('user-hash')">Copy Hash User</button>
    </div>

    <div class="instructions">
        <h4>📝 Langkah Setup Database:</h4>
        <ol>
            <li>Copy hash admin dan user di atas</li>
            <li>Buka file <code>database.sql</code></li>
            <li>Cari baris <code>INSERT INTO users</code></li>
            <li>Ganti hash password dengan hash yang sudah di-copy</li>
            <li>Import file <code>database.sql</code> ke phpMyAdmin</li>
            <li>Selesai! Sekarang bisa login dengan credential di atas</li>
        </ol>
    </div>

    <div class="database-setup">
        <strong>Update database.sql:</strong><br>
        Ganti password lama dengan:<br>
        <code style="display: block; margin-top: 8px; word-break: break-all;">
            ('Admin Mister Komputer', 'admin@misterkomputer.com', '<?php echo $hash_admin; ?>', '081234567890', 'admin'),<br>
            ('Budi Santoso', 'budi@email.com', '<?php echo $hash_user; ?>', '081234567890', 'Jl. Merdeka No. 123, Jakarta', 'user');
        </code>
    </div>
</div>

<script>
function copyHash(elementId) {
    const element = document.getElementById(elementId);
    const text = element.textContent;
    navigator.clipboard.writeText(text).then(() => {
        alert('Hash berhasil di-copy!');
    });
}
</script>

</body>
</html>
