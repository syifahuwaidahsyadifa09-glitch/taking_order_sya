<?php
/**
 * cetak_laporan.php
 * Halaman pratinjau + cetak laporan order.
 * Dibuka dari admin/laporan.php via target="_blank".
 *
 * Perbaikan:
 * - Path require konsisten (__DIR__)
 * - Auth check dengan redirect jelas
 * - Preview tetap tampil di layar (tidak langsung print+close)
 * - Print-safe CSS (konten tidak tersembunyi)
 * - Logo brand di header
 */

// ── 1. Bootstrapping ───────────────────────────────────
require_once __DIR__ . '/../config/koneksi.php';

// ── 2. Auth check ──────────────────────────────────────
if (!isset($_SESSION['admin_id'])) {
    // Redirect ke login dengan pesan, JANGAN echo HTML kosong
    header("Location: login.php");
    exit;
}

// ── 3. Ambil parameter tanggal ─────────────────────────
$tgl_awal  = $_GET['tgl_awal']  ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');

// Validasi format tanggal (kalau tidak valid, fallback ke default)
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_awal))  $tgl_awal  = date('Y-m-01');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_akhir)) $tgl_akhir = date('Y-m-d');

$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

// ── 4. Query data ──────────────────────────────────────
$q = mysqli_query(
    $koneksi,
    "SELECT o.*, p.nama
     FROM orders o
     JOIN pelanggan p ON o.pelanggan_id = p.id
     WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk'
     ORDER BY o.waktu_order ASC"
);

if (!$q) {
    // Query error — tampilkan pesan jelas, bukan blank
    die("Query error: " . mysqli_error($koneksi));
}

$data             = [];
$total_pendapatan = 0;
$total_order      = 0;
$total_selesai    = 0;

while ($row = mysqli_fetch_assoc($q)) {
    $data[]        = $row;
    $total_order++;
    if ($row['status'] === 'selesai') {
        $total_pendapatan += (float)$row['total_harga'];
        $total_selesai++;
    }
}

