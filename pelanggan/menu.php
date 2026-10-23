<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['pelanggan_id'])) { header("Location: ../index.php"); exit; }
$judul = "Menu — Taking Order Cafe";
$base_url = "../";
include '../includes/header.php';
include '../includes/navbar.php';

$kategori_filter = $_GET['kategori'] ?? '';
$where = "WHERE status='tersedia'";
if ($kategori_filter !== '') {
    $kf = mysqli_real_escape_string($koneksi, $kategori_filter);
    $where .= " AND kategori='$kf'";
}
$query = mysqli_query($koneksi, "SELECT * FROM menu $where ORDER BY kategori, nama_menu");
$kategori_list = mysqli_query($koneksi, "SELECT DISTINCT kategori FROM menu ORDER BY kategori");
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 w-full flex-1">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 border-b border-espresso-100 pb-6 mb-8">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Menu</p>
            <h1 class="font-display text-4xl font-semibold text-matcha-900">Pilih Favorit Anda</h1>
            <p class="text-espresso-500 text-sm mt-1">Disajikan segar setiap hari.</p>
        </div>
        <a href="keranjang.php" class="inline-flex items-center gap-2 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-4 py-2.5 rounded-xl transition text-sm">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Keranjang
            <span class="bg-cream-100/20 text-cream-50 text-xs rounded-full px-2 py-0.5"><?= count($_SESSION['keranjang'] ?? []); ?></span>
        </a>
    </div>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="mb-6 flex items-start gap-2 bg-matcha-50 border border-matcha-200 text-matcha-800 rounded-xl px-4 py-3 text-sm">
            <i data-lucide="check" class="w-4 h-4 shrink-0 mt-0.5"></i>
            <span><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="menu.php" class="px-4 py-1.5 rounded-full text-xs font-semibold border <?= $kategori_filter==='' ? 'bg-matcha-900 text-cream-100 border-matcha-900' : 'bg-white border-espresso-100 text-espresso-600 hover:bg-cream-100'; ?>">Semua</a>
        <?php while ($k = mysqli_fetch_assoc($kategori_list)): ?>
            <a href="menu.php?kategori=<?= urlencode($k['kategori']); ?>" class="px-4 py-1.5 rounded-full text-xs font-semibold border <?= $kategori_filter===$k['kategori'] ? 'bg-matcha-900 text-cream-100 border-matcha-900' : 'bg-white border-espresso-100 text-espresso-600 hover:bg-cream-100'; ?>">
                <?= htmlspecialchars($k['kategori']); ?>
            </a>
        <?php endwhile; ?>
    </div>

    <?php if (mysqli_num_rows($query)===0): ?>
        <div class="bg-cream-50 border border-espresso-100 text-espresso-500 rounded-2xl px-6 py-10 text-sm text-center">Belum ada menu tersedia.</div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($m = mysqli_fetch_assoc($query)): $img = gambarMenuUrl($m['gambar']); ?>
            <article class="card-hover bg-white rounded-2xl border border-espresso-100/60 overflow-hidden flex flex-col">
                <div class="relative aspect-[4/3] bg-cream-100 overflow-hidden">
                    <?php if ($img): ?>
                        <img src="<?= htmlspecialchars($img); ?>" alt="<?= htmlspecialchars($m['nama_menu']); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-espresso-200">
                            <i data-lucide="image-off" class="w-10 h-10"></i>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-3 left-3 bg-cream-50/95 backdrop-blur text-matcha-900 text-[10px] uppercase tracking-[0.15em] font-semibold px-2.5 py-1 rounded-full border border-espresso-100/40">
                        <?= htmlspecialchars($m['kategori']); ?>
                    </span>
                </div>
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-display text-lg font-semibold text-matcha-900 leading-tight"><?= htmlspecialchars($m['nama_menu']); ?></h3>
                    <p class="text-sm text-espresso-500 mt-1.5 line-clamp-2 flex-1 leading-relaxed"><?= htmlspecialchars($m['deskripsi']); ?></p>

                    <div class="flex items-center justify-between mt-4 pb-3 border-b border-espresso-100">
                        <span class="text-matcha-900 font-semibold"><?= rupiah($m['harga']); ?></span>
                        <span class="text-[11px] text-espresso-500">Stok <?= $m['stok']; ?></span>
                    </div>

                    <form action="../proses/tambah_keranjang.php" method="POST" class="mt-3 flex gap-2">
                        <input type="hidden" name="menu_id" value="<?= $m['id']; ?>">
                        <input type="number" name="jumlah" value="1" min="1" max="<?= $m['stok']; ?>" class="w-16 px-2 py-2 text-sm text-center rounded-lg border border-espresso-100 bg-white focus:outline-none focus:ring-2 focus:ring-matcha-500">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 bg-matcha-200 hover:bg-matcha-300 text-matcha-900 font-semibold text-xs py-2 rounded-lg transition uppercase tracking-wide">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah
                        </button>
                    </form>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>