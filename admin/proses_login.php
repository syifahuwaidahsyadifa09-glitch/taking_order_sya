<?php
require_once '../config/koneksi.php';
$username = mysqli_real_escape_string($koneksi, $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$admin = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$username' LIMIT 1"));
if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_nama'] = $admin['nama_lengkap'];
    header("Location: dashboard.php"); exit;
}
$_SESSION['error'] = "Username atau password salah.";
header("Location: login.php");