<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Laporan Order"; $base_url = "../";

$tgl_awal = $_GET['tgl_awal'] ?? date('Y-m-01');
$tgl_akhir = $_GET['tgl_akhir'] ?? date('Y-m-d');
$ta = mysqli_real_escape_string($koneksi, $tgl_awal);
$tk = mysqli_real_escape_string($koneksi, $tgl_akhir);

$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id WHERE DATE(o.waktu_order) BETWEEN '$ta' AND '$tk' ORDER BY o.waktu_order DESC");
$total_pendapatan = 0; $data = [];
while ($row = mysqli_fetch_assoc($q)) {
    $data[] = $row;
    if ($row['status']==='selesai') $total_pendapatan += $row['total_harga'];
}

include '../includes/header.php';
include 'includes_sidebar.php';
?>
<div class="p-6 lg:p-10 space-y-6">
    <div class="border-b border-espresso-100 pb-5">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Manajemen</p>
        <h1 class="font-display text-3xl font-semibold text-matcha-900">Laporan Order</h1>
    </div>

    <form method="GET" class="bg-white rounded-2xl border border-espresso-100/60 p-5 flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-semibold text-espresso-600 mb-1.5 uppercase tracking-wide">Dari</label>
            <input type="date" name="tgl_awal" value="<?= htmlspecialchars($tgl_awal); ?>" class="px-3.5 py-2 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-espresso-600 mb-1.5 uppercase tracking-wide">Sampai</label>
            <input type="date" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir); ?>" class="px-3.5 py-2 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
        </div>
        <button type="submit" class="inline-flex items-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-4 py-2 rounded-xl text-sm transition"><i data-lucide="search" class="w-4 h-4"></i> Filter</button>
        <a href="cetak_laporan.php?tgl_awal=<?= urlencode($tgl_awal); ?>&tgl_akhir=<?= urlencode($tgl_akhir); ?>" target="_blank" class="inline-flex items-center gap-1.5 bg-white border border-espresso-200 hover:bg-cream-100 text-espresso-700 font-semibold px-4 py-2 rounded-xl text-sm transition"><i data-lucide="printer" class="w-4 h-4"></i> Cetak</a>
    </form>

    <div class="bg-matcha-900 text-cream-50 rounded-2xl px-6 py-5 flex items-center justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-matcha-300 mb-1">Total Pendapatan</p>
            <p class="text-xs text-cream-200/70">Dihitung dari order berstatus selesai</p>
        </div>
        <p class="font-display text-3xl font-semibold"><?= rupiah($total_pendapatan); ?></p>
    </div>

    <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th class="!text-center">Meja</th><th class="!text-right">Total</th><th class="!text-center">Status</th><th>Waktu</th></tr></thead>
                <tbody>
                    <?php if (empty($data)): ?>
                        <tr><td colspan="7" class="text-center text-espresso-400 py-10 text-sm">Tidak ada data pada periode ini.</td></tr>
                    <?php else: $no=1; foreach ($data as $o): ?>
                        <tr>
                            <td class="text-espresso-500"><?= $no++; ?></td>
                            <td><code class="text-xs bg-espresso-50 text-espresso-700 px-2 py-0.5 rounded"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td class="text-espresso-800"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="text-center text-espresso-600"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="text-right font-medium text-espresso-800"><?= rupiah($o['total_harga']); ?></td>
                            <td class="text-center"><span class="badge badge-<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span></td>
                            <td class="text-sm text-espresso-500"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes_footer_admin.php'; ?>