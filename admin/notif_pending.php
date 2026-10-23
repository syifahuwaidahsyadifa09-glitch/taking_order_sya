<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit; }
$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders WHERE status='pending'"));
$latest = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT kode_order FROM orders WHERE status='pending' ORDER BY waktu_order DESC LIMIT 1"));
header('Content-Type: application/json');
echo json_encode(['pending'=>(int)$row['j'],'latest'=>$latest['kode_order']??null,'time'=>date('H:i:s')]);