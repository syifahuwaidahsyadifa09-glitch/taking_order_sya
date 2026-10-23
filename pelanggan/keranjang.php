<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: ../index.php"); exit; }
$judul = "Keranjang — Taking Order Cafe";
$base_url = "../";
include '../includes/header.php';
include '../includes/navbar.php';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-10 w-full flex-1">
    <div class="border-b border-espresso-100 pb-6 mb-8">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Pesanan</p>
        <h1 class="font-display text-4xl font-semibold text-matcha-900">Keranjang</h1>
    </div>

    <?php if (empty($keranjang)): ?>
        <div class="bg-cream-50 border border-espresso-100 rounded-2xl px-6 py-12 text-center">
            <i data-lucide="shopping-bag" class="w-10 h-10 mx-auto mb-3 text-espresso-300"></i>
            <p class="text-espresso-500 mb-5 text-sm">Keranjang Anda masih kosong.</p>
            <a href="menu.php" class="inline-flex items-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                <i data-lucide="arrow-right" class="w-4 h-4"></i> Lihat Menu
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-espresso-100/60 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table-clean">
                    <thead>
                        <tr>
                            <th>Menu</th><th class="!text-right">Harga</th>
                            <th class="!text-center">Jumlah</th><th class="!text-right">Subtotal</th>
                            <th class="!text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keranjang as $mid => $jml):
                            $mid = (int)$mid;
                            $m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$mid"));
                            if (!$m) continue;
                            $sub = $m['harga'] * $jml;
                            $total += $sub;
                        ?>
                        <tr>
                            <td class="font-medium text-espresso-800"><?= htmlspecialchars($m['nama_menu']); ?></td>
                            <td class="text-right text-espresso-600"><?= rupiah($m['harga']); ?></td>
                            <td class="text-center">
                                <form action="../proses/update_keranjang.php" method="POST" class="flex items-center justify-center gap-1">
                                    <input type="hidden" name="menu_id" value="<?= $mid; ?>">
                                    <input type="number" name="jumlah" value="<?= $jml; ?>" min="1" max="<?= $m['stok']; ?>" class="w-16 px-2 py-1 text-sm text-center rounded-lg border border-espresso-100 bg-white focus:outline-none focus:ring-2 focus:ring-matcha-500">
                                    <button type="submit" class="text-espresso-400 hover:text-matcha-800 p-1"><i data-lucide="refresh-cw" class="w-4 h-4"></i></button>
                                </form>
                            </td>
                            <td class="text-right font-semibold text-matcha-900"><?= rupiah($sub); ?></td>
                            <td class="text-center">
                                <a href="../proses/hapus_keranjang.php?id=<?= $mid; ?>" class="inline-flex text-espresso-400 hover:text-espresso-900 p-1"><i data-lucide="trash-2" class="w-4 h-4"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="bg-cream-100">
                            <td colspan="3" class="!text-right !text-xs !uppercase !tracking-wide !text-espresso-600">Total</td>
                            <td class="!text-right !font-display !font-semibold !text-matcha-900 !text-xl"><?= rupiah($total); ?></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-6">
            <a href="menu.php" class="inline-flex items-center justify-center gap-1.5 bg-white border border-espresso-200 hover:bg-cream-100 text-espresso-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Lanjut Pesan
            </a>
            <a href="checkout.php" class="inline-flex items-center justify-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
                Lanjut ke Checkout <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>