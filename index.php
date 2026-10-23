<?php
require_once 'config/koneksi.php';
$judul = "Selamat Datang — Taking Order Cafe";
$base_url = '';
include 'includes/header.php';
?>
<div class="bg-cafe-hero flex items-center justify-center min-h-screen px-4 py-12">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-0 rounded-3xl overflow-hidden shadow-2xl border border-matcha-700/40">

        <!-- Panel kiri: brand narasi -->
        <div class="hidden lg:flex flex-col justify-between p-10 bg-matcha-900/40 backdrop-blur-sm text-cream-100">
            <div>
                <img src="assets/logo-brand.png" alt="Logo" class="w-16 h-16 object-contain mb-6">
                <h1 class="font-display text-4xl font-semibold leading-tight mb-3">
                    Taking Order<br>Cafe
                </h1>
                <p class="text-cream-200/80 text-sm leading-relaxed max-w-xs">
                    Nikmati pengalaman memesan yang tenang dan langsung dari meja Anda. Kami menyajikan kopi pilihan dan hidangan segar setiap hari.
                </p>
            </div>
            <div class="space-y-3 text-xs text-cream-200/60">
                <p class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Jl. Contoh Alamat No. 123, Kota Anda</p>
                <p class="flex items-center gap-2"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Buka setiap hari, 08.00 – 22.00</p>
                <p class="flex items-center gap-2"><i data-lucide="phone" class="w-3.5 h-3.5"></i> 021-1234567</p>
            </div>
        </div>

        <!-- Panel kanan: form -->
        <div class="bg-cream-50 p-8 sm:p-10">
            <div class="lg:hidden flex items-center gap-2 mb-6">
                <img src="assets/logo-brand.png" alt="Logo" class="w-9 h-9 object-contain">
                <span class="font-display font-semibold text-matcha-900">Taking Order Cafe</span>
            </div>

            <h2 class="font-display text-2xl font-semibold text-matcha-900">Mulai Pemesanan</h2>
            <p class="text-espresso-500 text-sm mt-1 mb-6">Isi data diri Anda, lalu pilih menu favorit.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 flex items-start gap-2 bg-espresso-50 border border-espresso-200 text-espresso-700 text-sm rounded-xl px-4 py-3">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
                </div>
            <?php endif; ?>

            <form action="proses/simpan_pelanggan.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Nama Anda"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Nomor Meja</label>
                    <input type="text" name="no_meja" required placeholder="Contoh: A01"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">
                        No. HP <span class="text-espresso-300 normal-case font-normal">(opsional)</span>
                    </label>
                    <input type="text" name="no_hp" placeholder="08xxxxxxxxxx"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold py-3 rounded-xl transition">
                    Mulai Pesan
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-espresso-100 text-center">
                <a href="admin/login.php" class="text-xs text-espresso-500 hover:text-matcha-700 inline-flex items-center gap-1">
                    <i data-lucide="lock" class="w-3 h-3"></i> Login Admin
                </a>
            </div>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>