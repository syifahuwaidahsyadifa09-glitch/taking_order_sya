<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$id = (int)($_GET['id'] ?? 0);
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT gambar FROM menu WHERE id=$id"));
if ($m) {
    if ($m['gambar'] && file_exists(__DIR__ . '/../assets/uploads/menu/' . $m['gambar'])) {
        @unlink(__DIR__ . '/../assets/uploads/menu/' . $m['gambar']);
    }
    mysqli_query($koneksi, "DELETE FROM menu WHERE id=$id");
    $_SESSION['pesan'] = "Menu berhasil dihapus.";
}
header("Location: menu.php");