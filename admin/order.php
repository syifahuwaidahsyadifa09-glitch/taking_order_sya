<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Data Order"; $base_url = "../";
include '../includes/header.php';
include 'includes_sidebar.php';

$filter = $_GET['status'] ?? '';
$where = '';
if (in_array($filter, ['pending','diproses','selesai','dibatalkan'])) {
    $f = mysqli_real_escape_string($koneksi, $filter);
    $where = "WHERE o.status='$f'";
}
$q = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id $where ORDER BY o.waktu_order DESC");
$status_map = [''=>'Semua','pending'=>'Pending','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
?>
<div class="p-6 lg:p-10 space-y-6">
    <div class="border-b border-espresso-100 pb-5">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Manajemen</p>
        <h1 class="font-display text-3xl font-semibold text-matcha-900">Data Order</h1>
    </div>

    <div class="flex flex-wrap gap-2">
        <?php foreach ($status_map as $k=>$lbl): $active = $filter===$k; ?>
            <a href="order.php<?= $k ? '?status='.$k : ''; ?>" class="px-4 py-1.5 rounded-full text-xs font-semibold transition border <?= $active ? 'bg-matcha-900 text-cream-100 border-matcha-900' : 'bg-white border-espresso-100 text-espresso-600 hover:bg-cream-100'; ?>"><?= $lbl; ?></a>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead><tr><th>Kode</th><th>Nama</th><th class="!text-center">Meja</th><th class="!text-right">Total</th><th class="!text-center">Status</th><th>Waktu</th><th class="!text-center">Aksi</th></tr></thead>
                <tbody>
                    <?php while ($o = mysqli_fetch_assoc($q)): ?>
                        <tr>
                            <td><code class="text-xs bg-espresso-50 text-espresso-700 px-2 py-0.5 rounded"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td class="font-medium text-espresso-800"><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="text-right text-espresso-700"><?= rupiah($o['total_harga']); ?></td>
                            <td class="text-center"><span class="badge badge-<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span></td>
                            <td class="text-sm text-espresso-500"><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></td>
                            <td class="text-center"><a href="order_detail.php?id=<?= $o['id']; ?>" class="inline-flex text-espresso-500 hover:text-matcha-800 p-1"><i data-lucide="arrow-up-right" class="w-4 h-4"></i></a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'includes_footer_admin.php'; ?>