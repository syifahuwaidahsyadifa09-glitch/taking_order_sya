<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = in_array($_POST['status'] ?? '', ['pending','diproses','selesai','dibatalkan']) ? $_POST['status'] : 'pending';
    $st = mysqli_real_escape_string($koneksi, $status);
    mysqli_query($koneksi, "UPDATE orders SET status='$st' WHERE id=$id");
    $_SESSION['pesan'] = "Status order berhasil diperbarui.";
    header("Location: order_detail.php?id=$id"); exit;
}

$judul = "Detail Order"; $base_url = "../";
include '../includes/header.php';
include 'includes_sidebar.php';
$o = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id WHERE o.id=$id"));
if (!$o) { header("Location: order.php"); exit; }
$qd = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id=$id");
?>
<div class="p-6 lg:p-10">
    <a href="order.php" class="inline-flex items-center gap-1 text-sm text-espresso-500 hover:text-matcha-800 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar order</a>
    <div class="border-b border-espresso-100 pb-5 mb-6 flex items-end justify-between">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Detail Order</p>
            <h1 class="font-display text-2xl font-semibold text-matcha-900"><?= htmlspecialchars($o['kode_order']); ?></h1>
        </div>
        <span class="badge badge-<?= $o['status']; ?>"><?= ucfirst($o['status']); ?></span>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?><div class="mb-4 bg-matcha-50 border border-matcha-200 text-matcha-800 rounded-xl px-4 py-3 text-sm"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div><?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
                <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Informasi Pelanggan</div>
                <div class="p-5 space-y-2.5 text-sm">
                    <p><span class="text-espresso-500 text-xs uppercase tracking-wide block mb-0.5">Nama</span><?= htmlspecialchars($o['nama']); ?></p>
                    <p><span class="text-espresso-500 text-xs uppercase tracking-wide block mb-0.5">No. HP</span><?= htmlspecialchars($o['no_hp'] ?: '-'); ?></p>
                    <p><span class="text-espresso-500 text-xs uppercase tracking-wide block mb-0.5">No. Meja</span><?= htmlspecialchars($o['no_meja']); ?></p>
                    <p><span class="text-espresso-500 text-xs uppercase tracking-wide block mb-0.5">Waktu</span><?= date('d/m/Y H:i', strtotime($o['waktu_order'])); ?></p>
                    <p><span class="text-espresso-500 text-xs uppercase tracking-wide block mb-0.5">Catatan</span><?= htmlspecialchars($o['catatan'] ?: '-'); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
                <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Update Status</div>
                <div class="p-5">
                    <form method="POST" class="space-y-3">
                        <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
                            <?php foreach (['pending','diproses','selesai','dibatalkan'] as $s): ?>
                                <option value="<?= $s; ?>" <?= $o['status']===$s?'selected':''; ?>><?= ucfirst($s); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold py-2.5 rounded-xl transition text-sm"><i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
                <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Rincian Item</div>
                <div class="overflow-x-auto">
                    <table class="table-clean">
                        <thead><tr><th>Menu</th><th class="!text-right">Harga</th><th class="!text-center">Jumlah</th><th class="!text-right">Subtotal</th></tr></thead>
                        <tbody>
                            <?php while ($d = mysqli_fetch_assoc($qd)): ?>
                            <tr>
                                <td class="text-espresso-800"><?= htmlspecialchars($d['nama_menu']); ?></td>
                                <td class="text-right text-espresso-600"><?= rupiah($d['harga']); ?></td>
                                <td class="text-center text-espresso-600"><?= $d['jumlah']; ?></td>
                                <td class="text-right font-medium text-espresso-800"><?= rupiah($d['subtotal']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-cream-100"><th colspan="3" class="!text-right !text-espresso-700 text-xs uppercase tracking-wide">Total</th><th class="!text-right !text-matcha-900 !text-lg"><?= rupiah($o['total_harga']); ?></th></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'includes_footer_admin.php'; ?>