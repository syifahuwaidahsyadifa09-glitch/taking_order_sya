<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Dashboard Admin"; $base_url = "../";

$total_menu = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM menu"))['j'];
$total_order = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders"))['j'];
$total_pending = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) j FROM orders WHERE status='pending'"))['j'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) j FROM orders WHERE status='selesai'"))['j'];

$labels = []; $values = [];
for ($i=6; $i>=0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d M', strtotime($tgl));
    $row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(total_harga),0) j FROM orders WHERE DATE(waktu_order)='$tgl' AND status='selesai'"));
    $values[] = (float)$row['j'];
}
$top_menu = mysqli_query($koneksi, "SELECT nama_menu, SUM(jumlah) total FROM order_detail GROUP BY nama_menu ORDER BY total DESC LIMIT 5");
$recent = mysqli_query($koneksi, "SELECT o.*, p.nama FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id ORDER BY o.waktu_order DESC LIMIT 5");

include '../includes/header.php';
include 'includes_sidebar.php';
?>
<div class="p-6 lg:p-10 space-y-8">
    <div class="flex items-end justify-between border-b border-espresso-100 pb-5">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Dashboard</p>
            <h1 class="font-display text-3xl font-semibold text-matcha-900">Selamat datang, <?= htmlspecialchars($_SESSION['admin_nama']); ?></h1>
        </div>
        <p class="hidden sm:block text-sm text-espresso-500"><?= date('d F Y'); ?></p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-espresso-100/60">
            <p class="text-xs uppercase tracking-wide text-espresso-500 font-semibold mb-2">Total Menu</p>
            <p class="font-display text-4xl font-semibold text-matcha-900"><?= $total_menu; ?></p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-espresso-100/60">
            <p class="text-xs uppercase tracking-wide text-espresso-500 font-semibold mb-2">Total Order</p>
            <p class="font-display text-4xl font-semibold text-matcha-900"><?= $total_order; ?></p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-espresso-100/60">
            <p class="text-xs uppercase tracking-wide text-espresso-500 font-semibold mb-2">Order Pending</p>
            <p class="font-display text-4xl font-semibold text-matcha-900"><?= $total_pending; ?></p>
        </div>
        <div class="bg-matcha-900 rounded-2xl p-5 border border-matcha-700">
            <p class="text-xs uppercase tracking-wide text-matcha-300 font-semibold mb-2">Pendapatan</p>
            <p class="font-display text-2xl font-semibold text-cream-50 mt-2"><?= rupiah($total_pendapatan); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-espresso-100/60">
            <h2 class="font-display text-lg font-semibold text-matcha-900 mb-4">Pendapatan 7 Hari Terakhir</h2>
            <div class="h-64"><canvas id="chartPendapatan"></canvas></div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-espresso-100/60">
            <h2 class="font-display text-lg font-semibold text-matcha-900 mb-4">Menu Terlaris</h2>
            <ul class="space-y-3">
                <?php $rank=1; while ($tm = mysqli_fetch_assoc($top_menu)): ?>
                    <li class="flex items-center gap-3 pb-3 border-b border-espresso-100/60 last:border-0 last:pb-0">
                        <span class="w-6 h-6 rounded-full bg-matcha-100 text-matcha-900 text-xs font-bold flex items-center justify-center shrink-0"><?= $rank++; ?></span>
                        <span class="flex-1 text-sm truncate text-espresso-700"><?= htmlspecialchars($tm['nama_menu']); ?></span>
                        <span class="text-xs font-semibold text-matcha-700"><?= (int)$tm['total']; ?>x</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between border-b border-espresso-100/60">
            <h2 class="font-display text-lg font-semibold text-matcha-900">Order Terbaru</h2>
            <a href="order.php" class="text-sm text-matcha-700 hover:text-matcha-900 inline-flex items-center gap-1">Lihat semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
        </div>
        <div class="overflow-x-auto">
            <table class="table-clean">
                <thead><tr><th>Kode</th><th>Nama</th><th class="!text-center">Meja</th><th class="!text-right">Total</th><th class="!text-center">Status</th><th>Waktu</th></tr></thead>
                <tbody>
                    <?php while ($o = mysqli_fetch_assoc($recent)): ?>
                        <tr>
                            <td><code class="text-xs bg-espresso-50 text-espresso-700 px-2 py-0.5 rounded"><?= htmlspecialchars($o['kode_order']); ?></code></td>
                            <td><?= htmlspecialchars($o['nama']); ?></td>
                            <td class="text-center"><?= htmlspecialchars($o['no_meja']); ?></td>
                            <td class="text-right font-medium"><?= rupiah($o['total_harga']); ?></td>
                            <td class="text-center"><span class="badge badge-<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span></td>
                            <td class="text-sm text-espresso-500"><?= date('d/m H:i', strtotime($o['waktu_order'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('chartPendapatan');
if (ctx) new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels); ?>,
        datasets: [{ label:'Pendapatan', data: <?= json_encode($values); ?>,
            borderColor:'#4a6a2c', backgroundColor:'rgba(197,216,164,.28)', borderWidth:2, fill:true, tension:.35,
            pointBackgroundColor:'#213b26', pointBorderColor:'#f6f8ee', pointBorderWidth:1.5, pointRadius:4, pointHoverRadius:6 }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:{ callbacks:{ label:(c)=>'Rp '+new Intl.NumberFormat('id-ID').format(c.parsed.y) } } },
        scales:{
            y:{ beginAtZero:true, ticks:{ callback:(v)=>'Rp '+new Intl.NumberFormat('id-ID').format(v), color:'#5a3e2b', font:{size:11} }, grid:{color:'rgba(232,218,208,.6)'} },
            x:{ ticks:{color:'#5a3e2b', font:{size:11}}, grid:{display:false} }
        }
    }
});
</script>
<?php include 'includes_footer_admin.php'; ?>