// ── 5. Render halaman ──────────────────────────────────
$base_url = '../';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Order — Taking Order Cafe</title>
    <link rel="icon" type="image/png" href="<?= $base_url; ?>assets/logo-brand.png">
    <style>
        /* ============ BASE (layar) ============ */
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background: #f6f8ee;
            color: #3d2a1e;
            font-size: 13px;
            line-height: 1.5;
        }
        .page-wrap {
            max-width: 900px;
            margin: 24px auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(33, 59, 38, 0.08);
            border: 1px solid #e8dad0;
        }

        /* ============ HEADER LAPORAN ============ */
        .header-laporan {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 16px;
            border-bottom: 3px double #213b26;
            margin-bottom: 24px;
        }
        .header-laporan img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .header-laporan .brand-text h2 {
            margin: 0;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 22px;
            letter-spacing: 1.5px;
            color: #213b26;
            font-weight: 700;
        }
        .header-laporan .brand-text p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #5a3e2b;
        }

        /* ============ JUDUL ============ */
        .judul-laporan {
            text-align: center;
            margin: 12px 0 20px;
        }
        .judul-laporan h1 {
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 18px;
            letter-spacing: 3px;
            color: #213b26;
            margin: 0;
            font-weight: 700;
        }

        /* ============ INFO CETAK ============ */
        .info-cetak {
            display: grid;
            grid-template-columns: max-content max-content 1fr;
            gap: 4px 10px;
            font-size: 12px;
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #f6f8ee;
            border-radius: 8px;
            border: 1px solid #e8dad0;
        }
        .info-cetak .label { color: #5a3e2b; }
        .info-cetak .sep   { color: #5a3e2b; }
        .info-cetak .value { color: #3d2a1e; font-weight: 600; }

        /* ============ RINGKASAN ============ */
        .ringkasan {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .ringkasan .item {
            padding: 12px 16px;
            border: 1px solid #e8dad0;
            border-radius: 8px;
            background: #fbfcf5;
        }
        .ringkasan .item .lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #5a3e2b;
            font-weight: 600;
        }
        .ringkasan .item .val {
            font-size: 16px;
            font-weight: 700;
            color: #213b26;
            margin-top: 4px;
        }

        /* ============ TABEL ============ */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 16px;
        }
        table.data th,
        table.data td {
            border: 1px solid #3d2a1e;
            padding: 7px 9px;
            vertical-align: middle;
        }
        table.data th {
            background: #213b26;
            color: #f6f8ee;
            text-align: center;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.data tbody tr:nth-child(even) { background: #fbfcf5; }
        table.data tbody tr:hover { background: #f4f8ec; }
        table.data td.kode { font-family: 'Courier New', monospace; font-size: 11px; }
        table.data td.status { text-align: center; }
        table.data td.status .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .badge-pending    { background:#eef1e2; color:#4a3528; border-color:#d1b8a3; }
        .badge-diproses   { background:#e6efd3; color:#2a442e; border-color:#a9c47d; }
        .badge-selesai    { background:#d2e3b9; color:#213b26; border-color:#8bb058; }
        .badge-dibatalkan { background:#e8dad0; color:#3d2a1e; border-color:#b8a394; }

        .text-end    { text-align: right; }
        .text-center { text-align: center; }
        .empty-row   { text-align: center; color: #8b7d6b; padding: 24px !important; font-style: italic; }

        /* ============ TOTAL BOX ============ */
        .total-box {
            margin-top: 16px;
            padding: 14px 20px;
            background: #213b26;
            color: #f6f8ee;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .total-box .lbl {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 11px;
            color: #c5d8a4;
        }
        .total-box .val {
            font-size: 18px;
            font-weight: 700;
            font-family: Georgia, serif;
        }

        /* ============ TTD ============ */
        .ttd {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }
        .ttd p { margin: 2px 0; }
        .ttd .nama-ttd {
            margin-top: 60px;
            padding-top: 4px;
            border-top: 1px solid #3d2a1e;
            display: inline-block;
            min-width: 180px;
            text-align: center;
            font-weight: 600;
        }

        /* ============ TOOLBAR (LAYAR SAJA) ============ */
        .toolbar {
            position: sticky;
            top: 0;
            background: #213b26;
            color: #f6f8ee;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            margin: -24px -20px 24px;
            border-radius: 12px 12px 0 0;
        }
        .toolbar .title {
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .toolbar .actions { display: flex; gap: 8px; }
        .toolbar button, .toolbar a {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid rgba(246, 248, 238, 0.3);
            background: rgba(246, 248, 238, 0.1);
            color: #f6f8ee;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
        }
        .toolbar button:hover, .toolbar a:hover {
            background: rgba(246, 248, 238, 0.2);
        }
        .toolbar button.primary {
            background: #c5d8a4;
            color: #213b26;
            border-color: #c5d8a4;
        }
        .toolbar button.primary:hover {
            background: #d2e3b9;
        }

        /* ============ PRINT ============ */
        @page {
            size: A4;
            margin: 12mm;
        }
        @media print {
            /* Reset layar */
            html, body {
                background: #fff !important;
                font-size: 11px;
            }
            .page-wrap {
                max-width: none;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }

            /* Sembunyikan HANYA toolbar (yang memang tidak perlu dicetak) */
            .no-print { display: none !important; }

            /* Pastikan tabel & konten lain tetap tampil */
            table.data { page-break-inside: auto; }
            table.data tr { page-break-inside: avoid; page-break-after: auto; }
            table.data thead { display: table-header-group; }

            /* Hilangkan efek hover */
            table.data tbody tr:hover { background: transparent; }

            /* TTD jangan sampai terpotong */
            .ttd { page-break-inside: avoid; }

            /* Warna background tetap dicetak */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>

<div class="page-wrap">

    <!-- Toolbar (hanya tampil di layar, tidak dicetak) -->
    <div class="toolbar no-print">
        <div class="title">
            <span>Pratinjau Laporan</span>
        </div>
        <div class="actions">
            <button type="button" class="primary" onclick="window.print()">
                🖨&nbsp; Cetak Sekarang
            </button>
            <a href="laporan.php?tgl_awal=<?= urlencode($tgl_awal); ?>&tgl_akhir=<?= urlencode($tgl_akhir); ?>">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Header -->
    <div class="header-laporan">
        <img src="<?= $base_url; ?>assets/logo-brand.png" alt="Logo">
        <div class="brand-text">
            <h2>TAKING ORDER CAFE</h2>
            <p>Jl. Contoh Alamat No. 123, Kota Anda</p>
            <p>Telp: 021-1234567 &nbsp;|&nbsp; Email: info@takingordercafe.com</p>
        </div>
    </div>

    <!-- Judul -->
    <div class="judul-laporan">
        <h1>LAPORAN ORDER</h1>
    </div>

    <!-- Info -->
    <div class="info-cetak">
        <span class="label">Periode</span>
        <span class="sep">:</span>
        <span class="value">
            <?= date('d/m/Y', strtotime($tgl_awal)); ?>
            &nbsp;s/d&nbsp;
            <?= date('d/m/Y', strtotime($tgl_akhir)); ?>
        </span>

        <span class="label">Tanggal Cetak</span>
        <span class="sep">:</span>
        <span class="value"><?= date('d/m/Y H:i'); ?> WIB</span>

        <span class="label">Dicetak oleh</span>
        <span class="sep">:</span>
        <span class="value"><?= htmlspecialchars($_SESSION['admin_nama']); ?></span>
    </div>

    <!-- Ringkasan -->
    <div class="ringkasan">
        <div class="item">
            <div class="lbl">Total Order</div>
            <div class="val"><?= $total_order; ?> order</div>
        </div>
        <div class="item">
            <div class="lbl">Order Selesai</div>
            <div class="val"><?= $total_selesai; ?> order</div>
        </div>
        <div class="item">
            <div class="lbl">Total Pendapatan</div>
            <div class="val"><?= rupiah($total_pendapatan); ?></div>
        </div>
    </div>

    <!-- Tabel -->
    <table class="data">
        <thead>
            <tr>
                <th style="width: 32px;">No</th>
                <th style="width: 140px;">Kode Order</th>
                <th>Nama Pelanggan</th>
                <th style="width: 55px;">Meja</th>
                <th style="width: 105px;">Total</th>
                <th style="width: 90px;">Status</th>
                <th style="width: 115px;">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="7" class="empty-row">
                        Tidak ada data pada periode ini.
                    </td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($data as $o): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="kode"><?= htmlspecialchars($o['kode_order']); ?></td>
                        <td><?= htmlspecialchars($o['nama']); ?></td>
                        <td class="text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                        <td class="text-end"><?= rupiah($o['total_harga']); ?></td>
                        <td class="status">
                            <span class="badge badge-<?= $o['status']; ?>">
                                <?= ucfirst($o['status']); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Total Box -->
    <div class="total-box">
        <span class="lbl">Total Pendapatan (Order Selesai)</span>
        <span class="val"><?= rupiah($total_pendapatan); ?></span>
    </div>

    <!-- TTD -->
    <div class="ttd">
        <p>Kota Anda, <?= date('d/m/Y'); ?></p>
        <p>Administrator,</p>
        <p class="nama-ttd"><?= htmlspecialchars($_SESSION['admin_nama']); ?></p>
    </div>

</div>

</body>
</html>