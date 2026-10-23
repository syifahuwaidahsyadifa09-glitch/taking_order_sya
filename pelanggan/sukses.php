<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id']) || !isset($_SESSION['last_order_id'])) {
    header("Location: ../index.php"); exit;
}
$oid = (int)$_SESSION['last_order_id'];
$order = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT o.*, p.nama, p.no_hp FROM orders o JOIN pelanggan p ON o.pelanggan_id=p.id WHERE o.id=$oid"));
if (!$order) { header("Location: ../index.php"); exit; }

$q_detail = mysqli_query($koneksi, "SELECT * FROM order_detail WHERE order_id=$oid");
$detail = [];
while ($d = mysqli_fetch_assoc($q_detail)) $detail[] = $d;

$judul = "Struk Pesanan"; $base_url = "../";
include '../includes/header.php';
?>
<div class="max-w-xl mx-auto px-4 sm:px-6 py-10 w-full flex-1">
    <div class="text-center mb-8 no-print">
        <div class="w-14 h-14 mx-auto rounded-full bg-matcha-100 flex items-center justify-center mb-4 border border-matcha-200">
            <i data-lucide="check" class="w-7 h-7 text-matcha-800"></i>
        </div>
        <h1 class="font-display text-3xl font-semibold text-matcha-900">Pesanan Berhasil</h1>
        <p class="text-espresso-500 text-sm mt-1.5">Tunjukkan struk ini ke kasir atau pelayan.</p>
    </div>

    <div class="bg-cream-50 rounded-2xl p-5 sm:p-7 border border-espresso-100 struk-wrapper" id="strukArea">
        <div class="struk bg-white rounded-xl p-6">
            <div class="text-center pb-3 border-b border-dashed border-espresso-200 mb-3">
                <img src="../assets/logo-brand.png" alt="Logo" class="w-10 h-10 object-contain mx-auto mb-2">
                <h5 class="font-display text-base font-semibold text-matcha-900">TAKING ORDER CAFE</h5>
                <p class="text-[11px] text-espresso-500">Jl. Contoh Alamat No. 123, Kota Anda</p>
                <p class="text-[11px] text-espresso-500">Telp: 021-1234567</p>
            </div>
            <div class="struk-divider">================================</div>
            <div class="text-xs">
                <div class="row-line"><span>Kode Order</span><span><?= htmlspecialchars($order['kode_order']); ?></span></div>
                <div class="row-line"><span>Tanggal</span><span><?= date('d/m/Y H:i', strtotime($order['waktu_order'])); ?></span></div>
                <div class="row-line"><span>Nama</span><span><?= htmlspecialchars($order['nama']); ?></span></div>
                <div class="row-line"><span>No. Meja</span><span><?= htmlspecialchars($order['no_meja']); ?></span></div>
                <?php if (!empty($order['no_hp'])): ?>
                <div class="row-line"><span>No. HP</span><span><?= htmlspecialchars($order['no_hp']); ?></span></div>
                <?php endif; ?>
            </div>
            <div class="struk-divider">================================</div>
            <?php foreach ($detail as $d): ?>
                <div class="item-nama text-xs"><?= htmlspecialchars($d['nama_menu']); ?></div>
                <div class="row-line item-detail">
                    <span><?= $d['jumlah']; ?> × <?= number_format($d['harga'], 0, ',', '.'); ?></span>
                    <span><?= number_format($d['subtotal'], 0, ',', '.'); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="struk-divider">================================</div>
            <div class="row-line total-line text-sm"><span>TOTAL</span><span><?= rupiah($order['total_harga']); ?></span></div>
            <div class="row-line text-xs"><span>Status</span><span><?= ucfirst($order['status']); ?></span></div>
            <?php if (!empty($order['catatan'])): ?>
                <div class="struk-divider">--------------------------------</div>
                <div class="text-xs"><strong>Catatan:</strong><br><?= nl2br(htmlspecialchars($order['catatan'])); ?></div>
            <?php endif; ?>
            <div class="struk-divider">================================</div>
            <div class="text-center text-[11px] text-espresso-500 pt-1">
                <p>Terima kasih atas kunjungan Anda</p>
                <p>Selamat menikmati</p>
            </div>
        </div>
    </div>

    <div class="text-center mt-8 no-print flex flex-col sm:flex-row justify-center gap-3">
        <a href="menu.php" class="inline-flex items-center justify-center gap-1.5 bg-white border border-espresso-200 hover:bg-cream-100 text-espresso-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
            <i data-lucide="plus" class="w-4 h-4"></i> Order Lagi
        </a>
        <a href="logout_pelanggan.php" class="inline-flex items-center justify-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-5 py-2.5 rounded-xl transition text-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i> Selesai
        </a>
    </div>
</div>
<?php include '../includes/footer.php'; ?>