<?php
require_once '../config/koneksi.php';
$menu_id = (int)($_POST['menu_id'] ?? 0);
$jumlah = max(1, (int)($_POST['jumlah'] ?? 1));
$menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT stok FROM menu WHERE id=$menu_id"));
if ($menu) $_SESSION['keranjang'][$menu_id] = min($jumlah, (int)$menu['stok']);
header("Location: ../pelanggan/keranjang.php");