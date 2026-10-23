<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: ../index.php"); exit; }
$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) { header("Location: keranjang.php"); exit; }

$total = 0;
foreach ($keranjang as $mid => $jml) {
    $mid = (int)$mid;
    $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga FROM menu WHERE id=$mid"));
    if ($m) $total += $m['harga'] * $jml;
}
$judul = "Checkout"; $base_url = "../";
include '../includes/header.php';
include '../includes/navbar.php';
?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 w-full flex-1">
    <div class="border-b border-espresso-100 pb-6 mb-8">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Checkout</p>
        <h1 class="font-display text-4xl font-semibold text-matcha-900">Konfirmasi Pesanan</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="md:col-span-3 space-y-5">
            <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
                <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Data Pelanggan</div>
                <div class="p-5 space-y-2 text-sm">
                    <div class="flex justify-between border-b border-espresso-100 pb-2"><span class="text-espresso-500">Nama</span><span class="font-medium text-espresso-800"><?= htmlspecialchars($_SESSION['pelanggan_nama']); ?></span></div>
                    <div class="flex justify-between"><span class="text-espresso-500">No. Meja</span><span class="font-medium text-espresso-800"><?= htmlspecialchars($_SESSION['pelanggan_meja']); ?></span></div>
                </div>
            </div>

            <form action="../proses/simpan_order.php" method="POST">
                <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden mb-4">
                    <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Catatan Tambahan</div>
                    <div class="p-5">
                        <textarea name="catatan" rows="3" placeholder="Contoh: tanpa gula, extra shot, dll (opsional)" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500"></textarea>
                    </div>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold py-3 rounded-xl transition text-sm">
                    <i data-lucide="send" class="w-4 h-4"></i> Kirim Pesanan
                </button>
            </form>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden sticky top-24">
                <div class="bg-matcha-900 text-cream-100 px-5 py-3 text-xs uppercase tracking-wide font-semibold">Rincian Pesanan</div>
                <div class="p-5 space-y-2.5 text-sm">
                    <?php foreach ($keranjang as $mid => $jml):
                        $mid = (int)$mid;
                        $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$mid"));
                        if (!$m) continue;
                    ?>
                        <div class="flex justify-between">
                            <span class="text-espresso-600"><?= htmlspecialchars($m['nama_menu']); ?> <span class="text-espresso-400">× <?= $jml; ?></span></span>
                            <span class="font-medium text-espresso-800"><?= rupiah($m['harga'] * $jml); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr class="my-3 border-espresso-100">
                    <div class="flex justify-between items-baseline">
                        <span class="text-xs uppercase tracking-wide text-espresso-500">Total</span>
                        <span class="font-display font-semibold text-xl text-matcha-900"><?= rupiah($total); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>