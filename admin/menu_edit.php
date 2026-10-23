<?php
require_once '../config/koneksi.php';
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$judul = "Edit Menu"; $base_url = "../";
$id = (int)($_GET['id'] ?? 0);
$m = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM menu WHERE id=$id"));
if (!$m) { header("Location: menu.php"); exit; }
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_menu']);
    $kat = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $harga = (float)$_POST['harga'];
    $desc = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = max(0, (int)$_POST['stok']);
    $status = in_array($_POST['status'], ['tersedia','habis']) ? $_POST['status'] : 'tersedia';
    $gambar = uploadGambarMenu($_FILES['gambar'] ?? null, $m['gambar']);
    $gambar_sql = $gambar ? "'$gambar'" : "NULL";

    $sql = "UPDATE menu SET nama_menu='$nama',kategori='$kat',harga=$harga,deskripsi='$desc',stok=$stok,status='$status',gambar=$gambar_sql WHERE id=$id";
    if (mysqli_query($koneksi, $sql)) { $_SESSION['pesan'] = "Menu berhasil diperbarui."; header("Location: menu.php"); exit; }
    $error = "Gagal memperbarui menu.";
}

include '../includes/header.php';
include 'includes_sidebar.php';
$img = gambarMenuUrl($m['gambar']);
?>
<div class="p-6 lg:p-10">
    <a href="menu.php" class="inline-flex items-center gap-1 text-sm text-espresso-500 hover:text-matcha-800 mb-4"><i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar menu</a>
    <div class="border-b border-espresso-100 pb-5 mb-6">
        <p class="text-xs uppercase tracking-[0.18em] text-espresso-500 mb-1">Manajemen</p>
        <h1 class="font-display text-3xl font-semibold text-matcha-900">Edit Menu</h1>
    </div>

    <?php if ($error): ?><div class="mb-4 bg-espresso-50 border border-espresso-200 text-espresso-700 rounded-xl px-4 py-3 text-sm"><?= $error; ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl border border-espresso-100/60 p-6 space-y-5 max-w-3xl">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Nama Menu</label>
                <input type="text" name="nama_menu" required value="<?= htmlspecialchars($m['nama_menu']); ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Kategori</label>
                <input type="text" name="kategori" required value="<?= htmlspecialchars($m['kategori']); ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Harga (Rp)</label>
                <input type="number" name="harga" required min="0" step="100" value="<?= (float)$m['harga']; ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Stok</label>
                <input type="number" name="stok" required min="0" value="<?= (int)$m['stok']; ?>" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500">
                    <option value="tersedia" <?= $m['status']==='tersedia'?'selected':''; ?>>Tersedia</option>
                    <option value="habis" <?= $m['status']==='habis'?'selected':''; ?>>Habis</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Gambar Produk</label>
                <?php if ($img): ?><img src="<?= htmlspecialchars($img); ?>" alt="" class="w-24 h-24 object-cover rounded-lg mb-2 border border-espresso-100"><?php endif; ?>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" class="w-full px-3 py-2 rounded-xl border border-espresso-100 bg-white text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-matcha-100 file:text-matcha-800 file:font-semibold">
                <p class="text-[11px] text-espresso-500 mt-1">Kosongkan jika tidak ingin mengganti.</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-espresso-700 mb-1.5 uppercase tracking-wide">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-3.5 py-2.5 rounded-xl border border-espresso-100 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-matcha-500"><?= htmlspecialchars($m['deskripsi']); ?></textarea>
            </div>
        </div>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-1.5 bg-matcha-900 hover:bg-matcha-800 text-cream-100 font-semibold px-5 py-2.5 rounded-xl transition text-sm"><i data-lucide="save" class="w-4 h-4"></i> Update</button>
            <a href="menu.php" class="inline-flex items-center gap-1.5 bg-cream-100 hover:bg-cream-200 text-espresso-700 font-semibold px-5 py-2.5 rounded-xl transition text-sm border border-espresso-100">Batal</a>
        </div>
    </form>
</div>
<?php include 'includes_footer_admin.php'; ?>