<?php
require_once '../config/koneksi.php';
if (isset($_SESSION['admin_id'])) { header("Location: dashboard.php"); exit; }
$judul = "Login Admin"; $base_url = "../";
include '../includes/header.php';
?>
<div class="bg-cafe-hero flex items-center justify-center min-h-screen px-4 py-12">
    <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl border border-matcha-700/40">

        <!-- Panel kiri: narasi brand -->
        <div class="hidden lg:flex flex-col justify-between p-10 bg-matcha-900/40 backdrop-blur-sm text-cream-100">
            <div>
                <img src="../assets/logo-brand.png" alt="Logo" class="w-14 h-14 object-contain mb-6">
                <h1 class="font-display text-3xl font-semibold leading-tight mb-3">
                    Panel Admin<br>Taking Order Cafe
                </h1>
                <p class="text-cream-200/80 text-sm leading-relaxed">
                    Kelola menu, pantau pesanan, dan lihat laporan penjualan cafe Anda — semuanya dari satu tempat yang tenang dan rapi.
                </p>
            </div>
            <div class="space-y-2.5 text-xs text-cream-200/60">
                <p class="flex items-center gap-2"><i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i> Dashboard statistik realtime</p>
                <p class="flex items-center gap-2"><i data-lucide="image" class="w-3.5 h-3.5"></i> Upload foto produk</p>
                <p class="flex items-center gap-2"><i data-lucide="file-text" class="w-3.5 h-3.5"></i> Laporan siap cetak</p>
            </div>
        </div>

        <!-- Panel kanan: form login -->
        <div class="bg-cream-50 p-8 sm:p-10">
            <div class="lg:hidden flex items-center gap-2 mb-6">
                <img src="../assets/logo-brand.png" alt="Logo" class="w-9 h-9 object-contain">
                <span class="font-display font-semibold text-matcha-900">Admin Panel</span>
            </div>

            <h2 class="font-display text-2xl font-semibold text-matcha-900">Selamat datang kembali</h2>
            <p class="text-espresso-500 text-sm mt-1 mb-6">Masuk untuk mengelola cafe Anda.</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 bg-espresso-50 border border-espresso-200 text-espresso-700 text-sm rounded-xl px-4 py-3">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="proses_login.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Username</label>
                    <input type="text" name="username" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold py-2.5 rounded-xl transition">
                    <i data-lucide="log-in" class="w-4 h-4"></i> Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="../index.php" class="text-xs text-espresso-500 hover:text-matcha-700 inline-flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3 h-3"></i> Kembali ke halaman pelanggan
                </a>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>