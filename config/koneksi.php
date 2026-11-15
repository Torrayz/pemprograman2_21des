<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$host = getenv('DB_HOST') ?: 'database';
$database = getenv('DB_NAME') ?: 'tripujiantoro';
$user = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
if (!$user || !$password) {
    http_response_code(503);
    exit('Konfigurasi database belum tersedia. Isi DB_USER dan DB_PASSWORD.');
}
try {
    $koneksi = new mysqli($host, $user, $password, $database);
    $koneksi->set_charset('utf8mb4');
} catch (mysqli_sql_exception $error) {
    error_log($error->getMessage());
    http_response_code(503);
    exit('Koneksi database tidak tersedia.');
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
function csrf_input(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}
function valid_csrf(): bool {
    return isset($_POST['csrf_token']) && is_string($_POST['csrf_token'])
      && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}
