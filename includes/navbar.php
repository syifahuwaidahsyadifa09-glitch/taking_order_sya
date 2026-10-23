<nav class="bg-matcha-900 text-cream-100 border-b border-matcha-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
        <a href="<?= $base_url; ?>index.php" class="flex items-center gap-2.5">
            <img src="<?= $base_url; ?>assets/logo-brand.png" alt="Logo"
                 class="w-9 h-9 object-contain">
            <div class="leading-tight">
                <p class="font-display font-semibold text-base tracking-wide">Taking Order Cafe</p>
                <p class="text-[10px] uppercase tracking-[0.18em] text-matcha-300">Est. 2024 — Coffee &amp; Kitchen</p>
            </div>
        </a>
        <div class="flex items-center gap-3">
            <?php if (isset($_SESSION['pelanggan_id'])): ?>
                <span class="hidden md:flex items-center gap-2 text-xs text-cream-200/80">
                    <i data-lucide="user" class="w-3.5 h-3.5"></i>
                    <?= htmlspecialchars($_SESSION['pelanggan_nama']); ?>
                    <span class="w-px h-3 bg-matcha-700"></span>
                    Meja <?= htmlspecialchars($_SESSION['pelanggan_meja']); ?>
                </span>
                <a href="<?= $base_url; ?>pelanggan/keranjang.php"
                   class="flex items-center gap-1.5 bg-matcha-200 text-matcha-900 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-matcha-100 transition">
                    <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i>
                    Keranjang
                    <span class="bg-matcha-900 text-cream-100 text-[10px] rounded-full w-4 h-4 flex items-center justify-center font-bold">
                        <?= isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>
                    </span>
                </a>
                <a href="<?= $base_url; ?>pelanggan/logout_pelanggan.php"
                   class="flex items-center gap-1 text-xs text-cream-200 hover:text-cream-50 transition">
                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                    <span class="hidden sm:inline">Selesai</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>