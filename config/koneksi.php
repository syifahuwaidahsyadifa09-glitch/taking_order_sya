<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_cafe";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) die("Koneksi database gagal: " . mysqli_connect_error());
mysqli_set_charset($koneksi, "utf8mb4");

date_default_timezone_set('Asia/Jakarta');

/** Base URL aplikasi (tanpa trailing slash) */
define('APP_BASE', '/taking_order_sya');

function rupiah($angka) {
    return "Rp " . number_format((float)$angka, 0, ',', '.');
}

function generateKodeOrder() {
    return "ORD-" . date("YmdHis") . "-" . rand(100, 999);
}

/** URL logo brand */
function logoUrl() {
    return APP_BASE . '/assets/logo-brand.png';
}

/** Upload gambar menu — return nama file atau null */
function uploadGambarMenu($file, $oldFile = null) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) return $oldFile;
    if ($file['error'] !== UPLOAD_ERR_OK) return $oldFile;

    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    $mime    = @mime_content_type($file['tmp_name']);
    if (!isset($allowed[$mime])) return $oldFile;
    if ($file['size'] > 3 * 1024 * 1024) return $oldFile;

    $uploadDir = __DIR__ . '/../assets/uploads/menu/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);

    $ext      = $allowed[$mime];
    $filename = 'menu_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest     = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $dest)) {
        if ($oldFile && file_exists($uploadDir . $oldFile)) @unlink($uploadDir . $oldFile);
        return $filename;
    }
    return $oldFile;
}

/** URL gambar menu (null kalau tidak ada) */
function gambarMenuUrl($filename) {
    if (!$filename) return null;
    if (!file_exists(__DIR__ . '/../assets/uploads/menu/' . $filename)) return null;
    return APP_BASE . '/assets/uploads/menu/' . $filename;
}