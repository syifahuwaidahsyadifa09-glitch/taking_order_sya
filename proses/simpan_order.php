<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: ../index.php"); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: ../pelanggan/keranjang.php"); exit; }

$pelanggan_id = (int)$_SESSION['pelanggan_id'];
$no_meja = mysqli_real_escape_string($koneksi, $_SESSION['pelanggan_meja']);
$catatan = mysqli_real_escape_string($koneksi, $_POST['catatan'] ?? '');
$kode_order = generateKodeOrder();

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $mid = (int)$mid;
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga FROM menu WHERE id=$mid"));
    if ($m) $total += $m['harga'] * $jml;
}

$sql = "INSERT INTO orders (kode_order,pelanggan_id,no_meja,total_harga,catatan)
        VALUES ('$kode_order',$pelanggan_id,'$no_meja',$total,'$catatan')";
if (!mysqli_query($koneksi, $sql)) die("Gagal menyimpan order: " . mysqli_error($koneksi));
$order_id = mysqli_insert_id($koneksi);

foreach ($keranjang as $mid => $jml) {
    $mid = (int)$mid;
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$mid"));
    if (!$m) continue;
    $sub = $m['harga'] * $jml;
    $nama = mysqli_real_escape_string($koneksi, $m['nama_menu']);
    mysqli_query($koneksi, "INSERT INTO order_detail (order_id,menu_id,nama_menu,harga,jumlah,subtotal)
                            VALUES ($order_id,$mid,'$nama',{$m['harga']},$jml,$sub)");
    mysqli_query($koneksi, "UPDATE menu SET stok=GREATEST(stok-$jml,0) WHERE id=$mid");
}

$_SESSION['keranjang'] = [];
$_SESSION['last_order_id'] = $order_id;
header("Location: ../pelanggan/sukses.php");