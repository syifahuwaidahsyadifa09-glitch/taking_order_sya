<?php
require_once 'config/koneksi.php';
$judul = "Halaman Tidak Ditemukan";
$base_url = '';
include 'includes/header.php';
?>
<div class="bg-cafe-hero flex items-center justify-center min-h-screen px-4">
    <div class="text-center max-w-md">
        <img src="assets/logo-brand.png" alt="Logo" class="w-16 h-16 object-contain mx-auto mb-8 opacity-90">
        <p class="text-matcha-300 text-xs uppercase tracking-[0.3em] mb-3">Error 404</p>
        <h1 class="font-display text-5xl font-semibold text-cream-50 mb-4">Halaman tidak ditemukan</h1>
        <p class="text-cream-200/70 text-sm mb-8 leading-relaxed">
            Sepertinya halaman yang Anda cari sudah tidak tersedia atau telah dipindahkan.
        </p>
        <a href="index.php" class="inline-flex items-center gap-2 bg-matcha-200 hover:bg-matcha-100 text-matcha-900 font-semibold px-5 py-2.5 rounded-xl transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
        </a>
    </div>
</div>
<?php include 'includes/footer.php'; ?>